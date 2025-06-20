<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Services;
use Illuminate\Http\Request;

class CitaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $negocioId = $request->query('negocio_id');
        $usuarioId = $request->query('usuario_id');

        if ($negocioId) {
            $citas = Cita::whereHas('servicio', function ($query) use ($negocioId) {
                $query->where('negocio_id', $negocioId);
            })->with(['cliente', 'servicio.negocio.usuario'])
            ->orderBy('fecha', 'desc')
            ->orderBy('hora', 'desc') 
            ->get();
        } elseif ($usuarioId) {
            $citas = Cita::where('cliente_id', $usuarioId)
                        ->with(['cliente', 'servicio.negocio.usuario']) 
                        ->get();
        } else {
            return response()->json(['error' => 'Falta negocio_id o usuario_id'], 400);
        }

        return response()->json($citas, 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'cliente_id' => 'required|exists:users,id',
            'servicio_id' => 'required|exists:services,id',
            'fecha' => 'required|date',
            'hora' => 'required|date_format:H:i',
        ]);

        $cita = Cita::create($validated);

        return response()->json([
            'status' => 'success',
            'cita' => $cita,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Cita $cita)
    {
        return response()->json($cita, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cita $cita)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cita $cita)
    {
        $validated = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'servicio_id' => 'required|exists:servicios,id',
            'fecha' => 'required|date',
            'hora' => 'required|date_format:H:i',
            'estado' => 'required|string|max:50',
        ]);

        $cita->update($validated);

        return response()->json([
            'message' => 'Cita actualizada correctamente',
            'cita' => $cita,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cita $cita)
    {
        $cita->delete();

        return response()->json([
            'message' => 'Cita eliminada correctamente',
        ], 200);
    }

    public function getByNegocioAndUsuario($negocioId, $userId)
    {
        $servicioIds = Services::where('negocio_id', $negocioId)->pluck('id');

        $citas = Cita::whereIn('servicio_id', $servicioIds)
                    ->where('cliente_id', $userId)
                    ->get();

        return response()->json($citas);
    }

}
