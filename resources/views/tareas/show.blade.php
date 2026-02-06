@extends('layouts.app')

@section('titulo', $viewData["title"])
@section('subtitulo', $viewData["subtitle"])

@section('contenido')
<div class="card mb-3">
    <div class="row g-0">
        <div class="col-md-4">
            {{-- Verificamos si la tarea tiene imagen propia --}}
            @if($viewData["tarea"]->imagen)
                <img src="{{ asset('imagenes/' . $viewData["tarea"]->imagen) }}" class="card-img-top img-card" alt="Imagen de la tarea">
            @else
                <img src="{{ asset('/img/tarea1.jpg') }}" class="card-img-top img-card" alt="Imagen por defecto">
            @endif
        </div>
        <div class="col-md-8">
            <div class="card-body">
                <h5 class="card-title">
                    {{ $viewData["tarea"]->nombre }}
                </h5>
                <p class="card-text">
                    {{ $viewData["tarea"]->descripcion }}
                </p>
                
                <p class="card-text">
                    <small class="text-muted">Creada el: {{ $viewData["tarea"]->created_at }}</small>
                </p>

                {{-- Botón para regresar al listado principal --}}
                <a href="{{ route('tareas.index') }}" class="btn btn-secondary">Volver a la lista</a>

                <div class="d-flex justify-content-between align-items-center mt-3">
                    {{-- FORMULARIO DE LIKES (CORREGIDO) --}}
                    <form action="{{ route('tareas.like', $viewData['tarea']->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-link text-decoration-none p-0" style="font-size: 1.5rem;">
                            
                            {{-- Comprobamos si el usuario actual ya ha dado like a esta tarea específica --}}
                            @if($viewData["tarea"]->likes->contains(Auth::user()))
                                ❤️ 
                            @else
                                🤍 
                            @endif

                        </button>
                    </form>

                    <span class="text-muted">
                        {{-- Contador total de likes de la tarea --}}
                        {{ $viewData["tarea"]->likes->count() }} Likes
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection