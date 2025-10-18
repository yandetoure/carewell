<?php declare(strict_types=1);

namespace App\Traits;

use App\Models\User;
use Illuminate\Http\RedirectResponse;

trait RedirectToRoleDashboard
{
    /**
     * Rediriger l'utilisateur vers le dashboard approprié selon son rôle
     */
    protected function redirectToRoleDashboard(User $user): RedirectResponse
    {
        $roles = $user->getRoleNames();

        // Vérifier les rôles par ordre de priorité
        if ($roles->contains('Admin')) {
            return redirect()->route('admin.dashboard');
        } elseif ($roles->contains('Doctor')) {
            return redirect()->route('doctor.dashboard');
        } elseif ($roles->contains('Secretary')) {
            return redirect()->route('secretary.dashboard');
        } elseif ($roles->contains('Accountant')) {
            return redirect()->route('accountant.dashboard');
        } elseif ($roles->contains('Nurse')) {
            return redirect()->route('nurse.dashboard');
        } else {
            // Par défaut, dashboard patient
            return redirect()->route('patient.dashboard');
        }
    }
}
