<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'Cliente';
    protected $primaryKey = 'idCliente';
    public $timestamps = false; // Usually client tables from other systems don't use standard timestamps unless specified

    protected $fillable = [
        'nombre',
        'apellidoPaterno',
        'apellidoMaterno',
        'numeroDocumento',
        'idTipoDocumento',
        'telefono',
        'correo'
    ];
}
