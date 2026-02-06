@extends('layouts.app')

@section('titulo', $viewData["title"])

@section('contenido')
<div class="card mb-4">
    <div class="card-header">
        Editar Tarea
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('tareas.update', ['id'=> $viewData['tarea']->id]) }}">
            @csrf
            @method('PUT') 
            
            <div class="mb-3">
                <label class="form-label">Nombre:</label>
                <input name="nombre" value="{{ $viewData['tarea']->nombre }}" type="text" class="form-control">
            </div>
            
            <div class="mb-3">
                <label class="form-label">Asignatura:</label>
                <select name="asignatura" class="form-select">
                    <option value="General" {{ $viewData['tarea']->asignatura == 'General' ? 'selected' : '' }}>General</option>
                    <option value="Entorno Servidor" {{ $viewData['tarea']->asignatura == 'Entorno Servidor' ? 'selected' : '' }}>Entorno Servidor</option>
                    <option value="Entorno Cliente" {{ $viewData['tarea']->asignatura == 'Entorno Cliente' ? 'selected' : '' }}>Entorno Cliente</option>
                    <option value="Diseño Interfaces" {{ $viewData['tarea']->asignatura == 'Diseño Interfaces' ? 'selected' : '' }}>Diseño Interfaces</option>
                    <option value="Despliegue Aplicaciones" {{ $viewData['tarea']->asignatura == 'Despliegue Aplicaciones' ? 'selected' : '' }}>Despliegue Aplicaciones</option>
                    
                    <option value="Empresa II" {{ $viewData['tarea']->asignatura == 'Empresa II' ? 'selected' : '' }}>Empresa</option>
                    <option value="Optativa" {{ $viewData['tarea']->asignatura == 'Optativa' ? 'selected' : '' }}>Inglés</option>
                    <option value="Sostenibilidad" {{ $viewData['tarea']->asignatura == 'Sostenibilidad' ? 'selected' : '' }}>HLC</option>
                    <option value="Proyecto Final" {{ $viewData['tarea']->asignatura == 'Proyecto Final' ? 'selected' : '' }}>Proyecto Final</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Descripción:</label>
                <textarea name="descripcion" rows="3" class="form-control">{{ $viewData['tarea']->descripcion }}</textarea>
            </div>

            <div class="mb-3 form-check">
                <input name="completada" type="checkbox" class="form-check-input" id="checkCompletada" 
                       {{ $viewData['tarea']->completada ? 'checked' : '' }}>
                <label class="form-check-label" for="checkCompletada">¿Tarea Completada?</label>
            </div>
            
            <button type="submit" class="btn btn-primary">Actualizar</button>
        </form>
    </div>
</div>
@endsection