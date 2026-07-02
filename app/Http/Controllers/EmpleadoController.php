<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EmpleadoController extends Controller
{
    /**
     * HU-08: Registrar usuarios (empleados del sistema).
     */
    public function index(Request $request)
    {
        $busqueda = trim((string) $request->input('q', ''));

        $empleados = Empleado::where('activo', true)
            ->when($busqueda !== '', function ($query) use ($busqueda) {
                $query->where(function ($subQuery) use ($busqueda) {
                    $subQuery->where('nombre', 'like', "%{$busqueda}%")
                        ->orWhere('apellido', 'like', "%{$busqueda}%")
                        ->orWhere('usuario', 'like', "%{$busqueda}%");
                });
            })
            ->orderBy('rol')
            ->orderBy('nombre')
            ->paginate(15)
            ->withQueryString();

        return view('empleados.index', compact('empleados', 'busqueda'));
    }

    public function create()
    {
        return view('empleados.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'usuario' => 'required|string|max:50|unique:empleados,usuario',
            'password' => 'required|string|min:6|confirmed',
            'rol' => 'required|in:Administrador,Mesero,Cajero,Cocinero',
            'estado' => 'required|in:Activo,Inactivo',
        ]);

        Empleado::create([
            'nombre' => $data['nombre'],
            'apellido' => $data['apellido'],
            'usuario' => $data['usuario'],
            'contrasena' => Hash::make($data['password']),
            'rol' => $data['rol'],
            'estado' => $data['estado'],
        ]);

        return redirect()->route('empleados.index')
            ->with('success', 'Usuario registrado correctamente.');
    }

    public function edit(Empleado $empleado)
    {
        return view('empleados.edit', compact('empleado'));
    }

    public function update(Request $request, Empleado $empleado)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'usuario' => 'required|string|max:50|unique:empleados,usuario,' . $empleado->id,
            'password' => 'nullable|string|min:6|confirmed',
            'rol' => 'required|in:Administrador,Mesero,Cajero,Cocinero',
            'estado' => 'required|in:Activo,Inactivo',
        ]);

        $payload = [
            'nombre' => $data['nombre'],
            'apellido' => $data['apellido'],
            'usuario' => $data['usuario'],
            'rol' => $data['rol'],
            'estado' => $data['estado'],
        ];

        if (! empty($data['password'])) {
            $payload['contrasena'] = Hash::make($data['password']);
        }

        $empleado->update($payload);

        return redirect()->route('empleados.index')
            ->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy(Empleado $empleado)
    {
        $empleado->update([
            'estado' => 'Inactivo',
            'activo' => false,
        ]);

        return redirect()->route('empleados.index')
            ->with('success', 'Usuario desactivado correctamente (borrado lógico).');
    }
}
