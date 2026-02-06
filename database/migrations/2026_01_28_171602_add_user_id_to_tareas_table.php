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
        Schema::table('tareas', function (Blueprint $table) {
        // Añadimos la columna user_id
        // La ponemos 'nullable' (opcional) para que no fallen las tareas antiguas que ya tienes creadas
        $table->unsignedBigInteger('user_id')->nullable()->after('id');

        // (Opcional) Esto crea un vínculo real con la tabla de usuarios
        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tareas', function (Blueprint $table) {
        // Si deshacemos la migración, borramos la columna
        $table->dropForeign(['user_id']);
        $table->dropColumn('user_id');
    });
    }
};
