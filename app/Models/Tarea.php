<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//1. (notas:Un modelo es una clase de php, que representa una tabla en la bbdd)

class Tarea extends Model
{
    use HasFactory;

     // 1. Le decimos el nombre exacto de la tabla en la BBDD (la que creamos con la migración), aunque ya la sabria , mejor se explicitos 
    protected $table = 'tareas'; // en singular y plural en la bbdd


    
    /*----------------------------------------------------RELACIONES---------------------------------------------------------------*/

    // en este modelo tarea pongo la relacion con los likes 
    public function likes()
{
    // Una tarea TIENE MUCHOS registros de likes
    return $this->hasMany(Like::class);  // $tarea->likes->count() paso por el modelo intermedio para sacar el numero de likes
}

    // la tarea ahora pertenece a una categoria 
    public function categoria()
{
    return $this->belongsTo(Categoria::class);
}
 // relacion una tarea puede tener muchos comentarios 
public function comentarios() {
    return $this->hasMany(Comentario::class);
}

public function user()
{
    // Una tarea pertenece a un usuario (el creador)
    return $this->belongsTo(User::class);
}
// un comprador puede hacer muchos pedidos 
public function compradores()
{
    return $this->belongsToMany(User::class, 'pedidos');
}

} 