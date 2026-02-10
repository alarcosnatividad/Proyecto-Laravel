@extends('layouts.app')


@section('subtitulo', 'Mi Proyecto: Objetivos y Mejoras')

@section('contenido')
<div class="container mt-4">
    <div class="row bg-white shadow-sm p-4 rounded border mb-5">
        
        {{-- COLUMNA DE PERFIL --}}
        <div class="col-md-4 text-center border-end">
            <div class="mb-3">
                <img src="{{ asset('imagenes/nati.png') }}" 
                     alt="Mi foto" 
                     class="img-fluid rounded-circle shadow" 
                     style="width: 180px; height: 180px; object-fit: cover; border: 4px solid #1abc9c;">
            </div>
            <h3 class="fw-bold text-secondary">Natividad Alarcos</h3>
            <p class="text-muted">Desarrolladora del Proyecto</p>
            
            <div class="mt-4 p-3 bg-light rounded text-start border-start border-4 border-primary">
                <p class="small mb-0"><strong>Nota:</strong> He desarrollado este proyecto desde cero, implementando manualmente cada funcionalidad para asegurar el cumplimiento de los requisitos técnicos solicitados.</p>
            </div>
        </div>

        {{-- COLUMNA DE MEJORAS --}}
        <div class="col-md-8 ps-md-5">
            <h2 class="fw-bold mb-4 text-dark">Checklist de Requisitos</h2>
            
            <div class="row">
                {{-- LISTA 1: MEJORAS GENERALES (Orden exacto del profesor) --}}
                <div class="col-md-6">
                    <h5 class="fw-bold text-primary mb-3"><i class="bi bi-list-check"></i> Mejoras Generales</h5>
                    <ul class="list-unstyled small">
                        <li class="mb-2"><i class="bi bi-check-lg text-success"></i> Controlar que no pueda comprar si no tiene dinero</li>
                        <li class="mb-2"><i class="bi bi-check-lg text-success"></i> Scroll infinito</li>
                        <li class="mb-2"><i class="bi bi-check-lg text-success"></i> Personalización de colores, fuentes y resto del diseño. Importante usar iconos o emojis. Favicon.</li>
                        <li class="mb-2"><i class="bi bi-check-lg text-success"></i> Enviar por correo los pedidos cuando se realice y que se envíe otro correo al administrador</li>
                        <li class="mb-2"><i class="bi bi-check-lg text-success"></i> Vista para el administrador en la que salgan todos los pedidos</li>
                        <li class="mb-2"><i class="bi bi-check-lg text-success"></i> Posibilidad de enviar la ficha de producto a una red social</li>
                    </ul>
                </div>

                {{-- LISTA 2: MEJORAS ESPECÍFICAS (Tus 3 elegidas con sus palabras) --}}
                <div class="col-md-6">
                    <h5 class="fw-bold text-primary mb-3"><i class="bi bi-star-fill"></i> Mejoras Específicas</h5>
                    <ul class="list-unstyled">
                        <li class="mb-4">
                            <i class="bi bi-1-square-fill text-primary"></i> <strong>i18n en español e inglés</strong>
                            <span class="d-block small text-muted mt-1">Implementación de archivos de traducción dinámicos para soporte bilingüe.</span>
                        </li>
                        <li class="mb-4">
                            <i class="bi bi-2-square-fill text-primary"></i> <strong>Hacer que el carrito sea "permanente"</strong>
                            <span class="d-block small text-muted mt-1">Persistencia de datos del pedido y saldo vinculados a la cuenta del usuario.</span>
                        </li>
                        <li class="mb-4">
                            <i class="bi bi-3-square-fill text-primary"></i> <strong>Página de panel de control para el administrador con gráficos estadísticos</strong>
                            <span class="d-block small text-muted mt-1">Visualización de variables fundamentales mediante métricas visuales.</span>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- CIERRE --}}
            <div class="mt-4 p-3 bg-dark text-white rounded shadow-sm">
                <p class="mb-0 small">
                    <i class="bi bi-info-circle"></i> <em>"Proyecto construido desde cero integrando lógica de negocio avanzada en el lado del servidor y dinamismo en el cliente."</em>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection