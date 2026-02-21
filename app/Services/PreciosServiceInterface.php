<?php

namespace App\Services;

interface PreciosServiceInterface
{
    public function getIgv($precio,$tipo,$producto);
    public function getPrecioSinFacturar($precio,$grupo,$tipo,$producto);
    public function getPrecioFacturado($precio,$grupo,$tipo,$producto);
    public function getPromedio($precio,$grupo,$tipo,$producto);
    public function getEspecialPrice($precio,$tipo,$producto);
    public function getPrecioCalculado($precio,$grupo,$tipo,$estado,$producto);
    public function getPrecioTotal($precio,$grupo,$tipo,$estado,$ganancia,$producto);
}