<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PedidoWeb;
use App\Models\DetallePedidoWeb;
use Illuminate\Support\Facades\Http;
use App\Services\HeaderServiceInterface;

class CheckoutController extends Controller
{
    protected $headerService;

    public function __construct(HeaderServiceInterface $headerService)
    {
        $this->headerService = $headerService;
    }

    public function index()
    {
        if (!Auth::guard('cliente')->check()) {
            return redirect()->route('cliente.login.form')->withErrors(['Debes iniciar sesion para pagar.']);
        }

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->withErrors(['Tu carrito esta vacio.']);
        }

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        $categorias = $this->headerService->obtenerCategorias();
        $empresa = $this->headerService->obtenerEmpresa();
        $marcas = $this->headerService->obtenerMarcas();
        $tipos = $this->headerService->obtenerTipo();
        $tipoCambio = $this->headerService->obtenerCambioDolar();

        return view('checkout.index', compact('cart', 'total', 'categorias', 'empresa', 'marcas', 'tipos', 'tipoCambio'));
    }

    public function processMercadoPago(Request $request)
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index');
        }

        $cliente = Auth::guard('cliente')->user()->cliente;
        
        $total = 0;
        $items = [];
        foreach ($cart as $id => $item) {
            $price = $item['price'] ?? 0;
            if ($price <= 0) {
                return back()->withErrors(['Un producto en tu carrito tiene precio invalido. Por favor, vacia tu carrito y vuelve a agregarlo.']);
            }
            
            $total += $price * $item['quantity'];
            $items[] = [
                "title" => $item['name'],
                "quantity" => (int) $item['quantity'],
                "unit_price" => (float) $price,
                "currency_id" => "PEN"
            ];
        }

        // Crear PedidoWeb inicial PENDIENTE
        $pedido = PedidoWeb::create([
            'idCliente' => $cliente->idCliente,
            'pasarela' => 'mercadopago',
            'total' => $total,
            'estado' => 'PENDIENTE'
        ]);

        foreach ($cart as $id => $item) {
            DetallePedidoWeb::create([
                'idPedidoWeb' => $pedido->idPedidoWeb,
                'idProducto' => $id,
                'cantidad' => $item['quantity'],
                'precio' => $item['price']
            ]);
        }

        // Crear Preferencia en MercadoPago API
        $accessToken = env('MERCADOPAGO_ACCESS_TOKEN', 'APP_USR-TEST-TOKEN'); // TEST TOKEN
        
        $response = Http::withToken($accessToken)->withoutVerifying()->post('https://api.mercadopago.com/checkout/preferences', [
            "items" => $items,
            "payer" => [
                "name" => $cliente->nombre,
                "surname" => $cliente->apellidoPaterno,
                "email" => Auth::guard('cliente')->user()->email
            ],
            "back_urls" => [
                "success" => route('checkout.success', ['pedido' => $pedido->idPedidoWeb]),
                "failure" => route('checkout.failure', ['pedido' => $pedido->idPedidoWeb]),
                "pending" => route('checkout.pending', ['pedido' => $pedido->idPedidoWeb])
            ],
            "auto_return" => "approved",
            "external_reference" => (string) $pedido->idPedidoWeb
        ]);

        if ($response->successful()) {
            $preference = $response->json();
            // Redirigir al link de pago
            return redirect($preference['init_point']);
        }

        return back()->withErrors(['Error al contactar con MercadoPago. Revisa las credenciales.']);
    }

    public function processNiubiz(Request $request)
    {
        // Aqui iria la integracion con Niubiz (VisaNet)
        // Usualmente requiere generar un token de seguridad (Security Token), luego un token de sesion (Session Key),
        // y renderizar un script en la vista con el JS de Niubiz.
        return back()->withErrors(['Niubiz aun no ha sido configurado por completo (Requiere llaves de comercio reales).']);
    }

    public function success(Request $request, $pedidoId)
    {
        $pedido = PedidoWeb::findOrFail($pedidoId);
        
        if ($request->get('collection_status') === 'approved' || $request->get('status') === 'approved') {
            $pedido->estado = 'PAGADO';
            $pedido->codigoTransaccion = $request->get('payment_id');
            $pedido->save();

            session()->forget('cart');

            return redirect()->route('home')->with('success', 'Pago procesado correctamente! Tu orden es: ' . $pedido->idPedidoWeb);
        }

        return redirect()->route('home')->withErrors(['El pago no pudo ser confirmado.']);
    }

    public function failure(Request $request, $pedidoId)
    {
        $pedido = PedidoWeb::findOrFail($pedidoId);
        $pedido->estado = 'RECHAZADO';
        $pedido->save();

        return redirect()->route('cart.index')->withErrors(['El pago fue rechazado.']);
    }

    public function pending(Request $request, $pedidoId)
    {
        return redirect()->route('home')->with('success', 'Tu pago esta pendiente de confirmacion.');
    }
}
