<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('solicitudes_recuperacion', function (Blueprint $table) {
            $table->foreignId('objeto_perdido_id')
                ->nullable()
                ->constrained('objetos_perdidos')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('solicitudes_recuperacion', function (Blueprint $table) {
            $table->dropConstrainedForeignId('objeto_perdido_id');
        });
    }
};
