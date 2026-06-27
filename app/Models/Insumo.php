<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Insumo extends Model
{
    use HasFactory;

    protected $table = 'insumos';
    protected $fillable = ['nombre', 'stock_actual', 'stock_minimo', 'unidad_medida'];

    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'producto_insumo')
                    ->withPivot('cantidad_necesaria')
                    ->withTimestamps();
    }

    // Método para verificar si hay stock bajo (HU-19)
    public function tieneStockBajo()
    {
        return $this->stock_actual <= $this->stock_minimo;
    }
}