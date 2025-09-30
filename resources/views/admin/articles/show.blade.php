<div class="row">
    <div class="col-md-4">
        <!-- Article Image -->
        <div class="card mb-4">
            <div class="card-body text-center">
                @if($article->photo && file_exists(public_path('storage/' . $article->photo)))
                    <img src="{{ asset('storage/' . $article->photo) }}" 
                         alt="{{ $article->title }}" 
                         class="img-fluid rounded mb-3" 
                         style="max-height: 200px; object-fit: cover; width: 100%;"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="bg-light rounded d-flex align-items-center justify-content-center mb-3" 
                         style="height: 200px; display: none;">
                        <i class="fas fa-newspaper fa-3x text-muted"></i>
                    </div>
                @else
                    <div class="bg-light rounded d-flex align-items-center justify-content-center mb-3" 
                         style="height: 200px;">
                        <i class="fas fa-newspaper fa-3x text-muted"></i>
                    </div>
                @endif
                <h5 class="card-title">{{ $article->title }}</h5>
                <p class="text-muted">Article de santé</p>
                
                <!-- Debug info (à supprimer en production) -->
                @if(config('app.debug'))
                <small class="text-muted d-block mt-2">
                    Photo: {{ $article->photo ?? 'Aucune' }}<br>
                    Chemin: {{ $article->photo ? asset('storage/' . $article->photo) : 'N/A' }}
                </small>
                @endif
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-chart-bar me-2"></i>
                    Statistiques rapides
                </h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <div class="h4 text-primary mb-1">{{ $article->created_at->diffInDays(now()) }}</div>
                        <small class="text-muted">Jours actif</small>
                    </div>
                    <div class="col-6">
                        <div class="h4 text-success mb-1">{{ $article->updated_at != $article->created_at ? 'Oui' : 'Non' }}</div>
                        <small class="text-muted">Modifié</small>
                    </div>
                </div>
                <hr>
                <div class="text-center">
                    <div class="h4 text-warning mb-1">{{ Str::length($article->content) }}</div>
                    <small class="text-muted">Caractères</small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <!-- Article Details -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Informations de l'article
                </h6>
                <span class="badge bg-primary">{{ $article->id }}</span>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-sm-3">
                        <strong><i class="fas fa-heading me-1"></i>Titre :</strong>
                    </div>
                    <div class="col-sm-9">
                        <span class="fw-bold text-primary">{{ $article->title }}</span>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-sm-3">
                        <strong><i class="fas fa-align-left me-1"></i>Contenu :</strong>
                    </div>
                    <div class="col-sm-9">
                        <div class="bg-light p-3 rounded">
                            <p class="mb-0">{{ $article->content }}</p>
                        </div>
                    </div>
                </div>
                
                @if($article->symptoms)
                <div class="row mb-3">
                    <div class="col-sm-3">
                        <strong><i class="fas fa-exclamation-triangle me-1"></i>Symptômes :</strong>
                    </div>
                    <div class="col-sm-9">
                        <div class="bg-warning bg-opacity-10 p-3 rounded border-start border-warning border-4">
                            <p class="mb-0">{{ $article->symptoms }}</p>
                        </div>
                    </div>
                </div>
                @endif
                
                @if($article->advices)
                <div class="row mb-3">
                    <div class="col-sm-3">
                        <strong><i class="fas fa-lightbulb me-1"></i>Conseils :</strong>
                    </div>
                    <div class="col-sm-9">
                        <div class="bg-success bg-opacity-10 p-3 rounded border-start border-success border-4">
                            <p class="mb-0">{{ $article->advices }}</p>
                        </div>
                    </div>
                </div>
                @endif
                
                <div class="row mb-3">
                    <div class="col-sm-3">
                        <strong><i class="fas fa-calendar me-1"></i>Créé le :</strong>
                    </div>
                    <div class="col-sm-9">
                        <span class="text-muted">
                            {{ $article->created_at->format('d/m/Y à H:i') }}
                            <small>({{ $article->created_at->diffForHumans() }})</small>
                        </span>
                    </div>
                </div>
                
                @if($article->updated_at != $article->created_at)
                <div class="row mb-3">
                    <div class="col-sm-3">
                        <strong><i class="fas fa-edit me-1"></i>Modifié le :</strong>
                    </div>
                    <div class="col-sm-9">
                        <span class="text-muted">
                            {{ $article->updated_at->format('d/m/Y à H:i') }}
                            <small>({{ $article->updated_at->diffForHumans() }})</small>
                        </span>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Article Timeline -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0">
                    <i class="fas fa-history me-2"></i>
                    Historique de l'article
                </h6>
                <span class="badge bg-secondary">{{ Str::length($article->content) }} caractères</span>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-marker bg-primary"></div>
                        <div class="timeline-content">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1">
                                        <i class="fas fa-plus-circle me-1"></i>Article créé
                                    </h6>
                                    <p class="text-muted mb-0">{{ $article->created_at->format('d/m/Y à H:i') }}</p>
                                </div>
                                <small class="text-muted">{{ $article->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    </div>
                    
                    @if($article->updated_at != $article->created_at)
                    <div class="timeline-item">
                        <div class="timeline-marker bg-warning"></div>
                        <div class="timeline-content">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1">
                                        <i class="fas fa-edit me-1"></i>Dernière modification
                                    </h6>
                                    <p class="text-muted mb-0">{{ $article->updated_at->format('d/m/Y à H:i') }}</p>
                                </div>
                                <small class="text-muted">{{ $article->updated_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <div class="timeline-item">
                        <div class="timeline-marker bg-info"></div>
                        <div class="timeline-content">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1">
                                        <i class="fas fa-file-text me-1"></i>Contenu
                                    </h6>
                                    <p class="text-muted mb-0">{{ Str::length($article->content) }} caractères</p>
                                </div>
                                <span class="badge bg-info">{{ Str::length($article->content) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Actions -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-cogs me-2"></i>
                    Actions disponibles
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('admin.articles.edit', $article) }}" class="btn btn-warning">
                                <i class="fas fa-edit me-2"></i>Modifier l'article
                            </a>
                            <button class="btn btn-info" onclick="duplicateArticle({{ $article->id }})">
                                <i class="fas fa-copy me-2"></i>Dupliquer
                            </button>
                            <button class="btn btn-danger" onclick="deleteArticle({{ $article->id }})">
                                <i class="fas fa-trash me-2"></i>Supprimer
                            </button>
                            <a href="{{ route('admin.articles') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Retour à la liste
                            </a>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-end">
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Article #{{ $article->id }} • 
                                {{ $article->created_at->diffInDays(now()) }} jours actif
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: linear-gradient(to bottom, #007bff, #28a745);
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -22px;
    top: 5px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid #fff;
    box-shadow: 0 0 0 2px #dee2e6;
    z-index: 1;
}

.timeline-content {
    background: #f8f9fc;
    padding: 15px;
    border-radius: 8px;
    border-left: 3px solid #007bff;
    transition: all 0.3s ease;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.timeline-content:hover {
    transform: translateX(5px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

.badge {
    font-size: 0.75rem;
    padding: 0.5rem 0.75rem;
}

.bg-warning.bg-opacity-10 {
    background-color: rgba(255, 193, 7, 0.1) !important;
}

.bg-success.bg-opacity-10 {
    background-color: rgba(40, 167, 69, 0.1) !important;
}

.border-warning {
    border-color: #ffc107 !important;
}

.border-success {
    border-color: #28a745 !important;
}

.card {
    border: none;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 2rem 0 rgba(58, 59, 69, 0.2);
}

.btn {
    border-radius: 0.5rem;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}
</style>
