<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckModuleActive
{
    public function handle(Request $request, Closure $next, $moduleId): Response
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        if (!$user->hasModuleActive($moduleId)) {
            return response()->json([
                'error' => 'Module inactive. Please activate this module to use it.'
            ], 403);
        }

        return $next($request);
    }
}