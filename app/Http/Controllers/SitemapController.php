<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\CategoriaProducto;
use App\Models\MarcaProducto;

class SitemapController extends Controller
{
    public function index()
    {
        $productos = Producto::where('estadoProductoWeb', '<>', 'DESCONTINUADO')->get();
        $categorias = CategoriaProducto::all();
        $marcas = MarcaProducto::all();

        return response()->view('sitemap', [
            'productos' => $productos,
            'categorias' => $categorias,
            'marcas' => $marcas,
        ])->header('Content-Type', 'text/xml');
    }
}
