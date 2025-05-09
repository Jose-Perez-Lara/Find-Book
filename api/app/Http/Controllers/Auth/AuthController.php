<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Kreait\Laravel\Firebase\Facades\Firebase;
use App\Models\User;
use Kreait\Firebase\Exception\Auth\FailedToVerifyToken;



class AuthController extends Controller
{
    private $auth;
    public function __construct()
    {
        $this->auth = Firebase::auth();
    }

    /**
     * Registrar Usuario
     */
    public function register(Request $request)
    {
        try{
            $request->validate([
                'firebase_uid' => 'required|string|unique:users,firebase_uid',
                'correo_electronico' => 'required|email|unique:users,email',
                'nombre' => 'required|string',
                'telefono' => 'nullable|string',
                // 'rol_id' => 'required|integer|exists:roles,id',
            ]);
            $usuario = User::create([
                'firebase_uid' => $request->firebase_uid,
                'email' => $request->correo_electronico,
                'name' => $request->nombre,
                'password' => $request->password,
                'telefono' => $request->telefono,
                'rol_id' => $request->rol_id,
                'verificado' => true,
            ]);

            return response()->json($usuario, 201);
        }catch(Exception $e){
            return response()->json([
                'status' => 'Error',
                'message' => $e->getMessage(),
            ], 401);
        }
        
    }


    /**
     * Login
     */
    public function login(Request $request)
    {

    }

    /**
     * Probar token de verificacion
     */
    public function getUserByUid(Request $request)
    {
        $idToken = $request->bearerToken();

        if (!$idToken) {
            return response()->json(['error' => 'Token not provided'], 401);
        }

        try {
            $verifiedIdToken = $this->auth->verifyIdToken($idToken);
            $uid = $verifiedIdToken->claims()->get('sub');

            $user = User::where('firebase_uid', $uid)->get();

            return response()->json([
                'status' => 'success',
                'uid' => $uid,
                'user' => $user,
            ]);
        } catch (FailedToVerifyToken $e) {
            return response()->json(['error' => 'Invalid token'], 401);
        }
    }


}