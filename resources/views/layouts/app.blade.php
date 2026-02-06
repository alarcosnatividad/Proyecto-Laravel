<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous" />
    <link href="{{ asset('/css/app.css') }}" rel="stylesheet" />
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    
    <title>@yield('titulo', 'Gestor de Tareas')</title>

    {{-- Los MetaTags --}}

    <meta property="og:title" content="{{ $viewData['title'] ?? 'Mi Gestor de Tareas' }}" />
    <meta property="og:description" content="{{ $viewData['subtitle'] ?? 'Organiza y comparte tus tareas de clase' }}" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:type" content="website" />

{{-- Si estamos viendo una tarea concreta, enviamos su imagen, si no, una por defecto --}}
@if(isset($viewData['tarea']) && $viewData['tarea']->imagen)
    <meta property="og:image" content="{{ asset('imagenes/' . $viewData['tarea']->imagen) }}" />
@else
    <meta property="og:image" content="{{ asset('img/logo.png') }}" />
@endif
</head>
<body>
    {{-- BARRA DE NAVEGACIÓN --}}
    <nav class="navbar navbar-expand-lg navbar-dark bg-secondary py-4">
        <div class="container">
            <div class="navbar-nav">
                <a class="nav-link active" href="{{ route('home.index') }}">Inicio</a>
                
                {{-- 1. Muro Global: Para ver comentarios de todos --}}
                <a class="nav-link" href="{{ route('tareas.index') }}">🌐 Muro Global</a>
                
                {{-- 2. Mis Tareas: Pista del profesor (filtro por user_id) --}}
                <a class="nav-link" href="{{ route('tareas.index', ['filtro' => 'mias']) }}">✅ Mis Tareas</a>
                
                <a class="nav-link" href="{{ route('home.about') }}">Sobre Nosotros</a>
                
                <a class="nav-link" href="{{ route('tareas.favoritas') }}" style="color: pink; font-weight: bold;">
                    ❤️ Favoritas
                </a>
            </div>

            <ul class="navbar-nav ms-auto">
                @guest
                    @if (Route::has('login'))
                        <li class="nav-item">
                            <a class="nav-link text-white fw-bold" href="{{ route('login') }}">Iniciar Sesión</a>
                        </li>
                    @endif
                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link text-white fw-bold" href="{{ route('register') }}">Registrarse</a>
                        </li>
                    @endif
                @else
                    {{-- DROPDOWN DE USUARIO --}}
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{ Auth::user()->name }}
                        </a>

                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                Cerrar Sesión
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>
                    
                    {{-- SECCIÓN DE PUNTOS --}}
                    <li class="nav-item d-flex align-items-center ms-3">
                        <span class="badge bg-dark text-light p-2 shadow-sm">
                            🪙 Puntos: {{ Auth::user()->puntos }}
                        </span>
                    </li>

                    @if(Auth::user()->puntos < 10)
                        <li class="nav-item ms-2">
                            <form action="{{ route('puntos.recargar') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-warning btn-sm fw-bold">
                                    Recargar 100 pts
                                </button>
                            </form>
                        </li>
                    @endif
                @endguest
            </ul>
        </div>
    </nav>

    {{-- CABECERA DINÁMICA --}}
    <header class="masthead bg-primary text-white text-center py-4">
        <div class="container d-flex align-items-center flex-column">
            <h2>@yield('subtitulo', 'Organiza tu día con Laravel')</h2>
        </div>
    </header>

    {{-- CONTENIDO PRINCIPAL --}}
    <div class="container my-4">
        @yield('contenido')
    </div>

    {{-- FOOTER --}}
    <footer class="copyright py-4 text-center text-white bg-dark mt-auto">
        <div class="container">
            <small>
                Copyright © 2026 - <span class="fw-bold">Tu Nombre Aquí</span>
            </small>
        </div>
    </footer>

    {{-- SCRIPTS (Importante: jQuery antes que Bootstrap para el scroll) --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>