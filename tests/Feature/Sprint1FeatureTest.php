<?php

namespace Tests\Feature;

use App\Models\DetallePedido;
use App\Models\Empleado;
use App\Models\Factura;
use App\Models\Insumo;
use App\Models\Mesa;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\Venta;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class Sprint1FeatureTest extends TestCase
{
    use RefreshDatabase;

    private function crearEmpleado(string $rol, string $usuario): Empleado
    {
        return Empleado::create([
            'nombre' => ucfirst($rol),
            'apellido' => 'Test',
            'usuario' => $usuario,
            'contrasena' => Hash::make('secret123'),
            'rol' => $rol,
            'estado' => 'Activo',
        ]);
    }

    public function test_hu01_login_exitoso_y_fallido(): void
    {
        $admin = $this->crearEmpleado('Administrador', 'admin_test');

        $this->post('/login', [
            'usuario' => 'admin_test',
            'password' => 'mal_clave',
        ])->assertSessionHasErrors('usuario');

        $this->post('/login', [
            'usuario' => 'admin_test',
            'password' => 'secret123',
        ])->assertRedirect(route('dashboard.admin'));

        $this->assertAuthenticatedAs($admin);
    }

    public function test_hu08_registro_usuario_unico_con_hash(): void
    {
        $admin = $this->crearEmpleado('Administrador', 'admin_hu08');
        $this->actingAs($admin);

        Empleado::create([
            'nombre' => 'Dup',
            'apellido' => 'User',
            'usuario' => 'duplicado',
            'contrasena' => Hash::make('secret123'),
            'rol' => 'Mesero',
            'estado' => 'Activo',
        ]);

        $this->post(route('empleados.store'), [
            'nombre' => 'Nuevo',
            'apellido' => 'Usuario',
            'usuario' => 'duplicado',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            'rol' => 'Cajero',
            'estado' => 'Activo',
        ])->assertSessionHasErrors('usuario');

        $this->post(route('empleados.store'), [
            'nombre' => 'Nuevo',
            'apellido' => 'Usuario',
            'usuario' => 'nuevo_usuario',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            'rol' => 'Cajero',
            'estado' => 'Activo',
        ])->assertRedirect(route('empleados.index'));

        $empleado = Empleado::where('usuario', 'nuevo_usuario')->firstOrFail();
        $this->assertTrue(Hash::check('secret123', $empleado->contrasena));
    }

    public function test_hu14_asocia_insumos_en_registro_producto(): void
    {
        $admin = $this->crearEmpleado('Administrador', 'admin_hu14');
        $this->actingAs($admin);

        $insumoA = Insumo::create(['nombre' => 'Arroz', 'stock_actual' => 20, 'stock_minimo' => 5, 'unidad_medida' => 'kg']);
        $insumoB = Insumo::create(['nombre' => 'Charque', 'stock_actual' => 20, 'stock_minimo' => 5, 'unidad_medida' => 'kg']);

        $this->post(route('productos.store'), [
            'nombre' => 'Majau Test',
            'descripcion' => 'Producto de prueba',
            'precio' => 30,
            'categoria' => 'Plato Principal',
            'estado' => 'Activo',
            'insumos' => [$insumoA->id, $insumoB->id],
            'cantidades' => [
                $insumoA->id => 1.5,
                $insumoB->id => 0.8,
            ],
        ])->assertRedirect(route('productos.index'));

        $producto = Producto::where('nombre', 'Majau Test')->firstOrFail();
        $this->assertDatabaseHas('producto_insumo', [
            'producto_id' => $producto->id,
            'insumo_id' => $insumoA->id,
        ]);
    }

    public function test_hu19_inventario_muestra_alerta_y_actualiza_stock(): void
    {
        $admin = $this->crearEmpleado('Administrador', 'admin_hu19');
        $this->actingAs($admin);

        $insumo = Insumo::create([
            'nombre' => 'Mani',
            'stock_actual' => 2,
            'stock_minimo' => 5,
            'unidad_medida' => 'kg',
        ]);

        $this->get(route('inventario.index'))
            ->assertOk()
            ->assertSee('Stock bajo')
            ->assertSee('Mani');

        $this->put(route('inventario.update', $insumo), [
            'stock_actual' => 10,
            'stock_minimo' => 4,
            'unidad_medida' => 'kg',
        ])->assertRedirect(route('inventario.index'));

        $this->assertDatabaseHas('insumos', [
            'id' => $insumo->id,
            'stock_actual' => 10,
            'stock_minimo' => 4,
        ]);
    }

    public function test_hu02_registra_pedido_y_valida_stock(): void
    {
        $mesero = $this->crearEmpleado('Mesero', 'mesero_hu02');
        $this->actingAs($mesero);

        $mesa = Mesa::create(['numero_mesa' => 1, 'capacidad' => 4, 'estado' => 'Disponible']);
        $insumo = Insumo::create(['nombre' => 'Arroz', 'stock_actual' => 5, 'stock_minimo' => 1, 'unidad_medida' => 'kg']);
        $producto = Producto::create(['nombre' => 'Plato Test', 'descripcion' => '-', 'precio' => 20, 'categoria' => 'Plato', 'estado' => 'Activo']);
        $producto->insumos()->attach($insumo->id, ['cantidad_necesaria' => 1]);

        $this->post(route('pedidos.store'), [
            'mesa_id' => $mesa->id,
            'empleado_id' => $mesero->id,
            'tipo_pedido' => 'Mesa',
            'items' => [
                ['producto_id' => $producto->id, 'cantidad' => 2],
            ],
        ])->assertRedirect(route('pedidos.index'));

        $pedido = Pedido::firstOrFail();
        $this->assertEquals('Ocupada', $mesa->fresh()->estado);
        $this->assertDatabaseHas('detalle_pedidos', [
            'pedido_id' => $pedido->id,
            'producto_id' => $producto->id,
            'cantidad' => 2,
        ]);
    }

    public function test_hu03_hu17_venta_calcula_monto_y_genera_factura_con_detalle(): void
    {
        $cajero = $this->crearEmpleado('Cajero', 'cajero_hu03');
        $mesero = $this->crearEmpleado('Mesero', 'mesero_hu03');
        $this->actingAs($cajero);

        $mesa = Mesa::create(['numero_mesa' => 2, 'capacidad' => 4, 'estado' => 'Ocupada']);
        $producto = Producto::create(['nombre' => 'Sopa Test', 'descripcion' => '-', 'precio' => 25, 'categoria' => 'Sopa', 'estado' => 'Activo']);

        $pedido = Pedido::create([
            'mesa_id' => $mesa->id,
            'empleado_id' => $mesero->id,
            'tipo_pedido' => 'Mesa',
            'estado' => 'Entregado',
            'total' => 50,
        ]);

        DetallePedido::create([
            'pedido_id' => $pedido->id,
            'producto_id' => $producto->id,
            'cantidad' => 2,
            'precio_unitario' => 25,
            'subtotal' => 50,
        ]);

        $this->post(route('ventas.store'), [
            'pedido_id' => $pedido->id,
            'empleado_id' => $cajero->id,
            'metodo_pago' => 'QR',
            'generar_factura' => 1,
            'razon_social_cliente' => 'Cliente Test',
            'nit_cliente' => '123456',
        ])->assertRedirect();

        $venta = Venta::firstOrFail();
        $factura = Factura::firstOrFail();

        $this->assertEquals(50.0, (float) $venta->monto_total);
        $this->assertEquals($venta->id, $factura->venta_id);

        $this->get(route('ventas.factura', $venta))
            ->assertOk()
            ->assertSee('Sopa Test')
            ->assertSee($factura->numero_factura);
    }

    public function test_rutas_restringidas_por_rol(): void
    {
        $mesero = $this->crearEmpleado('Mesero', 'mesero_acl');
        $this->actingAs($mesero);

        $this->get(route('empleados.index'))->assertForbidden();
    }
}
