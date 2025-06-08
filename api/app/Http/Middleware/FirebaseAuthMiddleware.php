<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Kreait\Firebase\Auth as FirebaseAuth;
use App\Repositories\UserRepository;
use Kreait\Laravel\Firebase\Facades\Firebase;
use Exception;

class FirebaseAuthMiddleware
{
    protected $firebaseAuth;

    public function __construct()
    {
        $this->firebaseAuth = Firebase::auth();
    }

    /**
     * comprobar token de respuesta de firebase
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['error_message' => 'There is no token'], 401);
        }

        try {
            $verifiedToken = $this->firebaseAuth->verifyIdToken($token);

            $user = $verifiedToken->claims()->get('sub');

            $request->merge(['user' => $user]);

            $request->setUserResolver(function () use ($user) {
                return $user;
            });
            
        } catch (Exception $e) {
            return response()->json(['error_message' => $e->getMessage()], 401);
        }
        return $next($request);
    }
}
