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
        $productos = $this->productoRepository->searchCarrouselByColumn('nombreProducto',$query)
                                                ->take(6)
                                                ->map(function ($producto) {
                                                    // Agregar las URLs de las imgenes al producto
                                                    $producto->precioTotalDolar = $producto->precioTotalDolar($this->preciosService);
                                                    $producto->precioTotalSol = $producto->precioTotalSol($this->preciosService);
                                                    $producto->imageUrls = $producto->publicImages();
                                                    return $producto;
                                                });
        if($productos->isEmpty()){
            $productos = $this->productoRepository->searchCarrouselByColumn('modelo',$query)
                                                    ->take(6)
                                                    ->map(function ($producto) {
                                                        // Agregar las URLs de las imgenes al producto
                                                        $producto->precioTotalDolar = $producto->precioTotalDolar($this->preciosService);
                                                        $producto->precioTotalSol = $producto->precioTotalSol($this->preciosService);
                                                        $producto->imageUrls = $producto->publicImages();
                                                        return $producto;
                                                    });
        }
        return $productos;
    }
}