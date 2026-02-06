<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous" />
    <link href="{{ asset('/css/app.css') }}" rel="stylesheet" />
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    
    
    <title>@yield('titulo', 'Gestor de Tareas')</title>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-secondary py-4">
        <div class="navbar-nav ms-auto">
    <a class="nav-link active" href="{{ route('home.index') }}">Inicio</a>
    <a class="nav-link active" href="{{ route('tareas.index') }}">Mis Tareas</a>
    <a class="nav-link active" href="{{ route('home.about') }}">Sobre Nosotros</a>
    <li class="nav-item">
    <a class="nav-link" href="{{ route('tareas.favoritas') }}" style="color: pink; font-weight: bold;">
        ❤️ Favoritas
    </a>
     </li>
    {{-- aqui donde pongo el login --}}
    <ul class="navbar-nav ms-auto">
    @guest
        @if (Route::has('login'))
            <li class="nav-item">
                <a class="nav-link" href="{{ route('login') }}" style="color: white; font-weight: bold;">Iniciar Sesión</a>
            </li>
        @endif

        @if (Route::has('register'))
            <li class="nav-item">
                <a class="nav-link" href="{{ route('register') }}" style="color: white; font-weight: bold;">Registrarse</a>
            </li>
        @endif

    @else
        <li class="nav-item dropdown">
            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre style="color: white;">
                {{ Auth::user()->name }}
            </a>

            

            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="{{ route('logout') }}"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    Cerrar Sesión
                </a>

            
               {{-- boton para recargar puntos  --}}
            @auth
    <li class="nav-item">
        <span class="nav-link text-light">
            🪙 Puntos: {{ Auth::user()->puntos }}
        </span>
    </li>

    @if(Auth::user()->puntos < 10)
        <li class="nav-item ms-2">
            <form action="{{ route('puntos.recargar') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-warning btn-sm shadow-sm animate__animated animate__pulse animate__infinite">
                    <i class="bi bi-lightning-charge"></i> Recargar 100 pts
                </button>
            </form>
        </li>
    @endif
@endauth

                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </li>
    @endguest
</ul>
</div>
    </nav>

    <header class="masthead bg-primary text-white text-center py-4">
        <div class="container d-flex align-items-center flex-column">
            <h2>@yield('subtitulo', 'Organiza tu día con Laravel')</h2>
        </div>
    </header>

    <div class="container my-4">
        @yield('contenido')
    </div>

    <div class="copyright py-4 text-center text-white bg-dark">
        <div class="container">
            <small>
                Copyright - <a class="text-reset fw-bold text-decoration-none" target="_blank" href="#">
                    Tu Nombre Aquí
                </a>
            </small>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>
</body>
</html>