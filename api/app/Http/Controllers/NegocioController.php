<?php

namespace App\Http\Controllers;

use App\Models\Negocio;
use Illuminate\Http\Request;

class NegocioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $validated = $request->validate([
        'usuario_id' => 'required|exists:users,id',
        'nombre' => 'required|string|max:255',
        'descripcion' => 'nullable|string',
        'categoria' => 'nullable|string',
        ]);
        $negocio = Negocio::create($validated);

        return response()->json([
            'status' => 'success',
            $negocio,
        ], 201);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store()
    {
        
    }

    /**
     * Display the specified resource.
     */
    public function show(Negocio $negocio)
    {
        //
    }


    /**
     * Funcion para traer los negocios por usuario
     */
    public function getNegociosByUser($userId){
        $negocios = Negocio::with('categoria:id,id,nombre')
        ->where('usuario_id', $userId)
        ->get();

        return response()->json($negocios, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Negocio $negocio)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Negocio $negocio)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Negocio $negocio)
    {
        //
    }
}
