@extends('layouts.admin')

@section('title', 'Gestion des Articles - Admin')
@section('page-title', 'Gestion des Articles')
@section('page-subtitle', 'Gérer les articles de santé et conseils médicaux')
@section('user-role', 'Administrateur')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-newspaper me-2"></i>
                        Articles de santé
                    </h5>
                    <a href="{{ route('admin.articles.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Nouvel Article
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Filtres et recherche -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <input type="text" class="form-control" id="searchArticle" placeholder="Rechercher un article...">
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="sortBy">
                                <option value="latest">Plus récents</option>
                                <option value="oldest">Plus anciens</option>
                                <option value="title">Titre A-Z</option>
                                <option value="title_desc">Titre Z-A</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-outline-secondary w-100" onclick="resetFilters()">
                                <i class="fas fa-undo me-1"></i>Réinitialiser
                            </button>
                        </div>
                    </div>

                    <!-- Statistiques rapides -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center">
                                    <h4 class="mb-1">{{ $articles->total() }}</h4>
                                    <small>Total des articles</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h4 class="mb-1">{{ $articles->where('created_at', '>=', now()->subDays(7))->count() }}</h4>
                                    <small>Cette semaine</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <h4 class="mb-1">{{ $articles->where('created_at', '>=', now()->subMonth())->count() }}</h4>
                                    <small>Ce mois</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body text-center">
                                    <h4 class="mb-1">{{ $articles->whereNotNull('photo')->count() }}</h4>
                                    <small>Avec photos</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover" id="articlesTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Photo</th>
                                    <th>Titre</th>
                                    <th>Contenu</th>
                                    <th>Symptômes</th>
                                    <th>Conseils</th>
                                    <th>Date de création</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($articles as $article)
                                <tr>
                                    <td>
                                        @if($article->photo)
                                            <img src="{{ asset('storage/' . $article->photo) }}" 
                                                 alt="{{ $article->title }}" 
                                                 class="rounded" 
                                                 style="width: 60px; height: 60px; object-fit: cover;">
                                        @else
                                            <div class="bg-secondary rounded d-flex align-items-center justify-content-center" 
                                                 style="width: 60px; height: 60px;">
                                                <i class="fas fa-newspaper text-white"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ Str::limit($article->title, 50) }}</div>
                                        <small class="text-muted">{{ $article->created_at->format('d/m/Y') }}</small>
                                    </td>
                                    <td>
                                        <div class="text-truncate" style="max-width: 200px;" title="{{ $article->content }}">
                                            {{ Str::limit($article->content, 80) }}
                                        </div>
                                    </td>
                                    <td>
                                        @if($article->symptoms)
                                            <div class="text-truncate" style="max-width: 150px;" title="{{ $article->symptoms }}">
                                                {{ Str::limit($article->symptoms, 50) }}
                                            </div>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($article->advices)
                                            <div class="text-truncate" style="max-width: 150px;" title="{{ $article->advices }}">
                                                {{ Str::limit($article->advices, 50) }}
                                            </div>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div>{{ $article->created_at->format('d/m/Y') }}</div>
                                        <small class="text-muted">{{ $article->created_at->diffForHumans() }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button class="btn btn-outline-primary" 
                                                    onclick="viewArticle({{ $article->id }})" 
                                                    title="Voir les détails">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <a href="{{ route('admin.articles.edit', $article) }}" 
                                               class="btn btn-outline-warning" 
                                               title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button class="btn btn-outline-info" 
                                                    onclick="duplicateArticle({{ $article->id }})" 
                                                    title="Dupliquer">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                            <button class="btn btn-outline-danger" 
                                                    onclick="deleteArticle({{ $article->id }})" 
                                                    title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-5">
                                        <i class="fas fa-newspaper fa-3x mb-3"></i>
                                        <h5>Aucun article trouvé</h5>
                                        <p>Commencez par créer votre premier article de santé.</p>
                                        <a href="{{ route('admin.articles.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus me-2"></i>Créer un article
                                        </a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($articles->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $articles->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- View Article Modal -->
<div class="modal fade" id="viewArticleModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Détails de l'article</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="articleDetails">
                <!-- Content will be loaded via AJAX -->
                <div class="text-center">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Chargement...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<!-- Duplicate Article Modal -->
<div class="modal fade" id="duplicateArticleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Dupliquer l'article</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="duplicateArticleForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="duplicate_title" class="form-label">Titre du nouvel article *</label>
                        <input type="text" class="form-control" id="duplicate_title" name="title" required>
                    </div>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Information :</strong> Le nouvel article sera créé avec les mêmes détails que l'original.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Dupliquer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Recherche et filtres
document.getElementById('searchArticle').addEventListener('input', filterArticles);
document.getElementById('sortBy').addEventListener('change', filterArticles);

function filterArticles() {
    const searchTerm = document.getElementById('searchArticle').value.toLowerCase();
    const sortBy = document.getElementById('sortBy').value;
    
    const rows = document.querySelectorAll('#articlesTable tbody tr');
    
    rows.forEach(row => {
        const title = row.cells[1].textContent.toLowerCase();
        const content = row.cells[2].textContent.toLowerCase();
        const symptoms = row.cells[3].textContent.toLowerCase();
        const advices = row.cells[4].textContent.toLowerCase();
        
        const matchesSearch = title.includes(searchTerm) || 
                            content.includes(searchTerm) || 
                            symptoms.includes(searchTerm) || 
                            advices.includes(searchTerm);
        
        row.style.display = matchesSearch ? '' : 'none';
    });
}

function resetFilters() {
    document.getElementById('searchArticle').value = '';
    document.getElementById('sortBy').value = 'latest';
    filterArticles();
}

function viewArticle(articleId) {
    const modal = new bootstrap.Modal(document.getElementById('viewArticleModal'));
    const detailsContainer = document.getElementById('articleDetails');
    
    // Show loading
    detailsContainer.innerHTML = `
        <div class="text-center">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Chargement...</span>
            </div>
        </div>
    `;
    
    modal.show();
    
    // Load article details via AJAX
    fetch(`/admin/articles/${articleId}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`Erreur ${response.status}: ${response.statusText}`);
        }
        return response.text();
    })
    .then(html => {
        detailsContainer.innerHTML = html;
    })
    .catch(error => {
        console.error('Erreur:', error);
        detailsContainer.innerHTML = `
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Erreur lors du chargement des détails de l'article</strong><br>
                <small>${error.message}</small>
                <div class="mt-2">
                    <button class="btn btn-sm btn-outline-danger" onclick="location.reload()">
                        <i class="fas fa-refresh me-1"></i>Recharger la page
                    </button>
                </div>
            </div>
        `;
    });
}

function duplicateArticle(articleId) {
    const modal = new bootstrap.Modal(document.getElementById('duplicateArticleModal'));
    const form = document.getElementById('duplicateArticleForm');
    form.action = `/admin/articles/${articleId}/duplicate`;
    modal.show();
}

function deleteArticle(articleId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cet article ? Cette action est irréversible.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/articles/${articleId}`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        
        form.appendChild(csrfToken);
        form.appendChild(methodField);
        document.body.appendChild(form);
        form.submit();
    }
}

// Auto-refresh every 30 seconds
setInterval(() => {
    if (!document.hidden) {
        location.reload();
    }
}, 30000);
</script>

<style>
.card {
    border: 1px solid #e3e6f0;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
}

.table th {
    border-top: none;
    font-weight: 600;
    color: #5a5c69;
    background-color: #f8f9fc;
}

.btn-group-sm > .btn, .btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}

.text-truncate {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.bg-primary, .bg-success, .bg-info, .bg-warning {
    background-color: var(--bs-primary) !important;
}

.bg-success {
    background-color: var(--bs-success) !important;
}

.bg-info {
    background-color: var(--bs-info) !important;
}

.bg-warning {
    background-color: var(--bs-warning) !important;
}
</style>
@endsection
