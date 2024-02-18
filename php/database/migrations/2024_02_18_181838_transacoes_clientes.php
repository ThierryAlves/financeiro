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
        Schema::create('transacoes_clientes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_pagante_id')->references('id')->on('clientes');
            $table->foreignId('cliente_recebedor_id')->references('id')->on('clientes');
            $table->double('valor');
            $table->timestamp('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transacoes_clientes');
    }
};
