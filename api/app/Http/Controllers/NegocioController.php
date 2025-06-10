<?php

namespace App\Http\Controllers;

use App\Models\Negocio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Favoritos;

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
        $negocio->load('categoria', 'comentarios.usuario', 'usuario');
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
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'categoria' => 'nullable|exists:categorias,id',
            'direccion' => 'nullable|string|max:500',
            'latitud' => 'nullable|numeric',
            'longitud' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $negocio->nombre = $request->input('nombre');
        $negocio->descripcion = $request->input('descripcion');

        if ($request->filled('categoria')) {
            $negocio->categoria_id = $request->input('categoria');
        }

        if ($request->filled('direccion')) {
            $negocio->direccion = $request->input('direccion');
        }

        if ($request->filled('latitud')) {
            $negocio->latitud = $request->input('latitud');
        }

        if ($request->filled('longitud')) {
            $negocio->longitud = $request->input('longitud');
        }

        $negocio->save();

        return response()->json([
            'message' => 'Negocio actualizado con éxito',
            'negocio' => $negocio
        ]);
    }


    public function updateImage(Request $request, Negocio $negocio)
    {
        $request->validate([
            'imagen' => 'required|image|mimes:jpeg,png,jpg,gif|max:8192',
        ]);

        if ($request->hasFile('imagen')) {
            $imagePath = $request->file('imagen')->store('negocios', 'public');
            $negocio->imagen_portada = 'storage/' . $imagePath;
            $negocio->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Imagen actualizada con éxito',
                'negocio' => $negocio
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'No se recibió archivo de imagen'
        ], 400);
    }

    public function getFavoritos($userId)
    {
        $negociosId = Favoritos::where('usuario_id', $userId)->get()->pluck('negocio_id');
        
        $favoritos = Negocio::whereIn('id', $negociosId)->get();
        
        return response()->json($favoritos, 200);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Negocio $negocio)
    {
        //
    }
}
