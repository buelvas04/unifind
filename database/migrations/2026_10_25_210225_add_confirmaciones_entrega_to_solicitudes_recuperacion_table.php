
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('solicitudes_recuperacion', function (Blueprint $table) {

            $table->boolean('confirmacion_entrega')
                ->default(false);

            $table->boolean('confirmacion_recepcion')
                ->default(false);

            $table->timestamp('fecha_confirmacion_entrega')
                ->nullable();

            $table->timestamp('fecha_confirmacion_recepcion')
                ->nullable();

        });
    }

    public function down(): void
    {
        Schema::table('solicitudes_recuperacion', function (Blueprint $table) {

            $table->dropColumn([
                'confirmacion_entrega',
                'confirmacion_recepcion',
                'fecha_confirmacion_entrega',
                'fecha_confirmacion_recepcion',
            ]);

        });
    }
};
