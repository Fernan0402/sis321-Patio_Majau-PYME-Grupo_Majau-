<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleCompraInsumo extends Model
{
    use HasFactory;

    protected $table = 'detalle_compra_insumos';

    protected $fillable = [
        'compra_insumo_id',
        'insumo_id',
        'cantidad',
        'precio_unitario',
        'subtotal',
    ];

    public function compra()
    {
        return $this->belongsTo(CompraInsumo::class, 'compra_insumo_id');
    }

    public function insumo()
    {
        return $this->belongsTo(Insumo::class, 'insumo_id');
    }
}
