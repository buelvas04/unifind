<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('solicitudes_recuperacion', function (Blueprint $table) {
            $table->id();

            $table->foreignId('usuario_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->foreignId('objeto_encontrado_id')
                ->constrained('objetos_encontrados')
                ->restrictOnDelete();

            $table->string('estado', 30)
                ->default('pendiente');

            $table->timestamps();

            $table->unique(
                ['usuario_id', 'objeto_encontrado_id'],
                'solicitudes_usuario_objeto_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('solicitudes_recuperacion');
    }
};