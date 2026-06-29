<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompraInsumo extends Model
{
    use HasFactory;

    protected $table = 'compra_insumos';

    protected $fillable = [
        'proveedor_id',
        'empleado_id',
        'monto_total',
        'observacion',
        'activo',
        'fecha_hora',
    ];

    protected $casts = [
        'fecha_hora' => 'datetime',
        'activo' => 'boolean',
    ];

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'proveedor_id');
    }

    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'empleado_id');
    }

    public function detalles()
    {
        return $this->hasMany(DetalleCompraInsumo::class, 'compra_insumo_id');
    }
}
