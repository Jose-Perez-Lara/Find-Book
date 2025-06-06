<?php

namespace App\Http\Controllers;

use App\Models\Services;
use Illuminate\Http\Request;

class ServicesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $negocioId = $request->query('negocio');

        if ($negocioId) {
            $services = Services::where('negocio_id', $negocioId)->get();
        } else {
            $services = Services::all();
        }
        return response()->json($services, 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
        'negocio_id' => 'required|exists:negocios,id',
        'nombre' => 'required|string|max:255',
        'descripcion' => 'required|string',
        'precio' => 'required|integer',
        'duracion' => 'required|integer',
        
        ]);
        $negocio = Services::create($validated);

        return response()->json([
            'status' => 'success',
            $negocio,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Services $services)
    {
        return response()->json($services, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Services $services)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Services $service)
    {
        $validated = $request->validate([
        'negocio_id' => 'required|exists:negocios,id',
        'nombre' => 'required|string|max:255',
        'descripcion' => 'required|string',
        'precio' => 'required|integer',
        'duracion' => 'required|integer',
        ]);
         
        $service->update($validated);

        return response()->json([
            'message' => 'Service updated successfully',
            'service' => $service
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Services $service)
    {

        $service->delete();

        return response()->json([
            'message' => 'Service deleted successfully',
        ], 200);
    }
}
