<?php
namespace App\Services;

interface HeaderServiceInterface  {
    public function obtenerEmpresa();

    public function obtenerCategorias();

    public function obtenerMarcas();

    public function obtenerTipo();

    public function getApiDolar();

    function updateTipoCambio($backup);

    function obtenerCambioDolar();

    function obtenerCambioDolarFijo();
}
