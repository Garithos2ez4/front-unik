<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\HeaderServiceInterface;
use App\Services\ProductoServiceInterface;

class NovedadesController extends Controller
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
        // We take the 30 newest items and paginate it effectively as 1 page 
        $productos = $this->productoService->getLatestProductsPagination(30, $request);
        
        //Lista de productos (Ajax para los filtros)
        if($request->query('page') || $request->query('filtro')){
            $responseAjax = $this->productoService->getAjaxListaProductos($request,$empresa,$productos);
            return $responseAjax;
        }
        
        //variables de los filtros
        $filtros = $this->productoService->getFiltrosLatest(30);
        
        return view('novedades',[
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
