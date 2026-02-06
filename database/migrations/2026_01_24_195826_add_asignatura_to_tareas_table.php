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
            // AÑADIMOS ESTA LÍNEA:
            // Crea una columna de texto llamada 'asignatura'
            // 'default' sirve para que las tareas que ya existen se pongan como 'General' automáticamente
            $table->string('asignatura')->default('General');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tareas', function (Blueprint $table) {
            // Y ESTA PARA DESHACER EL CAMBIO:
            // Si deshacemos la migración, borramos la columna
            $table->dropColumn('asignatura');
        });
    }
};