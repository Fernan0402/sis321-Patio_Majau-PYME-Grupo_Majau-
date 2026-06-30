<?php

namespace Tests\Feature;

use App\Models\Empleado;
use App\Models\Insumo;
use App\Models\Mesa;
use App\Models\Pedido;
use App\Models\Venta;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class Sprint3FeatureTest extends TestCase
{
    use RefreshDatabase;

    private function crearAdmin(): Empleado
    {
        return Empleado::create([
            'nombre' => 'Gerente',
            'apellido' => 'Demo',
            'usuario' => 'gerente_demo',
            'contrasena' => Hash::make('Demo123456'),
            'rol' => 'Administrador',
            'estado' => 'Activo',
            'activo' => true,
        ]);
    }

    public function test_hu06_endpoint_kpis_retorna_datos_filtrables(): void
    {
        $admin = $this->crearAdmin();
        $this->actingAs($admin);

        $insumo = Insumo::create([
            'nombre' => 'Arroz',
            'stock_actual' => 3,
            'stock_minimo' => 5,
            'unidad_medida' => 'kg',
            'activo' => true,
        ]);

        $mesa = Mesa::create([
            'numero_mesa' => 1,
            'capacidad' => 4,
            'estado' => 'Disponible',
            'activo' => true,
        ]);

        $pedido = Pedido::create([
            'mesa_id' => $mesa->id,
            'empleado_id' => $admin->id,
            'tipo_pedido' => 'Mesa',
            'estado' => 'Entregado',
            'total' => 120,
        ]);

        Venta::create([
            'pedido_id' => $pedido->id,
            'empleado_id' => $admin->id,
            'monto_total' => 120,
            'metodo_pago' => 'QR',
        ]);

        $response = $this->get(route('dashboard.kpis', [
            'fecha_inicio' => now()->toDateString(),
            'fecha_fin' => now()->toDateString(),
        ]));

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'data' => [
                    'cards' => [
                        'ventas_periodo',
                        'total_vendido',
                        'pedidos_periodo',
                        'pedidos_pendientes',
                        'stock_bajo',
                        'tickets_emitidos',
                    ],
                    'series' => [
                        'ventas_diarias',
                        'metodo_pago',
                        'top_productos',
                    ],
                ],
            ]);
    }

    public function test_hu18_historial_ventas_filtra_por_fecha(): void
    {
        $admin = $this->crearAdmin();
        $this->actingAs($admin);

        $mesa = Mesa::create([
            'numero_mesa' => 2,
            'capacidad' => 4,
            'estado' => 'Disponible',
            'activo' => true,
        ]);

        $pedidoAyer = Pedido::create([
            'mesa_id' => $mesa->id,
            'empleado_id' => $admin->id,
            'tipo_pedido' => 'Mesa',
            'estado' => 'Entregado',
            'total' => 100,
            'created_at' => now()->subDay(),
            'updated_at' => now()->subDay(),
        ]);

        $pedidoHoy = Pedido::create([
            'mesa_id' => $mesa->id,
            'empleado_id' => $admin->id,
            'tipo_pedido' => 'Mesa',
            'estado' => 'Entregado',
            'total' => 200,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Venta::create([
            'pedido_id' => $pedidoAyer->id,
            'empleado_id' => $admin->id,
            'monto_total' => 100,
            'metodo_pago' => 'Efectivo',
        ]);

        Venta::create([
            'pedido_id' => $pedidoHoy->id,
            'empleado_id' => $admin->id,
            'monto_total' => 200,
            'metodo_pago' => 'QR',
        ]);

        Venta::where('pedido_id', $pedidoAyer->id)->update([
            'created_at' => now()->subDay(),
            'updated_at' => now()->subDay(),
        ]);

        $response = $this->get(route('ventas.index', [
            'fecha_inicio' => now()->toDateString(),
            'fecha_fin' => now()->toDateString(),
        ]));

        $response->assertOk()
            ->assertSee('200.00')
            ->assertDontSee('100.00');
    }
}
