<?php

namespace App\Repositories;

use App\Models\Caracteristicas;
use App\Models\Producto;

class ProductoRepository implements ProductoRepositoryInterface
{
    protected $modelColumns;

    public function __construct()
    {
        // Define las columnas válidas
        $this->modelColumns = (new Producto())->getFillable();
    }

    public function getAll(){
        return Producto::where('estadoProductoWeb','<>','DESCONTINUADO')->get();
    }

    public function getOne($column,$data){
        $this->validateColumns($column);
        return Producto::where('estadoProductoWeb','<>','DESCONTINUADO')->where($column,'=',$data)->first();
    }

    public function getAllByColumn($column,$data){
        $this->validateColumns($column);
        return Producto::where('estadoProductoWeb','<>','DESCONTINUADO')->where($column,'=',$data)->get();
    }

    public function searchByColumn($column,$data){
        $this->validateColumns($column);
        return Producto::where('estadoProductoWeb','<>','DESCONTINUADO')->where($column, 'LIKE', '%' . $data . '%')->get();
    }

    public function getPaginationByColumn($column,$data,$cant,array $querys){
        $query = Producto::query();
        $query->where('estadoProductoWeb','<>','DESCONTINUADO');
        $query->where($column,'=',$data);
        if($querys){
            if (isset($querys['caracteristicas'])) {
                $query->join('Caracteristicas_Producto','Caracteristicas_Producto.idProducto','=','Producto.idProducto')
                    ->whereIn('Caracteristicas_Producto.caracteristicaProducto',$querys['caracteristicas']);
            }

            if (isset($querys['dispo'])) {
                $query->whereIn('estadoProductoWeb', $querys['dispo']);
            }

            if (isset($querys['marcas'])) {
                $query->whereIn('idMarca', $querys['marcas']);
            }

            if (isset($querys['grupos'])) {
                $query->whereIn('idGrupo', $querys['grupos']);
            }

            if(isset($querys['orden'])){
                $query->orderBy('precioDolar', $querys['orden']);
            }

            if(isset($querys['ordensmall'])){
                $query->orderBy('precioDolar', $querys['ordensmall']);
            }

        }
        return   $query->paginate($cant);
    }

    public function getLatestProductsPagination($limit, array $querys){
        $latestIds = Producto::where('estadoProductoWeb','<>','DESCONTINUADO')
            ->orderBy('idProducto', 'desc')
            ->take($limit)
            ->pluck('idProducto');

        $query = Producto::query()->whereIn('Producto.idProducto', $latestIds);

        if($querys){
            if (isset($querys['caracteristicas'])) {
                $query->join('Caracteristicas_Producto','Caracteristicas_Producto.idProducto','=','Producto.idProducto')
                    ->whereIn('Caracteristicas_Producto.caracteristicaProducto',$querys['caracteristicas'])
                    ->select('Producto.*')->distinct();
            }

            if (isset($querys['dispo'])) {
                $query->whereIn('estadoProductoWeb', $querys['dispo']);
            }

            if (isset($querys['marcas'])) {
                $query->whereIn('idMarca', $querys['marcas']);
            }

            if (isset($querys['grupos'])) {
                $query->whereIn('idGrupo', $querys['grupos']);
            }

            if(isset($querys['orden'])){
                $query->orderBy('precioDolar', $querys['orden']);
            }
        }
        return $query->paginate($limit);
    }

    public function searchPaginationByColumn($column,$data,$cant,array $querys){
        $query = Producto::query();
        $query->where('estadoProductoWeb','<>','DESCONTINUADO');
        $query->where($column,'LIKE', '%' . $data . '%');
        if($querys){
            if (isset($querys['caracteristicas'])) {
                $query->join('Caracteristicas_Producto','Caracteristicas_Producto.idProducto','=','Producto.idProducto')
                    ->whereIn('Caracteristicas_Producto.caracteristicaProducto',$querys['caracteristicas']);
            }

            if (isset($querys['dispo'])) {
                $query->whereIn('estadoProductoWeb', $querys['dispo']);
            }

            if (isset($querys['marcas'])) {
                $query->whereIn('idMarca', $querys['marcas']);
            }

            if (isset($querys['grupos'])) {
                $query->whereIn('idGrupo', $querys['grupos']);
            }

            if(isset($querys['orden'])){
                $query->orderBy('precioDolar', $querys['orden']);
            }

        }
        return   $query->paginate($cant);
    }

