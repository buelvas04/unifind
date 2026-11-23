
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notificaciones', function (Blueprint $table) {
            $table->foreignId('objeto_encontrado_id')
                ->nullable()
                ->constrained('objetos_encontrados')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('notificaciones', function (Blueprint $table) {
            $table->dropConstrainedForeignId('objeto_encontrado_id');
        });
    }
};
