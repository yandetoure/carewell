<?php declare(strict_types=1); 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $query = Article::query();

        // Filtre par recherche
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('content', 'like', '%' . $request->search . '%')
                  ->orWhere('symptoms', 'like', '%' . $request->search . '%');
        }

        // Filtre par catégorie
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Article à la une (le plus récent)
        $featuredArticle = $query->clone()->latest()->first();

        // Articles normaux (excluant l'article à la une)
        $articles = $query->where('id', '!=', $featuredArticle?->id ?? 0)
                         ->latest()
                         ->paginate(9);

        return view('articles.index', compact('articles', 'featuredArticle'));
    }

    public function show(Article $article)
    {
        // Articles connexes basés sur le contenu
        $relatedArticles = Article::where('id', '!=', $article->id)
                                 ->where(function($query) use ($article) {
                                     $query->where('title', 'like', '%' . substr($article->title, 0, 20) . '%')
                                           ->orWhere('content', 'like', '%' . substr($article->content, 0, 50) . '%');
                                 })
                                 ->take(3)
                                 ->get();

        return view('articles.show', compact('article', 'relatedArticles'));
    }

    public function create()
    {
        return view('admin.articles.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:10000',
            'symptoms' => 'nullable|string|max:1000',
            'advices' => 'nullable|string|max:1000',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('articles', 'public');
        }

        Article::create($validated);

        return redirect()->route('admin.articles')->with('success', 'Article créé avec succès.');
    }

    public function edit(Article $article)
    {
        return view('admin.articles.edit', compact('article'));
    }

    public function update(Request $request, Article $article)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:10000',
            'symptoms' => 'nullable|string|max:1000',
            'advices' => 'nullable|string|max:1000',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            // Supprimer l'ancienne photo
            if ($article->photo) {
                \Illuminate\Support\Facades\Storage::delete('public/' . $article->photo);
            }
            $validated['photo'] = $request->file('photo')->store('articles', 'public');
        }

        $article->update($validated);

        return redirect()->route('admin.articles')->with('success', 'Article mis à jour avec succès.');
    }

    public function destroy(Article $article)
    {
        if ($article->photo) {
            \Illuminate\Support\Facades\Storage::delete('public/' . $article->photo);
        }

        $article->delete();

        return redirect()->route('admin.articles')->with('success', 'Article supprimé avec succès.');
    }

    public function adminIndex()
    {
        $articles = Article::latest()->paginate(20);
        return view('admin.articles.index', compact('articles'));
    }

    /**
     * Display articles for patients.
     */
    public function patientIndex(Request $request)
    {
        $query = Article::query();

        // Filtre par recherche
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('content', 'like', '%' . $request->search . '%');
        }

        // Tri par date de création (plus récent en premier)
        $query->orderBy('created_at', 'desc');

        // Articles normaux
        $articles = $query->paginate(12);

        return view('patient.articles.index', compact('articles'));
    }

    /**
     * Display a specific article for patients.
     */
    public function patientShow(Article $article)
    {
        // Articles connexes basés sur la date de création
        $relatedArticles = Article::where('id', '!=', $article->id)
                                 ->orderBy('created_at', 'desc')
                                 ->take(3)
                                 ->get();

        return view('patient.articles.show', compact('article', 'relatedArticles'));
    }
}
