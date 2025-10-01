<?php declare(strict_types=1); 

namespace App\Http\Controllers;

use App\Models\Bed;
use App\Models\Service;
use App\Models\MedicalFile;
use Illuminate\Http\Request;

class BedController extends Controller
{
    /**
     * Display a listing of the beds.
     */
    public function index()
    {
        $beds = Bed::with(['service', 'medicalFile.user'])->get();
        $services = Service::all();
        
        // Statistiques
        $totalBeds = $beds->count();
        $availableBeds = $beds->where('status', 'libre')->count();
        $occupiedBeds = $beds->where('status', 'occupe')->count();
        $maintenanceBeds = $beds->where('status', 'maintenance')->count();
        
        // Transformer les données pour la vue
        $bedsData = $beds->map(function($bed) {
            return [
                'id' => $bed->id,
                'number' => $bed->bed_number,
                'room' => $bed->room_number,
                'status' => $bed->status === 'occupe' ? 'occupied' : 'available',
                'patient' => $bed->patient_name,
                'service' => $bed->service ? $bed->service->name : null,
                'bed_type' => $bed->bed_type,
                'admission_date' => $bed->admission_date,
                'days_admitted' => $bed->days_admitted,
            ];
        });
        
        return view('admin.beds.index', compact('bedsData', 'totalBeds', 'availableBeds', 'occupiedBeds', 'maintenanceBeds', 'services'))
            ->with('beds', $bedsData);
    }

    /**
     * Show the form for creating a new bed.
     */
    public function create()
    {
        $services = Service::all();
        return view('admin.beds.create', compact('services'));
    }

    /**
     * Store a newly created bed in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'bed_number' => 'required|string|unique:beds,bed_number',
            'room_number' => 'required|string',
            'service_id' => 'nullable|exists:services,id',
            'bed_type' => 'required|in:standard,premium,vip',
            'status' => 'nullable|in:libre,occupe,maintenance,admission_impossible',
        ]);

        $bed = Bed::create($validated);

        return redirect()->route('admin.beds.index')
            ->with('success', 'Lit ajouté avec succès');
    }

    /**
     * Display the specified bed.
     */
    public function show(Bed $bed)
    {
        $bed->load(['service', 'medicalFile.user', 'admissions.medicalFile.user']);
        
        // Récupérer les patients disponibles (avec dossier médical et non déjà admis)
        $availablePatients = MedicalFile::with('user')
            ->whereDoesntHave('beds', function($query) {
                $query->where('status', 'occupe');
            })
            ->get()
            ->map(function($medicalFile) {
                return [
                    'id' => $medicalFile->id,
                    'user_id' => $medicalFile->user_id,
                    'name' => $medicalFile->user ? $medicalFile->user->first_name . ' ' . $medicalFile->user->last_name : 'N/A',
                    'email' => $medicalFile->user ? $medicalFile->user->email : 'N/A',
                    'phone' => $medicalFile->user ? $medicalFile->user->phone_number : 'N/A',
                ];
            });
        
        // Préparer les données pour la vue
        $bedData = [
            'id' => $bed->id,
            'number' => $bed->bed_number,
            'room' => $bed->room_number,
            'status' => $bed->status === 'occupe' ? 'occupied' : 'available',
            'patient' => $bed->patient_name,
            'service' => $bed->service ? $bed->service->name : null,
            'type' => ucfirst($bed->bed_type),
            'admission_date' => $bed->admission_date,
            'expected_discharge_date' => $bed->expected_discharge_date,
            'days_admitted' => $bed->days_admitted,
            'notes' => $bed->notes,
        ];
        
        return view('admin.beds.show', [
            'bed' => $bedData, 
            'bedModel' => $bed,
            'availablePatients' => $availablePatients
        ]);
    }

    /**
     * Show the form for editing the specified bed.
     */
    public function edit(Bed $bed)
    {
        $bed->load(['service', 'medicalFile.user']);
        $services = Service::all();
        
        // Préparer les données pour la vue
        $bedData = [
            'id' => $bed->id,
            'number' => $bed->bed_number,
            'room' => $bed->room_number,
            'status' => $bed->status,
            'service_id' => $bed->service_id,
            'bed_type' => $bed->bed_type,
            'notes' => $bed->notes,
        ];
        
        return view('admin.beds.edit', ['bed' => $bedData, 'bedModel' => $bed, 'services' => $services]);
    }

