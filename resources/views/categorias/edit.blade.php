@extends('layouts.app')
@section('contenido')
<form action="{{ route('categorias.update', $viewData['categoria']->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
    <div class="mb-3">
        <label class="form-label">Nombre de la Asignatura</label>
        <input type="text" name="nombre" class="form-control" value="{{ $viewData['categoria']->nombre }}">
    </div>

    {{-- AÑADE ESTE CAMPO PARA LA IMAGEN --}}
    <div class="mb-3">
        <label class="form-label">Cambiar Imagen (opcional)</label>
        <input type="file" name="imagen" class="form-control">
    </div>
    
    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
</form>