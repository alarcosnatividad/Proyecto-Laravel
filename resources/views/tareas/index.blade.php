@extends('layouts.app')

@section('titulo', $viewData["title"])
@section('subtitulo', $viewData["subtitle"])

@section('contenido')
<div class="mb-3 d-flex justify-content-between align-items-center">
    <a href="{{ route('tareas.create') }}" class="btn btn-success shadow-sm">
        <i class="bi bi-plus-circle"></i> + Nueva Tarea
    </a>
    
    {{-- Indicador visual de qué estamos viendo --}}
    @if(request()->query('filtro') == 'mias')
        <span class="badge bg-success p-2">Mostrando: Mis Tareas Asignadas</span>
    @else
        <span class="badge bg-secondary p-2">Mostrando: Muro Global</span>
    @endif
</div>

{{-- Contenedor donde se cargarán las tareas --}}
<div class="row" id="contenedor-tareas">
    {{-- USAMOS $tareas DIRECTAMENTE (Arregla el error Undefined Variable) --}}
    @if(isset($tareas) && $tareas->count() > 0)
        @include('tareas._item', ['tareas' => $tareas])
    @else
        <div class="col-12 text-center mt-5">
            <div class="alert alert-light border shadow-sm" role="alert">
                <h3 class="alert-heading">📭 Vaya, no hay nada por aquí...</h3>
                <p>No se han encontrado tareas en esta sección.</p>
                <hr>
                <a href="{{ route('tareas.index') }}" class="btn btn-outline-primary">Ver todas las tareas</a>
            </div>
        </div>
    @endif
</div>

{{-- Spinner de carga que aparece al bajar (Scroll Infinito) --}}
<div id="cargando" class="text-center my-4" style="display: none;">
    <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Cargando...</span>
    </div>
</div>

{{-- Scripts para el Scroll Infinito --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript">
    var pagina = 1;
    var cargando = false;
    var ultimaPagina = false;

    $(window).scroll(function() {
        // Si llegamos cerca del final del scroll
        if ($(window).scrollTop() + $(window).height() >= $(document).height() - 100) {
            if (!cargando && !ultimaPagina) {
                cargarMasTareas();
            }
        }
    });

    function cargarMasTareas() {
        pagina++;
        cargando = true;
        $('#cargando').show();

        // Detectamos si hay filtros en la URL actual para mantenerlos en la paginación
        var urlParams = new URLSearchParams(window.location.search);
        var filtro = urlParams.get('filtro') || '';
        var urlAjax = "?page=" + pagina;
        if(filtro) urlAjax += "&filtro=" + filtro;

        $.ajax({
            url: urlAjax,
            type: "get"
        })
        .done(function(data) {
            if (data.trim() == "") {
                ultimaPagina = true;
                $('#cargando').hide();
                return;
            }
            $('#cargando').hide();
            $("#contenedor-tareas").append(data); 
            cargando = false;
        })
        .fail(function() {
            console.log("Error al cargar más tareas");
            $('#cargando').hide();
        });
    }
</script>
@endsection