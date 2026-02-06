@extends('layouts.app')

@section('titulo', $viewData["title"])
@section('subtitulo', $viewData["subtitle"])

@section('contenido')
<div class="mb-3 d-flex gap-2">
    <a href="{{ route('tareas.index') }}" class="btn btn-success">
        Ver Todas mis Tareas
    </a>
</div>

{{-- Sección de cabecera con botón de creación protegido --}}
<div class="container mb-4">
    <div class="row">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h2>Asignaturas Disponibles</h2>
            
            {{-- Botón nueva asignatura: Solo visible para administradores --}}
            @if(Auth::check() && Auth::user()->role == 'admin')
                <a href="{{ route('categorias.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> + Nueva Asignatura
                </a>
            @endif
            {{-- EL BOTÓN DE EDITAR SE HA ELIMINADO DE AQUÍ PORQUE DABA ERROR DE VARIABLE INDEFINIDA --}}
        </div>
    </div>
</div>

@if(session('error'))
    <div class="alert alert-danger shadow-sm border-start border-5 border-danger">
        <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
    </div>
@endif

<hr>

<div class="row">
    @forelse ($viewData["categorias"] as $categoria)
        <div class="col-md-4 col-lg-3 mb-4">
            <div class="card h-100 shadow-sm border-0">
                
                @if($categoria->imagen)
                    <img src="{{ asset('imagenes/' . $categoria->imagen) }}" 
                         class="card-img-top" 
                         alt="{{ $categoria->nombre }}" 
                         style="height: 150px; object-fit: contain; padding: 10px;">
                @else
                    <img src="{{ asset('/img/tarea1.jpg') }}" 
                         class="card-img-top" 
                         style="height: 150px; object-fit: cover;">
                @endif
                
                <div class="card-body text-center d-flex flex-column">
                    <h5 class="card-title fw-bold">{{ $categoria->nombre }}</h5>
                    
                    <div class="mt-auto d-grid gap-2">
                        <a href="{{ route('tareas.index', ['categoria_id' => $categoria->id]) }}" class="btn btn-primary">
                          Ver Tareas
                            </a>
                         

                        {{-- ACCIONES DE ADMINISTRADOR: EDITAR Y BORRAR --}}
                        @if(Auth::check() && Auth::user()->role == 'admin')
                            <div class="d-flex gap-1">
                                {{-- BOTÓN EDITAR (Movido aquí para que funcione con cada $categoria) --}}
                                <a href="{{ route('categorias.edit', $categoria->id) }}" class="btn btn-warning btn-sm flex-grow-1">
                                    <i class="bi bi-pencil"></i> Editar
                                </a>

                                {{-- BOTÓN BORRAR --}}
                                <form action="{{ route('categorias.destroy', $categoria->id) }}" method="POST" class="flex-grow-1" onsubmit="return confirm('¿Seguro que quieres borrar {{ $categoria->nombre }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                                        <i class="bi bi-trash"></i> Borrar
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center mt-5">
            <div class="alert alert-info">
                Aún no hay asignaturas creadas.
            </div>
        </div>
    @endforelse
</div>
@endsection