<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    protected $table = 'pedidos';
    protected $fillable = [
        'mesa_id', 
        'empleado_id', 
        'tipo_pedido', 
        'estado', 
        'total',
        'fecha_hora'
    ];

    protected $casts = [
        'fecha_hora' => 'datetime'
    ];

    public function mesa()
    {
        return $this->belongsTo(Mesa::class);
    }

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }

    public function detalles()
    {
        return $this->hasMany(DetallePedido::class);
    }

    public function venta()
    {
        return $this->hasOne(Venta::class);
    }

    // Método para calcular el total del pedido
    public function calcularTotal()
    {
        $total = $this->detalles()->sum('subtotal');
        $this->total = $total;
        $this->save();
        return $total;
    }
}