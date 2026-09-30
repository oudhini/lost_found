<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Usage: ->middleware('role:superviseur') or 'role:gerant,superviseur'.
 * Must run after the "auth" middleware.
 */
class EnsureUserHasRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if ($user === null) {
            return redirect()->guest(route('login'));
        }

        abort_unless(in_array($user->role, $roles, true), Response::HTTP_FORBIDDEN);

        return $next($request);
    }
}
