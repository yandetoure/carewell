@extends('layouts.patient')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">
                        <i class="fas fa-newspaper me-2"></i>
                        Articles santé
                    </h5>
                    <p class="text-muted mb-0">Restez informé des dernières actualités médicales et conseils santé</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres et recherche -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('patient.articles') }}" class="row g-3">
                        <div class="col-md-8">
                            <label for="search" class="form-label">Rechercher</label>
                            <input type="text" class="form-control" id="search" name="search" 
                                   value="{{ request('search') }}" placeholder="Titre ou contenu...">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-outline-primary">
                                    <i class="fas fa-search me-2"></i>Rechercher
                                </button>
                                <a href="{{ route('patient.articles') }}" class="btn btn-outline-secondary mt-2">
                                    <i class="fas fa-times me-2"></i>Réinitialiser
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des articles -->
    <div class="row">
        @if(isset($articles) && $articles->count() > 0)
            @foreach($articles as $article)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100 article-card">
                    @if($article->photo)
                        <img src="{{ asset('storage/' . $article->photo) }}" 
                             class="card-img-top" alt="{{ $article->title }}"
                             style="height: 180px; object-fit: cover;">
                    @else
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                             style="height: 180px;">
                            <i class="fas fa-newspaper fa-2x text-muted"></i>
                        </div>
                    @endif
                    
                    <div class="card-body d-flex flex-column">
                        <div class="mb-2">
                            <small class="text-muted">
                                <i class="fas fa-calendar me-1"></i>
                                {{ $article->created_at ? $article->created_at->format('d/m/Y') : 'N/A' }}
                            </small>
                        </div>
                        
                        <h6 class="card-title">{{ Str::limit($article->title, 60) }}</h6>
                        <p class="card-text text-muted flex-grow-1">
                            {{ Str::limit($article->content, 120) }}
                        </p>
                        
                        @if($article->symptoms)
                        <div class="mb-2">
                            <small class="text-info">
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                <strong>Symptômes :</strong> {{ Str::limit($article->symptoms, 80) }}
                            </small>
                        </div>
                        @endif
                        
                        @if($article->advices)
                        <div class="mb-3">
                            <small class="text-success">
                                <i class="fas fa-lightbulb me-1"></i>
                                <strong>Conseils :</strong> {{ Str::limit($article->advices, 80) }}
                            </small>
                        </div>
                        @endif
                        
                        <div class="mt-auto">
                            <a href="{{ route('patient.articles.show', $article->id) }}" 
                               class="btn btn-primary w-100">
                                <i class="fas fa-book-open me-2"></i>Lire l'article
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        @else
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="fas fa-newspaper fa-3x text-muted mb-3"></i>
                    <h5>Aucun article trouvé</h5>
                    <p class="text-muted mb-3">
                        @if(request('search'))
                            Aucun article ne correspond à votre recherche "{{ request('search') }}".
                        @else
                            Aucun article n'est disponible pour le moment.
                        @endif
                    </p>
                    @if(request('search'))
                        <a href="{{ route('patient.articles') }}" class="btn btn-outline-primary">
                            <i class="fas fa-times me-2"></i>Effacer la recherche
                        </a>
                    @endif
                </div>
            </div>
        @endif
    </div>

    <!-- Pagination -->
    @if(isset($articles) && $articles->hasPages())
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-center">
                {{ $articles->links() }}
            </div>
        </div>
    </div>
    @endif
</div>

<style>
.article-card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    border: 1px solid #e9ecef;
}

.article-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

.article-card .card-img-top {
    border-bottom: 1px solid #e9ecef;
}

.article-card .card-title {
    color: #2c3e50;
    font-weight: 600;
}

.article-card .card-text {
    color: #6c757d;
    line-height: 1.5;
}

.article-card .btn {
    border-radius: 8px;
    font-weight: 500;
}

.article-card .text-info {
    color: #17a2b8 !important;
}

.article-card .text-success {
    color: #28a745 !important;
}
</style>
@endsection
