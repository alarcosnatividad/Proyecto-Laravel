<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Tarea;
use App\Models\Categoria; //  PASO 1: Importamos el modelo nuevo

class HomeController extends Controller
{
    // Función para la página principal (Portada)
    public function index()
    {
        $viewData = [];
        $viewData["title"] = "Página Principal - Gestor de Tareas"; // lo que sale en la pestaña 
        $viewData["subtitle"] = __("messages.choose_subject");; //  antes..."Elige una Asignatura"
        
        //  PASO 3: Enviamos las categorías para que el @forelse de la vista no falle
        $viewData["categorias"] = Categoria::all(); 

        return view('home.index')->with("viewData", $viewData);
    }

    // funcion about
    public function about()
    {
        $viewData = [];
        $viewData["title"] = "Sobre Nosotros - Gestor de Tareas";
        $viewData["subtitle"] = "Acerca de este proyecto";
        $viewData["description"] = "Esta es una aplicación para gestionar tareas personales desarrollada en Laravel.";
        
        if(Auth::check()){
            $nombreUsuario = Auth::user()->name;
            $viewData["autor"] = "Bienvenido a nuestra página : " . $nombreUsuario;
        } else {
            $viewData["autor"] = "Bienvenido a nuestra pagina";
        }
        
        return view('home.about')->with("viewData", $viewData);
    }
}