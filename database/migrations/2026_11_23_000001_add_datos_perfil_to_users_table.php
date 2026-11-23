<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('foto_perfil')->nullable();
            $table->string('telefono', 30)->nullable();
            $table->string('facultad', 120)->nullable();
            $table->string('programa', 120)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'foto_perfil',
                'telefono',
                'facultad',
                'programa',
            ]);
        });
    }
};
