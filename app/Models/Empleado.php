<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * HU-01 / HU-08: Modelo de empleado del restaurante.
 *
 * Representa a cada usuario del sistema (Administrador, Mesero, Cajero, Cocinero).
 * Laravel usa esta clase como proveedor de autenticación (config/auth.php).
 */
class Empleado extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'empleados';

    protected $fillable = [
        'nombre',
        'apellido',
        'usuario',
        'contrasena',
        'rol',
        'estado',
        'activo',
    ];

    protected $hidden = [
        'contrasena',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    /**
     * La columna de contraseña en BD es 'contrasena', no 'password'.
     */
    public function getAuthPassword()
    {
        return $this->contrasena;
    }

    /**
     * Nombre completo para mostrar en la barra de navegación.
     */
    public function getNombreCompletoAttribute(): string
    {
        return trim("{$this->nombre} {$this->apellido}");
    }

    public function pedidos()
    {
        return $this->hasMany(Pedido::class, 'empleado_id');
    }

    public function ventas()
    {
        return $this->hasMany(Venta::class, 'empleado_id');
    }
}
