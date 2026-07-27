<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
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
            'cartCount' => count($cart)
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
}
