<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoriaController extends Controller
{
    /**
     * Muestra el formulario para crear una asignatura.
     */
    public function create()
    {
        $viewData = [];
        $viewData["title"] = "Crear Asignatura";
        $viewData["subtitle"] = "Panel de Gestión de Administrador";

        return view('categorias.create')->with("viewData", $viewData);
    }

    /**
     * Guarda la asignatura y gestiona la subida de la imagen.
     */
    public function store(Request $request)
    {
        // 1. Validamos que el nombre esté y que el archivo sea una imagen real
        $request->validate([
            "nombre" => "required|max:255",
            "imagen" => "image|mimes:jpeg,png,jpg,gif|max:2048" 
        ]);

        // 2. Creamos el objeto del modelo Categoria
        $nuevaCategoria = new Categoria();
        $nuevaCategoria->nombre = $request->input('nombre');
        
        // Imagen por defecto por si el usuario no sube nada
        $nuevaCategoria->imagen = "default.png"; 

        // 3. Procesamos el archivo que vimos en el diagnóstico (UploadedFile)
        if ($request->hasFile('imagen')) {
            // Creamos un nombre único (ej: 1738685123_Interfaces.png)
            $nombreImagen = time() . "_" . $nuevaCategoria->nombre . "." . $request->file('imagen')->extension();
            
            // Movemos el archivo de la carpeta temporal a public/imagenes
            // Hemos quitado '/categorias' para que coincida con tus carpetas actuales
            $request->file('imagen')->move(public_path('imagenes'), $nombreImagen);
            
            // Guardamos el nombre final en la base de datos
            $nuevaCategoria->imagen = $nombreImagen;
        }

        // 4. Guardamos todo en la base de datos
        $nuevaCategoria->save();

        // 5. Redirigimos a la Home con un mensaje de éxito
        return redirect()->route('home.index')->with('success', '¡Asignatura creada con éxito!');
    }

    public function destroy($id)
{
    $categoria = Categoria::findOrFail($id);
    
    // Opcional: Borrar el archivo físico de la carpeta imágenes
    if ($categoria->imagen && $categoria->imagen != 'default.png') {
        $rutaImagen = public_path('imagenes/' . $categoria->imagen);
        if (file_exists($rutaImagen)) {
            unlink($rutaImagen);
        }
    }

    $categoria->delete();

    return redirect()->route('home.index')->with('success', 'Asignatura eliminada correctamente');
}

// --------Para editar y actualizar  Muestra el formulario con los datos actuales
public function edit($id)
{
    if (Auth::user()->id !== 1) return redirect()->route('home.index'); // Bloqueo manual

    $viewData = [];
    $viewData["title"] = "Editar Asignatura";
    $viewData["categoria"] = Categoria::findOrFail($id); //
    
    return view('categorias.edit')->with("viewData", $viewData);
}

// Guarda los cambios
public function update(Request $request, $id)
{
    $categoria = Categoria::findOrFail($id);
    $categoria->nombre = $request->input('nombre');

    if ($request->hasFile('imagen')) {
        // 1. Borrar la imagen antigua si existe física mente
        $rutaAntigua = public_path('imagenes/' . $categoria->imagen);
        if (file_exists($rutaAntigua) && $categoria->imagen) {
            unlink($rutaAntigua); // Elimina el archivo anterior
        }

        // 2. Guardar la nueva imagen
        $nombreImagen = time() . '.' . $request->file('imagen')->extension();
        $request->file('imagen')->move(public_path('imagenes'), $nombreImagen);
        $categoria->imagen = $nombreImagen;
    }

    $categoria->save();
    return redirect()->route('home.index')->with('success', 'Asignatura actualizada con éxito');
}
}