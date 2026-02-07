@extends('layouts.app')

@section('titulo', $viewData["title"])

@section('contenido')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-warning text-dark fw-bold">
                    <i class="bi bi-pencil-square"></i> Editar Tarea: {{ $viewData['tarea']->nombre }}
                </div>

                <div class="card-body">
                    {{-- Bloque para mostrar errores de validación --}}
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('tareas.update', $viewData['tarea']->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label fw-bold">Nombre de la Tarea:</label>
                            <input name="nombre" value="{{ old('nombre', $viewData['tarea']->nombre) }}" type="text" class="form-control" placeholder="Ej: Estudiar para el examen">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Asignatura:</label>
                            <select name="categoria_id" class="form-select">
                                @foreach ($viewData['categorias'] as $categoria)
                                    <option value="{{ $categoria->id }}" 
                                        {{ $viewData['tarea']->categoria_id == $categoria->id ? 'selected' : '' }}>
                                        {{ $categoria->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Descripción detallada:</label>
                            <textarea name="descripcion" rows="4" class="form-control" placeholder="Escribe aquí los detalles...">{{ old('descripcion', $viewData['tarea']->descripcion) }}</textarea>
                        </div>

                        <div class="mb-3 form-check form-switch">
                            <input name="completada" type="checkbox" class="form-check-input" id="checkCompletada" 
                                   {{ $viewData['tarea']->completada ? 'checked' : '' }}>
                            <label class="form-check-label" for="checkCompletada">¿Marcar como completada?</label>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Imagen de la tarea:</label>
                            <div class="mb-2">
                                @if($viewData['tarea']->imagen)
                                    <img src="{{ asset('imagenes/' . $viewData['tarea']->imagen) }}" class="img-thumbnail" style="width: 150px;">
                                    <p class="small text-muted">Imagen actual</p>
                                @endif
                            </div>
                            <input type="file" name="imagen" class="form-control">
                            <div class="form-text">Si no seleccionas nada, se mantendrá la imagen actual.</div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary fw-bold">
                                <i class="bi bi-save"></i> Guardar cambios
                            </button>
                            <a href="{{ route('tareas.index') }}" class="btn btn-outline-secondary">
                                Cancelar y volver
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection