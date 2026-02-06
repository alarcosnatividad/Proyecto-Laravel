@extends('layouts.app')

@section('titulo', $viewData["title"])
@section('subtitulo', $viewData["subtitle"])

@section('contenido')
<div class="mb-3">
    <a href="{{ route('tareas.create') }}" class="btn btn-success">
        + Nueva Tarea
    </a>
</div>

<div class="row">
    {{-- CAMBIO CLAVE: Usamos @forelse en lugar de @foreach --}}
    @forelse ($viewData["tareas"] as $tarea)
        <div class="col-md-4 col-lg-3 mb-2">
            <div class="card {{ $tarea->completada ? 'border-success border-3' : '' }} h-100">
                
                {{-- LÓGICA DE LA IMAGEN --}}
                @if($tarea->imagen)
                    <img src="{{ asset('imagenes/' . $tarea->imagen) }}" class="card-img-top img-card" alt="Imagen de {{ $tarea->nombre }}" style="height: 200px; object-fit: cover;">
                @else
                    <img src="{{ asset('/img/tarea1.jpg') }}" class="card-img-top img-card" alt="Imagen por defecto" style="height: 200px; object-fit: cover;">
                @endif
                
                <div class="card-body text-center d-flex flex-column">
                    <h5 class="card-title">{{ $tarea->nombre }}</h5>

                    <div class="mb-2">
                        @if($tarea->completada)
                            <span class="badge bg-success">¡Completada!</span>
                        @else
                            <span class="badge bg-secondary">Pendiente</span>
                        @endif
                    </div>

                    
                    
                    {{-- BOTONES DE ACCIÓN --}}
                    <div class="mb-2">
                        <a href="{{ route('tareas.show', ['id'=> $tarea->id]) }}" class="btn btn-primary btn-sm">Ver</a>
                        <a href="{{ route('tareas.edit', ['id'=> $tarea->id]) }}" class="btn btn-warning btn-sm">Editar</a>
                        
                        <form action="{{ route('tareas.destroy', $tarea->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE') 
                            <button type="submit" class="btn btn-danger btn-sm">Borrar</button>
                        </form>
                    </div>

                    {{-- SECCIÓN DE LIKES (CORAZÓN) --}}
                    <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top">
                        <form action="{{ route('tareas.like', $tarea->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-link text-decoration-none p-0" style="font-size: 1.5rem;">
                                {{-- Si el usuario actual ya le dio like, sale rojo --}}
                                @if($tarea->likes->contains(Auth::user()))
                                    ❤️ 
                                @else
                                    🤍 
                                @endif
                            </button>
                        </form>

                        <span class="text-muted small">
                            {{ $tarea->likes->count() }} Likes
                        </span>
                    </div>

                </div>
            </div>
        </div>

    @empty
        {{-- ESTO ES LO NUEVO: Se muestra solo si la lista está vacía --}}
        <div class="col-12 text-center mt-5">
            <div class="alert alert-light" role="alert">
                <h3 class="alert-heading">📭 Vaya, no hay nada por aquí...</h3>
                <p>No se han encontrado tareas en esta sección.</p>
                <hr>
                <p class="mb-0">¡Prueba a crear una nueva o marca alguna como favorita!</p>
                <br>
                <a href="{{ route('tareas.create') }}" class="btn btn-primary">Crear Tarea Ahora</a>
            </div>
        </div>
    @endforelse
</div>
@endsection