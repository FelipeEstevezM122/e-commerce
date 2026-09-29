<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class IsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->user() || !$request->user()->isAdmin()) {
            return response()->json([
                'message' => 'Solo administradores pueden realizar esta acción'
            ], Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}