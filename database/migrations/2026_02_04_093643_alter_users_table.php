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
         Schema::table('users', function (Blueprint $table) {  // Blueprint herramienta de Laravel ( permite usar String, boolean..)
        
        // 2. Añadimos la columna(atributo) 'role' (string) con un valor por defecto
        $table->string('role')->default('client');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
