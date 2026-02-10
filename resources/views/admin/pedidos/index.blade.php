@extends('layouts.app')

@section('contenido')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-cart-fill text-primary"></i> Historial Global de Pedidos</h2>
        <a href="{{ route('admin.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Volver al Panel
        </a>
    </div>

    <div class="card shadow border-0">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th class="ps-4">Usuario</th>
                        <th>Tarea Comprada</th>
                        <th>Puntos</th>
                        <th>Fecha de Compra</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pedidos as $pedido)
                    <tr>
                        <td class="ps-4">
                            <span class="fw-bold">{{ $pedido->user->name }}</span><br>
                            <small class="text-muted">{{ $pedido->user->email }}</small>
                        </td>
                        <td>{{ $pedido->tarea->nombre ?? 'Tarea eliminada' }}</td>
                        <td><span class="badge bg-success">{{ $pedido->puntos_pagados }} 🪙</span></td>
                        <td>{{ $pedido->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection