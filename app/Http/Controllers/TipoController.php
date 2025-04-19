<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TipoProducto;
use App\Models\Producto;
use App\Models\GrupoProducto;
use Illuminate\Support\Facades\DB;
use App\Services\HeaderService;
use App\Services\FiltroService;


class TipoController extends Controller
{
    public function index($slug,$grup,Request $request){
        //Variables para el header,nav y footer
        $header = new HeaderService();
        $categorias = $header->obtenerCategorias();
        $empresa = $header->obtenerEmpresa();
        $marcas = $header->obtenerMarcas();
        $tipos = $header->obtenerTipo();
        $redes = $header->obtenerLinkRedes();
        $tipoCambio = $header->obtenerCambioDolar();
        
        //Variables propias del controlador
        $slugTipo = TipoProducto::select('idTipoProducto')->where('slugTipo','=',$slug)->first();
        
        $grupos = DB::select('CALL sp_get_grupoxtipo(?)', [$slugTipo->idTipoProducto]);
        $tipo = TipoProducto::firstWhere('idTipoProducto', $slugTipo->idTipoProducto);
        $getProductos = DB::select('CALL sp_get_productoxgrupoxtipo(?,?)', [$slugTipo->idTipoProducto,$this->getIdGroup($grup)]);
        $modelProductos = Producto::hydrate($getProductos);
        $selectgroup = '';
        
        
        foreach($grupos as $grupo){
            if($grupo->idGrupoProducto == $this->getIdGroup($grup)){
                $selectgroup = $grupo;
            }
        }
        
        //variables para el filtro
        $filtro = new FiltroService();
        $parametrosFiltro = $filtro->parameterFilter($modelProductos);
        $productos = $filtro->productsFilter($modelProductos,$request);
        
        return view('tipo',[
                    'categorias' => $categorias,
                    'empresa' => $empresa,
                    'marcas' => $marcas,
                    'tipos' => $tipos,
                    'redes' => $redes,
                    'tipoCambio' => $tipoCambio,
                    'tipo' => $tipo,
                    'grupos' => $grupos,
                    'selectgroup' => $selectgroup,
                    'productos' => $productos,
                    'parametrosFiltro' => $parametrosFiltro
]);
    }
    
    private function getIdGroup($request){
        $grupo = GrupoProducto::select('idGrupoProducto')->where('slugGrupo','=',$request)->first();
        
        if(!empty($grupo)){
            return $grupo->idGrupoProducto;
        }else{
            return -1;
        }
    }
}