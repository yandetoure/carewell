@extends('layouts.dashboard')

@section('title', 'Modifier le Service - Admin')
@section('page-title', 'Modifier le service')
@section('page-subtitle', 'Modifier les informations du service médical')
@section('user-role', 'Administrateur')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-edit me-2"></i>
                        Modifier le service : {{ $service->name }}
                    </h5>
                    <a href="{{ route('admin.services') }}" class="btn btn-outline-secondary">
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

                    <!-- Current Service Info -->
                    <div class="card bg-light mb-4">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-3">
                                    @if($service->photo)
                                        <img src="{{ asset('storage/' . $service->photo) }}" 
                                             alt="{{ $service->name }}" 
                                             class="img-thumbnail" 
                                             style="max-width: 100px; max-height: 100px; object-fit: cover;">
                                    @else
                                        <div class="bg-secondary rounded d-flex align-items-center justify-content-center" 
                                             style="width: 100px; height: 100px;">
                                            <i class="fas fa-stethoscope text-white fa-2x"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-9">
                                    <h6 class="mb-1">{{ $service->name }}</h6>
                                    <p class="text-muted mb-1">{{ Str::limit($service->description, 100) }}</p>
                                    <div class="d-flex gap-3">
                                        <small class="text-success">
                                            <i class="fas fa-euro-sign me-1"></i>{{ number_format($service->price, 2) }}€
                                        </small>
                                        <small class="text-muted">
                                            <i class="fas fa-calendar me-1"></i>Créé le {{ $service->created_at->format('d/m/Y') }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('admin.services.update', $service) }}" method="POST" enctype="multipart/form-data" id="serviceForm">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-4">
                                    <label for="name" class="form-label">
                                        <i class="fas fa-stethoscope me-1"></i>
                                        Nom du service *
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name', $service->name) }}" 
                                           placeholder="Ex: Consultation générale, Échographie cardiaque..."
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-4">
                                    <label for="price" class="form-label">
                                        <i class="fas fa-euro-sign me-1"></i>
                                        Prix (€) *
                                    </label>
                                    <input type="number" 
                                           class="form-control @error('price') is-invalid @enderror" 
                                           id="price" 
                                           name="price" 
                                           value="{{ old('price', $service->price) }}" 
                                           step="0.01" 
                                           min="0"
                                           placeholder="0.00"
                                           required>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label">
                                <i class="fas fa-align-left me-1"></i>
                                Description du service *
                            </label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="5" 
                                      placeholder="Décrivez en détail ce que comprend ce service, les examens effectués, la durée, etc."
                                      required>{{ old('description', $service->description) }}</textarea>
                            <div class="form-text">Maximum 1000 caractères</div>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="photo" class="form-label">
                                <i class="fas fa-image me-1"></i>
                                Photo du service
                            </label>
                            
                            <!-- Current photo display -->
                            @if($service->photo)
                                <div class="mb-3">
                                    <label class="form-label">Photo actuelle :</label>
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="{{ asset('storage/' . $service->photo) }}" 
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

                        <!-- Service Details -->
                        <div class="card bg-light">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Informations supplémentaires
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="duration" class="form-label">Durée estimée (minutes)</label>
                                            <input type="number" 
                                                   class="form-control" 
                                                   id="duration" 
                                                   name="duration" 
                                                   value="{{ old('duration', $service->duration ?? 30) }}" 
                                                   min="1" 
                                                   max="480"
                                                   placeholder="30">
                                            <div class="form-text">Temps approximatif pour ce service</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="category" class="form-label">Catégorie</label>
                                            <select class="form-select" id="category" name="category">
                                                <option value="">Sélectionner une catégorie</option>
                                                <option value="consultation" {{ old('category', $service->category) == 'consultation' ? 'selected' : '' }}>Consultation</option>
                                                <option value="examen" {{ old('category', $service->category) == 'examen' ? 'selected' : '' }}>Examen</option>
                                                <option value="chirurgie" {{ old('category', $service->category) == 'chirurgie' ? 'selected' : '' }}>Chirurgie</option>
                                                <option value="urgence" {{ old('category', $service->category) == 'urgence' ? 'selected' : '' }}>Urgence</option>
                                                <option value="suivi" {{ old('category', $service->category) == 'suivi' ? 'selected' : '' }}>Suivi</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="requirements" class="form-label">Prérequis / Préparation</label>
                                    <textarea class="form-control" 
                                              id="requirements" 
                                              name="requirements" 
                                              rows="3" 
                                              placeholder="Ex: À jeun, apporter les examens précédents, etc.">{{ old('requirements', $service->requirements) }}</textarea>
                                    <div class="form-text">Instructions pour le patient avant le service</div>
                                </div>
                            </div>
                        </div>

                        <!-- Service Statistics -->
                        <div class="card bg-info text-white mt-4">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="fas fa-chart-bar me-2"></i>
                                    Statistiques du service
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row text-center">
                                    <div class="col-md-3">
                                        <h4 class="mb-1">{{ $service->appointments_count ?? 0 }}</h4>
                                        <small>Rendez-vous</small>
                                    </div>
                                    <div class="col-md-3">
                                        <h4 class="mb-1">{{ $service->created_at->diffInDays(now()) }}</h4>
                                        <small>Jours actif</small>
                                    </div>
                                    <div class="col-md-3">
                                        <h4 class="mb-1">{{ number_format(($service->appointments_count ?? 0) * $service->price, 2) }}€</h4>
                                        <small>Chiffre d'affaires</small>
                                    </div>
                                    <div class="col-md-3">
                                        <h4 class="mb-1">{{ $service->updated_at->diffForHumans() }}</h4>
                                        <small>Dernière modification</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="d-flex justify-content-between mt-4">
                            <div>
                                <a href="{{ route('admin.services') }}" class="btn btn-outline-secondary me-2">
                                    <i class="fas fa-times me-2"></i>Annuler
                                </a>
                                <button type="button" class="btn btn-outline-danger" onclick="deleteService()">
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
                <p>Êtes-vous sûr de vouloir supprimer le service <strong>"{{ $service->name }}"</strong> ?</p>
                <p class="text-muted">Tous les rendez-vous associés à ce service seront également affectés.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form action="{{ route('admin.services.destroy', $service) }}" method="POST" class="d-inline">
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

function deleteService() {
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

// Form validation
document.getElementById('serviceForm').addEventListener('submit', function(e) {
    const submitBtn = document.getElementById('submitBtn');
    const name = document.getElementById('name').value.trim();
    const price = document.getElementById('price').value;
    const description = document.getElementById('description').value.trim();
    
    if (!name || !price || !description) {
        e.preventDefault();
        alert('Veuillez remplir tous les champs obligatoires.');
        return;
    }
    
    if (parseFloat(price) < 0) {
        e.preventDefault();
        alert('Le prix ne peut pas être négatif.');
        return;
    }
    
    if (description.length > 1000) {
        e.preventDefault();
        alert('La description ne peut pas dépasser 1000 caractères.');
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
</style>
@endsection
