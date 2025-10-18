<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Absence;
use App\Models\Availability;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AbsenceController extends Controller
{
    /**
     * Afficher le calendrier avec les disponibilités et absences
     */
    public function calendar()
    {
        $doctor = Auth::user();

        if (!$doctor || !$doctor->hasRole('Doctor')) {
            abort(403, 'Accès non autorisé');
        }

        // Récupérer les disponibilités du médecin
        $availabilities = Availability::where('doctor_id', $doctor->id)
            ->where('available_date', '>=', now()->startOfMonth())
            ->where('available_date', '<=', now()->addMonths(2)->endOfMonth())
            ->orderBy('available_date')
            ->get();

        // Récupérer les absences du médecin
        $absences = Absence::where('doctor_id', $doctor->id)
            ->where('start_date', '>=', now()->startOfMonth())
            ->where('end_date', '<=', now()->addMonths(2)->endOfMonth())
            ->orderBy('start_date')
            ->get();

        // Statistiques
        $totalAvailabilities = $availabilities->count();
        $totalAbsences = $absences->count();
        $upcomingAbsences = $absences->where('start_date', '>', now()->toDateString())->count();

        return view('doctor.calendar.index', compact(
            'availabilities',
            'absences',
            'totalAvailabilities',
            'totalAbsences',
            'upcomingAbsences'
        ));
    }

    /**
     * Afficher le formulaire de création d'absence
     */
    public function create()
    {
        $doctor = Auth::user();

        if (!$doctor || !$doctor->hasRole('Doctor')) {
            abort(403, 'Accès non autorisé');
        }

        return view('doctor.calendar.create-absence');
    }

    /**
     * Enregistrer une nouvelle absence
     */
    public function store(Request $request)
    {
        $doctor = Auth::user();

        if (!$doctor || !$doctor->hasRole('Doctor')) {
            abort(403, 'Accès non autorisé');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:congé,formation,maladie,personnel,autre',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'is_full_day' => 'boolean',
            'start_time' => 'nullable|date_format:H:i|required_if:is_full_day,false',
            'end_time' => 'nullable|date_format:H:i|after:start_time|required_if:is_full_day,false',
        ]);

        // Vérifier s'il y a des conflits avec des disponibilités existantes
        $conflicts = $this->checkAvailabilityConflicts($doctor->id, $request->start_date, $request->end_date, $request->start_time, $request->end_time, $request->is_full_day);

        $absence = Absence::create([
            'doctor_id' => $doctor->id,
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'is_full_day' => $request->is_full_day ?? true,
            'status' => 'planned',
            'appointments_pending' => $conflicts > 0,
        ]);

        return redirect()->route('doctor.calendar')
            ->with('success', 'Absence créée avec succès. ' . ($conflicts > 0 ? "Attention: {$conflicts} disponibilité(s) en conflit." : ''));
    }

    /**
     * Afficher le formulaire d'édition d'absence
     */
    public function edit(Absence $absence)
    {
        $doctor = Auth::user();

        if (!$doctor || !$doctor->hasRole('Doctor') || $absence->doctor_id !== $doctor->id) {
            abort(403, 'Accès non autorisé');
        }

        return view('doctor.calendar.edit-absence', compact('absence'));
    }

    /**
     * Mettre à jour une absence
     */
    public function update(Request $request, Absence $absence)
    {
        $doctor = Auth::user();

        if (!$doctor || !$doctor->hasRole('Doctor') || $absence->doctor_id !== $doctor->id) {
            abort(403, 'Accès non autorisé');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:congé,formation,maladie,personnel,autre',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'is_full_day' => 'boolean',
            'start_time' => 'nullable|date_format:H:i|required_if:is_full_day,false',
            'end_time' => 'nullable|date_format:H:i|after:start_time|required_if:is_full_day,false',
            'status' => 'required|in:planned,confirmed,cancelled',
        ]);

        $absence->update([
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'is_full_day' => $request->is_full_day ?? true,
            'status' => $request->status,
        ]);

        return redirect()->route('doctor.calendar')
            ->with('success', 'Absence mise à jour avec succès.');
    }

    /**
     * Supprimer une absence
     */
    public function destroy(Absence $absence)
    {
        $doctor = Auth::user();

        if (!$doctor || !$doctor->hasRole('Doctor') || $absence->doctor_id !== $doctor->id) {
            abort(403, 'Accès non autorisé');
        }

        $absence->delete();

        return redirect()->route('doctor.calendar')
            ->with('success', 'Absence supprimée avec succès.');
    }

    /**
     * Vérifier les conflits avec les disponibilités existantes
     */
    private function checkAvailabilityConflicts($doctorId, $startDate, $endDate, $startTime = null, $endTime = null, $isFullDay = true)
    {
        $query = Availability::where('doctor_id', $doctorId)
            ->whereBetween('available_date', [$startDate, $endDate]);

        if (!$isFullDay && $startTime && $endTime) {
            // Pour les absences partielles, vérifier les conflits d'horaires
            $query->where(function($q) use ($startTime, $endTime) {
                $q->where(function($subQ) use ($startTime, $endTime) {
                    $subQ->where('start_time', '<', $endTime)
                         ->where('end_time', '>', $startTime);
                });
            });
        }

        return $query->count();
    }

    /**
     * API pour récupérer les données du calendrier (pour AJAX)
     */
    public function getCalendarData(Request $request)
    {
        $doctor = Auth::user();

        if (!$doctor || !$doctor->hasRole('Doctor')) {
            return response()->json(['error' => 'Accès non autorisé'], 403);
        }

        $startDate = $request->get('start', now()->startOfMonth()->toDateString());
        $endDate = $request->get('end', now()->endOfMonth()->toDateString());

        $availabilities = Availability::where('doctor_id', $doctor->id)
            ->whereBetween('available_date', [$startDate, $endDate])
            ->with('service')
            ->get()
            ->map(function($availability) {
                return [
                    'id' => 'avail_' . $availability->id,
                    'title' => 'Disponible: ' . ($availability->service->name ?? 'Service non spécifié'),
                    'start' => $availability->available_date,
                    'end' => $availability->available_date,
                    'color' => '#28a745',
                    'type' => 'availability',
                    'time' => $availability->start_time . ' - ' . $availability->end_time,
                    'duration' => $availability->appointment_duration . ' min',
                ];
            });

        $absences = Absence::where('doctor_id', $doctor->id)
            ->whereBetween('start_date', [$startDate, $endDate])
            ->get()
            ->map(function($absence) {
                $color = match($absence->type) {
                    'congé' => '#ffc107',
                    'formation' => '#17a2b8',
                    'maladie' => '#dc3545',
                    'personnel' => '#6c757d',
                    'autre' => '#6f42c1',
                    default => '#6c757d'
                };

                return [
                    'id' => 'absence_' . $absence->id,
                    'title' => $absence->title,
                    'start' => $absence->start_date,
                    'end' => Carbon::parse($absence->end_date)->addDay()->toDateString(),
                    'color' => $color,
                    'type' => 'absence',
                    'status' => $absence->status,
                    'is_full_day' => $absence->is_full_day,
                ];
            });

        return response()->json([
            'availabilities' => $availabilities,
            'absences' => $absences,
        ]);
    }
}