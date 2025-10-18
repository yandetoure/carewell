<?php declare(strict_types=1); 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        return view('profile', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone_number' => 'required|string|max:20',
            'day_of_birth' => 'required|date',
            'adress' => 'required|string|max:500',
            'height' => 'nullable|numeric|min:100|max:250',
            'weight' => 'nullable|numeric|min:20|max:300',
            'blood_type' => 'nullable|string|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'biographie' => 'nullable|string|max:1000',
        ]);

        $user->update($validated);

        return redirect()->back()->with('success', 'Votre profil a été mis à jour avec succès.');
    }

    public function updatePhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $user = Auth::user();

        // Supprimer l'ancienne photo si elle existe
        if ($user->photo) {
            Storage::delete('public/' . $user->photo);
        }

        // Stocker la nouvelle photo
        $path = $request->file('photo')->store('users/photos', 'public');
        $user->update(['photo' => $path]);

        return redirect()->back()->with('success', 'Votre photo de profil a été mise à jour avec succès.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();
        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return redirect()->back()->with('success', 'Votre mot de passe a été modifié avec succès.');
    }

    /**
     * Afficher le profil du médecin
     */
    public function doctorProfile()
    {
        $doctor = Auth::user();

        if (!$doctor || !$doctor->hasRole('Doctor')) {
            abort(403, 'Accès non autorisé');
        }

        // Récupérer les statistiques du médecin
        $totalAppointments = \App\Models\Appointment::where('service_id', $doctor->service_id)->count();
        $todayAppointments = \App\Models\Appointment::where('service_id', $doctor->service_id)
            ->whereDate('appointment_date', today())
            ->count();
        $totalPatients = \App\Models\User::whereHas('appointments', function($query) use ($doctor) {
            $query->where('service_id', $doctor->service_id);
        })->count();
        $totalMessages = \App\Models\Message::where('sender_id', $doctor->id)->count();

        // Récupérer le service du médecin
        $service = \App\Models\Service::find($doctor->service_id);

        return view('doctor.profile', compact('doctor', 'service', 'totalAppointments', 'todayAppointments', 'totalPatients', 'totalMessages'));
    }

    /**
     * Afficher les paramètres du médecin
     */
    public function doctorSettings()
    {
        $doctor = Auth::user();

        if (!$doctor || !$doctor->hasRole('Doctor')) {
            abort(403, 'Accès non autorisé');
        }

        // Récupérer les services disponibles pour le changement
        $services = \App\Models\Service::all();

        // Paramètres par défaut
        $settings = [
            'email_notifications' => true,
            'sms_notifications' => false,
            'appointment_reminders' => true,
            'urgent_notifications' => true,
            'theme' => 'light',
            'language' => 'fr',
            'timezone' => 'Africa/Dakar',
            'date_format' => 'd/m/Y',
            'two_factor_auth' => false,
            'session_timeout' => true,
            'session_duration' => 120,
            'login_notifications' => true,
            'working_hours_start' => '08:00',
            'working_hours_end' => '18:00',
            'appointment_duration' => 30,
            'weekend_appointments' => false,
        ];

        return view('doctor.settings', compact('doctor', 'services', 'settings'));
    }

    /**
     * Mettre à jour le profil du médecin
     */
    public function updateDoctorProfile(Request $request)
    {
        $doctor = Auth::user();

        if (!$doctor || !$doctor->hasRole('Doctor')) {
            abort(403, 'Accès non autorisé');
        }

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $doctor->id,
            'phone_number' => 'required|string|max:20',
            'day_of_birth' => 'required|date',
            'adress' => 'required|string|max:500',
            'height' => 'nullable|numeric|min:100|max:250',
            'weight' => 'nullable|numeric|min:20|max:300',
            'blood_type' => 'nullable|string|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'biographie' => 'nullable|string|max:1000',
            'specialization' => 'nullable|string|max:255',
            'experience_years' => 'nullable|integer|min:0|max:50',
        ]);

        $doctor->update($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'status' => true,
                'message' => 'Profil mis à jour avec succès',
                'data' => $doctor
            ]);
        }

        return redirect()->back()->with('success', 'Votre profil a été mis à jour avec succès.');
    }

    /**
     * Mettre à jour les paramètres du médecin
     */
    public function updateDoctorSettings(Request $request)
    {
        $doctor = Auth::user();

        if (!$doctor || !$doctor->hasRole('Doctor')) {
            abort(403, 'Accès non autorisé');
        }

        $validated = $request->validate([
            'service_id' => 'nullable|exists:services,id',
            'notification_preferences' => 'nullable|array',
            'working_hours' => 'nullable|string|max:500',
            'consultation_duration' => 'nullable|integer|min:15|max:120',
        ]);

        $doctor->update($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'status' => true,
                'message' => 'Paramètres mis à jour avec succès',
                'data' => $doctor
            ]);
        }

        return redirect()->back()->with('success', 'Vos paramètres ont été mis à jour avec succès.');
    }
}
