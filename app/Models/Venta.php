<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use HasFactory;

    protected $table = 'ventas';
    protected $fillable = [
        'pedido_id', 
        'empleado_id', 
        'monto_total', 
        'metodo_pago',
        'fecha_hora'
    ];

    protected $casts = [
        'fecha_hora' => 'datetime'
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }

    public function factura()
    {
        return $this->hasOne(Factura::class);
    }
}