<?php
require __DIR__."/vendor/autoload.php";
$app = require_once __DIR__."/bootstrap/app.php";
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// 1. Force all products in Group 152 to DESCONTINUADO
App\Models\Producto::where('idGrupo', 152)->update(['estadoProductoWeb' => 'DESCONTINUADO']);

// 2. Fetch categories using the repository
$repo = app(App\Repositories\CategoriaProductoRepositoryInterface::class);
$categorias = $repo->getAll();

$found = false;
foreach($categorias as $cat) {
    if($cat->idCategoriaProducto == 12) { // Repuestos
        foreach($cat->GrupoProducto as $grupito) {
            if($grupito->idGrupoProducto == 152) {
                $found = true;
                echo "BUG: GROUP 152 FOUND EVEN THOUGH ALL PRODUCTS ARE DESCONTINUADO!\n";
            }
        }
    }
}

if(!$found) {
    echo "CORRECT: GROUP 152 WAS FILTERED OUT SUCCESSFULLY.\n";
}

// 3. Restore to DISPONIBLE so I don't break the local DB
App\Models\Producto::where('idGrupo', 152)->update(['estadoProductoWeb' => 'DISPONIBLE']);
