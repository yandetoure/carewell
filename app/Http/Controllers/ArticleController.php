<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use Illuminate\Support\Facades\Storage;



class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //afficher la liste des articles
        $articles = Article::all();
        return response()->json(['data' => $articles]);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //creer une nouvelle instance de l'article
            $request->validate([
                'title' => 'required|string|max:255|unique:articles',  
                'content' => 'required|string|min:50',  
                'photo' => 'nullable|file|image|max:2048',
            ]);
        
            // Gestion du fichier
            $path = null;
            if ($request->hasFile('photo')) {
                $path = $request->file('photo')->store('articles_photos', 'public'); // Stockage dans le dossier 'storage/app/public/service_photos'
            }
        
            $article = Article::create([
                'title' => $request->title,
                'content' => $request->content,
                'photo' => $path,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Article créé avec succès',
                'data' => $article,
            ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //Afficher les details d'un article
        $article = Article::find($id);
        return response()->json(['data' => $article]);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Vérifier si l'article existe
        $article = Article::find($id);
        if (!$article) {
            return response()->json(['message' => 'Article non trouvé'], 404);
        }
    
        // Valider les données d'entrée
        $request->validate([
            'title' => 'required|string|max:255|unique:articles,title,' . $article->id,  
            'content' => 'required|string|min:50',
            'photo' => 'nullable|file|image|max:2048',
        ]);
    
        // Gérer la mise à jour du fichier si une nouvelle image est fournie
        if ($request->hasFile('photo')) {
            // Supprimer l'ancienne image si elle existe
            if ($article->photo) {
                Storage::disk('public')->delete($article->photo);
            }
            // Stocker la nouvelle image
            $path = $request->file('photo')->store('articles_photos', 'public');
            $article->photo = $path;
        }
    
        // Mettre à jour les autres champs
        $article->title = $request->input('title');
        $article->content = $request->input('content');
        $article->save();
    
        return response()->json([
            'status' => true,
            'message' => 'Article mis à jour avec succès',
            'data' => $article,
        ], 200);
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Vérifier si l'article existe
        $article = Article::find($id);
        if (!$article) {
            return response()->json(['message' => 'Article non trouvé'], 404);
        }

        // Supprimer l'image associée si elle existe
        if ($article->photo) {
            Storage::disk('public')->delete($article->photo);
        }

        // Supprimer l'article
        $article->delete();

        return response()->json([
            'status' => true,
            'message' => 'Article supprimé avec succès'
        ], 200);
    }
    
}
