<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(403);
        }

        $allowedRoles = [];
        foreach ($roles as $role) {
            $role = strtoupper(trim($role));
            $allowedRoles[] = $role === 'DEV' ? 'DESENVOLUPADOR' : $role;
        }

        $userRole = strtoupper(trim((string) $user->rol));

        if (! in_array($userRole, $allowedRoles, true)) {
            abort(403);
        }

        return $next($request);
    }
}

