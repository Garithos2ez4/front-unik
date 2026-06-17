<?php

namespace App\Services;

use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

interface ProductoServiceInterface
{
    public function getOneProducto($slug);
    public function getProductosByCategoria($idCategoria,$cantidad);
    public function getAjaxListaProductos(Request $request,Empresa $empresa,LengthAwarePaginator $productos);
    public function getFiltros($column,$data);
    public function searchFiltros($column,$data);
    public function getProductsFilter($column,$data,$cantidad,Request $request);
    public function searchProductsFilter($column,$data,$cantidad,Request $request);
    public function searchProductsMulti($data,$cantidad,Request $request);
    public function getLatestProductsPagination($limit, Request $request);
    public function getFiltrosLatest($limit);
    public function getLatestProducts($limit = 15);
}