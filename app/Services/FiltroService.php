<?php

namespace App\Services;

use App\Models\Producto;
use Illuminate\Http\Request;

class FiltroService
{
    // Este método generará los parámetros de filtro basados en los productos dados
    public function parameterFilter($productos)
    {
        // Aquí, puedes usar la lógica que desees para extraer los filtros
        $disponibilidad = $productos->unique('estadoProductoWeb')->pluck('estadoProductoWeb');
        $marcas = $productos->load('MarcaProducto')->pluck('MarcaProducto')->unique('idMarca'); 
        $grupos = $productos->load('GrupoProducto')->pluck('GrupoProducto')->unique('idGrupoProducto');
        $precios = $productos->map(function ($producto) {
            return floatval(str_replace(',', '', $producto->precioTotalSol()));
        });
        $precioMax = $precios->max();
        $precioMin = $precios->min();
        
        // Agrupar las características de los productos
        $especificaciones = $productos->load('Caracteristicas_Producto.Caracteristicas')
            ->pluck('Caracteristicas_Producto.*')
            ->flatten()
            ->unique('caracteristicaProducto');

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
                    'tipo' => $tipoCaracteristica
                ];
            });

        // Devolver los filtros
        return [
            'disponibilidad' => $disponibilidad,
            'marcas' => $marcas,
            'grupos' => $grupos,
            'precioMin' => $precioMin,
            'precioMax' => $precioMax,
            'caracteristicas' => $caracteristicas
        ];
    }

    // Este método filtrará los productos según los filtros recibidos en la solicitud
    public function productsFilter($productos, Request $request)
    {
        // Aquí se puede agregar la lógica de los filtros de productos basada en los parámetros de la solicitud
        // Ejemplo de filtrado por precios:
        if ($request->has('precioMin') && $request->has('precioMax')) {
            $productos = $productos->whereBetween('precioTotalSol', [$request->input('precioMin'), $request->input('precioMax')]);
        }

        // Filtrar por marca, si existe el filtro
        if ($request->has('marca')) {
            $productos = $productos->whereHas('MarcaProducto', function ($query) use ($request) {
                $query->where('idMarca', $request->input('marca'));
            });
        }

        // Puedes agregar más filtros según lo que necesites (como grupo, disponibilidad, etc.)

        // Devuelve los productos filtrados
        return $productos;
    }
}
