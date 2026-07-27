<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class DetalleCliente extends Authenticatable
{
    protected $table = 'Detalle_Cliente';
    protected $primaryKey = 'idDetalleCliente';

    protected $fillable = [
        'idCliente',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'idCliente', 'idCliente');
    }
}
