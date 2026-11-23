
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('solicitudes_recuperacion', function (Blueprint $table) {
            $table->index(
                'usuario_id',
                'solicitudes_recuperacion_usuario_id_index'
            );
        });

        Schema::table('solicitudes_recuperacion', function (Blueprint $table) {
            $table->dropUnique('solicitudes_usuario_objeto_unique');
        });
    }

    public function down(): void
    {
        Schema::table('solicitudes_recuperacion', function (Blueprint $table) {
            $table->unique(
                ['usuario_id', 'objeto_encontrado_id'],
                'solicitudes_usuario_objeto_unique'
            );
        });

        Schema::table('solicitudes_recuperacion', function (Blueprint $table) {
            $table->dropIndex(
                'solicitudes_recuperacion_usuario_id_index'
            );
        });
    }
};
