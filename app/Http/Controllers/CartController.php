<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use Illuminate\Support\Facades\Http;
use App\Services\HeaderServiceInterface;

class CartController extends Controller
{
    protected $headerService;

    public function __construct(HeaderServiceInterface $headerService)
    {
        $this->headerService = $headerService;
    }

    public function index()
    {
        $categorias = $this->headerService->obtenerCategorias();
        $empresa = $this->headerService->obtenerEmpresa();
        $marcas = $this->headerService->obtenerMarcas();
        $tipos = $this->headerService->obtenerTipo();
        $tipoCambio = $this->headerService->obtenerCambioDolar();

        $cart = session()->get('cart', []);
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return view('cart.index', compact('categorias', 'empresa', 'marcas', 'tipos', 'tipoCambio', 'cart', 'total'));
    }

    public function add(Request $request, $id, \App\Services\PreciosServiceInterface $preciosService)
    {
        $producto = Producto::find($id);

        if (!$producto) {
            return response()->json(['success' => false, 'message' => 'Producto no encontrado']);
        }

        $cart = session()->get('cart', []);

        $mostrarPrecio = optional($producto->DetalleProducto)->mostrarPrecioWeb ?? true;
        if (!$mostrarPrecio) {
            return response()->json(['success' => false, 'message' => 'Este producto no está disponible para compra directa en la web.']);
        }

        if (strtoupper($producto->estadoProductoWeb) === 'AGOTADO') {
            return response()->json(['success' => false, 'message' => 'Este producto se encuentra agotado y no puede ser añadido al carrito.']);
        }

        // Calcular precio final en SOLES usando el PreciosService y eliminar las comas del string
        $precioFormat = $producto->precioTotalSol($preciosService);
        $precio = (float) str_replace(',', '', $precioFormat);
        
        // El precioTotalSol ya incluye la logica de si es dolar, ganancia, tc, etc.
        // Aplicar descuento si existe - TODO: revisar si precioTotalSol ya aplica descuentos

        // Add to cart
        if (isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                "name" => $producto->nombreProducto,
                "quantity" => 1,
                "price" => $precio,
                "image" => $producto->imagenProducto1,
                "slug" => $producto->slugProducto
            ];
        }

        session()->put('cart', $cart);

        return response()->json([
            'success' => true, 
            'message' => 'Producto agregado al carrito',
            'cartCount' => count($cart),
            'product' => [
                'name' => $producto->nombreProducto,
                'price' => number_format($precio, 2),
                'image' => asset('storage/'.$producto->imagenProducto1),
                'quantity' => $cart[$id]['quantity']
            ]
        ]);
    }

    public function update(Request $request)
    {
        if ($request->id && $request->quantity) {
            $cart = session()->get('cart');
            if (isset($cart[$request->id])) {
                $cart[$request->id]['quantity'] = $request->quantity;
                session()->put('cart', $cart);
            }
            return response()->json(['success' => true, 'message' => 'Carrito actualizado']);
        }
    }

    public function remove(Request $request)
    {
        if ($request->id) {
            $cart = session()->get('cart');
            if (isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
            }
            return response()->json(['success' => true, 'message' => 'Producto eliminado del carrito']);
        }
    }

    public function clear()
    {
        session()->forget('cart');
        return redirect()->route('cart.index')->with('success', 'Carrito vaciado');
    }

    public function calculateShipping(Request $request)
    {
        $request->validate([
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
            'direccion' => 'nullable|string'
        ]);

        $storeLat = env('STORE_LATITUDE', '-12.0545');
        $storeLng = env('STORE_LONGITUDE', '-77.0388');
        $apiKey = env('OPENROUTESERVICE_API_KEY');

        if (!$apiKey) {
            return response()->json(['success' => false, 'message' => 'API de rutas no configurada.']);
        }

        try {
            $response = Http::get("https://api.openrouteservice.org/v2/directions/driving-car", [
                'api_key' => $apiKey,
                'start' => "{$storeLng},{$storeLat}",
                'end' => "{$request->lng},{$request->lat}"
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                if (isset($data['features'][0]['properties']['segments'][0]['distance'])) {
                    $distanceMeters = $data['features'][0]['properties']['segments'][0]['distance'];
                    $distanceKm = round($distanceMeters / 1000); // Redondear a número entero
                    
                    // Si es <= 2km, o el costo por km es menor a 10, cobramos el base de 10.
                    // En caso contrario, 3 soles por KM.
                    if ($distanceKm <= 2) {
                        $costoEnvio = 10;
                    } else {
                        $costoEnvio = $distanceKm * 3;
                        // Asegurar que nunca sea menor al costo base
                        if ($costoEnvio < 10) $costoEnvio = 10;
                    }

                    // Save shipping info to session
                    session()->put('shipping_info', [
                        'tipo_entrega' => 'domicilio',
                        'costo_envio' => $costoEnvio,
                        'latitud' => $request->lat,
                        'longitud' => $request->lng,
                        'direccion' => $request->direccion
                    ]);

                    return response()->json([
                        'success' => true,
                        'costo' => $costoEnvio,
                        'distancia_km' => round($distanceKm, 2)
                    ]);
                }
            }

            return response()->json(['success' => false, 'message' => 'No se pudo calcular la ruta. Verifica las coordenadas.']);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al conectar con el servicio de rutas.']);
        }
    }

    public function geocode(Request $request)
    {
        $apiKey = env('OPENROUTESERVICE_API_KEY');
        $query = $request->get('text');
        
        if (!$apiKey || empty($query)) {
            return response()->json(['features' => []]);
        }

        try {
            $response = Http::get("https://api.openrouteservice.org/geocode/autocomplete", [
                'api_key' => $apiKey,
                'text' => $query,
                'boundary.country' => 'PE',
                'focus.point.lat' => -12.046374, // Lima
                'focus.point.lon' => -77.042793,
                'lang' => 'es'
            ]);
            
            return $response->json();
        } catch (\Exception $e) {
            return response()->json(['features' => []]);
        }
    }
}