    public function searchPaginationMultiColumn($data, $cant, array $querys){
        $query = Producto::query();
        $query->where('estadoProductoWeb','<>','DESCONTINUADO');
        $query->where(function($q) use ($data) {
            $q->where('partNumber','LIKE', '%' . $data . '%')
              ->orWhere('nombreProducto','LIKE', '%' . $data . '%')
              ->orWhere('modelo','LIKE', '%' . $data . '%');
        });

        if($querys){
            if (isset($querys['caracteristicas'])) {
                $query->join('Caracteristicas_Producto','Caracteristicas_Producto.idProducto','=','Producto.idProducto')
                    ->whereIn('Caracteristicas_Producto.caracteristicaProducto',$querys['caracteristicas'])
                    ->select('Producto.*')
                    ->distinct();
            }

            if (isset($querys['dispo'])) {
                $query->whereIn('estadoProductoWeb', $querys['dispo']);
            }

            if (isset($querys['marcas'])) {
                $query->whereIn('idMarca', $querys['marcas']);
            }

            if (isset($querys['grupos'])) {
                $query->whereIn('idGrupo', $querys['grupos']);
            }

            if(isset($querys['orden'])){
                $query->orderBy('precioDolar', $querys['orden']);
            }
        }

        return $query->paginate($cant);
    }

    public function getAllByCategoria($idCategoria){
        return Producto::join('GrupoProducto','GrupoProducto.idGrupoProducto','=','Producto.idGrupo')
                        ->select('Producto.*')->where('Producto.estadoProductoWeb','<>','DESCONTINUADO')
                        ->where('GrupoProducto.idCategoria','=',$idCategoria)
                        ->get();
    }

    public function getLatestProducts($limit = 15) {
        return Producto::where('estadoProductoWeb','<>','DESCONTINUADO')
                       ->orderBy('idProducto', 'desc')
                       ->take($limit)
                       ->get();
    }

    public function getSpectsByColumn($column,$data){
        return Producto::with('Caracteristicas_Producto.Caracteristicas')->where('estadoProductoWeb','<>','DESCONTINUADO')->where($column,'=',$data)->get();
    }

    public function create(array $data){
        return Producto::create($data);
    }

    public function update($id, array $data){
        $producto = Producto::findOrFail($id);
        $producto->update($data);
        return $producto;
    }

    private function validateColumns($column){
        if (!in_array($column, $this->modelColumns)) {
            throw new \InvalidArgumentException("La columna '$column' no es válida.");
        }
    }

    public function getCarrouselAllByColumn($column, $data) {
        $this->validateColumns($column);
        return Producto::conHistorialRegistro()->where('estadoProductoWeb','<>','DESCONTINUADO')->where($column,'=',$data)->get();
    }

    public function getCarrouselAllByCategoria($idCategoria) {
        return Producto::conHistorialRegistro()
                        ->join('GrupoProducto','GrupoProducto.idGrupoProducto','=','Producto.idGrupo')
                        ->select('Producto.*')->where('Producto.estadoProductoWeb','<>','DESCONTINUADO')
                        ->where('GrupoProducto.idCategoria','=',$idCategoria)
                        ->get();
    }

    public function searchCarrouselByColumn($column, $data) {
        $this->validateColumns($column);
        return Producto::conHistorialRegistro()->where('estadoProductoWeb','<>','DESCONTINUADO')->where($column, 'LIKE', '%' . $data . '%')->get();
    }

    public function getLatestCarrouselProducts($limit = 15) {
        return Producto::conHistorialRegistro()
                       ->where('estadoProductoWeb','<>','DESCONTINUADO')
                       ->orderBy('idProducto', 'desc')
                       ->take($limit)
                       ->get();
    }
}