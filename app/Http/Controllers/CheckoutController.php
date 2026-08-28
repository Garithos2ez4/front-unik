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

    public function processManual(Request $request)
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index');
        }

        $cliente = Auth::guard('cliente')->user()->cliente;

        $total = 0;
        foreach ($cart as $id => $item) {
            $price = $item['price'] ?? 0;
            if ($price <= 0) {
                return back()->withErrors(['Un producto en tu carrito tiene precio invalido. Por favor, vacia tu carrito y vuelve a agregarlo.']);
            }
            
            $producto = \App\Models\Producto::find($id);
            if (!$producto) {
                return back()->withErrors(['Un producto en tu carrito ya no existe.']);
            }
            $mostrarPrecio = optional($producto->DetalleProducto)->mostrarPrecioWeb ?? true;
            if (!$mostrarPrecio) {
                return back()->withErrors(['El producto "' . $producto->nombreProducto . '" ya no está disponible para compra web. Por favor, elimina este producto de tu carrito.']);
            }

            $total += round($price, 2) * $item['quantity'];
        }

        // Crear PedidoWeb inicial PENDIENTE
        $pedido = PedidoWeb::create([
            'idCliente' => $cliente->idCliente,
            'pasarela' => 'whatsapp_manual',
            'total' => $total,
            'estado' => 'PENDIENTE'
        ]);

        foreach ($cart as $id => $item) {
            DetallePedidoWeb::create([
                'idPedidoWeb' => $pedido->idPedidoWeb,
                'idProducto' => $id,
                'cantidad' => $item['quantity'],
                'precio' => round($item['price'], 2)
            ]);
        }

        // Limpiar el carrito
        session()->forget('cart');

        // Cargar detalles con producto para obtener modelos
        $pedido->load('detalles.producto');

        $whatsapp = '959062011';
        $modelos = $pedido->detalles->map(function ($detalle) {
            return $detalle->producto->modelo ?? $detalle->producto->nombreProducto ?? 'Producto';
        })->implode(', ');

        $mensaje = urlencode("Hola, acabo de realizar el pedido #{$pedido->idPedidoWeb} del modelo {$modelos} por S/ " . number_format($pedido->total, 2) . ". Me gustaría solicitar los numeros de cuenta o el link o QR de pago.");
        $linkWs = "https://wa.me/51{$whatsapp}?text={$mensaje}";

        // Redirigir directamente a WhatsApp
        return redirect($linkWs);
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
