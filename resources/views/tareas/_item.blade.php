@foreach ($tareas as $tarea)
    <div class="col-md-4 col-lg-3 mb-2 tarea-item animate__animated animate__fadeInUp">
        <div class="card {{ $tarea->completada ? 'border-success border-3' : '' }} h-100 shadow-sm">
            
            {{-- LÓGICA DE LA IMAGEN (MANTENIDA) --}}
            @if($tarea->imagen)
                <img src="{{ asset('imagenes/' . $tarea->imagen) }}" class="card-img-top img-card" alt="Imagen de {{ $tarea->nombre }}" style="height: 200px; object-fit: cover;">
            @else
                <img src="{{ asset('/img/tarea1.jpg') }}" class="card-img-top img-card" alt="Imagen por defecto" style="height: 200px; object-fit: cover;">
            @endif
            
            <div class="card-body text-center d-flex flex-column">
                <h5 class="card-title fw-bold mb-1">{{ $tarea->nombre }}</h5>
                
                {{-- NOMBRE DEL AUTOR (MANTENIDO) --}}
                <div class="mb-3">
                    <small class="text-muted">
                        <i class="bi bi-person-circle"></i> 
                        {{ $tarea->user->name ?? 'Anónimo' }}
                    </small>
                </div>

                <div class="mb-2">
                    @if($tarea->completada)
                        <span class="badge bg-success">¡Completada!</span>
                    @else
                        <span class="badge bg-secondary">Pendiente</span>
                    @endif
                </div>

                {{-- BOTONES DE ACCIÓN (ADAPTADOS CON LÓGICA DE PUNTOS) --}}
                <div class="mt-auto mb-2">
                    @php
                        // 1. Verificamos si ya la compró
                        $yaPagada = \App\Models\Pedido::where('user_id', Auth::id())
                                        ->where('tarea_id', $tarea->id)
                                        ->exists();
                        
                        // 2. Verificamos si es el dueño
                        $esMia = ($tarea->user_id == Auth::id());
                    @endphp

                    @if($esMia || $yaPagada)
                        {{-- Botones normales si tiene acceso --}}
                        <a href="{{ route('tareas.show', ['id'=> $tarea->id]) }}" class="btn btn-primary btn-sm">Ver</a>
                        
                        {{-- Solo el dueño puede editar o borrar  controlador index tarea metodo show--}}
                        @if($esMia)
                            <a href="{{ route('tareas.edit', ['id'=> $tarea->id]) }}" class="btn btn-warning btn-sm">Editar</a>
                            <form action="{{ route('tareas.destroy', $tarea->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('¿ESTÁS SEGURO? Esta acción borrará la tarea definitivamente.')">
                                @csrf
                                @method('DELETE') 
                                <button type="submit" class="btn btn-danger btn-sm" >Borrar</button>
                            </form>
                        @endif
                    @else
                        {{-- Botón de COMPRA si no tiene acceso --}}
                        <form action="{{ route('pedidos.comprar', $tarea->id) }}" method="POST" class="d-inline w-100">
                           @csrf
                        <button type="submit" class="btn btn-warning btn-sm w-100">
                        <i class="bi bi-lock-fill"></i> Desbloquear (10 pts)
                        </button>
                        </form>
                    @endif
                </div>



{{-- Regla: Solo se muestra el botón si NO es del usuario y si NO está pagada --}}
@if(!$esMia && !$yaPagada)
    <form method="POST" action="{{ route('carrito.add', ['id' => $tarea->id]) }}" class="mt-2">
        @csrf {{-- Seguridad obligatoria para POST --}}
        <button type="submit" class="btn btn-primary w-100">
            <i class="bi bi-cart-plus"></i> Añadir al carrito
        </button>
    </form>
@else
    {{-- Si es mía o ya está pagada, mostramos un mensaje o lo dejamos vacío --}}
    <div class="alert alert-light small mt-2 text-center">
        <i class="bi bi-info-circle"></i> 
        {{ $esMia ? 'Tu propia tarea' : 'Tarea ya adquirida' }}
    </div>
@endif




                {{-- SECCIÓN DE LIKES (MANTENIDA) --}}
                <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top">
                    @php
                        $haDadoLike = \App\Models\Like::where('user_id', Auth::id())
                                                    ->where('tarea_id', $tarea->id)
                                                    ->exists();
                    @endphp

                    <form action="{{ route('tareas.like', $tarea->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-link text-decoration-none p-0" style="font-size: 1.5rem;">
                            @if($haDadoLike) ❤️ @else 🤍 @endif
                        </button>
                    </form>
                    <span class="text-muted small">
                        {{ \App\Models\Like::where('tarea_id', $tarea->id)->count() }} Likes
                    </span>
                </div>
            </div>
        </div>
    </div>
@endforeach