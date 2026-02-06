<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth; // <--- Añadido por seguridad
// Importamos LOS DOS controladores
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TareaController;
use App\Http\Controllers\CategoriaController;  // añado categoria controlers

/*
|--------------------------------------------------------------------------
| Rutas de la Aplicación
|--------------------------------------------------------------------------
*/

// 1. Rutas Públicas (Portada y Login)
Route::get('/', [HomeController::class, 'index'])->name('home.index');
Route::get('/about', [HomeController::class, 'about'])->name('home.about');

Auth::routes(); // Rutas de Login/Registro

Route::get('/home', [HomeController::class, 'index'])->name('home');

// 2. Rutas Protegidas (Solo usuarios registrados)
// Ponemos esto dentro de un grupo 'auth' para que nadie entre sin loguearse
Route::middleware(['auth'])->group(function () {

    // Lista de tareas
    Route::get('/tareas', [TareaController::class, 'index'])->name('tareas.index');

    // Crear tarea
    Route::get('/tareas/crear', [TareaController::class, 'create'])->name('tareas.create');
    Route::post('/tareas/guardar', [TareaController::class, 'store'])->name('tareas.store');
    // ruta filtrado por asignatura 
    Route::get('/tareas/categoria/{id}', [TareaController::class, 'filtrarPorCategoria'])->name('tareas.por_categoria');

    Route::get('/tareas/favoritas', [TareaController::class, 'favoritas'])->name('tareas.favoritas');// tiene que ir antes que show

    Route::post('/puntos/recargar', [TareaController::class, 'recargarPuntos'])->name('puntos.recargar');



    // Ver detalle
    Route::get('/tareas/{id}', [TareaController::class, 'show'])->name('tareas.show');

    // Editar tarea
    Route::get('/tareas/{id}/editar', [TareaController::class, 'edit'])->name('tareas.edit');
    Route::put('/tareas/{id}/actualizar', [TareaController::class, 'update'])->name('tareas.update');

    // Borrar tarea
    Route::delete('/tareas/{id}', [TareaController::class, 'destroy'])->name('tareas.destroy');

    // --- AQUÍ ESTABA EL ERROR CORREGIDO ---
    // He quitado el "App\Http\Http..." duplicado y usado el nombre corto
    Route::post('/tareas/{id}/like', [TareaController::class, 'toggleLike'])->name('tareas.like');

    
    
    
    //------- Grupo de rutas protegidas por nuestro administrador"--------------------------------------------------
    Route::middleware(['auth', 'admin'])->group(function () {     // ya lo he registrado previamente en midddlware
    
    // Ruta para ver el formulario de creación de asignaturas
    Route::get('/categorias/crear', [CategoriaController::class, 'create'])->name('categorias.create');
    
    // Ruta para recibir los datos del formulario (nombre y foto)
    Route::post('/categorias/guardar', [CategoriaController::class, 'store'])->name('categorias.store');
    // rutas para editar y acutalizar 
    Route::get('/categorias/{id}/editar', [CategoriaController::class, 'edit'])->name('categorias.edit');
    Route::put('/categorias/{id}/actualizar', [CategoriaController::class, 'update'])->name('categorias.update');
    
    // ruta borrar categoria 
    Route::delete('/categorias/{id}', [CategoriaController::class, 'destroy'])->name('categorias.destroy');

    
});

});