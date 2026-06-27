<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    use HasFactory;

    protected $table = 'facturas';
    protected $fillable = [
        'venta_id',
        'numero_factura',
        'razon_social_cliente',
        'nit_cliente',
        'monto_total',
        'fecha_emision'
    ];

    protected $casts = [
        'fecha_emision' => 'datetime'
    ];

    public function venta()
    {
        return $this->belongsTo(Venta::class);
    }
}