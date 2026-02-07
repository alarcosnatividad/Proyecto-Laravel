<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;

    protected $table = 'likes'; 

    // AQUÍ ESTÁN LOS CAMPOS: Esto le dice a Laravel qué columnas puede rellenar
    protected $fillable = [  // like conecta las dos tablas mediante user_id e tarea_id, ya no es pivote es modelo 
        'user_id', 
        'tarea_id'
    ];

    /*----------------------------------------------------RELACIONES---------------------------------------------------------------*/

    // Relación: Un Like pertenece a un Usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación: Un Like pertenece a una Tarea
    public function tarea()
    {
        return $this->belongsTo(Tarea::class);
    }
}

