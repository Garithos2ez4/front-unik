<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\HeaderServiceInterface;
use App\Services\ProductoServiceInterface;
use Illuminate\Support\Facades\URL;
// llamar a los models: use App\Models\CategoriaProducto;

class ProductoController extends Controller
{
    protected $headerService;
    protected $productoService;

    public function __construct(HeaderServiceInterface $headerService,
                                ProductoServiceInterface $productoService)
    {
        $this->headerService = $headerService;
        $this->productoService = $productoService;
    }
    public function index($product){
        //Variables para el header,nav y footer
        $categorias = $this->headerService->obtenerCategorias();
        $empresa = $this->headerService->obtenerEmpresa();
        $marcas = $this->headerService->obtenerMarcas();
        $tipos = $this->headerService->obtenerTipo();
        $tipoCambio = $this->headerService->obtenerCambioDolar();
        $tipoCambioFijo = $this->headerService->obtenerCambioDolarFijo();

        //Variables propias del controlador
        $producto = $this->productoService->getOneProducto($product);
        $miUrl = URL::current();

        $productosCategoria = $this->productoService->getProductosByCategoria($producto->GrupoProducto->idCategoria,17);

        return view('producto',[
                    'categorias' => $categorias,
                    'empresa' => $empresa,
                    'marcas' => $marcas,
                    'tipos' => $tipos,
                    'tipoCambio' => $tipoCambio,
                    'tipoCambioFijo' => $tipoCambioFijo,
                    'producto' => $producto,
                    'miUrl' => $miUrl,
                    'productosCategoria' => $productosCategoria
        ]);
    }
}
