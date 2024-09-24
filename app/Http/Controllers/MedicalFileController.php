<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;

class MedicalFileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //affichage des dossier medicaux
        return view('medical_files.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:services',   
            'photo' => 'nullable|file|image|max:2048',
        ]);
    
        // Gestion du fichier
        $path = null;
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('service_photos', 'public'); // Stockage dans le dossier 'storage/app/public/service_photos'
        }
    
        $service = Service::create([
            'name' => $request->name,
            'photo' => $path,
        ]);
    
        return response()->json([
            'status' => true,
            'message' => 'Service créé avec succès',
            'data' => $service,
        ], 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
