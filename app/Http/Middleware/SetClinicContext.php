<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SetClinicContext
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            if ($user->hasRole('Super Admin')) {
                // Pour le Super Admin, utiliser la clinique sélectionnée en session
                $selectedClinicId = session('selected_clinic_id');
                if ($selectedClinicId) {
                    session(['current_clinic_id' => $selectedClinicId]);
                } else {
                    // Si aucune clinique n'est sélectionnée, ne pas définir de contexte
                    session()->forget('current_clinic_id');
                }
            } else {
                // Pour les autres utilisateurs, utiliser leur clinique assignée
                if ($user->clinic_id) {
                    session(['current_clinic_id' => $user->clinic_id]);
                }
            }
        }

        return $next($request);
    }
}
