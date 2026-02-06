<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tarea extends Model
{
    use HasFactory;

    // AHORA SÍ: La función está DENTRO de la clase (antes de la última llave)
    
    // Relación: Una tarea tiene muchos usuarios que le dieron like
    public function likes()
    {
        return $this->belongsToMany(User::class, 'likes'); 
    }

    // la tarea ahora pertenece a una categoria 
    public function categoria()
{
    return $this->belongsTo(Categoria::class);
}


} 