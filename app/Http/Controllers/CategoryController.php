<?php declare(strict_types=1); 

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Afficher une catégorie spécifique
     */
    public function show(Category $category)
    {
        // Charger les services de cette catégorie
        $category->load('services');
        
        return view('admin.categories.show', compact('category'));
    }

    /**
     * Afficher le formulaire d'édition d'une catégorie
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Mettre à jour une catégorie
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'icon' => 'required|string|max:255',
            'color' => 'required|string|max:50',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        // Gérer la checkbox is_active (si non coché, la valeur n'est pas envoyée)
        $validated['is_active'] = $request->has('is_active') ? (bool)$request->is_active : false;

        // Générer le slug si le nom a changé
        if ($category->name !== $validated['name']) {
            $validated['slug'] = Category::generateSlug($validated['name']);
        }

        $category->update($validated);

        return redirect()->route('admin.categories')->with('success', 'Catégorie mise à jour avec succès.');
    }
}

