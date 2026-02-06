@extends('layouts.app')

@section('contenido')
<div class="container">
    <div class="card">
        <div class="card-header">Crear Nueva Tarea</div>
        <div class="card-body">
            {{-- Mostramos errores de validación si los hay --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('tareas.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- 1. Imagen de la tarea --}}
                <div class="mb-3">
                    <label class="form-label">Imagen de la tarea</label>
                    <input type="file" name="imagen" class="form-control">
                </div>

                {{-- 2. Nombre --}}
                <div class="mb-3">
                    <label class="form-label">Nombre:</label>
                    <input type="text" name="nombre" class="form-control" value="{{ old('nombre') }}" required>
                </div>

                {{-- 3. Asignatura (ESTE ES EL CAMBIO CLAVE) --}}
                <div class="mb-3">
                    <label class="form-label">Asignatura:</label>
                    <select name="categoria_id" class="form-select" required>
                        <option value="">Selecciona una asignatura...</option>
                        @foreach($viewData["categorias"] as $cat)
                            <option value="{{ $cat->id }}" 
                                {{ (old('categoria_id') == $cat->id || $viewData['categoria_id_seleccionada'] == $cat->id) ? 'selected' : '' }}>
                                {{ $cat->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- 4. Descripción --}}
                <div class="mb-3">
                    <label class="form-label">Descripción:</label>
                    <textarea name="descripcion" class="form-control" rows="3">{{ old('descripcion') }}</textarea>
                </div>

                {{-- 5. Asignar a usuario --}}
                <div class="mb-3">
                    <label class="form-label">Asignar esta tarea a:</label>
                    <select name="user_id" class="form-select">
                        @foreach($viewData["users"] as $user)
                            <option value="{{ $user->id }}" {{ Auth::id() == $user->id ? 'selected' : '' }}>
                                {{ $user->id == Auth::id() ? 'A mí mismo (' . $user->name . ')' : $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-primary w-100">Guardar Tarea</button>
            </form>
        </div>
    </div>
</div>
@endsection