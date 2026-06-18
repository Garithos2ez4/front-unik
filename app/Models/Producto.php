<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Producto extends Model
{
    public $timestamps = false;
 
    protected $table = 'Producto';
    
    protected $primaryKey = 'idProducto';

    protected $guarded = ['idProducto'];
    
    protected $fillable = ['idProducto',
                            'idMarca',
                            'idGrupo',
                            'nombreProducto',
                            'codigoProducto',
                            'UPC',
                            'partNumber',
                            'numeroSerie',
                            'modelo',
                            'precioDolar',
                            'gananciaExtra',
                            'stockTienda',
                            'stockColombia',
                            'stockProveedor',
                            'idProveedor',
                            'garantia',
                            'descripcionProducto',
                            'imagenProducto1',
                            'imagenProducto2',
                            'imagenProducto3',
                            'imagenProducto4',
                            'videoUrl1',  
                            'videoUrl2',
                            'estadoProductoWeb',
                            'slugProducto',
                            'usar_tc_fijo'
                            ];

    
    protected $hidden = [
        
    ];

    
    protected $casts = [
        'idProducto' => 'int',
        'idMarca' => 'int',
        'idGrupo' => 'int',
        'gananciaExtra' => 'decimal:2',
        'precioDolar' => 'decimal:2',
        'stockTienda' => 'int',
        'stockColombia' => 'int',
        'stockProveedor' => 'int',
        'idProveedor' => 'int',
        'usar_tc_fijo' => 'boolean'
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($producto) {
            $producto->slugProducto = Str::slug($producto->nombreProducto);
        });

        static::updating(function ($producto) {
            $producto->slugProducto = Str::slug($producto->nombreProducto);
        });
    }

    public function scopeConHistorialRegistro($query)
    {
        return $query->whereExists(function ($subquery) {
            $subquery->select(\Illuminate\Support\Facades\DB::raw(1))
                     ->from('RegistroProducto')
                     ->join('DetalleComprobante', 'DetalleComprobante.idDetalleComprobante', '=', 'RegistroProducto.idDetalleComprobante')
                     ->whereColumn('DetalleComprobante.idProducto', 'Producto.idProducto');
        });
    }
    
    public function Publicacion()
    {
        return $this->hasMany(Publicacion::class, 'idProducto', 'idProducto');
    }

    public function MarcaProducto()
    {
        return $this->belongsTo(MarcaProducto::class,'idMarca','idMarca');
    }
    
    public function GrupoProducto()
    {
        return $this->belongsTo(GrupoProducto::class, 'idGrupo', 'idGrupoProducto');
    }
    
    public function Preveedor()
    {
        return $this->belongsTo(Preveedor::class, 'idProveedor', 'idProveedor');
    }

    public function Inventario()
    {
        return $this->hasMany(Inventario::class, 'idProducto', 'idProducto');
    }
    
    public function Caracteristicas_Producto()
    {
        return $this->hasMany(Caracteristicas_Producto::class, 'idProducto', 'idProducto');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'idProducto', 'idProducto')->where('estado', 1);
    }
    
    public function precioTotalDolar($preciosService)
    {
        return number_format($preciosService->getPrecioTotal($this->precioDolar, $this->idGrupo, 'DOLAR', $this->estadoProductoWeb, $this->gananciaExtra,$this), 1, '.', ',') . '0';
    }

    public function precioTotalSol($preciosService)
    {
        return number_format($preciosService->getPrecioTotal($this->precioDolar, $this->idGrupo, 'SOL', $this->estadoProductoWeb, $this->gananciaExtra,$this), 1, '.', ',') . '0';
    }
    
    public function publicImages(){
        
            $default = asset('storage/noimagen.webp');
    
            $imagen1 = $this->imagenProducto1 ? asset('storage/'.$this->imagenProducto1) : $default;
            $imagen2 = $this->imagenProducto2 ? asset('storage/'.$this->imagenProducto2) : $default;
            $imagen3 = $this->imagenProducto3 ? asset('storage/'.$this->imagenProducto3) : $default;
            $imagen4 = $this->imagenProducto4 ? asset('storage/'.$this->imagenProducto4) : $default;
    
            $images = [$imagen1, $imagen2, $imagen3, $imagen4];
            
    
        return $images;
    }
    
    public function estadoColor(){
        switch($this->estadoProductoWeb){
            case 'DISPONIBLE':
                return 'text-success';
            case 'OFERTA':
                return 'text-oferta';
            case 'AGOTADO':
                return 'text-agotado text-decoration-line-through';
            case 'DESCONTINUADO':
                return 'text-dark text-decoration-line-through';
            default:
                return 'text-dark';
        }
    }
    
    public function displayImg($img){
        if($img == "asset('storage/images/noimagen.webp')"){
            return "d-none";
        }else{
            return "";
        }
    }
    public function publicVideos()
    {
        return array_filter([
            $this->videoUrl1,
            $this->videoUrl2
        ]);
    }
    public function getYoutubeThumbnail($videoId)
{
    if (empty($videoId)) return null;

    // Opcional: Validar que sea un ID de 11 caracteres
    if (!preg_match('/^[\w-]{11}$/', $videoId)) return null;

    return "https://img.youtube.com/vi/{$videoId}/hqdefault.jpg";
}


    public function getYoutubeEmbed($videoId)
{
    if (empty($videoId)) return null;

    // Validar que sea un ID válido (11 caracteres alfanum + guiones)
    if (!preg_match('/^[\w-]{11}$/', $videoId)) return null;

    // URL para iframe embed de YouTube con autoplay desactivado por defecto
    return "https://www.youtube.com/embed/{$videoId}";
}


}