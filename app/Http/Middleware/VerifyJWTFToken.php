<?php

namespace App\Http\Middleware;

use App\Libraries\JWTFAuth;
use Closure;

class VerifyJWTFToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            $token = $request->bearerToken();
            $data = JWTFAuth::GetDataCredentials($token);
            \Log::info("Data: ".$data);
            \Log::info("Token: ".$token);
                //code...
        } catch (\Throwable $th) {
            return response()->json('Tiempo de acceso expirado', 401);
            //throw $th;
        }
        return $next($request);
    }
}
