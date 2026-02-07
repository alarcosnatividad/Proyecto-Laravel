@extends('layouts.app')

@section('contenido')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-3 fw-bold text-secondary">
                <i class="bi bi-speedometer2"></i> {{ __('Dashboard Estadístico') }}
            </h1>
            <hr>
        </div>
    </div>

    {{-- TARJETAS DE MÉTRICAS PRINCIPALES --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white shadow-sm border-0 h-100">
                <div class="card-body text-center d-flex flex-column justify-content-center">
                    <h6 class="text-uppercase small fw-bold opacity-75">Ventas Totales (Desbloqueos)</h6>
                    <h2 class="display-5 fw-bold">{{ $viewData['totalVentas'] }}</h2>
                    <i class="bi bi-cart-check fs-1 mt-2 opacity-25"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white shadow-sm border-0 h-100">
                <div class="card-body text-center d-flex flex-column justify-content-center">
                    <h6 class="text-uppercase small fw-bold opacity-75">Puntos Recaudados (Ingresos)</h6>
                    <h2 class="display-5 fw-bold">{{ $viewData['puntosRecaudados'] }} pts</h2>
                    <i class="bi bi-coin fs-1 mt-2 opacity-25"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-dark text-white shadow-sm border-0 h-100">
                <div class="card-body text-center d-flex flex-column justify-content-center">
                    <h6 class="text-uppercase small fw-bold opacity-75">Usuarios en la Plataforma</h6>
                    <h2 class="display-5 fw-bold">{{ $viewData['totalUsuarios'] }}</h2>
                    <i class="bi bi-people fs-1 mt-2 opacity-25"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- GRÁFICO DE TAREAS MÁS POPULARES --}}
        <div class="col-lg-7 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white fw-bold py-3">
                    <i class="bi bi-pie-chart-fill text-primary"></i> Ranking de Tareas más Vendidas
                </div>
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                    @if(count($viewData['data']) > 0)
                        <div style="width: 85%;">
                            <canvas id="canvasVentas"></canvas>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-bar-chart text-muted fs-1"></i>
                            <p class="text-muted mt-2">Aún no hay datos de ventas para mostrar el gráfico.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- ÚLTIMOS PEDIDOS REGISTRADOS --}}
        <div class="col-lg-5 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white fw-bold py-3">
                    <i class="bi bi-clock-history text-success"></i> Últimas Transacciones
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr class="small text-uppercase">
                                    <th>Usuario</th>
                                    <th>Tarea</th>
                                    <th class="text-end">Coste</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($viewData['ventasRecientes'] as $venta)
                                    <tr>
                                        <td><small class="fw-bold text-dark">{{ $venta->usuario }}</small></td>
                                        <td><small class="text-truncate d-inline-block" style="max-width: 150px;">{{ $venta->tarea }}</small></td>
                                        <td class="text-end text-success fw-bold small">{{ $venta->puntos_pagados }} pts</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-4 text-muted small">No hay pedidos recientes</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- CARGA DE CHART.JS DESDE CDN --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('canvasVentas');
        
        if (ctx) {
            new Chart(ctx, {
                type: 'doughnut', // Estilo "Donut" que es más moderno que el Pie normal
                data: {
                    labels: {!! json_encode($viewData['labels']) !!},
                    datasets: [{
                        label: 'Ventas Totales',
                        data: {!! json_encode($viewData['data']) !!},
                        backgroundColor: [
                            '#0d6efd', // Azul
                            '#198754', // Verde
                            '#ffc107', // Amarillo
                            '#dc3545', // Rojo
                            '#6610f2', // Morado
                            '#0dcaf0'  // Cyan
                        ],
                        hoverOffset: 15,
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                font: { size: 12 }
                            }
                        }
                    },
                    cutout: '60%' // Hace que el centro sea hueco (estilo donut)
                }
            });
        }
    });
</script>
@endsection