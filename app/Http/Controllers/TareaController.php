<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tarea;
use App\Models\User;
use App\Models\Categoria;
use Illuminate\Support\Facades\Auth;
 use App\Mail\FichaTareaMail;
 use Illuminate\Support\Facades\Mail;

class TareaController extends Controller
{
    /**
     * 1. MUESTRA LA LISTA DE TAREAS (Muro Global vs Mis Tareas)
     * Soluciona el error de "Undefined variable $tareas" y la visibilidad de comentarios.
     */
    public function index(Request $request) 
{
    $viewData = [];
    $viewData["title"] = "Muro de Tareas";
    $viewData["subtitle"] = "Explora el contenido global";

    // 1. Recogemos los posibles filtros de la URL
    $filtroUser = $request->query('filtro'); // 'mias' o vacio
    $categoriaId = $request->query('categoria_id'); // ID de la asignatura

    // 2. Iniciamos la consulta base con las relaciones necesarias
    $query = Tarea::with(['categoria', 'user'])->latest();

    // 3. Aplicamos filtro de Usuario si existe
    if ($filtroUser == 'mias') {
        $query->where('user_id', Auth::id());
        $viewData["subtitle"] = "Mis Tareas Asignadas ✅";
    }

    // 4. Aplicamos filtro de Categoría (Asignatura) si existe
    if ($categoriaId) {
        $query->where('categoria_id', $categoriaId);
        $catNombre = Categoria::find($categoriaId)->nombre ?? 'Asignatura';
        $viewData["subtitle"] = "Tareas de: " . $catNombre . " 📚";
    }

    // 5. Ejecutamos la paginación
    $tareas = $query->paginate(5);
    $viewData["tareas"] = $tareas;

    // 6. Respuesta para AJAX (Scroll Infinito)
    if ($request->ajax()) {
        return view('tareas._item')->with('tareas', $tareas)->render();
    }

    return view('tareas.index')
        ->with("viewData", $viewData)
        ->with("tareas", $tareas);
}
    /**
     * 2. Muestra el formulario de creación
     */
    public function create(Request $request)
    {
        $viewData = [];
        $viewData["title"] = "Crear nueva tarea";
        $viewData["users"] = User::all(); 
        $viewData["categorias"] = Categoria::all(); 
        $viewData["categoria_id_seleccionada"] = $request->query('categoria_id');

        return view('tareas.create')->with("viewData", $viewData);
    }

    /**
     * 3. STORE: Guarda la tarea
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|max:255',
            'descripcion' => 'required',
            'categoria_id' => 'required|exists:categorias,id',
            'imagen' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $tarea = new Tarea(); 
        $tarea->nombre = $request->input('nombre');
        $tarea->descripcion = $request->input('descripcion');
        $tarea->completada = $request->has('completada') ? 1 : 0;
        $tarea->user_id = Auth::id(); 
        $tarea->categoria_id = $request->input('categoria_id'); 

        if ($request->hasFile('imagen')) {
            $file = $request->file('imagen');
            $nombreArchivo = time() . "_" . $file->getClientOriginalName();
            $file->move(public_path('imagenes'), $nombreArchivo);
            $tarea->imagen = $nombreArchivo;
        }

        $tarea->save(); 

        return redirect()->route('tareas.index')->with('success', 'Tarea creada correctamente');
    }

    /**
     * 4. DESTROY: Elimina una tarea
     */
    public function destroy($id)
    {
        $tarea = Tarea::findOrFail($id);
        
        // Solo el propietario puede eliminarla
        if($tarea->user_id != Auth::id()){
            return back()->with('error', 'Acceso denegado: No eres el propietario de esta tarea.');
        }

        $tarea->delete();
        return redirect()->route('tareas.index')->with('success', 'Tarea eliminada.');
    }

