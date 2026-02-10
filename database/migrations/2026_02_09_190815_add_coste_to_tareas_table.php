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
        // Añadimos la columna 'coste' como un número entero
        // Le ponemos un valor por defecto de 10 para que las tareas viejas no tengan 0
        $table->integer('coste')->default(10)->after('nombre');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       Schema::table('tareas', function (Blueprint $table) {
        // Por si queremos deshacerlo, borramos la columna
        $table->dropColumn('coste');
    });
    }
};
