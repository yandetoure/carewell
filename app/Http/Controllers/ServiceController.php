<?php declare(strict_types=1); 

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Service::query();

        // Filtre par recherche
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        // Tri
        switch ($request->get('sort')) {
            case 'name':
                $query->orderBy('name');
                break;
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        $services = $query->paginate(12);

        return view('services.index', compact('services'));
    }

    public function show(Service $service)
    {
        // Récupérer les services similaires
        $relatedServices = Service::where('id', '!=', $service->id)
                                 ->where('price', '>=', $service->price * 0.7)
                                 ->where('price', '<=', $service->price * 1.3)
                                 ->take(3)
                                 ->get();

        return view('services.show', compact('service', 'relatedServices'));
    }

    public function create()
    {
        return view('admin.services.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:services',
            'description' => 'required|string|max:1000',
            'price' => 'required|numeric|min:0',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('services', 'public');
        }

        Service::create($validated);

        return redirect()->route('admin.services')->with('success', 'Service créé avec succès.');
    }

    public function edit(Service $service)
    {
        return view('admin.services.edit', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'price' => 'required|numeric|min:0',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'remove_photo' => 'nullable|boolean',
            'duration' => 'nullable|integer|min:1|max:480',
            'category' => 'nullable|string|max:255',
            'requirements' => 'nullable|string|max:1000',
        ]);

        // Gestion de la suppression de photo
        if ($request->has('remove_photo') && $request->remove_photo == '1') {
            if ($service->photo) {
                Storage::delete('public/' . $service->photo);
            }
            $validated['photo'] = null;
        } elseif ($request->hasFile('photo')) {
            // Supprimer l'ancienne photo
            if ($service->photo) {
                Storage::delete('public/' . $service->photo);
            }
            $validated['photo'] = $request->file('photo')->store('services', 'public');
        }

        $service->update($validated);

        return redirect()->route('admin.services')->with('success', 'Service mis à jour avec succès.');
    }

    public function destroy(Service $service)
    {
        if ($service->photo) {
            Storage::delete('public/' . $service->photo);
        }

        $service->delete();

        return redirect()->route('admin.services')->with('success', 'Service supprimé avec succès.');
    }

    public function adminIndex()
    {
        $services = Service::paginate(20);
        return view('admin.services.index', compact('services'));
    }

    public function adminShow(Service $service)
    {
        try {
            // Charger les statistiques du service
            $service->loadCount('appointments');
            
            return view('admin.services.show', compact('service'));
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Service non trouvé',
                'message' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Display services for patients.
     */
    public function patientIndex(Request $request)
    {
        $query = Service::query();

        // Filtre par recherche
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        // Tri par nom (ordre alphabétique)
        $query->orderBy('name');

        $services = $query->paginate(12);

        return view('patient.services.index', compact('services'));
    }

    /**
     * Display a specific service for patients.
     */
    public function patientShow(Service $service)
    {
        // Récupérer les services similaires (basés sur le prix ou la description)
        $relatedServices = Service::where('id', '!=', $service->id)
                                 ->orderBy('price')
                                 ->take(3)
                                 ->get();

        // Récupérer les médecins disponibles pour ce service (à implémenter selon votre modèle)
        $doctors = collect([]);

        return view('patient.services.show', compact('service', 'relatedServices', 'doctors'));
    }
}
