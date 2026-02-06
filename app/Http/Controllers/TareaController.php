<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tarea;
use App\Models\User;
use App\Models\Categoria;
use Illuminate\Support\Facades\Auth;

class TareaController extends Controller
{
    // 1. Muestra la lista de tareas
    public function index(Request $request) 
    {
        $viewData = [];
        $viewData["title"] = "Lista de Tareas";
        $viewData["subtitle"] = "Mis Tareas Pendientes";
        
        $viewData["tareas"] = Tarea::where('user_id', Auth::id())->with('categoria')->get();
        return view('tareas.index')->with("viewData", $viewData);
    }

    // 2. Muestra el formulario de crear
    public function create(Request $request)
    {
        $viewData = [];
        $viewData["title"] = "Crear nueva tarea";
        $viewData["users"] = User::all(); 
        $viewData["categorias"] = Categoria::all(); 
        $viewData["categoria_id_seleccionada"] = $request->query('categoria_id');

        return view('tareas.create')->with("viewData", $viewData);
    }

    // 3. STORE: Guarda la tarea en la BD
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
        $tarea->user_id = $request->input('user_id', Auth::id());
        $tarea->categoria_id = $request->input('categoria_id'); 

        if ($request->hasFile('imagen')) {
            $file = $request->file('imagen');
            $nombreArchivo = time() . "_" . $file->getClientOriginalName();
            $file->move(public_path('imagenes'), $nombreArchivo);
            $tarea->imagen = $nombreArchivo;
        }

        $tarea->save(); 

        return redirect()->route('home.index')->with('success', 'Tarea creada correctamente');
    }

    // 4. DESTROY: Elimina una tarea
    public function destroy($id)
    {
        $tarea = Tarea::findOrFail($id);
        $tarea->delete();
        return redirect()->route('tareas.index');
    }

    // 5. FILTRAR POR CATEGORIA 
    public function filtrarPorCategoria($id)
    {
        $viewData = [];
        $categoria = Categoria::findOrFail($id); 
        
        $viewData["title"] = "Tareas de: " . $categoria->nombre;
        $viewData["subtitle"] = "Listado de tareas para esta asignatura";
        
        $viewData["tareas"] = Tarea::where('categoria_id', $id)
                                   ->where('user_id', Auth::id()) 
                                   ->with('categoria')
                                   ->get();

        return view('tareas.index')->with("viewData", $viewData);
    }

    // 6. FAVORITAS
    public function favoritas()
    {
        $viewData = [];
        $viewData["title"] = "Mis Tareas Favoritas";
        $viewData["subtitle"] = "Lo más importante ❤️";

        $viewData["tareas"] = Auth::user()->likes; 

        return view('tareas.index')->with("viewData", $viewData);
    }

    // 7. TOGGLE LIKE
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

    // 8. SHOW: Detalle de tarea con lógica de puntos
    public function show($id)
    {
        $user = Auth::user();
        $costePuntos = 10; // Coste de ver la tarea

        // Controlar que no pueda ver tareas si no le quedan puntos
        if ($user->puntos < $costePuntos) {
            return redirect()->route('home.index')
                ->with('error', 'No tienes puntos suficientes (Necesitas ' . $costePuntos . ')');
        }

        // Restar puntos y guardar el nuevo balance
        $user->puntos -= $costePuntos;
        $user->save();

        // Cargar los datos de la tarea
        $tarea = Tarea::findOrFail($id); 
        $viewData = [];
        $viewData["title"] = $tarea->nombre . " - Detalles";
        $viewData["subtitle"] = "Información completa de la tarea (Coste: " . $costePuntos . " puntos)";
        $viewData["tarea"] = $tarea; 

        return view('tareas.show')->with("viewData", $viewData);
    }
    public function recargarPuntos()
{
    $user = Auth::user();

    // Validación de seguridad en el servidor
    if ($user->puntos >= 10) {
        return redirect()->back()->with('error', 'Aún tienes puntos suficientes. ¡Gástalos primero!');
    }

    $user->puntos += 100;
    $user->save();

    return redirect()->back()->with('success', '¡Recarga de 100 puntos completada! 🚀');
}
}