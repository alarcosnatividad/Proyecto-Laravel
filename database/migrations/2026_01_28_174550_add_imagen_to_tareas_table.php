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
        // Creamos columna para texto (la ruta de la imagen) y nullable (opcional)
        $table->string('imagen')->nullable()->after('descripcion');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       Schema::table('tareas', function (Blueprint $table) {
        $table->dropColumn('imagen');
    });
    }
};
