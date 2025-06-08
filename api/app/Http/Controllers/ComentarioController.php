<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comentario;
use App\Models\Negocio;
use App\Models\User;

class ComentarioController extends Controller
{
    public function index($negocioId)
    {
        $comentarios = Comentario::where('negocio_id', $negocioId)
            ->with('usuario:id,name')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($comentarios);
    }

    public function store(Request $request, $negocioId)
    {

        $request->validate([
            'comentario' => 'required|string|max:1000',
            'calificacion' => 'nullable|integer|min:1|max:5',
        ]);

        $negocio = Negocio::findOrFail($negocioId);

        $usuario = User::where('firebase_uid', $uid)->firstOrFail();

        $comentario = Comentario::create([
            'negocio_id' => $negocio->id,
            'usuario_id' => $usuario->id,
            'comentario' => $request->comentario,
            'calificacion' => $request->calificacion,
        ]);

        return response()->json($comentario, 201);

    }
}
