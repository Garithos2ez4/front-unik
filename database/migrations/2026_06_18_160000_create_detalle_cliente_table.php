<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Detalle_Cliente', function (Blueprint $table) {
            $table->id('idDetalleCliente');
            $table->integer('idCliente');
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('idCliente')
                  ->references('idCliente')
                  ->on('Cliente')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Detalle_Cliente');
    }
};
