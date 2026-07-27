<?php

namespace App\Services;

use App\Models\Calculadora;
use App\Models\Comision;

class PreciosService implements PreciosServiceInterface
{
    protected $headerService;

    public function __construct(HeaderServiceInterface $headerService)
    {
        $this->headerService = $headerService;
    }

    public function getIgv($precio,$tipo,$producto)
    {
        $igv = Calculadora::where('idCalculadora',1)
                        ->value('igv');

        $precioIgv = $this->validateMoney($precio,$tipo,$producto)
                    * $this->porcent($igv);

        return $precioIgv;
    }


    public function getPrecioSinFacturar($precio,$grupo,$tipo,$producto){
        $comisiones = Comision::where('idGrupoProducto','=',$grupo)->get();

        foreach($comisiones as $comision){
            if($precio > $comision->RangoPrecio->rangoMin && $precio < $comision->RangoPrecio->rangoMax){
                return $this->getIgv($precio,$tipo,$producto) * $this->porcent($comision->comision);

            }
        }
    }


    public function getPrecioFacturado($precio,$grupo,$tipo,$producto)
    {
        $facturacion = Calculadora::where('idCalculadora',1)
                                ->value('facturacion');

        return $this->getPrecioSinFacturar($precio,$grupo,$tipo,$producto)
            * $this->porcent($facturacion);
    }


    public function getPromedio($precio,$grupo,$tipo,$producto){
        return ($this->getPrecioSinFacturar($precio,$grupo,$tipo,$producto) + $this->getPrecioFacturado($precio,$grupo,$tipo,$producto))/2;
    }

    public function getEspecialPrice($precio,$tipo,$producto){
            $totalPrecio = $this->getIgv($precio,$tipo,$producto) ;
        return $totalPrecio;
    }

    public function getPrecioCalculado($precio,$grupo,$tipo,$estado,$producto){
        if($estado == 'EXCLUSIVO' || $estado == 'OFERTA'){
            $total =  $this->getEspecialPrice($precio,$tipo,$producto);
        }else{
            $total = $this->getPromedio($precio,$grupo,$tipo,$producto);
        }

         return $total;
    }

    public function getPrecioTotal($precio,$grupo,$tipo,$estado,$ganancia,$producto){
        // Si el producto está en liquidación, anula los cálculos regulares y usa el precio final en Soles
        if ($producto->DetalleProducto && $producto->DetalleProducto->en_liquidacion && $producto->DetalleProducto->precio_liquidacion > 0) {
            $precioLiqSoles = $producto->DetalleProducto->precio_liquidacion;
            
            if ($tipo == 'SOL') {
                return $precioLiqSoles;
            } else {
                $tipoCambio = $this->getTipoCambioPorProducto($producto);
                return $tipoCambio > 0 ? ($precioLiqSoles / $tipoCambio) : $precioLiqSoles;
            }
        }

        $empresa = $this->headerService->obtenerEmpresa();
        $gananciaValidate = $this->validateMoney($ganancia,$tipo,$producto);
        $total = $this->getPrecioCalculado($precio,$grupo,$tipo,$estado,$producto) * $this->porcent($empresa->comision) + $gananciaValidate;
        return $total;
    }

    private function getTipoCambioPorProducto($producto)
    {
        // Si el producto tiene un tipo de cambio personalizado, usarlo prioritariamente
        if (!empty($producto->tc_fijo) && $producto->tc_fijo > 0) {
            return $producto->tc_fijo;
        }

        // $producto->usar_tc_fijo == 0 significa "Usar Tipo de Cambio Sunat" APAGADO (usar FIJO GLOBAL)
        // $producto->usar_tc_fijo == 1 significa "Usar Tipo de Cambio Sunat" ENCENDIDO (usar SUNAT)
        if(!$producto->usar_tc_fijo){
            return Calculadora::find(2)->tasaCambio; // FIJO
        }

        return $this->headerService->obtenerCambioDolar(); // SUNAT
    }

    private function validateMoney($precio,$tipo,$producto){
        $tipoCambio = $this->getTipoCambioPorProducto($producto);

        if($tipo == 'SOL'){
            return $precio * $tipoCambio;
        }else{
            return $precio;
        }
    }

    private function porcent($number){
        return 1 + ($number / 100);
    }
}
