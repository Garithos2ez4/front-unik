<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\TipoController;
use App\Http\Controllers\BuscarController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\NovedadesController;

use App\Http\Controllers\OfertasController;
use App\Http\Controllers\LiquidacionController;
use App\Http\Controllers\MedioDePagoController;
use App\Http\Controllers\EnviosController;
use App\Http\Controllers\ReviewsController;
use App\Http\Controllers\PrivacidadController;
use App\Http\Controllers\GarantiaController;
use App\Http\Controllers\NosotrosController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\LibroReclamacionController;

use App\Http\Controllers\StyleController;
use App\Http\Controllers\ScriptController;

use App\Http\Controllers\ClienteAuthController;
use App\Http\Controllers\TrabajaConNosotrosController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/trabaja-con-nosotros', [TrabajaConNosotrosController::class, 'index'])->name('trabaja-con-nosotros');
Route::get('/css/dynamic-styles.css', [StyleController::class, 'generateStyles'])->name('css.dynamic-styles');
Route::get('/css/slide-styles.css', [StyleController::class, 'carruselMarcasStyles'])->name('css.carrusel-marcas-styles');

Route::get('/js/slider-scripts/{titulo}/{slideSmall}/{slideMedio}.js', [ScriptController::class, 'sliderScript'])->name('js.slider-scripts');

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
Route::get('/novedades', [NovedadesController::class, 'index'])->name('novedades');

Route::get('/ofertas', [OfertasController::class, 'index'])->name('ofertas');
Route::get('/liquidacion', [LiquidacionController::class, 'index'])->name('liquidacion');
Route::get('/mediodepago', [MedioDePagoController::class, 'index'])->name('mediodepago');
Route::get('/envios', [EnviosController::class, 'index'])->name('envios');
Route::get('/reviews', [ReviewsController::class, 'index'])->name('reviews');
Route::post('/reviews', [ReviewsController::class, 'store'])->name('reviews.store');
Route::get('/reviews/cliente/{documento}', [ReviewsController::class, 'buscarCliente'])->name('reviews.cliente');
Route::get('/privacidad', [PrivacidadController::class, 'index'])->name('privacidad');
Route::get('/garantia', [GarantiaController::class, 'index'])->name('garantia');
Route::get('/nosotros', [NosotrosController::class, 'index'])->name('nosotros');
Route::get('/preguntas-frecuentes', [FaqController::class, 'index'])->name('faq');

Route::get('/libro-reclamaciones', [LibroReclamacionController::class, 'index'])->name('libroreclamacion');
Route::post('/nuevoreclamo', [LibroReclamacionController::class, 'createReclamo'])->name('insertreclamo');

Route::get('/sitemap.xml', [App\Http\Controllers\SitemapController::class, 'index'])->name('sitemap');

use App\Http\Controllers\CartController;

// ========================================
// Rutas del Carrito de Compras
// ========================================
Route::get('/carrito', [CartController::class, 'index'])->name('cart.index');
Route::post('/carrito/add/{id}', [CartController::class, 'add'])->name('cart.add');
Route::patch('/carrito/update', [CartController::class, 'update'])->name('cart.update');
Route::delete('/carrito/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::delete('/carrito/clear', [CartController::class, 'clear'])->name('cart.clear');

// ========================================
// Rutas de Checkout / Pasarelas de Pago
// ========================================
use App\Http\Controllers\CheckoutController;

Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout/manual', [CheckoutController::class, 'processManual'])->name('checkout.manual');
Route::get('/checkout/success/{pedido}', [CheckoutController::class, 'success'])->name('checkout.success');
Route::get('/checkout/failure/{pedido}', [CheckoutController::class, 'failure'])->name('checkout.failure');
Route::get('/checkout/pending/{pedido}', [CheckoutController::class, 'pending'])->name('checkout.pending');

// ========================================
// Rutas de Autenticacion de Clientes
// ========================================
Route::get('/cliente/login', [ClienteAuthController::class, 'showLogin'])->name('cliente.login.form');
Route::post('/cliente/login', [ClienteAuthController::class, 'login'])->name('cliente.login');
Route::get('/cliente/registro', [ClienteAuthController::class, 'showRegister'])->name('cliente.register.form');
Route::post('/cliente/registro', [ClienteAuthController::class, 'register'])->name('cliente.register');
Route::post('/cliente/logout', [ClienteAuthController::class, 'logout'])->name('cliente.logout');
Route::get('/api/cliente/buscar', [ClienteAuthController::class, 'searchClientByDocument'])->name('api.cliente.buscar');

Route::get('/laravel', function () {
    return view('welcome');
});
