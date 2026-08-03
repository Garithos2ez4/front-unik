<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\HeaderService;
use App\Services\HeaderServiceInterface;
use App\Services\ProductoServiceInterface;

// llamar a los models: use App\Models\CategoriaProducto;

class OfertasController extends Controller
{
    protected $headerService;
    protected $productoService;

    public function __construct(HeaderServiceInterface $headerService,
                                ProductoServiceInterface $productoService)
    {
        $this->headerService = $headerService;
        $this->productoService = $productoService;
    }

    public function index(Request $request){
        //Variables para el header,nav y footer
        $categorias = $this->headerService->obtenerCategorias();
        $empresa = $this->headerService->obtenerEmpresa();
        $marcas = $this->headerService->obtenerMarcas();
        $tipos = $this->headerService->obtenerTipo();
        $tipoCambio = $this->headerService->obtenerCambioDolar();
        
        //Variables propias del controlador
        $productos = $this->productoService->getProductsFilter('estadoProductoWeb','OFERTA',24,$request);
        //Lista de productos paginados
        if($request->ajax()){
            $responseAjax = $this->productoService->getAjaxListaProductos($request,$empresa,$productos);
            return $responseAjax;
        }
        //variables de los filtros
        $filtros = $this->productoService->getFiltros('estadoProductoWeb','OFERTA');
        
        return view('ofertas',[
                    'categorias' => $categorias,
                    'empresa' => $empresa,
                    'marcas' => $marcas,
                    'tipos' => $tipos,
                    'tipoCambio' => $tipoCambio,
                    'productos' => $productos,
                    'filtros' => $filtros
]);
    }
}