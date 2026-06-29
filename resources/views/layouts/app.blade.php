<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Patio del Majau') }}</title>

    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-dark bg-primary shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ auth()->check() ? route('dashboard') : route('login') }}">
                    Patio del Majau
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto">
                        @auth
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('menu.index') }}">Menú</a>
                            </li>

                            @if(Auth::user()->rol === 'Administrador')
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('empleados.index') }}">Usuarios</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('productos.index') }}">Productos</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('inventario.index') }}">
                                        Inventario
                                        @if(($totalAlertasStock ?? 0) > 0)
                                            <span class="badge bg-danger">{{ $totalAlertasStock }}</span>
                                        @endif
                                    </a>
                                </li>
                            @endif

                            @if(in_array(Auth::user()->rol, ['Administrador', 'Mesero'], true))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('pedidos.index') }}">Pedidos</a>
                                </li>
                            @endif

                            @if(in_array(Auth::user()->rol, ['Administrador', 'Cajero'], true))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('ventas.index') }}">Ventas</a>
                                </li>
                            @endif
                        @endauth
                    </ul>

                    <ul class="navbar-nav ms-auto">
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">Ingresar</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                    {{ Auth::user()->nombre_completo }}
                                    <small class="text-light opacity-75">({{ Auth::user()->rol }})</small>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        Cerrar sesión
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @if(session('success'))
                <div class="container">
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                </div>
            @endif
            @yield('content')
        </main>
    </div>
</body>
</html>