    /**
     * Update the specified bed in storage.
     */
    public function update(Request $request, Bed $bed)
    {
        $validated = $request->validate([
            'bed_number' => 'required|string|unique:beds,bed_number,' . $bed->id,
            'room_number' => 'required|string',
            'service_id' => 'nullable|exists:services,id',
            'bed_type' => 'required|in:standard,premium,vip',
            'status' => 'required|in:libre,occupe,maintenance,admission_impossible',
            'notes' => 'nullable|string',
        ]);

        $bed->update($validated);

        return redirect()->route('admin.beds.index')
            ->with('success', 'Lit mis à jour avec succès');
    }

    /**
     * Remove the specified bed from storage.
     */
    public function destroy(Bed $bed)
    {
        // Vérifier si le lit est occupé
        if ($bed->isOccupied()) {
            return redirect()->route('admin.beds.index')
                ->with('error', 'Impossible de supprimer un lit occupé');
        }

        $bed->delete();

        return redirect()->route('admin.beds.index')
            ->with('success', 'Lit supprimé avec succès');
    }

    /**
     * Admit a patient to a bed.
     */
    public function admitPatient(Request $request, Bed $bed)
    {
        $validated = $request->validate([
            'medical_file_id' => 'required|exists:medical_files,id',
            'admission_date' => 'nullable|date',
            'expected_discharge_date' => 'nullable|date|after:admission_date',
            'notes' => 'nullable|string',
        ]);

        if (!$bed->isAvailable()) {
            return back()->with('error', 'Ce lit n\'est pas disponible pour une admission');
        }

        $bed->admitPatient(
            $validated['medical_file_id'],
            $validated['admission_date'] ?? null,
            $validated['expected_discharge_date'] ?? null,
            $validated['notes'] ?? null,
            auth()->id()
        );

        return redirect()->route('admin.beds.show', $bed)
            ->with('success', 'Patient admis avec succès');
    }

    /**
     * Discharge a patient from a bed.
     */
    public function dischargePatient(Request $request, Bed $bed)
    {
        if (!$bed->isOccupied()) {
            return back()->with('error', 'Ce lit n\'est pas occupé');
        }

        $validated = $request->validate([
            'discharge_date' => 'nullable|date',
            'discharge_reason' => 'nullable|in:gueri,transfert,deces,autre',
            'notes' => 'nullable|string',
        ]);

        $bed->dischargePatient(
            $validated['discharge_date'] ?? null,
            $validated['discharge_reason'] ?? null,
            $validated['notes'] ?? null,
            auth()->id()
        );

        return redirect()->route('admin.beds.index')
            ->with('success', 'Patient sorti avec succès. Le lit est maintenant disponible.');
    }

    /**
     * Set bed to maintenance mode.
     */
    public function setMaintenance(Request $request, Bed $bed)
    {
        $validated = $request->validate([
            'notes' => 'nullable|string',
        ]);

        if ($bed->isOccupied()) {
            return back()->with('error', 'Impossible de mettre en maintenance un lit occupé');
        }

        $bed->setMaintenance($validated['notes'] ?? null);

        return redirect()->route('admin.beds.index')
            ->with('success', 'Lit mis en maintenance');
    }

    /**
     * Make bed available again.
     */
    public function makeAvailable(Bed $bed)
    {
        $bed->makeAvailable();

        return redirect()->route('admin.beds.index')
            ->with('success', 'Lit maintenant disponible');
    }

    /**
     * Get beds by service (API endpoint).
     */
    public function getBedsByService($serviceId)
    {
        $beds = Bed::byService($serviceId)
            ->with(['medicalFile.user'])
            ->get();

        return response()->json([
            'success' => true,
            'data' => $beds,
        ]);
    }

    /**
     * Get available beds (API endpoint).
     */
    public function getAvailableBeds()
    {
        $beds = Bed::libre()
            ->with(['service'])
            ->get();

        return response()->json([
            'success' => true,
            'data' => $beds,
        ]);
    }

    /**
     * Get bed statistics (API endpoint).
     */
    public function getStatistics()
    {
        $stats = [
            'total' => Bed::count(),
            'libre' => Bed::libre()->count(),
            'occupe' => Bed::occupe()->count(),
            'maintenance' => Bed::maintenance()->count(),
            'admission_impossible' => Bed::where('status', 'admission_impossible')->count(),
            'occupation_rate' => Bed::count() > 0 
                ? round((Bed::occupe()->count() / Bed::count()) * 100, 2) 
                : 0,
        ];

        return response()->json([
            'success' => true,
            'data' => $stats,
        ]);
    }
}
