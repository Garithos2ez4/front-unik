<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('PedidoWeb', function (Blueprint $table) {
            $table->string('tipo_entrega')->default('tienda'); // 'tienda', 'domicilio'
            $table->decimal('costo_envio', 10, 2)->default(0);
        });

        Schema::create('DireccionPedidoWeb', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pedido_web_id');
            $table->string('direccion');
            $table->decimal('latitud', 10, 8);
            $table->decimal('longitud', 11, 8);
            $table->timestamps();

            $table->foreign('pedido_web_id')
                  ->references('idPedidoWeb')
                  ->on('PedidoWeb')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('DireccionPedidoWeb');

        Schema::table('PedidoWeb', function (Blueprint $table) {
            $table->dropColumn(['tipo_entrega', 'costo_envio']);
        });
    }
};
