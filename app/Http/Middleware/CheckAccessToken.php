<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAccessToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->bearerToken()) {

            $response = [
                'success' => false,
                'message' => 'Invalid Request!',
            ];
            return response()->json($response, 401);
        }

        return $next($request);
    }
    
}
