<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DiseaseMedicalfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //Affichage des maladie du patient
        $diseaseMedicalfiles = \App\Models\DiseaseMedicalfile::with(['disease', 'medicalFile'])->get();
        return response()->json($diseaseMedicalfiles);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //Enregistrement avec validations
        

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
