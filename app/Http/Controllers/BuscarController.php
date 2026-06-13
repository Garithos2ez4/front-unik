<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MarcaProducto;
use App\Models\Producto;
use App\Models\Caracteristicas_Producto;
use App\Services\BuscarServiceInterface;
use Illuminate\Support\Facades\DB;
use App\Services\HeaderService;
use App\Services\FiltroService;
use App\Services\HeaderServiceInterface;
use App\Services\ProductoServiceInterface;

class BuscarController extends Controller
{
    protected $headerService;
    protected $productoService;
    protected $buscarService;

    public function __construct(HeaderServiceInterface $headerService,
                                ProductoServiceInterface $productoService,
                                BuscarServiceInterface $buscarService)
    {
        $this->headerService = $headerService;
        $this->productoService = $productoService;
        $this->buscarService = $buscarService;
    }
    public function index(Request $request){
        //Variables para el header,nav y footer
        $categorias = $this->headerService->obtenerCategorias();
        $empresa = $this->headerService->obtenerEmpresa();
        $marcas = $this->headerService->obtenerMarcas();
        $tipos = $this->headerService->obtenerTipo();
        $tipoCambio = $this->headerService->obtenerCambioDolar();
        $tipoCambioFijo = $this->headerService->obtenerCambioDolarFijo();
        //Variables propias del controlador
        $obt = $request->input('header');
        if(!empty($obt)){


           session(['buscar' => $obt]);
        }

        $productos = $this->productoService->searchProductsMulti(session()->get('buscar', $obt), 32, $request);


        if($request->query('page') || $request->query('filtro')){
            $responseAjax = $this->productoService->getAjaxListaProductos($request,$empresa,$productos);
            return $responseAjax;
        }

        //variables de los filtros
        $filtros = $this->productoService->searchFiltros('nombreProducto',session()->get('buscar',$obt));

        return view('buscar',[
                    'categorias' => $categorias,
                    'empresa' => $empresa,
                    'marcas' => $marcas,
                    'tipos' => $tipos,
                    'tipoCambio' => $tipoCambio,
                    'tipoCambioFijo'=> $tipoCambioFijo,
                    'obt' => $obt,
                    'productos' => $productos,
                    'filtros' => $filtros
]);
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        $results = $this->buscarService->searchProducts($query);


        return response()->json($results);
    }
}
