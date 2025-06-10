<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Favoritos;

class FavoritosController extends Controller
{
    public function index($userId)
    {

        $favoritos = Favoritos::where('usuario_id', $userId)->get();

        return response()->json($favoritos, 200);
    }

    public function toggle(Request $request)
    {
        $request->validate([
            'negocio_id' => 'required|exists:negocios,id',
            'user_id' => 'required|exists:users,id',
        ]);

        $userId = $request->user_id;
        $negocioId = $request->negocio_id;

        $favorito = Favoritos::where('usuario_id', $userId)
            ->where('negocio_id', $negocioId)
            ->first();

        if ($favorito) {
            $favorito->delete();

            return response()->json([
                'message' => 'Negocio eliminado de favoritos'
            ]);
        } else {
            Favoritos::create([
                'usuario_id' => $userId,
                'negocio_id' => $negocioId,
            ]);

            return response()->json([
                'message' => 'Negocio añadido a favoritos'
            ]);
        }
    }
}
