@extends('layouts.app')

@section('titulo', $viewData["title"])
@section('subtitulo', $viewData["subtitle"])

@section('contenido')
{{-- TARJETA PRINCIPAL DE LA TAREA --}}
<div class="card mb-3 shadow-sm">
    <div class="row g-0">
        <div class="col-md-4">
            @if($viewData["tarea"]->imagen)
                <img src="{{ asset('imagenes/' . $viewData["tarea"]->imagen) }}" class="card-img-top img-card" alt="Imagen de la tarea" style="height: 100%; object-fit: cover;">
            @else
                <img src="{{ asset('/img/tarea1.jpg') }}" class="card-img-top img-card" alt="Imagen por defecto" style="height: 100%; object-fit: cover;">
            @endif
        </div>
        <div class="col-md-8">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <h5 class="card-title fw-bold">{{ $viewData["tarea"]->nombre }}</h5>
                    
                    {{-- 1. BOTÓN COMPARTIR RÁPIDO (REDES SOCIALES) --}}
                    <button onclick="compartirApp()" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                        <i class="bi bi-share"></i> Compartir
                    </button>
                </div>

                <p class="card-text mt-3">{{ $viewData["tarea"]->descripcion }}</p>
                
                <p class="card-text">
                    <small class="text-muted">Creada el: {{ $viewData["tarea"]->created_at->format('d-m-Y H:i') }}</small>
                </p>

                <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                    <a href="{{ route('tareas.index') }}" class="btn btn-outline-secondary btn-sm">Volver a la lista</a>

                    <div class="d-flex align-items-center">
                        <form action="{{ route('tareas.like', $viewData['tarea']->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-link text-decoration-none p-0 me-2" style="font-size: 1.5rem;">
                                @if($viewData["tarea"]->likes->contains(Auth::user())) ❤️ @else 🤍 @endif
                            </button>
                        </form>
                        <span class="text-muted fw-bold">{{ $viewData["tarea"]->likes->count() }} Likes</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- 2. SECCIÓN DE ENVÍO POR EMAIL (ESTILO FICHA PRODUCTO) --}}
<div class="card mb-4 border-info shadow-sm">
    <div class="card-body py-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h6 class="mb-1 text-info"><i class="bi bi-envelope-at-fill"></i> ¿Quieres recomendar esta tarea?</h6>
                <p class="small text-muted mb-0">Envía la ficha técnica completa a un compañero por email.</p>
            </div>
            <div class="col-md-6">
                <form action="{{ route('tareas.enviar.email', $viewData['tarea']->id) }}" method="POST">
                    @csrf
                    <div class="input-group">
                        <input type="email" name="email_destino" class="form-control form-control-sm" placeholder="correo@ejemplo.com" required>
                        <button class="btn btn-info btn-sm text-white px-3" type="submit">Enviar Ficha</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- SECCIÓN DE OPINIONES Y VALORACIONES --}}
<div class="card shadow-sm">
    <div class="card-header bg-dark text-white">
        <h4 class="mb-0" style="font-size: 1.1rem;">💬 Opiniones de la comunidad</h4>
    </div>
    <div class="card-body">
        <div class="comentarios-listado mb-4">
            @forelse($viewData['tarea']->comentarios as $comentario)
                <div class="d-flex mb-3 border-bottom pb-3">
                    <div class="flex-shrink-0">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($comentario->user->name) }}&background=random&color=fff" class="rounded-circle shadow-sm" width="45" height="45">
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 fw-bold">{{ $comentario->user->name }}</h6>
                            <span class="text-warning small">{{ str_repeat('⭐', $comentario->valoracion) }}</span>
                        </div>
                        <p class="text-muted mb-1" style="font-size: 0.75rem;">{{ $comentario->created_at->diffForHumans() }}</p>
                        <p class="mb-0 text-dark small">{{ $comentario->contenido }}</p>
                    </div>
                </div>
            @empty
                <div class="text-center py-4">
                    <p class="text-muted italic">Nadie ha valorado esta tarea todavía.</p>
                </div>
            @endforelse
        </div>

        {{-- FORMULARIO PARA COMENTAR --}}
        <div class="bg-light p-3 rounded border">
            <h6 class="fw-bold mb-3">Deja tu valoración</h6>
            <form action="{{ route('comentarios.store', $viewData['tarea']->id) }}" method="POST">
                @csrf
                <div class="row g-2">
                    <div class="col-md-4 mb-2">
                        <select name="valoracion" class="form-select form-select-sm">
                            <option value="5">⭐⭐⭐⭐⭐</option>
                            <option value="4">⭐⭐⭐⭐</option>
                            <option value="3" selected>⭐⭐⭐</option>
                            <option value="2">⭐⭐</option>
                            <option value="1">⭐</option>
                        </select>
                    </div>
                    <div class="col-12 mb-2">
                        <textarea name="contenido" class="form-control form-control-sm" rows="2" placeholder="Tu opinión..." required></textarea>
                    </div>
                </div>
                <div class="text-end">
                    <button type="submit" class="btn btn-success btn-sm fw-bold">Publicar</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- SCRIPT COMPARTIR NATIVO --}}
<script>
function compartirApp() {
    if (navigator.share) {
        navigator.share({
            title: '{{ $viewData["tarea"]->nombre }}',
            text: 'Mira esta tarea: {{ $viewData["tarea"]->nombre }}',
            url: window.location.href
        }).catch((error) => console.log('Error compartiendo', error));
    } else {
        alert("Copia este enlace para compartir: " + window.location.href);
    }
}
</script>
@endsection