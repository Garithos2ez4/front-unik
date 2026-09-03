<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Quitar ON UPDATE CURRENT_TIMESTAMP de la columna para que cambios de precio no la modifiquen
        DB::statement("ALTER TABLE Producto MODIFY COLUMN updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP");

        // 2. Eliminar el trigger si ya existe
        DB::unprepared("DROP TRIGGER IF EXISTS trigger_actualizar_novedad_producto");

        // 3. Crear el trigger que solo actualiza si cambia modelo, partNumber, nombre o descripcion
        $triggerSql = <<<SQL
CREATE TRIGGER trigger_actualizar_novedad_producto 
BEFORE UPDATE ON Producto 
FOR EACH ROW 
BEGIN
    IF (NEW.modelo <=> OLD.modelo) = 0 
       OR (NEW.partNumber <=> OLD.partNumber) = 0 
       OR (NEW.nombreProducto <=> OLD.nombreProducto) = 0 
       OR (NEW.descripcionProducto <=> OLD.descripcionProducto) = 0 THEN
        SET NEW.updated_at = CURRENT_TIMESTAMP;
    END IF;
END;
SQL;
        DB::unprepared($triggerSql);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP TRIGGER IF EXISTS trigger_actualizar_novedad_producto");
        DB::statement("ALTER TABLE Producto MODIFY COLUMN updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP");
    }
};
