<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    // Créer un service
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:services',   
            'price' => 'nullable|numeric',
            'photo' => 'nullable|file|image|max:2048',
            'description' => 'required|string|max:255|unique:services',
        ]);
    
        $path = null;
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('service_photos', 'public');
        }
    
        $service = Service::create([
            'name' => $request->name,
            'price' => $request->price,
            'photo' => $path,
            'description' => $request->description,
        ]);
    
        return response()->json([
            'status' => true,
            'message' => 'Service créé avec succès',
            'data' => $service,
        ], 201);
    }

    public function index()
    {
        $services = Service::all();
        return response()->json(['data' => $services]);
    }

    public function update(Request $request, $id)
    {
         $service = Service::findOrFail($id);
        if (!$service) {
             return response()->json(['message' => 'Service non trouvé'], 404);
        
            }


        $request->validate([
            'name' => 'required|string|max:255|unique:services,name,' . $id,
            'photo' => 'nullable|file|image|max:2048',
            'description' => 'required|string|max:255|unique:services,description,' . $id,
        ]);

        $service->update($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Service mis à jour avec succès',
            'data' => $service,
        ]);
    }

    // Supprimer un service
    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        $service->delete();

        return response()->json([
            'status' => true,
            'message' => 'Service supprimé avec succès',
        ]);
    }
}
