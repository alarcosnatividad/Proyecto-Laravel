<?php

namespace App\Http\Controllers;

use App\Models\Tarea;
use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Auth; // Necesario para sacar el ID

class CarritoController extends Controller
{
    public function index(Request $request)
    {
        // Creamos una llave única para este usuario
        $cartKey = "tareas_" . Auth::id(); // $cartKey seria si se mete el usuario 5 la clave seria tareas_5
        $cookieKey = "carrito_tareas_" . Auth::id(); // cookies distinatas para cada usuario, seria carrito_tareas_5

        // 1. creamos un array vacio usando la funcion session de tareas (mediante el get)
        $tareasSesion = $request->session()->get($cartKey, []); 

        // 2. Si la sesión está vacía, comprobamos si tiene cookie , esto le llega desde mi navegador(Chrome, tiene una pequeña bbdd con lo que ha guardado en mi disco duro )
        if (empty($tareasSesion) && $request->hasCookie($cookieKey)) {
            // Recuperamos el JSON de la cookie y lo pasamos a array de PHP
            $tareasSesion = json_decode($request->cookie($cookieKey), true);// Con true consigo que me lo de como un array: $tareasSesion['id'] y no como objeto $tareasSesion->id
            
            // Lo volvemos a meter en la sesión para que el resto del código funcione
            $request->session()->put($cartKey, $tareasSesion);
        }

        // 3. // hago que en $tareasEnCarrito este metida toda la informacion de todas esas tareas 
        $totalPuntos = 0; //comienzo la variable a cero .. despues calculo
        $tareasEnCarrito = Tarea::whereIn('id', array_keys($tareasSesion))->get();// con wherIn(dame una lista no solo el id) y te quedas  solo con la clave del array el id

        foreach ($tareasEnCarrito as $tarea) {
            $totalPuntos += (int) $tarea->coste;// de cada tarea de $tareasEnCarrito(mi lista) le vas sumando el coste de cada tarea
        }
        // aqui preparo los datos que voy a mandar a la vista , la lista de mis tareas y la suma total de los puntos 
        $viewData = [
            "titulo" => "Carrito - Mi Selección Permanente",
            "tareas" => $tareasEnCarrito,
            "total" => $totalPuntos
        ];
        // me lleva a la vista carrito.index
        return view('carrito.index')->with("viewData", $viewData);
    }

    /*------------------------------------------------METODO PARA AÑADIR  CON LAS COOKIES---------------------------------------------------------------*/ 
        
    // metodo para añadir con cookies
    public function add(Request $request, $id)
    {
        $cartKey = "tareas_" . Auth::id();
        $cookieKey = "carrito_tareas_" . Auth::id();

        // 1. Obtenemos las tareas actuales de la sesión (o array vacío si no hay nada)
        $tareas = $request->session()->get($cartKey, []);// se pone el array vacio, por si es la primera vez que le da a añadir, no sea null, con el get si hay se meten dentro

        // 2. Añadimos la nueva tarea
        $tareas[$id] = 1; // de la tarea que me has pasadao(id) le ponemos cantidad 1 , si fuera el id 5 seria [ 5 => 1 ]

        // 3. Actualizamos la SESIÓN
        $request->session()->put($cartKey, $tareas);

        // 4. Actualizamos la COOKIE (Permanente por 43200 minutos = 30 días)
        // esto es le mando la cookie al navegador pero lo codifico a json
        Cookie::queue($cookieKey, json_encode($tareas), 43200);

        return redirect()->route('carrito.index'); 
    }

    /*-----------------------------------------------------------METODO PARA BORRAR------------------------------------------------------------*/
    public function delete(Request $request)
    {
        $cartKey = "tareas_" . Auth::id();
        $cookieKey = "carrito_tareas_" . Auth::id();

        // Borramos la sesión
        $request->session()->forget($cartKey); // olvida(forget) es el delete pero en sesiones , borra la sesion de tareas(una vez se ha comprado el producto ya no apareceria)

        // Borramos la cookie poniéndola en negativo o con forget
        Cookie::queue(Cookie::forget($cookieKey)); // con el envio de una nueva cookie identica a la mia pero caducada(forget lo hace) y por eso desaparece de mi disco duro

        return redirect()->route('tareas.index');
    }

    /*--------------------------------------------------funcion compar---------------------------------------------------------------*/

    public function comprar(Request $request)
    {
        $cartKey = "tareas_" . Auth::id();
        $cookieKey = "carrito_tareas_" . Auth::id();

        $tareasSesion = $request->session()->get($cartKey, []); // array vacio , si hay algo en la sesion lo pone
        
        if (!empty($tareasSesion)) { // en el caso de que en la sesion de tareas exista algo , no este vaci
            // 1. Buscamos las tareas para saber cuánto cuestan
            $tareas = Tarea::whereIn('id', array_keys($tareasSesion))->get(); // sesion cookies solo tiene el id y la cantidad , asi que digo quedate con la clave el id y me das toda la info de cada tarea 
            
            // 2. Por cada tarea, creamos un registro en la tabla pedidos 
            foreach ($tareas as $tarea) {
                $pedido = new Pedido();
                $pedido->puntos_pagados = $tarea->coste;
                $pedido->user_id = auth()->id(); // El usuario que está logueado
                $pedido->tarea_id = $tarea->id;
                $pedido->save();
            }

            // 3. una vez se ha hecho la compra y hemos hecho el nuevo pedido , hay que vaciar el carrito 
            $request->session()->forget($cartKey); // borra la sesion de tareas 
            Cookie::queue(Cookie::forget($cookieKey)); // y manda una cookie caducada para que desaparezca del disco duro 

            return redirect()->route('tareas.index')->with('success', '¡Compra realizada con éxito!');
        }

        return back()->with('error', 'El carrito está vacío.');
    }


    
}