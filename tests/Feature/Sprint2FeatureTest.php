<?php

namespace Tests\Feature;

use App\Models\Empleado;
use App\Models\Insumo;
use App\Models\Mesa;
use App\Models\Producto;
use App\Models\Proveedor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class Sprint2FeatureTest extends TestCase
{
    use RefreshDatabase;

    private function crearAdmin(): Empleado
    {
        return Empleado::create([
            'nombre' => 'Admin',
            'apellido' => 'Sprint2',
            'usuario' => 'admin_s2',
            'contrasena' => Hash::make('secret123'),
            'rol' => 'Administrador',
            'estado' => 'Activo',
            'activo' => true,
        ]);
    }

    public function test_hu04_actualiza_stock_y_registra_movimiento(): void
    {
        $admin = $this->crearAdmin();
        $this->actingAs($admin);

        $insumo = Insumo::create([
            'nombre' => 'Arroz',
            'stock_actual' => 10,
            'stock_minimo' => 5,
            'unidad_medida' => 'kg',
            'activo' => true,
        ]);

        $this->put(route('inventario.update', $insumo), [
            'stock_actual' => 15,
            'stock_minimo' => 4,
            'unidad_medida' => 'kg',
        ])->assertRedirect(route('inventario.index'));

        $this->assertDatabaseHas('insumos', [
            'id' => $insumo->id,
            'stock_actual' => 15,
        ]);

        $this->assertDatabaseHas('movimiento_inventarios', [
            'insumo_id' => $insumo->id,
            'tipo' => 'Entrada',
            'motivo' => 'Ajuste manual de inventario',
            'stock_anterior' => 10,
            'stock_nuevo' => 15,
        ]);
    }

    public function test_hu05_registra_compra_y_actualiza_inventario(): void
    {
        $admin = $this->crearAdmin();
        $this->actingAs($admin);

        $proveedor = Proveedor::create([
            'nombre' => 'Proveedor Test',
            'telefono' => '70000010',
            'email' => 'proveedor@test.bo',
            'direccion' => 'Santa Cruz',
            'activo' => true,
        ]);

        $insumo = Insumo::create([
            'nombre' => 'Aceite',
            'stock_actual' => 3,
            'stock_minimo' => 2,
            'unidad_medida' => 'lt',
            'activo' => true,
        ]);

        $this->post(route('compras-insumos.store'), [
            'proveedor_id' => $proveedor->id,
            'observacion' => 'Compra semanal',
            'items' => [
                [
                    'insumo_id' => $insumo->id,
                    'cantidad' => 4,
                    'precio_unitario' => 12.5,
                ],
            ],
        ])->assertRedirect();

        $this->assertDatabaseHas('compra_insumos', [
            'proveedor_id' => $proveedor->id,
            'empleado_id' => $admin->id,
            'monto_total' => 50,
        ]);

        $this->assertDatabaseHas('detalle_compra_insumos', [
            'insumo_id' => $insumo->id,
            'cantidad' => 4,
            'precio_unitario' => 12.5,
            'subtotal' => 50,
        ]);

        $this->assertDatabaseHas('insumos', [
            'id' => $insumo->id,
            'stock_actual' => 7,
        ]);

        $this->assertDatabaseHas('movimiento_inventarios', [
            'insumo_id' => $insumo->id,
            'tipo' => 'Entrada',
            'motivo' => 'Compra de insumos',
            'stock_anterior' => 3,
            'stock_nuevo' => 7,
        ]);
    }

    public function test_hu11_hu12_registro_y_cambio_estado_mesa(): void
    {
        $admin = $this->crearAdmin();
        $this->actingAs($admin);

        $this->post(route('mesas.store'), [
            'numero_mesa' => 10,
            'capacidad' => 6,
            'estado' => 'Disponible',
        ])->assertRedirect(route('mesas.index'));

        $mesa = Mesa::where('numero_mesa', 10)->firstOrFail();

        $this->post(route('mesas.cambiarEstado', $mesa), [
            'estado' => 'Reservada',
        ])->assertRedirect(route('mesas.index'));

        $this->assertDatabaseHas('mesas', [
            'id' => $mesa->id,
            'estado' => 'Reservada',
            'activo' => true,
        ]);
    }

    public function test_borrado_logico_en_usuarios_y_productos(): void
    {
        $admin = $this->crearAdmin();
        $this->actingAs($admin);

        $empleado = Empleado::create([
            'nombre' => 'Temp',
            'apellido' => 'User',
            'usuario' => 'temp_user',
            'contrasena' => Hash::make('secret123'),
            'rol' => 'Mesero',
            'estado' => 'Activo',
            'activo' => true,
        ]);

        $producto = Producto::create([
            'nombre' => 'Producto Temp',
            'descripcion' => '-',
            'precio' => 10,
            'categoria' => 'Prueba',
            'estado' => 'Activo',
            'activo' => true,
        ]);

        $this->delete(route('empleados.destroy', $empleado))
            ->assertRedirect(route('empleados.index'));

        $this->delete(route('productos.destroy', $producto))
            ->assertRedirect(route('productos.index'));

        $this->assertDatabaseHas('empleados', [
            'id' => $empleado->id,
            'estado' => 'Inactivo',
            'activo' => false,
        ]);

        $this->assertDatabaseHas('productos', [
            'id' => $producto->id,
            'estado' => 'Inactivo',
            'activo' => false,
        ]);
    }
}
