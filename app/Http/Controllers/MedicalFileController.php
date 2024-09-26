<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\MedicalFile;

class MedicalFileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //affichage des dossier medicaux
        $medicalficles = MedicalFile::all();
        return response()->json(['data' => $medicalficles]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
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
