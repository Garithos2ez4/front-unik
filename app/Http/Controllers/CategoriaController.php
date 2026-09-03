<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CategoriaServiceInterface;
use App\Services\HeaderServiceInterface;
use App\Services\ProductoServiceInterface;

class CategoriaController extends Controller
{
    protected $headerService;
    protected $categoriaService;
    protected $productoService;

    public function __construct(HeaderServiceInterface $headerService,
                                CategoriaServiceInterface $categoriaService,
                                ProductoServiceInterface $productoService)
    {
        $this->headerService = $headerService;
        $this->categoriaService = $categoriaService;
        $this->productoService = $productoService;
    }
    public function index($cat,$grup,Request $request){
        //Variables para el header,nav y footer
        $categorias = $this->headerService->obtenerCategorias();
        $empresa = $this->headerService->obtenerEmpresa();
        $marcas = $this->headerService->obtenerMarcas();
        $tipos = $this->headerService->obtenerTipo();
        $tipoCambio = $this->headerService->obtenerCambioDolar();
        
        //Variables propias del controlador
        $categoria = $this->categoriaService->getCategoriaXSlug($cat);
        
        if (!$categoria) {
            abort(404);
        }

        $grupo = $this->categoriaService->getGrupoXSlug($grup, $categoria->idCategoriaProducto);

        if (!$grupo) {
            abort(404);
        }

        $productos = $this->productoService->getProductsFilter('idGrupo',$grupo->idGrupoProducto,24,$request);
        //Lista de productos paginados
        if($request->ajax()){
            $responseAjax = $this->productoService->getAjaxListaProductos($request,$empresa,$productos);
            return $responseAjax;
        }
        //variables de los filtros
        $filtros = $this->productoService->getFiltros('idGrupo',$grupo->idGrupoProducto);
        return view('categoria',[
                    'categorias' => $categorias,
                    'empresa' => $empresa,
                    'marcas' => $marcas,
                    'tipos' => $tipos,
                    'tipoCambio' => $tipoCambio,
                    'categoria' => $categoria,
                    'grupo' => $grupo,
                    'productos' => $productos,
                    'filtros' => $filtros
        ]);
    }
}