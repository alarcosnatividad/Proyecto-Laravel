<?php

namespace App\Http\Controllers;

use App\Models\Tarea;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
   public function index()
{
    // 1. Estadísticas basadas en PEDIDOS REALES
    $totalVentas = \DB::table('pedidos')->count(); // Cuántas veces se ha comprado contenido
    $totalUsuarios = \App\Models\User::count();
    
    // Sumamos los puntos que realmente se han gastado en la tienda
    $puntosRecaudados = \DB::table('pedidos')->sum('puntos_pagados');

    // 2. Gráfico: Tareas más vendidas (Top 5)
    // Esto es muy pro: buscamos qué tareas tienen más registros en la tabla pedidos
    $topTareas = \App\Models\Tarea::withCount('compradores')
        ->orderBy('compradores_count', 'desc')
        ->take(5)
        ->get();
    
    $labels = $topTareas->pluck('nombre');
    $data = $topTareas->pluck('compradores_count');

    $viewData = [
        "title" => "Panel de Control Estadístico",
        "totalVentas" => $totalVentas,
        "totalUsuarios" => $totalUsuarios,
        "puntosRecaudados" => $puntosRecaudados,
        "labels" => $labels,
        "data" => $data,
        "ventasRecientes" => \DB::table('pedidos')
            ->join('users', 'pedidos.user_id', '=', 'users.id')
            ->join('tareas', 'pedidos.tarea_id', '=', 'tareas.id')
            ->select('users.name as usuario', 'tareas.nombre as tarea', 'pedidos.puntos_pagados', 'pedidos.created_at')
            ->latest('pedidos.created_at')
            ->take(5)
            ->get()
    ];

    return view('admin.index')->with("viewData", $viewData);
}
}