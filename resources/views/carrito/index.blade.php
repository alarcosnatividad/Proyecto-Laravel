@extends('layouts.app') 

@section('contenido') 

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow border-0">
                <div class="card-header bg-dark text-white py-3">
                    <h5 class="mb-0"><i class="bi bi-cart-check-fill me-2"></i> Mi Selección de Tareas</h5>
                </div>
                <div class="card-body p-4">
                    
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Nombre de la Tarea</th>
                                <th class="text-center">Puntos</th>
                                <th class="text-center">Cantidad</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($viewData["tareas"] as $tarea)
                                <tr>
                                    <td><span class="badge bg-secondary">*{{ $tarea->id }}</span></td>
                                    <td class="fw-bold">{{ $tarea->nombre }}</td>
                                    <td class="text-center">{{ $tarea->coste }} pts</td>
                                    <td class="text-center">1</td> {{-- Cantidad fija por ahora --}}
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="bi bi-cart-x fs-1"></i>
                                            <p class="mt-2">No has seleccionado ninguna tarea aún.</p>
                                            <a href="{{ route('tareas.index') }}" class="btn btn-primary btn-sm">Ver tareas disponibles</a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    @if(count($viewData["tareas"]) > 0)
                        <hr class="my-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h3 class="fw-bold text-primary">Total: {{ $viewData["total"] }} puntos</h3>
                            </div>
                            <div>
                                <a href="{{ route('carrito.delete') }}" class="btn btn-outline-danger me-2">
                                    <i class="bi bi-trash"></i> Vaciar
                                </a>
                                <form action="{{ route('carrito.comprar') }}" method="POST">
                                 @csrf
                                <button type="submit" class="btn btn-success btn-lg px-4 shadow-sm">
                                <i class="bi bi-check-circle"></i> Confirmar Pedido
                                 </button>
                                 </form>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>

@endsection