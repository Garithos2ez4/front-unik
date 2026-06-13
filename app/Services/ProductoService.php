<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\Empresa;
use App\Repositories\CategoriaProductoRepositoryInterface;
use App\Repositories\ProductoRepositoryInterface;
use App\Repositories\TipoProductoRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductoService implements ProductoServiceInterface
{
    protected $productoRepository;
    protected $preciosService;
    protected $categoriaRepository;
    protected $tipoRepository;

    public function __construct(ProductoRepositoryInterface $productoRepository,
                                PreciosServiceInterface $preciosService,
                                CategoriaProductoRepositoryInterface $categoriaRepository,
                                TipoProductoRepositoryInterface $tipoRepository)
    {
        $this->productoRepository = $productoRepository;
        $this->preciosService = $preciosService;
        $this->categoriaRepository = $categoriaRepository;
        $this->tipoRepository = $tipoRepository;
    }

    public function getOneProducto($slug){
        return $this->productoRepository->getOne('slugProducto',$slug);
    }

    public function getProductosByCategoria($idCategoria,$cantidad){
        $categoria = $this->categoriaRepository->getOne('idCategoria',$idCategoria);
        $productos = $categoria->GrupoProducto->pluck('Producto')->flatten();

        $productosFiltrados = $productos->filter(function ($producto) {
            return $producto->estadoProductoWeb !== 'DESCONTINUADO';
        });

        $productosFinales = $productosFiltrados->shuffle()->take($cantidad);
        return $productosFinales;
    }
    public function getAjaxListaProductos(Request $request, Empresa $empresa, LengthAwarePaginator $productos)
    {
        $colmedio = $request->query('colmedio');
        $colsmall = $request->query('colsmall');
        $empres = $empresa;
        return response()->json([
            'html' => view('components.partials.lista-productos', compact('productos', 'colmedio','colsmall','empres'))->render(),
            'paginas' => $productos->total()
        ]);
    }

    public function getFiltros($column,$data) { 
        $productos = $this->productoRepository->getAllByColumn($column,$data);
        $filtros = $this->getParametros($productos);
        return $filtros;
    }

    public function searchFiltros($column,$data) { 
        $productos = $this->productoRepository->searchByColumn($column,$data);
        $filtros = $this->getParametros($productos);
        return $filtros;
    }

    public function getProductsFilter($column,$data,$cantidad,Request $request){
        $consultas = array();
        if($request->query('filtro')){
            $consultas = $request->query('filtro');
        }
        $productos = $this->productoRepository->getPaginationByColumn($column,$data,$cantidad,$consultas);
        return $productos;
    }

    public function searchProductsFilter($column,$data,$cantidad,Request $request){
        $consultas = array();
        if($request->query('filtro')){
            $consultas = $request->query('filtro');
        }
        $productos = $this->productoRepository->searchPaginationByColumn($column,$data,$cantidad,$consultas);
        return $productos;
    }

    public function searchProductsMulti($data,$cantidad,Request $request){
        $consultas = array();
        if($request->query('filtro')){
            $consultas = $request->query('filtro');
        }
        $productos = $this->productoRepository->searchPaginationMultiColumn($data,$cantidad,$consultas);
        return $productos;
    }

    private function getParametros($productos){
        $disponibilidad = $productos->unique('estadoProductoWeb')->pluck('estadoProductoWeb');
        $marcas = $productos->load('MarcaProducto')->pluck('MarcaProducto')->unique('idMarca'); 
        $grupos = $productos->load('GrupoProducto')->pluck('GrupoProducto')->unique('idGrupoProducto');
        $precios = $productos->map(function ($producto) {
                                    return floatval(str_replace(',', '', $producto->precioTotalSol($this->preciosService)));
                                    });
        $precioMax = $precios->max();
        $precioMin = $precios->min();
        $especificaciones = $productos->load('Caracteristicas_Producto.Caracteristicas')
                                    ->pluck('Caracteristicas_Producto.*')
                                    ->flatten()
                                    ->unique('caracteristicaProducto');

        // $especificaciones = $productos->load('Caracteristicas_Producto');
        // dd($productos);

        $caracteristicas = $especificaciones->groupBy('Caracteristicas.idCaracteristica')
                                            ->sortBy(function ($spect) {
                                                return $spect->first()->Caracteristicas->idCaracteristica; 
                                            })->map(function ($group) {
                                                $idCaracteristica = $group->first()->Caracteristicas->idCaracteristica;
                                                $nombreCaracteristica = $group->first()->Caracteristicas->especificacion;
                                                $tipoCaracteristica = $group->first()->Caracteristicas->tipo;
                                                return [
                                                    'id' => $idCaracteristica,
                                                    'nombre' => $nombreCaracteristica,
                                                    'especificaciones' => $group,
                                                    'tipo' =>$tipoCaracteristica
                                                ];
                                            });
        $filtros = ['disponibilidad' => $disponibilidad,
                    'marcas' => $marcas,
                    'grupos' => $grupos,
                    'precioMin' => $precioMin,
                    'precioMax' => $precioMax,
                    'caracteristicas' => $caracteristicas];
        return $filtros;
    }
}