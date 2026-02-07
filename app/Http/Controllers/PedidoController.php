<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido;
use App\Models\Tarea;
use Illuminate\Support\Facades\Auth;

class PedidoController extends Controller
{
    /**
     * --------------funcion comprar -------------------------------------------
     */
    public function comprar($id)
    {
        $user = Auth::user();  // el comprador: el que esta logeado 
        $tarea = Tarea::findOrFail($id); // selecciona la tarea por id 
        $costePuntos = 10;    // establezco que el coste de puntos es 10

        // 1. Verificamos si ya es suya o ya la compró
        // En lugar de: $user->tareasCompradas()...
        $yaPagado = Pedido::where('user_id', $user->id)
                  ->where('tarea_id', $id)
                  ->exists();
        
        if ($yaPagado || $tarea->user_id == $user->id) {
            return redirect()->route('tareas.show', $id)->with('info', 'Ya tienes acceso.');
        }

        // 2. Verificamos saldo
        if ($user->puntos < $costePuntos) {
            return back()->with('error', 'Saldo insuficiente. Necesitas ' . $costePuntos . ' puntos.');
        }

        // 3. Realizamos la transacción
        $user->puntos -= $costePuntos;
        $user->save();

        Pedido::create([
            'user_id' => $user->id,
            'tarea_id' => $tarea->id,
            'puntos_pagados' => $costePuntos,
        ]);

        return redirect()->route('tareas.show', $id)->with('success', '¡Compra realizada con éxito!');
    }

    /**
     * ------------------funcion recarga de puntos ----------------------------------------------
     */
    public function recargarPuntos()
    {
        $user = Auth::user();
        $user->puntos += 100;
        $user->save();

        return redirect()->back()->with('success', '¡Has recibido 100 puntos! 🚀');
    }
}