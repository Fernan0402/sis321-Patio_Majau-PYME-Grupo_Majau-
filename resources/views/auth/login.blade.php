@extends('layouts.app')

@section('content')
<div class="container login-screen py-2 py-md-4">
    <div class="login-card">
        <div class="login-brand-panel">
            <div class="login-brand-icon">🍽️</div>
            <h2>Patio del Majau</h2>
            <p>Comida típica cruceña · Sabor tradicional</p>
            <div class="login-brand-dots">
                <span></span><span></span><span></span><span></span>
            </div>
        </div>

        <div class="login-form-panel">
            <h3>Bienvenido de vuelta</h3>
            <p>Ingresa tus credenciales para acceder al sistema.</p>

            @if($errors->any())
                <div class="alert alert-danger py-2 mb-3">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-3">
                    <label for="usuario" class="form-label login-label">Usuario</label>
                    <div class="login-input-wrap">
                        <span>👤</span>
                        <input
                            id="usuario"
                            type="text"
                            class="form-control @error('usuario') is-invalid @enderror"
                            name="usuario"
                            value="{{ old('usuario') }}"
                            required
                            autocomplete="username"
                            autofocus
                            placeholder="Ej: admin"
                        >
                    </div>
                    @error('usuario')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label login-label">Contraseña</label>
                    <div class="login-input-wrap">
                        <span>🔒</span>
                        <input
                            id="password"
                            type="password"
                            class="form-control @error('password') is-invalid @enderror"
                            name="password"
                            required
                            autocomplete="current-password"
                            placeholder="******"
                        >
                    </div>
                    @error('password')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="form-check mb-0">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label small" for="remember">Recordarme</label>
                    </div>
                    <span class="small text-muted">¿Olvidaste tu contraseña?</span>
                </div>

                <button type="submit" class="btn login-submit-btn w-100 rounded-pill">
                    Iniciar Sesión
                </button>
            </form>


        </div>
    </div>
</div>
@endsection
