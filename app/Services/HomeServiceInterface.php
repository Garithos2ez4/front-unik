<?php

namespace App\Services;

interface HomeServiceInterface {
    public function getPublicidad($idEmpresa);
    public function getProductsByLaptopGamers();
    public function getProductsByMonitores();
    public function getProductsByImpresoras();
    public function getAccesorios();
    public function getExclusivos();
    public function getProductsBySuministros();
}