<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TareaController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ComentarioController;
use App\Http\Controllers\PedidoController; // <--- IMPORTANTE: Nuevo controlador
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Rutas de la Aplicación
|--------------------------------------------------------------------------
*/

// 1. Rutas Públicas
Route::get('/', [HomeController::class, 'index'])->name('home.index');
Route::get('/about', [HomeController::class, 'about'])->name('home.about');
Route::get('/home', [HomeController::class, 'index'])->name('home');

Auth::routes(); // Login/Registro

// Idioma
Route::get('lang/{locale}', [LanguageController::class, 'switchLang'])->name('lang.switch');

// 2. Rutas Protegidas (Solo usuarios registrados)
Route::middleware(['auth'])->group(function () {

    // --- SECCIÓN TAREAS (TareaController) ---
    Route::get('/tareas', [TareaController::class, 'index'])->name('tareas.index');
    Route::get('/tareas/crear', [TareaController::class, 'create'])->name('tareas.create');
    Route::post('/tareas/guardar', [TareaController::class, 'store'])->name('tareas.store');
    Route::get('/tareas/categoria/{id}', [TareaController::class, 'filtrarPorCategoria'])->name('tareas.por_categoria');
    Route::get('/tareas/favoritas', [TareaController::class, 'favoritas'])->name('tareas.favoritas');
    
    // Ver, Editar, Borrar
    Route::get('/tareas/{id}', [TareaController::class, 'show'])->name('tareas.show');
    Route::get('/tareas/{id}/editar', [TareaController::class, 'edit'])->name('tareas.edit');
    Route::put('/tareas/{id}/actualizar', [TareaController::class, 'update'])->name('tareas.update');
    Route::delete('/tareas/{id}', [TareaController::class, 'destroy'])->name('tareas.destroy');

    // Interacciones (Like, Comentario, Email)
    Route::post('/tareas/{id}/like', [TareaController::class, 'toggleLike'])->name('tareas.like');
    Route::post('/tareas/{id}/comentarios', [ComentarioController::class, 'store'])->name('comentarios.store');
    Route::post('/tareas/{id}/compartir', [TareaController::class, 'enviarPorEmail'])->name('tareas.enviar.email');

    // --- SECCIÓN TRANSACCIONES (PedidoController) ---
    // Recargar puntos y comprar acceso
    Route::post('/puntos/recargar', [PedidoController::class, 'recargarPuntos'])->name('puntos.recargar');
    Route::post('/tareas/{id}/comprar', [PedidoController::class, 'comprar'])->name('pedidos.comprar');

    //------- Rutas de Administración --------------------------------------------------
    Route::middleware(['admin'])->group(function () { 
        Route::get('/categorias/crear', [CategoriaController::class, 'create'])->name('categorias.create');
        Route::post('/categorias/guardar', [CategoriaController::class, 'store'])->name('categorias.store');
        Route::get('/categorias/{id}/editar', [CategoriaController::class, 'edit'])->name('categorias.edit');
        Route::put('/categorias/{id}/actualizar', [CategoriaController::class, 'update'])->name('categorias.update');
        Route::delete('/categorias/{id}', [CategoriaController::class, 'destroy'])->name('categorias.destroy');

        // Dashboard estadístico
        Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.index');
    });

});