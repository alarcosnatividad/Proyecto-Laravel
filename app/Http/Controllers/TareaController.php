<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tarea;
use App\Models\User;
use App\Models\Categoria;
use Illuminate\Support\Facades\Auth;
use App\Mail\FichaTareaMail;
use Illuminate\Support\Facades\Mail;
use App\Models\Pedido; 

class TareaController extends Controller
{
    /**
     * 1. MUESTRA LA LISTA DE TAREAS (Muro Global vs Mis Tareas)
     * */
    public function index(Request $request) //public String index( Long categoriaId, Model mochila) equivalente en springboot
    {
        $viewData = [];   //" aqui meto mi consulta preparada al final"         mochila.addAttribute("categorias", categoriaRepository.findAll()); lo equivalente en springboot
        $viewData["title"] = "Muro de Tareas";
        $viewData["subtitle"] = "TODAS LAS TAREAS";

        // 1. Recogemos los posibles filtros de la URL--los que yo le maque en htlm de layout
        $filtroUser = $request->query('filtro'); // $request(lo que llega, aplicale filtro y lo guardo en la variable)--<a href="/tareas?filtro=mias">Ver Mis Tareas</a>
        $categoriaId = $request->query('categoria_id'); // ID de la asignatura

        // 2. Iniciamos la consulta ($query) base con las relaciones necesarias
        $query = Tarea::with(['categoria', 'user'])->latest();  //(del modelo Tarea, me das todas las categorias y los usuarios a la vez)   las mas nuevas las primeras     equivalente en espring->    repository.findAll()

        // 3. Aplicamos filtro de Usuario si existe 
        if ($filtroUser == 'mias') {  
            $query->where('user_id', Auth::id()); // de la columna user_id(bbdd) del usuario logeado en este momento 
            $viewData["subtitle"] = "Mis Tareas Asignadas ✅"; // cambias el contenido 
        }

        // 4. Aplicamos filtro de Categoría (Asignatura) si existe
        if ($categoriaId) {
            $query->where('categoria_id', $categoriaId); // el primero es bbdd , el segundo la variable 
            $catNombre = Categoria::find($categoriaId)->nombre ?? 'Asignatura'; // recoge el nombre , si no esta le pone asignatura 
            $viewData["subtitle"] = "Tareas de: " . $catNombre . " 📚"; // tares de .. "empleabilidad" y el icono 
        }

        // 5. Ejecutamos la paginación y termino la consulta 
        $tareas = $query->paginate(5); // IMPORTANTE: CAMBIO A $tareas y  le uno a mi consulta preparada que me de 5 tareas por pagina (scroll infinito)
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
     * 2. Muestra el formulario de creación-----PARA CREAR TAREAS 
     */
    public function create(Request $request)
    {
        $viewData = [];
        $viewData["title"] = "Crear una tarea nueva ";
        $viewData["users"] = User::all(); // del modelo User me lo da todo, lo usamos luego para asignar tareas a los usuarios 
        $viewData["categorias"] = Categoria::all();  // del modelo categorias me lo das todo 
        $viewData["categoria_id_seleccionada"] = $request->query('categoria_id'); //tareas/create?categoria_id=3 mira eso y me mete el numero en el view data 

        return view('tareas.create')->with("viewData", $viewData);  // como el Model mochila
    }

    /**
     * 3. STORE: Guarda la tarea
     */
    public function store(Request $request)
    {
        $request->validate([ //metodo de laravel que pone reglas a lo que viene del formulario 
            'nombre' => 'required|max:255',  //<input name="nombre"> del formulario , es obligatorio  y max 255
            'descripcion' => 'required',
            'categoria_id' => 'required|exists:categorias,id', // se asegura de que exista esa categoria en la bbdd
            'imagen' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // solo deja subir imagenes con esos formatos y max 2MB de peso
        ]);

        $tarea = new Tarea(); // crea un objeto de la clase Tarea con lo que me pasa del formulario 
        $tarea->nombre = $request->input('nombre');  
        $tarea->descripcion = $request->input('descripcion');
        $tarea->completada = $request->has('completada') ? 1 : 0;  //Si el check estaba marcado o no 
        $tarea->user_id = Auth::id(); 
        $tarea->categoria_id = $request->input('categoria_id'); 

        if ($request->hasFile('imagen')) {
            $file = $request->file('imagen');
            $nombreArchivo = time() . "_" . $file->getClientOriginalName();  // le añade el tiempo evita dos archivos se llamen igual 
            $file->move(public_path('imagenes'), $nombreArchivo); // aqui me mueve la imagen a public imagenes 
            $tarea->imagen = $nombreArchivo; // con lo que se queda de verdad el nombre 
        }

        $tarea->save(); // ejecutamos el metodo save, para guardar la tarea

        return redirect()->route('tareas.index')->with('success', 'Tarea creada correctamente'); // me devuelve a la pagina index con mensaje 
    }

    /**
     * 4. EDIT: Muestra el formulario de edición
     */
    public function edit($id)
    {
        $viewData = [];
        $tarea = Tarea::findOrFail($id);

        // Seguridad: Solo el propietario puede editarla
        if ($tarea->user_id != Auth::id()) {
            return redirect()->route('tareas.index')->with('error', 'No tienes permiso para editar esta tarea.');
        }

        $viewData["title"] = "Editar Tarea";
        $viewData["subtitle"] = "Modificando: " . $tarea->nombre;
        $viewData["tarea"] = $tarea;
        $viewData["categorias"] = Categoria::all();

        return view('tareas.edit')->with("viewData", $viewData);
    }

    /**
     * 5. UPDATE: Actualiza la tarea en la base de datos
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|max:255',
            'descripcion' => 'required',
            'imagen' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $tarea = Tarea::findOrFail($id);

        // Seguridad: Solo el dueño puede actualizar su tarea
        if ($tarea->user_id != Auth::id()) {
            return redirect()->route('tareas.index')->with('error', 'No tienes permiso para editar esta tarea.');
        }

        $tarea->nombre = $request->input('nombre');
        $tarea->descripcion = $request->input('descripcion');
        $tarea->completada = $request->has('completada') ? 1 : 0;
        $tarea->categoria_id = $request->input('categoria_id');

        // Gestión de la nueva imagen si se sube una
        if ($request->hasFile('imagen')) {
            $file = $request->file('imagen');
            $nombreArchivo = time() . "_" . $file->getClientOriginalName();
            $file->move(public_path('imagenes'), $nombreArchivo);
            $tarea->imagen = $nombreArchivo;
        }

        $tarea->save();

        return redirect()->route('tareas.index')->with('success', 'Tarea actualizada correctamente');
    }

    /**
     * 6. DESTROY: Elimina una tarea
     */
    public function destroy($id)
    {
        $tarea = Tarea::findOrFail($id); // funcion de eloquent encuenta o falla , busca por el id 
        
        // Solo el propietario puede eliminarla
        if($tarea->user_id != Auth::id()){
            return back()->with('error', 'Acceso denegado: No eres el propietario de esta tarea.');
        }

        $tarea->delete();
        return redirect()->route('tareas.index')->with('success', 'Tarea eliminada.');
    }

    /**
     * 7. FILTRA las tareas por la asignatura
     */
    public function filtrarPorCategoria($id)
    {
        $viewData = [];
        $categoria = Categoria::findOrFail($id); // encuentra por id (de categoria)
        
        $viewData["title"] = "Asignatura: " . $categoria->nombre;  // por ejemplo entorno servidor 
        $viewData["subtitle"] = "Listado de tareas generales";
        
        $viewData["tareas"] = Tarea::where('categoria_id', $id) // solo saldran las de categoria de entorno servidor su id 
                                   ->with(['categoria', 'user']) // de la clase tarea  me das las categorias  y los usuarios.. esto por ejemplo lo pondre en las tarjetas 
                                   ->paginate(5); // me las das de 5 en 5 (scroll)

        return view('tareas.index')->with("viewData", $viewData);
    }

    /**
     * 8. FAVORITAS (Corregido para que vuelvan a aparecer)
     */
    public function favoritas()
    {
        $viewData = [];
        $viewData["title"] = "Mis Tareas Favoritas";
        $viewData["subtitle"] = "Lo más importante ❤️";

        $tareas = Auth::user()->likes()->with(['categoria', 'user'])->latest()->paginate(5); // del usuario que esta logeado ahora de sus likes, me traes las categorias y los usuarios .. me gusta una de entorno servidor y de pepe 

        $viewData["tareas"] = $tareas;

        // Retorno blindado (igual que el index)--se accede de forma distinta en la vista 
        return view('tareas.index')
            ->with("viewData", $viewData)// {{ $viewData['title'] }}
            ->with("tareas", $tareas);  //@foreach($tareas as $tarea)
    }

    /**
     * 9. LIKES (Toggle)
     */
    public function toggleLike($id)
    {
        $user = Auth::user();

        // 1. Buscamos si ya existe un registro en la tabla likes para este usuario y esta tarea
        $likeExistente = \App\Models\Like::where('user_id', $user->id)
                                         ->where('tarea_id', $id)
                                         ->first();

        if ($likeExistente) {
            // Si existe, lo borramos (quitar el like)
            $likeExistente->delete();
        } else {
            // Si no existe, creamos uno nuevo (dar like)
            \App\Models\Like::create([
                'user_id' => $user->id,
                'tarea_id' => $id
            ]);
        }

        return back(); 
    }

    /**
     * 10. SHOW: Muestra el detalle de la tarea con control de puntos.
     */
    public function show($id)
    {
        $tarea = Tarea::findOrFail($id);
        $user = Auth::user();

        // 1. Comprobamos si tiene permiso para verla (Es suya O existe un pedido)
        $tieneAcceso = Pedido::where('user_id', $user->id)
                            ->where('tarea_id', $id)
                            ->exists();

        $esSuya = ($tarea->user_id == $user->id);

        // 2. Si NO es suya y NO ha pagado, mostramos vista de bloqueo
        if (!$esSuya && !$tieneAcceso) {
            return view('tareas.bloqueada', compact('tarea'));
        }

        // 3. Preparamos el viewData para la vista show (evita error undefined variable)
        $viewData = [];
        $viewData["title"] = "Detalle de Tarea";
        $viewData["subtitle"] = $tarea->nombre;
        $viewData["tarea"] = $tarea; 

        return view('tareas.show')->with("viewData", $viewData);
    }

    /**
     * 11. EMAIL: Enviar ficha por correo
     */
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