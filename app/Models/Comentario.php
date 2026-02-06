<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comentario extends Model
{
    // Permitimos que estos campos se rellenen desde el formulario
    protected $fillable = ['contenido', 'valoracion', 'user_id', 'tarea_id'];

    // Relación: Un comentario pertenece a un usuario
    public function user() {
        return $this->belongsTo(User::class);
    }

    // Relación: Un comentario pertenece a una tarea
    public function tarea() {
        return $this->belongsTo(Tarea::class);
    }
}