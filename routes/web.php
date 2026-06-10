<?php
use App\Models\Producto;
use App\Models\CategoriaProducto; 
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\TipoController;
use App\Http\Controllers\BuscarController;
use App\Http\Controllers\ProductoController;

use App\Http\Controllers\OfertasController;
use App\Http\Controllers\MedioDePagoController;
use App\Http\Controllers\EnviosController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ReviewsController;
use App\Http\Controllers\PrivacidadController;
use App\Http\Controllers\GarantiaController;
use App\Http\Controllers\NosotrosController;
use App\Http\Controllers\LibroReclamacionController;

use App\Http\Controllers\StyleController;
use App\Http\Controllers\ScriptController;

Route::get('/', [HomeController::class,'index']);
Route::get('/css/dynamic-styles.css', [StyleController::class, 'generateStyles'])->name('css.dynamic-styles');
Route::get('/css/slide-styles.css', [StyleController::class, 'carruselMarcasStyles'])->name('css.carrusel-marcas-styles');

Route::get('/js/slider-scripts/{titulo}/{slideSmall}/{slideMedio}.js', [ScriptController::class, 'sliderScript'])->name('js.slider-scripts');
Route::get('/js/header-scripts.js', [ScriptController::class, 'headerScripts'])->name('js.header-scripts');
Route::get('/js/filter-scripts.js', [ScriptController::class, 'filterScripts'])->name('js.filter-scripts');
Route::get('/js/pagination-scripts.js', [ScriptController::class, 'paginationScript'])->name('js.pagination-scripts');
Route::get('/js/carrusel-marcas-scripts.js', [ScriptController::class, 'carruselMarcasScript'])->name('js.carrusel-marcas-scripts');
Route::get('/js/reclamos-scripts.js', [ScriptController::class, 'reclamosScript'])->name('js.reclamos-scripts');


Route::get('/categoria/{cat}/{grup}', [CategoriaController::class, 'index'])->name('categoria');
//Route::get('/productos-component', [CategoriaController::class, 'productosComponent'])->name('productos-component');
Route::get('/marca/{marca}', [MarcaController::class, 'index'])->name('marca');
Route::get('/tipo/{tipo}/{tipGrup}', [TipoController::class, 'index'])->name('tipo');
Route::get('/buscar', [BuscarController::class, 'index'])->name('buscar');
Route::get('/buscar/search', [BuscarController::class, 'search'])->name('search');
Route::get('/producto/{slug}', [ProductoController::class, 'index'])->name('producto');

Route::get('/ofertas', [OfertasController::class,'index'])->name('ofertas');
Route::get('/mediodepago', [MedioDePagoController::class,'index'])->name('mediodepago');
Route::get('/envios', [EnviosController::class,'index'])->name('envios');
Route::get('/reviews', [ReviewsController::class,'index'])->name('reviews');
Route::post('/reviews', [ReviewsController::class,'store'])->name('reviews.store');
Route::get('/reviews/cliente/{documento}', [ReviewsController::class,'buscarCliente'])->name('reviews.cliente');
Route::get('/privacidad', [PrivacidadController::class,'index'])->name('privacidad');
Route::get('/garantia', [GarantiaController::class,'index'])->name('garantia');
Route::get('/nosotros', [NosotrosController::class,'index'])->name('nosotros');

Route::get('/libro-reclamaciones', [LibroReclamacionController::class,'index'])->name('libroreclamacion');
Route::post('/nuevoreclamo', [LibroReclamacionController::class,'createReclamo'])->name('insertreclamo');

Route::get('/laravel', function () {
    return view('welcome');
});

