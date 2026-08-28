<?php

namespace App\Http\Controllers;

use App\Services\HeaderService;
use App\Services\HeaderServiceInterface;

class TrabajaConNosotrosController extends Controller
{
    protected $headerService;

    public function __construct(HeaderServiceInterface $headerService)
    {
        $this->headerService = $headerService;
    }

    public function index()
    {
        // Variables para el header, nav y footer
        $categorias = $this->headerService->obtenerCategorias();
        $empresa = $this->headerService->obtenerEmpresa();
        $marcas = $this->headerService->obtenerMarcas();
        $tipos = $this->headerService->obtenerTipo();
        $tipoCambio = $this->headerService->obtenerCambioDolar();
        
        return view('trabaja-con-nosotros', [
            'categorias' => $categorias,
            'empresa' => $empresa,
            'marcas' => $marcas,
            'tipos' => $tipos,
            'tipoCambio' => $tipoCambio
        ]);
    }
}
