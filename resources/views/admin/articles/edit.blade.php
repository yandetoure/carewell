@extends('layouts.dashboard')

@section('title', 'Modifier l\'Article - Admin')
@section('page-title', 'Modifier l\'article')
@section('page-subtitle', 'Modifier les informations de l\'article de santé')
@section('user-role', 'Administrateur')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-edit me-2"></i>
                        Modifier l'article : {{ $article->title }}
                    </h5>
                    <a href="{{ route('admin.articles') }}" class="btn btn-outline-secondary">
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

                    <!-- Current Article Info -->
                    <div class="card bg-light mb-4">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-3">
                                    @if($article->photo)
                                        <img src="{{ asset('storage/' . $article->photo) }}" 
                                             alt="{{ $article->title }}" 
                                             class="img-thumbnail" 
                                             style="max-width: 100px; max-height: 100px; object-fit: cover;">
                                    @else
                                        <div class="bg-secondary rounded d-flex align-items-center justify-content-center" 
                                             style="width: 100px; height: 100px;">
                                            <i class="fas fa-newspaper text-white fa-2x"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-9">
                                    <h6 class="mb-1">{{ $article->title }}</h6>
                                    <p class="text-muted mb-1">{{ Str::limit($article->content, 100) }}</p>
                                    <div class="d-flex gap-3">
                                        <small class="text-info">
                                            <i class="fas fa-calendar me-1"></i>Créé le {{ $article->created_at->format('d/m/Y') }}
                                        </small>
                                        <small class="text-muted">
                                            <i class="fas fa-edit me-1"></i>{{ $article->updated_at->diffForHumans() }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('admin.articles.update', $article) }}" method="POST" enctype="multipart/form-data" id="articleForm">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <label for="title" class="form-label">
                                <i class="fas fa-heading me-1"></i>
                                Titre de l'article *
                            </label>
                            <input type="text" 
                                   class="form-control @error('title') is-invalid @enderror" 
                                   id="title" 
                                   name="title" 
                                   value="{{ old('title', $article->title) }}" 
                                   placeholder="Ex: Prévention des maladies cardiovasculaires..."
                                   required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="content" class="form-label">
                                <i class="fas fa-align-left me-1"></i>
                                Contenu de l'article *
                            </label>
                            <textarea class="form-control @error('content') is-invalid @enderror" 
                                      id="content" 
                                      name="content" 
                                      rows="8" 
                                      placeholder="Rédigez le contenu principal de votre article de santé..."
                                      required>{{ old('content', $article->content) }}</textarea>
                            <div class="form-text">Maximum 10000 caractères</div>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="symptoms" class="form-label">
                                        <i class="fas fa-exclamation-triangle me-1"></i>
                                        Symptômes
                                    </label>
                                    <textarea class="form-control @error('symptoms') is-invalid @enderror" 
                                              id="symptoms" 
                                              name="symptoms" 
                                              rows="4" 
                                              placeholder="Décrivez les symptômes à surveiller...">{{ old('symptoms', $article->symptoms) }}</textarea>
                                    <div class="form-text">Maximum 1000 caractères</div>
                                    @error('symptoms')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="advices" class="form-label">
                                        <i class="fas fa-lightbulb me-1"></i>
                                        Conseils
                                    </label>
                                    <textarea class="form-control @error('advices') is-invalid @enderror" 
                                              id="advices" 
                                              name="advices" 
                                              rows="4" 
                                              placeholder="Donnez des conseils pratiques...">{{ old('advices', $article->advices) }}</textarea>
                                    <div class="form-text">Maximum 1000 caractères</div>
                                    @error('advices')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="photo" class="form-label">
                                <i class="fas fa-image me-1"></i>
                                Photo de l'article
                            </label>
                            
                            <!-- Current photo display -->
                            @if($article->photo)
                                <div class="mb-3">
                                    <label class="form-label">Photo actuelle :</label>
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="{{ asset('storage/' . $article->photo) }}" 
                                             alt="Photo actuelle" 
                                             class="img-thumbnail" 
                                             style="max-width: 150px; max-height: 150px; object-fit: cover;">
                                        <div>
                                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeCurrentPhoto()">
                                                <i class="fas fa-trash me-1"></i>Supprimer la photo
                                            </button>
                                        </div>
                                    </div>
                                    <input type="hidden" id="removePhoto" name="remove_photo" value="0">
                                </div>
                            @endif
                            
                            <input type="file" 
                                   class="form-control @error('photo') is-invalid @enderror" 
                                   id="photo" 
                                   name="photo" 
                                   accept="image/*"
                                   onchange="previewImage(this)">
                            <div class="form-text">Formats acceptés: JPEG, PNG, JPG, GIF. Taille max: 2MB</div>
                            @error('photo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            
                            <!-- Image preview -->
                            <div id="imagePreview" class="mt-3" style="display: none;">
                                <label class="form-label">Nouvelle photo :</label>
                                <img id="previewImg" src="" alt="Aperçu" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                                <div class="mt-2">
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removePreview()">
                                        <i class="fas fa-trash me-1"></i>Supprimer
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Article Statistics -->
                        <div class="card bg-info text-white mt-4">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="fas fa-chart-bar me-2"></i>
                                    Statistiques de l'article
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row text-center">
                                    <div class="col-md-3">
                                        <h4 class="mb-1">{{ Str::length($article->content) }}</h4>
                                        <small>Caractères</small>
                                    </div>
                                    <div class="col-md-3">
                                        <h4 class="mb-1">{{ $article->created_at->diffInDays(now()) }}</h4>
                                        <small>Jours actif</small>
                                    </div>
                                    <div class="col-md-3">
                                        <h4 class="mb-1">{{ $article->updated_at != $article->created_at ? 'Oui' : 'Non' }}</h4>
                                        <small>Modifié</small>
                                    </div>
                                    <div class="col-md-3">
                                        <h4 class="mb-1">{{ $article->photo ? 'Oui' : 'Non' }}</h4>
                                        <small>Photo</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="d-flex justify-content-between mt-4">
                            <div>
                                <a href="{{ route('admin.articles') }}" class="btn btn-outline-secondary me-2">
                                    <i class="fas fa-times me-2"></i>Annuler
                                </a>
                                <button type="button" class="btn btn-outline-danger" onclick="deleteArticle()">
                                    <i class="fas fa-trash me-2"></i>Supprimer
                                </button>
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

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmer la suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Attention !</strong> Cette action est irréversible.
                </div>
                <p>Êtes-vous sûr de vouloir supprimer l'article <strong>"{{ $article->title }}"</strong> ?</p>
                <p class="text-muted">Toutes les données associées à cet article seront perdues.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form action="{{ route('admin.articles.destroy', $article) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-2"></i>Supprimer définitivement
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            document.getElementById('previewImg').src = e.target.result;
            document.getElementById('imagePreview').style.display = 'block';
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}

