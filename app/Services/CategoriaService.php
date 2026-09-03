<?php

namespace App\Services;

use App\Repositories\CategoriaProductoRepositoryInterface;
use App\Repositories\GrupoProductoRepositoryInterface;
use App\Repositories\ProductoRepositoryInterface;
use App\Models\CategoriaProducto;

class CategoriaService implements CategoriaServiceInterface
{
    protected $categoriaRepository;
    protected $grupoRepository;
    protected $productoRepository;

    public function __construct(
        CategoriaProductoRepositoryInterface $categoriaRepository,
        GrupoProductoRepositoryInterface $grupoRepository,
        ProductoRepositoryInterface $productoRepository
    ) {
        $this->categoriaRepository = $categoriaRepository;
        $this->grupoRepository = $grupoRepository;
        $this->productoRepository = $productoRepository;
    }

    public function getCategoriaXSlug($slug)
    {
        return CategoriaProducto::with(['GrupoProducto' => function ($query) {
            $query->whereHas('Producto', function ($q) {
                $q->where('estadoProductoWeb', '<>', 'DESCONTINUADO');
            });
        }])->where('slugCategoria', '=', $slug)->first();
    }

    public function getGrupoXSlug($slugGrupo, $idCategoria = null)
    {
        if ($idCategoria) {
            return \App\Models\GrupoProducto::where('slugGrupo', $slugGrupo)
                ->where('idCategoria', $idCategoria)
                ->first();
        }
        return $this->grupoRepository->getOne('slugGrupo', $slugGrupo);
    }

    public function getProductoPaginationXGrupo($idGrupo)
    {
        return $this->productoRepository->getPaginationByColumn('idGrupo', $idGrupo, 24);
    }
}
