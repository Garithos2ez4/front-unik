<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('PedidoWeb', function (Blueprint $table) {
            $table->id('idPedidoWeb');
            $table->integer('idCliente');
            $table->string('codigoTransaccion')->nullable(); // transaction ID from Niubiz or MercadoPago
            $table->string('pasarela')->nullable(); // 'niubiz', 'mercadopago'
            $table->decimal('total', 10, 2);
            $table->string('estado')->default('PENDIENTE'); // PENDIENTE, PAGADO, RECHAZADO
            $table->timestamps();
            
            $table->foreign('idCliente')
                  ->references('idCliente')
                  ->on('Cliente')
                  ->onDelete('cascade');
        });

        Schema::create('Detalle_PedidoWeb', function (Blueprint $table) {
            $table->id('idDetallePedidoWeb');
            $table->unsignedBigInteger('idPedidoWeb');
            $table->integer('idProducto');
            $table->integer('cantidad');
            $table->decimal('precio', 10, 2);
            $table->timestamps();

            $table->foreign('idPedidoWeb')
                  ->references('idPedidoWeb')
                  ->on('PedidoWeb')
                  ->onDelete('cascade');
            
            $table->foreign('idProducto')
                  ->references('idProducto')
                  ->on('Producto')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Detalle_PedidoWeb');
        Schema::dropIfExists('PedidoWeb');
    }
};
