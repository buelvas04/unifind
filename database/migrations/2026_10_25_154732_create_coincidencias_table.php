<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coincidencias', function (Blueprint $table) {
            $table->id();

            $table->foreignId('objeto_perdido_id')
                ->constrained('objetos_perdidos')
                ->cascadeOnDelete();

            $table->foreignId('objeto_encontrado_id')
                ->constrained('objetos_encontrados')
                ->cascadeOnDelete();

            $table->decimal('nivel_coincidencia', 5, 2)
                ->nullable();

            $table->timestamps();

            $table->unique(
                ['objeto_perdido_id', 'objeto_encontrado_id'],
                'coincidencias_objetos_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coincidencias');
    }
};