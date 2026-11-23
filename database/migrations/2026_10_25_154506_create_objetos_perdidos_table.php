<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('objetos_perdidos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('usuario_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->foreignId('categoria_id')
                ->constrained('categorias')
                ->restrictOnDelete();

            $table->string('titulo', 150);
            $table->text('descripcion');

            $table->string('lugar_perdida', 200);

            $table->date('fecha_perdida');

            $table->string('imagen')->nullable();

            $table->enum('estado', [
                'perdido',
                'recuperado'
            ])->default('perdido');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('objetos_perdidos');
    }
};