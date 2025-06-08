<?php

namespace App\Http\Controllers;

use App\Models\Negocio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NegocioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $negocios = Negocio::with('categoria')->get();

        return response()->json($negocios, 200);
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
            'usuario_id' => 'required|exists:users,id',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'categoria' => 'nullable|exists:categorias,id',
        ]);

        $negocio = Negocio::create([
            'usuario_id' => $validated['usuario_id'],
            'nombre' => $validated['nombre'],
            'descripcion' => $validated['descripcion'] ?? null,
            'categoria_id' => $validated['categoria'] ?? null,
        ]);

        return response()->json([
            'status' => 'success',
            'negocio' => $negocio
        ], 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(Negocio $negocio)
    {
        $negocio->load('categoria', 'comentarios.usuario');
        return response()->json($negocio, 200);
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
        // Validar los datos recibidos
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'categoria' => 'nullable|exists:categorias,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        // Actualizar el negocio
        $negocio->nombre = $request->input('nombre');
        $negocio->descripcion = $request->input('descripcion');
        if ($request->filled('categoria')) {
            $negocio->categoria_id = $request->input('categoria');
        }

        $negocio->save();

        return response()->json([
            'message' => 'Negocio actualizado con éxito',
            'negocio' => $negocio
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Negocio $negocio)
    {
        //
    }
}
