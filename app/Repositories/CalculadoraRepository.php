<?php

namespace App\Repositories;

use App\Models\Calculadora;
use Exception;

class CalculadoraRepository implements CalculadoraRepositoryInterface
{
    public function updateTC($tc){
        try{
            $calculadora = Calculadora::first(); // Captura el pirmer elemento de un array EN ESTE CASO EL PRIMER REGISTRO DEL OBJETO.
            $calculadora->tasaCambio = $tc; //No es una comparacion es una asiganicon o mutando el valor de un elemento o parametro.

            $calculadora->save();
        }catch(Exception $e){
            throw new \InvalidArgumentException("Error en la operacion. '$e'");
        }

    }

    //Metodo first() Consulta a la base de datos y retorna el primer registro o objeto eloquent  de la tabla o devuelve el primer resultado de la consulta
    public function get(){
        return Calculadora::first();
    }

    //Consulta dinamica
    //getOne solo es nombre de la funcion no afecta al metodo o consulta del mismo.
    //Con los parametros podemos elegir la columna y el dato para ejecutar la consulta con first() que nos retorna el primer registro o objeto
    //public function getOne($column, $data) {
    //    return Calculadora::where($column, '=', $data)->array_first();
    //}

    //Consulta explicita con el campo (id)
    public function findById(int $id)
    {
        return Calculadora::find($id);
    }

}