function removePreview() {
    document.getElementById('photo').value = '';
    document.getElementById('imagePreview').style.display = 'none';
    document.getElementById('previewImg').src = '';
}

function removeCurrentPhoto() {
    if (confirm('Supprimer la photo actuelle ?')) {
        document.getElementById('removePhoto').value = '1';
        document.querySelector('.img-thumbnail').style.opacity = '0.5';
        document.querySelector('.btn-outline-danger').innerHTML = '<i class="fas fa-undo me-1"></i>Restaurer';
        document.querySelector('.btn-outline-danger').onclick = function() {
            document.getElementById('removePhoto').value = '0';
            document.querySelector('.img-thumbnail').style.opacity = '1';
            this.innerHTML = '<i class="fas fa-trash me-1"></i>Supprimer la photo';
            this.onclick = removeCurrentPhoto;
        };
    }
}

function deleteArticle() {
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

// Form validation
document.getElementById('articleForm').addEventListener('submit', function(e) {
    const submitBtn = document.getElementById('submitBtn');
    const title = document.getElementById('title').value.trim();
    const content = document.getElementById('content').value.trim();
    
    if (!title || !content) {
        e.preventDefault();
        alert('Veuillez remplir tous les champs obligatoires.');
        return;
    }
    
    if (title.length > 255) {
        e.preventDefault();
        alert('Le titre ne peut pas dépasser 255 caractères.');
        return;
    }
    
    if (content.length > 10000) {
        e.preventDefault();
        alert('Le contenu ne peut pas dépasser 10000 caractères.');
        return;
    }
    
    const symptoms = document.getElementById('symptoms').value.trim();
    const advices = document.getElementById('advices').value.trim();
    
    if (symptoms.length > 1000) {
        e.preventDefault();
        alert('Les symptômes ne peuvent pas dépasser 1000 caractères.');
        return;
    }
    
    if (advices.length > 1000) {
        e.preventDefault();
        alert('Les conseils ne peuvent pas dépasser 1000 caractères.');
        return;
    }
    
    // Show loading state
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Mise à jour en cours...';
    submitBtn.disabled = true;
});

// Character counters
function setupCharacterCounter(textareaId, maxLength, counterId) {
    const textarea = document.getElementById(textareaId);
    const counter = document.getElementById(counterId) || createCounter(textarea, counterId);
    
    textarea.addEventListener('input', function() {
        const length = this.value.length;
        const remaining = maxLength - length;
        
        counter.textContent = `${remaining} caractères restants`;
        
        if (remaining < 50) {
            counter.className = 'form-text text-end text-warning';
        } else if (remaining < 0) {
            counter.className = 'form-text text-end text-danger';
        } else {
            counter.className = 'form-text text-end text-muted';
        }
    });
}

function createCounter(textarea, counterId) {
    const counter = document.createElement('div');
    counter.id = counterId;
    counter.className = 'form-text text-end text-muted';
    textarea.parentNode.appendChild(counter);
    return counter;
}

// Setup character counters
setupCharacterCounter('title', 255, 'titleCounter');
setupCharacterCounter('content', 10000, 'contentCounter');
setupCharacterCounter('symptoms', 1000, 'symptomsCounter');
setupCharacterCounter('advices', 1000, 'advicesCounter');
</script>

<style>
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

.img-thumbnail {
    border: 2px solid #dee2e6;
    border-radius: 0.375rem;
}

.is-invalid {
    border-color: #dc3545;
}

.invalid-feedback {
    display: block;
}

.bg-info {
    background-color: var(--bs-info) !important;
}
</style>
@endsection
