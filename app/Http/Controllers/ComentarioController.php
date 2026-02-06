<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comentario;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ComentarioController extends Controller
{
    public function store(Request $request, $tareaId)
    {
        $request->validate([
            'contenido' => 'required|min:5',
            'valoracion' => 'required|integer|between:1,5',
        ]);

        $comentario = new Comentario();
        $comentario->contenido = $request->input('contenido');
        $comentario->valoracion = $request->input('valoracion');
        $comentario->user_id = Auth::id();
        $comentario->tarea_id = $tareaId;
        $comentario->save();

        //  REGALO: Sumamos 5 puntos al usuario por comentar
        $user = Auth::user();
        $user->puntos += 5;
        $user->save();

        return back()->with('success', '¡Gracias por tu valoración! Has ganado 5 puntos 🚀');
    }
}