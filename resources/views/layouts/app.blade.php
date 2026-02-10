<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="{{ asset('/css/app.css') }}" rel="stylesheet" />
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="icon" href="{{ asset('imagenes/logo.png') }}" type="image/png">

    <title>@yield('titulo', __('messages.brand'))</title>

    {{-- MetaTags --}}
    <meta property="og:title" content="{{ $viewData['title'] ?? __('messages.brand') }}" />
    <meta property="og:description" content="{{ $viewData['subtitle'] ?? __('messages.welcome_subtitle') }}" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:type" content="website" />

    @if(isset($viewData['tarea']) && $viewData['tarea']->imagen)
        <meta property="og:image" content="{{ asset('imagenes/' . $viewData['tarea']->imagen) }}" />
    @else
        <meta property="og:image" content="{{ asset('img/logo.png') }}" />
    @endif
</head>
<body class="d-flex flex-column min-vh-100">
    {{-- BARRA DE NAVEGACIÓN --}}
    <nav class="navbar navbar-expand-lg navbar-dark bg-secondary py-3 shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('home.index') }}">
                <i class="bi bi-check2-square"></i> {{ __('messages.brand') }}
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                {{-- Menú Izquierdo --}}
                <div class="navbar-nav me-auto">
                    <a class="nav-link" href="{{ route('home.index') }}">{{ __('messages.home') }}</a>
                    <a class="nav-link" href="{{ route('tareas.index') }}">🌐 {{ __('messages.global_wall') }}</a>
                    <a class="nav-link" href="{{ route('tareas.index', ['filtro' => 'mias']) }}">✅ {{ __('messages.my_tasks') }}</a>  
                    <a class="nav-link" href="{{ route('home.about') }}">{{ __('messages.about') }}</a>
                    <a class="nav-link fw-bold" href="{{ route('tareas.favoritas') }}" style="color: #ffc1e3;">
                        <i class="bi bi-heart-fill"></i> {{ __('messages.favorites') }}
                    </a>
                </div>

                {{-- Menú Derecho --}}
                <ul class="navbar-nav ms-auto align-items-center">
                    @guest
                        @if (Route::has('login'))
                            <li class="nav-item"><a class="nav-link text-white fw-bold" href="{{ route('login') }}">{{ __('messages.login') }}</a></li>
                        @endif
                        @if (Route::has('register'))
                            <li class="nav-item"><a class="nav-link text-white fw-bold" href="{{ route('register') }}">{{ __('messages.register') }}</a></li>
                        @endif
                    @else
                        {{-- BOTÓN DE RECARGA (Ahora sale si tiene pocos puntos) --}}
                        @if(Auth::user()->puntos < 10)
                            <li class="nav-item me-2">
                                <form action="{{ route('puntos.recargar') }}" method="POST" class="m-0">
                                    @csrf
                                    <button type="submit" class="btn btn-warning btn-sm fw-bold shadow-sm">
                                        <i class="bi bi-lightning-fill"></i> {{ __('messages.recharge') }}
                                    </button>
                                </form>
                            </li>
                        @endif

                        {{-- SECCIÓN DE PUNTOS --}}
                        <li class="nav-item me-3">
                            <span class="badge bg-dark text-light p-2 border border-secondary shadow-sm">
                                🪙 {{ __('messages.points') }}: {{ Auth::user()->puntos }}
                            </span>
                        </li>

                        {{-- CARRITO (Ahora dentro de la lista para que no se deforme) --}}
                        <li class="nav-item me-3">
                            <a class="nav-link d-flex align-items-center text-white" href="{{ route('carrito.index') }}" style="white-space: nowrap;">
                                <i class="bi bi-cart me-1"></i>
                                @php $cartKey = "tareas_" . auth()->id(); @endphp
                                @if(session()->has($cartKey) && count(session($cartKey)) > 0)
                                    <span class="badge rounded-pill bg-danger ms-1" style="font-size: 0.7rem;">
                                        {{ count(session($cartKey)) }}
                                    </span>
                                @endif
                            </a>
                        </li>

                        {{-- DROPDOWN DE USUARIO --}}
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle text-white fw-bold" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i> {{ Auth::user()->name }}
                            </a>

                            <div class="dropdown-menu dropdown-menu-end shadow">
                                <a class="dropdown-item fw-bold text-primary" href="{{ route('admin.index') }}">
                                    <i class="bi bi-speedometer2"></i> {{ __('Panel Admin') }}
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="bi bi-box-arrow-right"></i> {{ __('messages.logout') }}
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest

                    {{-- SELECTOR DE IDIOMA --}}
                    <li class="nav-item dropdown ms-lg-3 mt-2 mt-lg-0">
                        <a class="btn btn-outline-light btn-sm dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-translate"></i> {{ strtoupper(app()->getLocale()) }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow">
                            <li><a class="dropdown-item" href="{{ route('lang.switch', 'es') }}">🇪🇸 Español</a></li>
                            <li><a class="dropdown-item" href="{{ route('lang.switch', 'en') }}">🇺🇸 English</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    {{-- CABECERA DINÁMICA --}}
    <header class="masthead bg-primary text-white text-center py-4 shadow-sm">
        <div class="container d-flex align-items-center flex-column">
            <h2 class="m-0">@yield('subtitulo', __('messages.default_subtitle'))</h2>
        </div>
    </header>

    {{-- ALERTAS DE SISTEMA --}}
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success mt-3 shadow-sm border-0 border-start border-4 border-success">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger mt-3 shadow-sm border-0 border-start border-4 border-danger">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            </div>
        @endif
    </div>

    {{-- CONTENIDO PRINCIPAL --}}
    <main class="container my-5">
        @yield('contenido')
    </main>

    {{-- PIE DE PÁGINA --}}
    <footer class="copyright py-4 text-center text-white bg-dark mt-auto border-top border-secondary">
        <div class="container">
            <small>Copyright © 2026 - <span class="fw-bold"> {{ \App\Models\User::where('role', 'admin')->first()->name ?? 'Admin' }} </span></small>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>