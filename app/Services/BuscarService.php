<?php

namespace App\Services;

use App\Repositories\ProductoRepositoryInterface;

class BuscarService implements BuscarServiceInterface
{
    protected $productoRepository;
    protected $preciosService;

    public function __construct(ProductoRepositoryInterface $productoRepository,
                                PreciosServiceInterface $preciosService)
    {
        $this->productoRepository = $productoRepository;
        $this->preciosService = $preciosService;
    }

    public function searchProducts($query){
        $mapFunction = function ($producto) {
            $detalle = $producto->DetalleProducto ?? \App\Models\DetalleProducto::where('idProducto', $producto->idProducto)->first();
            $mostrarPrecio = $detalle ? (bool)$detalle->mostrarPrecioWeb : true;

            $producto->mostrarPrecioWeb = $mostrarPrecio;
            if (!$mostrarPrecio) {
                $producto->precioTotalDolar = "0";
                $producto->precioTotalSol = "0";
            } else {
                $producto->precioTotalDolar = $producto->precioTotalDolar($this->preciosService);
                $producto->precioTotalSol = $producto->precioTotalSol($this->preciosService);
            }
            $producto->imageUrls = $producto->publicImages();
            return $producto;
        };

        $productos = $this->productoRepository->searchCarrouselByColumn('nombreProducto', $query)
                                                ->take(6)
                                                ->map($mapFunction);

        if ($productos->isEmpty()) {
            $productos = $this->productoRepository->searchCarrouselByColumn('modelo', $query)
                                                    ->take(6)
                                                    ->map($mapFunction);
        }

        if ($productos->isEmpty()) {
            $productos = $this->productoRepository->searchCarrouselByColumn('partNumber', $query)
                                                    ->take(6)
                                                    ->map($mapFunction);
        }

        return $productos;
    }
}