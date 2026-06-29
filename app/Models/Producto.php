<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $table = 'productos';
    protected $fillable = ['nombre', 'descripcion', 'precio', 'categoria', 'estado', 'activo'];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function detallePedidos()
    {
        return $this->hasMany(DetallePedido::class);
    }

    public function insumos()
    {
        return $this->belongsToMany(Insumo::class, 'producto_insumo')
                    ->withPivot('cantidad_necesaria')
                    ->withTimestamps();
    }
}