<?php declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use App\Traits\RedirectToRoleDashboard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectToDashboard
{
    use RedirectToRoleDashboard;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Si l'utilisateur est connecté et essaie d'accéder à la page d'accueil
        if (Auth::check() && $request->routeIs('home')) {
            $user = Auth::user();

            // Rediriger directement vers le dashboard approprié selon le rôle
            return $this->redirectToRoleDashboard($user);
        }

        return $next($request);
    }

}
