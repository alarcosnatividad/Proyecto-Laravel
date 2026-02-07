<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;// para rellenar datos de prueba ficticios
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'imagen'];

    // RELACIÓN: Una categoría tiene muchas tareas
    public function tareas()
    {
        return $this->hasMany(Tarea::class);
    }
}
