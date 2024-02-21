<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tipo_clientes', function (Blueprint $table) {
            $table->id();
            $table->string('tipo');
        });

        DB::table('tipo_clientes')->insert(
            [
                [
                    'id' => 1,
                    'tipo' => 'Pessoa Fisica'
                ],
                [
                    'id' => 2,
                    'tipo' => 'Pessoa Juridica'
                ],
            ]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipo_clientes');
    }
};
