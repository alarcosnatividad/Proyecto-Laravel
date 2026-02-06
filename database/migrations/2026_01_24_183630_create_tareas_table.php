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
       Schema::create('tareas', function (Blueprint $table) {
        $table->id(); // Este es el ID automático (1, 2, 3...)
        
        // AÑADE ESTAS LÍNEAS:
        $table->string('nombre'); // El título de la tarea
        $table->text('descripcion')->nullable(); // Detalles (opcional)
        $table->boolean('completada')->default(false); // ¿Está hecha? Por defecto NO.
        
        $table->timestamps(); // Esto guarda la fecha de creación automáticamente
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tareas');
    }
};
