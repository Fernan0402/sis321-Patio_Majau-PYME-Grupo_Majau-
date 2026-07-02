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
        @auth
            <div class="majau-app-shell" id="majauShell">
                <aside class="majau-sidebar" id="majauSidebar">
                    <div class="majau-brand">
                        <div class="majau-brand-logo">🍽️</div>
                        <div>
                            <strong>Patio del Majau</strong>
                            <small>Panel Operativo</small>
                        </div>
                    </div>

                    <div class="majau-nav-title">Navegación</div>
                    <nav class="majau-nav">
                        <a class="majau-nav-link {{ request()->routeIs('dashboard*') ? 'active' : '' }}" href="{{ route('dashboard') }}">Dashboard</a>
                        <a class="majau-nav-link {{ request()->routeIs('menu.*') ? 'active' : '' }}" href="{{ route('menu.index') }}">Menú</a>

                        @if(Auth::user()->rol === 'Administrador')
                            <a class="majau-nav-link {{ request()->routeIs('empleados.*') ? 'active' : '' }}" href="{{ route('empleados.index') }}">Usuarios</a>
                            <a class="majau-nav-link {{ request()->routeIs('productos.*') ? 'active' : '' }}" href="{{ route('productos.index') }}">Productos</a>
                            <a class="majau-nav-link {{ request()->routeIs('inventario.*') ? 'active' : '' }}" href="{{ route('inventario.index') }}">
                                Inventario
                                @if(($totalAlertasStock ?? 0) > 0)
                                    <span class="badge rounded-pill text-bg-danger">{{ $totalAlertasStock }}</span>
                                @endif
                            </a>
                            <a class="majau-nav-link {{ request()->routeIs('compras-insumos.*') ? 'active' : '' }}" href="{{ route('compras-insumos.index') }}">Compras insumos</a>
                        @endif

                        @if(in_array(Auth::user()->rol, ['Administrador', 'Mesero'], true))
                            <a class="majau-nav-link {{ request()->routeIs('pedidos.*') ? 'active' : '' }}" href="{{ route('pedidos.index') }}">Pedidos</a>
                            <a class="majau-nav-link {{ request()->routeIs('mesas.*') ? 'active' : '' }}" href="{{ route('mesas.index') }}">Mesas</a>
                        @endif

                        @if(in_array(Auth::user()->rol, ['Administrador', 'Cajero'], true))
                            <a class="majau-nav-link {{ request()->routeIs('ventas.*') ? 'active' : '' }}" href="{{ route('ventas.index') }}">Ventas</a>
                        @endif
                    </nav>

                    <div class="majau-user-card">
                        <div>
                            <strong>{{ Auth::user()->nombre_completo }}</strong>
                            <small>{{ Auth::user()->rol }}</small>
                        </div>
                        <a class="btn btn-sm btn-outline-light rounded-pill" href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Salir
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </aside>

                <section class="majau-main">
                    <header class="majau-topbar">
                        <button class="btn btn-sm btn-dark rounded-pill d-lg-none" id="majauSidebarToggle" type="button">
                            Menú
                        </button>
                        <div class="majau-topbar-meta">
                            <span>{{ now()->translatedFormat('l, d \\d\\e F \\d\\e Y') }}</span>
                            <span>{{ now()->format('h:i A') }}</span>
                        </div>
                    </header>

                    <main class="majau-content py-3">
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
                </section>
            </div>
        @else
            <main class="py-4">
                <div class="container mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <a class="navbar-brand fw-bold text-dark" href="{{ route('login') }}">Patio del Majau</a>
                    </div>
                </div>
                @yield('content')
            </main>
        @endauth
    </div>
    <script>
        (function () {
            const toggle = document.getElementById('majauSidebarToggle');
            const shell = document.getElementById('majauShell');
            if (!toggle || !shell) return;
            toggle.addEventListener('click', () => shell.classList.toggle('majau-sidebar-open'));
        })();
    </script>
</body>
</html>
