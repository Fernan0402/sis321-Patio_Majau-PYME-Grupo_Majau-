<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

/**
 * HU-01: Inicio de sesión
 *
 * Criterios de aceptación:
 * - Solicitar usuario y contraseña
 * - Validar credenciales contra la tabla empleados
 * - Mostrar error si son incorrectas o el empleado está inactivo
 * - Redirigir al dashboard tras un login exitoso
 */
class LoginController extends Controller implements HasMiddleware
{
    use AuthenticatesUsers;

    /** Redirección tras autenticación exitosa */
    protected $redirectTo = '/dashboard';

    /**
     * En Laravel 12 el middleware se declara aquí (no en __construct).
     */
    public static function middleware(): array
    {
        return [
            new Middleware('guest', except: ['logout']),
            new Middleware('auth', only: ['logout']),
        ];
    }

    /**
     * El campo de login es 'usuario' (no email).
     */
    public function username(): string
    {
        return 'usuario';
    }

    /**
     * Validación del formulario de acceso.
     */
    protected function validateLogin(Request $request): void
    {
        $request->validate([
            $this->username() => 'required|string|max:50',
            'password' => 'required|string',
        ], [
            'usuario.required' => 'El usuario es obligatorio.',
            'password.required' => 'La contraseña es obligatoria.',
        ]);
    }

    /**
     * Credenciales enviadas al guard de autenticación.
     * 'password' se compara con la columna contrasena vía getAuthPassword().
     */
    protected function credentials(Request $request): array
    {
        return [
            'usuario' => $request->input('usuario'),
            'password' => $request->input('password'),
            'estado' => 'Activo',
        ];
    }

    /**
     * Mensaje cuando las credenciales no coinciden.
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        return redirect()
            ->back()
            ->withInput($request->only('usuario', 'remember'))
            ->withErrors([
                'usuario' => 'Usuario o contraseña incorrectos, o cuenta inactiva.',
            ]);
    }
}
