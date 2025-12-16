@extends('layouts.admin')

@section('title', 'Modifier la Catégorie - Admin')
@section('page-title', 'Modifier la catégorie')
@section('page-subtitle', 'Modifier les informations de la catégorie')
@section('user-role', 'Administrateur')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-edit me-2"></i>
                        Modifier la catégorie : {{ $category->name }}
                    </h5>
                    <a href="{{ route('admin.categories') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Retour
                    </a>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Erreurs détectées :</strong>
                            <ul class="mb-0 mt-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Current Category Info -->
                    <div class="card bg-light mb-4">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-3 text-center">
                                    <div class="category-icon-preview mb-2">
                                        <i class="{{ $category->icon }} fa-3x text-{{ $category->color }}"></i>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <h6 class="mb-1">{{ $category->name }}</h6>
                                    <p class="text-muted mb-1">{{ Str::limit($category->description ?? 'Aucune description', 100) }}</p>
                                    <div class="d-flex gap-3">
                                        <small class="text-muted">
                                            <i class="fas fa-tag me-1"></i>{{ $category->slug }}
                                        </small>
                                        <small class="text-muted">
                                            <i class="fas fa-calendar me-1"></i>Créé le {{ $category->created_at->format('d/m/Y') }}
                                        </small>
                                        <small class="text-muted">
                                            <i class="fas fa-stethoscope me-1"></i>{{ $category->services->count() }} service{{ $category->services->count() > 1 ? 's' : '' }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('admin.categories.update', $category) }}" method="POST" id="categoryForm">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-4">
                                    <label for="name" class="form-label">
                                        <i class="fas fa-tag me-1"></i>
                                        Nom de la catégorie *
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name', $category->name) }}" 
                                           placeholder="Ex: Santé générale, Prévention..."
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-4">
                                    <label for="sort_order" class="form-label">
                                        <i class="fas fa-sort-numeric-down me-1"></i>
                                        Ordre d'affichage
                                    </label>
                                    <input type="number" 
                                           class="form-control @error('sort_order') is-invalid @enderror" 
                                           id="sort_order" 
                                           name="sort_order" 
                                           value="{{ old('sort_order', $category->sort_order) }}" 
                                           min="0"
                                           placeholder="0">
                                    @error('sort_order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label">
                                <i class="fas fa-align-left me-1"></i>
                                Description de la catégorie
                            </label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="4" 
                                      placeholder="Décrivez cette catégorie de services...">{{ old('description', $category->description) }}</textarea>
                            <div class="form-text">Maximum 1000 caractères</div>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="icon" class="form-label">
                                        <i class="fas fa-icons me-1"></i>
                                        Icône FontAwesome *
                                    </label>
                                    <select class="form-select @error('icon') is-invalid @enderror" 
                                            id="icon" 
                                            name="icon" 
                                            required>
                                        <option value="fas fa-heartbeat" {{ old('icon', $category->icon) == 'fas fa-heartbeat' ? 'selected' : '' }}>heartbeat</option>
                                        <option value="fas fa-shield-alt" {{ old('icon', $category->icon) == 'fas fa-shield-alt' ? 'selected' : '' }}>shield</option>
                                        <option value="fas fa-apple-alt" {{ old('icon', $category->icon) == 'fas fa-apple-alt' ? 'selected' : '' }}>apple</option>
                                        <option value="fas fa-dumbbell" {{ old('icon', $category->icon) == 'fas fa-dumbbell' ? 'selected' : '' }}>dumbbell</option>
                                        <option value="fas fa-brain" {{ old('icon', $category->icon) == 'fas fa-brain' ? 'selected' : '' }}>brain</option>
                                        <option value="fas fa-heart" {{ old('icon', $category->icon) == 'fas fa-heart' ? 'selected' : '' }}>heart</option>
                                        <option value="fas fa-child" {{ old('icon', $category->icon) == 'fas fa-child' ? 'selected' : '' }}>child</option>
                                        <option value="fas fa-female" {{ old('icon', $category->icon) == 'fas fa-female' ? 'selected' : '' }}>female</option>
                                        <option value="fas fa-bone" {{ old('icon', $category->icon) == 'fas fa-bone' ? 'selected' : '' }}>bone</option>
                                        <option value="fas fa-hand-holding-medical" {{ old('icon', $category->icon) == 'fas fa-hand-holding-medical' ? 'selected' : '' }}>hand-holding-medical</option>
                                        <option value="fas fa-stethoscope" {{ old('icon', $category->icon) == 'fas fa-stethoscope' ? 'selected' : '' }}>stethoscope</option>
                                        <option value="fas fa-user-md" {{ old('icon', $category->icon) == 'fas fa-user-md' ? 'selected' : '' }}>user-md</option>
                                        <option value="fas fa-ambulance" {{ old('icon', $category->icon) == 'fas fa-ambulance' ? 'selected' : '' }}>ambulance</option>
                                    </select>
                                    <div class="form-text">Icône qui sera affichée pour cette catégorie</div>
                                    @error('icon')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="color" class="form-label">
                                        <i class="fas fa-palette me-1"></i>
                                        Couleur Bootstrap *
                                    </label>
                                    <select class="form-select @error('color') is-invalid @enderror" 
                                            id="color" 
                                            name="color" 
                                            required>
                                        <option value="primary" {{ old('color', $category->color) == 'primary' ? 'selected' : '' }}>Bleu (primary)</option>
                                        <option value="success" {{ old('color', $category->color) == 'success' ? 'selected' : '' }}>Vert (success)</option>
                                        <option value="warning" {{ old('color', $category->color) == 'warning' ? 'selected' : '' }}>Orange (warning)</option>
                                        <option value="info" {{ old('color', $category->color) == 'info' ? 'selected' : '' }}>Cyan (info)</option>
                                        <option value="danger" {{ old('color', $category->color) == 'danger' ? 'selected' : '' }}>Rouge (danger)</option>
                                        <option value="secondary" {{ old('color', $category->color) == 'secondary' ? 'selected' : '' }}>Gris (secondary)</option>
                                        <option value="dark" {{ old('color', $category->color) == 'dark' ? 'selected' : '' }}>Noir (dark)</option>
                                    </select>
                                    <div class="form-text">Couleur utilisée pour les badges et icônes</div>
                                    @error('color')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Icon and Color Preview -->
                        <div class="card bg-light mb-4">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="fas fa-eye me-2"></i>
                                    Aperçu
                                </h6>
                            </div>
                            <div class="card-body text-center">
                                <div class="category-icon-preview-large mb-3">
                                    <i id="previewIcon" class="{{ $category->icon }} fa-4x text-{{ $category->color }}"></i>
                                </div>
                                <h5 id="previewName">{{ $category->name }}</h5>
                                <p class="text-muted" id="previewDescription">{{ $category->description ?? 'Aucune description' }}</p>
                                <span id="previewBadge" class="badge bg-{{ $category->color }} px-3 py-2">
                                    <i id="previewBadgeIcon" class="{{ $category->icon }} me-1"></i>
                                    Catégorie active
                                </span>
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="is_active" 
                                       name="is_active" 
                                       value="1"
                                       {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    <i class="fas fa-toggle-on me-1"></i>
                                    Catégorie active
                                </label>
                                <div class="form-text">Une catégorie inactive ne sera pas affichée dans les listes publiques</div>
                            </div>
                        </div>

                        <!-- Category Statistics -->
                        <div class="card bg-info text-white mt-4">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="fas fa-chart-bar me-2"></i>
                                    Statistiques de la catégorie
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row text-center">
                                    <div class="col-md-4">
                                        <h4 class="mb-1">{{ $category->services->count() }}</h4>
                                        <small>Services associés</small>
                                    </div>
                                    <div class="col-md-4">
                                        <h4 class="mb-1">{{ $category->created_at->diffInDays(now()) }}</h4>
                                        <small>Jours actif</small>
                                    </div>
                                    <div class="col-md-4">
                                        <h4 class="mb-1">{{ $category->updated_at->diffForHumans() }}</h4>
                                        <small>Dernière modification</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="d-flex justify-content-between mt-4">
                            <div>
                                <a href="{{ route('admin.categories') }}" class="btn btn-outline-secondary me-2">
                                    <i class="fas fa-times me-2"></i>Annuler
                                </a>
                                <a href="{{ route('admin.categories.show', $category) }}" class="btn btn-outline-info">
                                    <i class="fas fa-eye me-2"></i>Voir les détails
                                </a>
                            </div>
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="fas fa-save me-2"></i>Mettre à jour
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Update preview when icon or color changes
document.getElementById('icon').addEventListener('change', function() {
    const previewIcon = document.getElementById('previewIcon');
    const previewBadgeIcon = document.getElementById('previewBadgeIcon');
    const iconClass = this.value;
    previewIcon.className = iconClass + ' fa-4x text-' + document.getElementById('color').value;
    previewBadgeIcon.className = iconClass + ' me-1';
});

document.getElementById('color').addEventListener('change', function() {
    const color = this.value;
    const previewIcon = document.getElementById('previewIcon');
    const previewBadge = document.getElementById('previewBadge');
    previewIcon.className = document.getElementById('icon').value + ' fa-4x text-' + color;
    previewBadge.className = 'badge bg-' + color + ' px-3 py-2';
});

document.getElementById('name').addEventListener('input', function() {
    document.getElementById('previewName').textContent = this.value || 'Nom de la catégorie';
});

document.getElementById('description').addEventListener('input', function() {
    document.getElementById('previewDescription').textContent = this.value || 'Aucune description';
});

// Form validation
document.getElementById('categoryForm').addEventListener('submit', function(e) {
    const submitBtn = document.getElementById('submitBtn');
    const name = document.getElementById('name').value.trim();
    
    if (!name) {
        e.preventDefault();
        alert('Veuillez remplir tous les champs obligatoires.');
        return;
    }
    
    // Show loading state
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Mise à jour en cours...';
    submitBtn.disabled = true;
});

// Character counter for description
document.getElementById('description').addEventListener('input', function() {
    const length = this.value.length;
    const maxLength = 1000;
    const remaining = maxLength - length;
    
    let counter = document.getElementById('charCounter');
    if (!counter) {
        counter = document.createElement('div');
        counter.id = 'charCounter';
        counter.className = 'form-text text-end';
        this.parentNode.appendChild(counter);
    }
    
    counter.textContent = `${remaining} caractères restants`;
    
    if (remaining < 50) {
        counter.className = 'form-text text-end text-warning';
    } else if (remaining < 0) {
        counter.className = 'form-text text-end text-danger';
    } else {
        counter.className = 'form-text text-end text-muted';
    }
});
</script>

<style>
.category-icon-preview {
    width: 80px;
    height: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(0,0,0,0.05);
    border-radius: 50%;
    margin: 0 auto;
}

.category-icon-preview-large {
    width: 120px;
    height: 120px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(0,0,0,0.05);
    border-radius: 50%;
    margin: 0 auto;
}

.card {
    border: 1px solid #e3e6f0;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
}

.form-label {
    font-weight: 600;
    color: #5a5c69;
}

.bg-light {
    background-color: #f8f9fc !important;
}

.is-invalid {
    border-color: #dc3545;
}

.invalid-feedback {
    display: block;
}
</style>
@endsection


