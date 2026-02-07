<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // permite meter datos de prueba de forma masiva para testear, viene por defecto
use Illuminate\Database\Eloquent\Model;   // esta es la parte que me permite usar funciones como  find(), save(), where()

class Pedido extends Model
{
    use HasFactory;

    // 1. Le decimos el nombre exacto de la tabla (la que creamos con la migración)
    protected $table = 'pedidos';

    // 2. Definimos qué campos se pueden rellenar puede venir de formulario o de codigo (para evitar errores de seguridad)
    protected $fillable = [
        'user_id', 
        'tarea_id', 
        'puntos_pagados' // en el controlador de tarea puntos_pagados=>$costePuntos ( el valor se lo doy yo 10) en  metodo show 
    ];

    /*----------------------------------------------- RELACIONES--------------------------------------------------------- */

    // Un pedido pertenece a un único usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Un pedido pertenece a una única tarea
    public function tarea()
    {
        return $this->belongsTo(Tarea::class);
    }
}