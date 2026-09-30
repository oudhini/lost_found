<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * A manager only works on the depot they run. Until a supervisor assigns one,
 * every depot-scoped page redirects to the dashboard with an explanation.
 */
class EnsureManagerHasDepot
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()?->depot === null) {
            return redirect()->route('dashboard')
                ->with('error', "Aucun dépôt ne vous est assigné pour l'instant. Contactez un superviseur.");
        }

        return $next($request);
    }
}
