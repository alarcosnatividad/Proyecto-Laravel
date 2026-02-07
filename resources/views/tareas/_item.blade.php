@foreach ($tareas as $tarea)
    <div class="col-md-4 col-lg-3 mb-2 tarea-item animate__animated animate__fadeInUp">
        <div class="card {{ $tarea->completada ? 'border-success border-3' : '' }} h-100 shadow-sm">
            {{-- LÓGICA DE LA IMAGEN --}}
            @if($tarea->imagen)
                <img src="{{ asset('imagenes/' . $tarea->imagen) }}" class="card-img-top img-card" alt="Imagen de {{ $tarea->nombre }}" style="height: 200px; object-fit: cover;">
            @else
                <img src="{{ asset('/img/tarea1.jpg') }}" class="card-img-top img-card" alt="Imagen por defecto" style="height: 200px; object-fit: cover;">
            @endif
            
            <div class="card-body text-center d-flex flex-column">
                <h5 class="card-title fw-bold mb-1">{{ $tarea->nombre }}</h5>
                
                {{-- --- AQUÍ ESTÁ EL NOMBRE DEL AUTOR --- --}}
                <div class="mb-3">
                    <small class="text-muted">
                        <i class="bi bi-person-circle"></i> 
                        {{ $tarea->user->name ?? 'Anónimo' }}
                    </small>
                </div>
                {{-- ------------------------------------- --}}

                <div class="mb-2">
                    @if($tarea->completada)
                        <span class="badge bg-success">¡Completada!</span>
                    @else
                        <span class="badge bg-secondary">Pendiente</span>
                    @endif
                </div>

                {{-- BOTONES DE ACCIÓN --}}
                <div class="mt-auto mb-2">
                    <a href="{{ route('tareas.show', ['id'=> $tarea->id]) }}" class="btn btn-primary btn-sm">Ver</a>
                    <a href="{{ route('tareas.edit', ['id'=> $tarea->id]) }}" class="btn btn-warning btn-sm">Editar</a>
                    
                    <form action="{{ route('tareas.destroy', $tarea->id) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE') 
                        <button type="submit" class="btn btn-danger btn-sm">Borrar</button>
                    </form>
                </div>

                {{-- SECCIÓN DE LIKES (CORAZÓN) --}}
                <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top">
                    <form action="{{ route('tareas.like', $tarea->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-link text-decoration-none p-0" style="font-size: 1.5rem;">
                            @if($tarea->likes->contains(Auth::user()))
                                ❤️ 
                            @else
                                🤍 
                            @endif
                        </button>
                    </form>
                    <span class="text-muted small">
                        {{ $tarea->likes->count() }} Likes
                    </span>
                </div>
            </div>
        </div>
    </div>
@endforeach