    /**
     * 5. FILTRAR POR CATEGORÍA (Global)
     */
    public function filtrarPorCategoria($id)
    {
        $viewData = [];
        $categoria = Categoria::findOrFail($id); 
        
        $viewData["title"] = "Asignatura: " . $categoria->nombre;
        $viewData["subtitle"] = "Listado de tareas generales";
        
        $viewData["tareas"] = Tarea::where('categoria_id', $id)
                                   ->with(['categoria', 'user'])
                                   ->paginate(5);

        return view('tareas.index')->with("viewData", $viewData);
    }

    // 6. FAVORITAS (Corregido para que vuelvan a aparecer)
public function favoritas()
{
    $viewData = [];
    $viewData["title"] = "Mis Tareas Favoritas";
    $viewData["subtitle"] = "Lo más importante ❤️";

    // Obtenemos las tareas que el usuario actual ha marcado con "Like"
    // Usamos paginate para que el scroll infinito no falle aquí tampoco
    $tareas = Auth::user()->likes()->with(['categoria', 'user'])->latest()->paginate(5);

    $viewData["tareas"] = $tareas;

    // Retorno blindado (igual que el index)
    return view('tareas.index')
        ->with("viewData", $viewData)
        ->with("tareas", $tareas);
}

    /**
     * 7. TOGGLE LIKE
     */
    public function toggleLike($id)
    {
        $tarea = Tarea::findOrFail($id);
        $user = Auth::user();

        if ($tarea->likes()->where('user_id', $user->id)->exists()) {
            $tarea->likes()->detach($user->id);
        } else {
            $tarea->likes()->attach($user->id);
        }

        return back(); 
    }

    /**
     * 8. SHOW: Detalle con SISTEMA DE PEDIDOS PERSISTENTE (MEJORA)
     */
    public function show($id)
    {
        $user = Auth::user();
        // 1. Buscamos la tarea primero para saber quién es el dueño
        $tarea = Tarea::with(['categoria', 'comentarios.user', 'likes', 'user'])->findOrFail($id); 
        $costePuntos = 10; 

        // 2. COMPROBACIÓN CLAVE: ¿Ya ha pagado antes por esta tarea?
        // (Esto requiere que tengas 'tareasCompradas' en el modelo User)
        $yaPagado = $user->tareasCompradas()->where('tarea_id', $id)->exists();

        // 3. Lógica de cobro: Solo cobramos si NO ha pagado y NO es su propia tarea
        if (!$yaPagado && $tarea->user_id != $user->id) {
            
            if ($user->puntos < $costePuntos) {
                return redirect()->route('tareas.index')
                    ->with('error', 'Saldo insuficiente. Necesitas ' . $costePuntos . ' puntos.');
            }

            // Restamos puntos al usuario
            $user->puntos -= $costePuntos;
            $user->save();

            // AQUÍ LA MAGIA: Registramos el pedido en la base de datos
            $user->tareasCompradas()->attach($tarea->id, ['puntos_pagados' => $costePuntos]);
        }

        // 4. Preparamos la vista
        $viewData = [];
        $viewData["title"] = "Detalle: " . $tarea->nombre;
        $viewData["subtitle"] = $yaPagado ? "Ya tienes acceso a esta tarea ✅" : "Información compartida";
        $viewData["tarea"] = $tarea; 

        return view('tareas.show')->with("viewData", $viewData);
    }
    /**
     * 9. RECARGAR PUNTOS
     */
    public function recargarPuntos()
    {
        $user = Auth::user();
        $user->puntos += 100;
        $user->save();

        return redirect()->back()->with('success', '¡Has recibido 100 puntos! 🚀');
    }

    // No olvides añadir esto arriba del todo del archivo, con los otros "use"


public function enviarPorEmail(Request $request, $id) 
{
    // Validamos que el campo sea un email real
    $request->validate([
        'email_destino' => 'required|email'
    ]);

    $tarea = Tarea::findOrFail($id);
    $emailDestino = $request->input('email_destino');

    // Enviamos el correo usando el Mailable que creamos
    Mail::to($emailDestino)->send(new FichaTareaMail($tarea));

    return back()->with('success', '¡Ficha enviada correctamente a ' . $emailDestino . '!');
}
}