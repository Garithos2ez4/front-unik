<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Calculadora extends Model
{
    public $timestamps = false;
 
    protected $table = 'Calculadora';
    
    protected $primaryKey = 'idCalculadora';

    protected $guarded = ['idCalculadora'];
    
    protected $fillable = [
        'igv',
                            ];

    
    protected $hidden = [
        
    ];

    
    protected $casts = [
        'idCalculadora' => 'int',
        'igv' => 'float',
    ];

    /**
     * Obtener las relaciones del modelo.
     */
}