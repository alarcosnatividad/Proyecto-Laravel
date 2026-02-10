<?php

namespace App\Http\Controllers;

use App\Models\Tarea;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
   public function index()

    /*utilizamos facade  y con esto Query Builder  que es un sistema de contructor de consultas (como Eloquent) solo que es 
    para que procese mas rápido al ser mas calculos , mas rendimiento */

{
    // 1. Estadísticas basadas en PEDIDOS REALES \DB es la facade
    $totalVentas = \DB::table('pedidos')->count(); // aqui utiliza DB porque normalmente son muchos , no pasa por el modelo , mas rapido 
    $totalUsuarios = \App\Models\User::count();    // de los usuarios me los cuentas // aqui si pasa por el modelo 
    
    // Sumamos los puntos que realmente se han gastado en la tienda
    $puntosRecaudados = \DB::table('pedidos')->sum('puntos_pagados'); // de la tabla pedidos me sumas todos los puntos 

    // 2. Gráfico: Tareas más vendidas (Top 5)
    // Esto es muy pro: buscamos qué tareas tienen más registros en la tabla pedidos
    $topTareas = \App\Models\Tarea::withCount('compradores') // me cuenta el id de la tarea en la tabla pedidos .. si 10 veces .. la han comprado 10 veces // compradores: atributo de la tabla tareas 
        ->orderBy('compradores_count', 'desc') 
        ->take(5)// dame las 5 primeras 
        ->get();
    
    $labels = $topTareas->pluck('nombre'); // a Chart.js no le gustan los objetos grandes por eso se utiliza pluk para sacar lo necesario para el grafico 
    $data = $topTareas->pluck('compradores_count');

    $viewData = [
        "title" => "Panel de Control Estadístico",
        "totalVentas" => $totalVentas,
        "totalUsuarios" => $totalUsuarios,
        "puntosRecaudados" => $puntosRecaudados,
        "labels" => $labels, // $topTareas nombre  --lista de nombres para el grafico 
        "data" => $data,     //$topTareas contar compradores --
        "ventasRecientes" => \DB::table('pedidos')
            ->join('users', 'pedidos.user_id', '=', 'users.id')  //"ID de usuario" del pedido sea igual al "ID" de la tabla usuarios.
            ->join('tareas', 'pedidos.tarea_id', '=', 'tareas.id') //comparando que el "ID de tarea" del pedido sea igual al "ID" de la tabla tareas.
            ->select('users.name as usuario', 'tareas.nombre as tarea', 'pedidos.puntos_pagados', 'pedidos.created_at')// selecciona nombre de usuario nombre de tarea  puntos pagados del pedido y fecha de creacion
            ->latest('pedidos.created_at')
            ->take(5)
            ->get()
    ];

    return view('admin.index')->with("viewData", $viewData);
}

public function pedidos()
{
    // Recuperamos todos los pedidos de la base de datos
    // Usamos 'with' para traer el nombre del usuario y de la tarea sin hacer mil consultas
    $pedidos = \App\Models\Pedido::with(['user', 'tarea'])->latest()->get();

    // mandamos los datos a la vista admin.pedidos.index
    return view('admin.pedidos.index', ['pedidos' => $pedidos]);
}
}