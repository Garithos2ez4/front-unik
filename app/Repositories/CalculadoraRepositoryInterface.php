<?php

namespace App\Repositories;

interface CalculadoraRepositoryInterface {
    public function updateTC($tc);
    public function get();
    public function findById(int $id);
}
