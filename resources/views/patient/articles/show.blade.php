@extends('layouts.patient')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8">
            <!-- Article principal -->
            <div class="card">
                @if($article->photo)
                <img src="{{ asset('storage/' . $article->photo) }}" 
                     class="card-img-top" alt="{{ $article->title }}"
                     style="max-height: 400px; object-fit: cover;">
                @endif
                
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted">
                            <i class="fas fa-calendar me-1"></i>
                            Publié le {{ $article->created_at ? $article->created_at->format('d/m/Y à H:i') : 'N/A' }}
                        </small>
                    </div>
                    
                    <h1 class="card-title mb-4">{{ $article->title }}</h1>
                    
                    <div class="article-content mb-4">
                        {!! nl2br(e($article->content)) !!}
                    </div>
                    
                    @if($article->symptoms)
                    <div class="alert alert-info">
                        <h6 class="alert-heading">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Symptômes
                        </h6>
                        <p class="mb-0">{{ $article->symptoms }}</p>
                    </div>
                    @endif
                    
                    @if($article->advices)
                    <div class="alert alert-success">
                        <h6 class="alert-heading">
                            <i class="fas fa-lightbulb me-2"></i>
                            Conseils
                        </h6>
                        <p class="mb-0">{{ $article->advices }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <!-- Articles connexes -->
            @if(isset($relatedArticles) && $relatedArticles->count() > 0)
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-newspaper me-2"></i>
                        Articles connexes
                    </h6>
                </div>
                <div class="card-body">
                    @foreach($relatedArticles as $relatedArticle)
                    <div class="related-article mb-3 pb-3 border-bottom">
                        @if($relatedArticle->photo)
                        <img src="{{ asset('storage/' . $relatedArticle->photo) }}" 
                             class="img-fluid rounded mb-2" alt="{{ $relatedArticle->title }}"
                             style="width: 100%; height: 120px; object-fit: cover;">
                        @else
                        <div class="bg-light rounded d-flex align-items-center justify-content-center mb-2" 
                             style="height: 120px;">
                            <i class="fas fa-newspaper fa-2x text-muted"></i>
                        </div>
                        @endif
                        
                        <h6 class="card-title">
                            <a href="{{ route('patient.articles.show', $relatedArticle->id) }}" 
                               class="text-decoration-none">
                                {{ Str::limit($relatedArticle->title, 60) }}
                            </a>
                        </h6>
                        
                        <small class="text-muted">
                            <i class="fas fa-calendar me-1"></i>
                            {{ $relatedArticle->created_at ? $relatedArticle->created_at->format('d/m/Y') : 'N/A' }}
                        </small>
                        
                        <p class="card-text mt-2">
                            {{ Str::limit($relatedArticle->content, 100) }}
                        </p>
                        
                        <a href="{{ route('patient.articles.show', $relatedArticle->id) }}" 
                           class="btn btn-sm btn-outline-primary">
                            Lire plus
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
            
            <!-- Navigation -->
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-arrow-left me-2"></i>
                        Navigation
                    </h6>
                </div>
                <div class="card-body">
                    <a href="{{ route('patient.articles') }}" class="btn btn-outline-secondary w-100 mb-2">
                        <i class="fas fa-list me-2"></i>Retour à la liste
                    </a>
                    
                    <a href="{{ route('patient.dashboard') }}" class="btn btn-outline-primary w-100">
                        <i class="fas fa-home me-2"></i>Tableau de bord
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.article-content {
    line-height: 1.8;
    font-size: 1.1rem;
    color: #2c3e50;
}

.article-content p {
    margin-bottom: 1.5rem;
}

.related-article:last-child {
    border-bottom: none !important;
    margin-bottom: 0 !important;
    padding-bottom: 0 !important;
}

.related-article .card-title a {
    color: #2c3e50;
    font-weight: 600;
}

.related-article .card-title a:hover {
    color: #007bff;
}

.alert-heading {
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.card-img-top {
    border-bottom: 1px solid #e9ecef;
}
</style>
@endsection
