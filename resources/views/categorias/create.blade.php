@extends('layouts.app')

@section('titulo', $viewData["title"])
@section('subtitulo', $viewData["subtitle"])

@section('contenido')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Nueva Asignatura</h4>
                </div>
{{-- --------------------------------CREATE ES UN FORMULARIO QUE ENLAZA CON RUTA STORE Y METODO DEL CONTROLADOR A TRAVES DE ACTION --}}
                <div class="card-body">
                    {{-- IMPORTANTE: enctype permite subir archivos binarios (fotos) --}}
                    <form action="{{ route('categorias.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="nombre" class="form-label fw-bold">Nombre de la Asignatura</label>
                            <input type="text" name="nombre" id="nombre" class="form-control" 
                                   placeholder="Ej: Despliegue de Aplicaciones" required>
                            @error('nombre')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="imagen" class="form-label fw-bold">Imagen o Logo oficial</label>
                            <input type="file" name="imagen" id="imagen" class="form-control" accept="image/*">
                            <div class="form-text">Formatos permitidos: JPG, PNG, JPG (Máx. 2MB)</div>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('home.index') }}" class="btn btn-secondary">Cancelar</a>
                            {{-- con submit lanza lo que lleva el formulario en el action.. que te lleva a store que guarda --}}
                            <button type="submit" class="btn btn-primary px-4">Guardar Asignatura</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection