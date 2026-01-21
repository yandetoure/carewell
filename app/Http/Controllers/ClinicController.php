<?php

namespace App\Http\Controllers;

use App\Models\Clinic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ClinicController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Seul le Super Admin peut voir toutes les cliniques
        if (!Auth::user()->hasRole('Super Admin')) {
            abort(403, 'Accès non autorisé');
        }

        $clinics = Clinic::withCount(['users', 'appointments', 'services'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.clinics.index', compact('clinics'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Seul le Super Admin peut créer des cliniques
        if (!Auth::user()->hasRole('Super Admin')) {
            abort(403, 'Accès non autorisé');
        }

        return view('admin.clinics.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Seul le Super Admin peut créer des cliniques
        if (!Auth::user()->hasRole('Super Admin')) {
            abort(403, 'Accès non autorisé');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:clinics,email',
            'phone_number' => 'required|string|max:20',
            'address' => 'required|string',
            'city' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('clinics', 'public');
        }

        $validated['is_active'] = $request->has('is_active') ? true : false;

        $clinic = Clinic::create($validated);

        return redirect()->route('admin.clinics.index')
            ->with('success', 'Clinique créée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Clinic $clinic)
    {
        // Seul le Super Admin peut voir les détails d'une clinique
        if (!Auth::user()->hasRole('Super Admin')) {
            abort(403, 'Accès non autorisé');
        }

        $clinic->loadCount(['users', 'appointments', 'services', 'medicalFiles']);

        return view('admin.clinics.show', compact('clinic'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Clinic $clinic)
    {
        // Seul le Super Admin peut modifier des cliniques
        if (!Auth::user()->hasRole('Super Admin')) {
            abort(403, 'Accès non autorisé');
        }

        return view('admin.clinics.edit', compact('clinic'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Clinic $clinic)
    {
        // Seul le Super Admin peut modifier des cliniques
        if (!Auth::user()->hasRole('Super Admin')) {
            abort(403, 'Accès non autorisé');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:clinics,email,' . $clinic->id,
            'phone_number' => 'required|string|max:20',
            'address' => 'required|string',
            'city' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('logo')) {
            // Supprimer l'ancien logo s'il existe
            if ($clinic->logo) {
                Storage::disk('public')->delete($clinic->logo);
            }
            $validated['logo'] = $request->file('logo')->store('clinics', 'public');
        }

        $validated['is_active'] = $request->has('is_active') ? true : false;

        $clinic->update($validated);

        return redirect()->route('admin.clinics.index')
            ->with('success', 'Clinique mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Clinic $clinic)
    {
        // Seul le Super Admin peut supprimer des cliniques
        if (!Auth::user()->hasRole('Super Admin')) {
            abort(403, 'Accès non autorisé');
        }

        // Vérifier s'il y a des utilisateurs associés
        if ($clinic->users()->count() > 0) {
            return redirect()->route('admin.clinics.index')
                ->with('error', 'Impossible de supprimer la clinique car elle contient des utilisateurs.');
        }

        // Supprimer le logo s'il existe
        if ($clinic->logo) {
            Storage::disk('public')->delete($clinic->logo);
        }

        $clinic->delete();

        return redirect()->route('admin.clinics.index')
            ->with('success', 'Clinique supprimée avec succès.');
    }

    /**
     * Afficher la page de sélection de clinique pour le Super Admin
     */
    public function select()
    {
        // Seul le Super Admin peut sélectionner une clinique
        if (!Auth::user()->hasRole('Super Admin')) {
            abort(403, 'Accès non autorisé');
        }

        $clinics = Clinic::where('is_active', true)
            ->withCount(['users', 'appointments', 'services'])
            ->orderBy('name')
            ->get();

        $selectedClinicId = session('selected_clinic_id');
        $selectedClinic = $selectedClinicId ? Clinic::find($selectedClinicId) : null;

        return view('admin.clinics.select', compact('clinics', 'selectedClinic'));
    }

    /**
     * Sélectionner une clinique (stocker en session)
     */
    public function setSelected(Request $request)
    {
        // Seul le Super Admin peut sélectionner une clinique
        if (!Auth::user()->hasRole('Super Admin')) {
            abort(403, 'Accès non autorisé');
        }

        $validated = $request->validate([
            'clinic_id' => 'required|exists:clinics,id',
        ]);

        session(['selected_clinic_id' => $validated['clinic_id']]);

        return redirect()->route('admin.dashboard')
            ->with('success', 'Clinique sélectionnée avec succès.');
    }

    /**
     * Désélectionner la clinique (retourner à la vue globale)
     */
    public function clearSelected()
    {
        // Seul le Super Admin peut désélectionner une clinique
        if (!Auth::user()->hasRole('Super Admin')) {
            abort(403, 'Accès non autorisé');
        }

        session()->forget('selected_clinic_id');

        return redirect()->route('admin.clinics.select')
            ->with('success', 'Vue globale activée.');
    }
}
