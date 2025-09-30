@extends('layouts.admin')

@section('title', 'Modifier le Médecin')

@section('content')
<div class="container-fluid">
    <!-- En-tête de la page -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-user-edit me-2"></i>
                        Modifier {{ $doctor->name }}
                    </h1>
                    <p class="text-muted mb-0">Modifiez les informations du médecin</p>
                </div>
                <div>
                    <a href="{{ route('admin.doctors.show', $doctor) }}" class="btn btn-outline-primary">
                        <i class="fas fa-eye me-2"></i>
                        Voir
                    </a>
                    <a href="{{ route('admin.doctors') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>
                        Retour
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulaire de modification -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user-md me-2"></i>
                        Informations du Médecin
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.doctors.update', $doctor) }}" method="POST" enctype="multipart/form-data" id="doctorForm">
                        @csrf
                        @method('PUT')
                        
                        <!-- Informations personnelles -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="first_name" class="form-label">
                                        <i class="fas fa-user me-1"></i>
                                        Prénom *
                                    </label>
                                    <input type="text"
                                           class="form-control @error('first_name') is-invalid @enderror"
                                           id="first_name"
                                           name="first_name"
                                           value="{{ old('first_name', $doctor->first_name) }}"
                                           placeholder="Jean"
                                           required>
                                    @error('first_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="last_name" class="form-label">
                                        <i class="fas fa-user me-1"></i>
                                        Nom de famille *
                                    </label>
                                    <input type="text"
                                           class="form-control @error('last_name') is-invalid @enderror"
                                           id="last_name"
                                           name="last_name"
                                           value="{{ old('last_name', $doctor->last_name) }}"
                                           placeholder="Dupont"
                                           required>
                                    @error('last_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">
                                        <i class="fas fa-envelope me-1"></i>
                                        Adresse email *
                                    </label>
                                    <input type="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           id="email"
                                           name="email"
                                           value="{{ old('email', $doctor->email) }}"
                                           placeholder="jean.dupont@example.com"
                                           required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone" class="form-label">
                                        <i class="fas fa-phone me-1"></i>
                                        Téléphone *
                                    </label>
                                    <input type="tel"
                                           class="form-control @error('phone') is-invalid @enderror"
                                           id="phone"
                                           name="phone"
                                           value="{{ old('phone', $doctor->phone) }}"
                                           placeholder="+237 6XX XXX XXX"
                                           required>
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="specialty" class="form-label">
                                        <i class="fas fa-stethoscope me-1"></i>
                                        Spécialité
                                    </label>
                                    <select class="form-select @error('specialty') is-invalid @enderror"
                                            id="specialty"
                                            name="specialty">
                                        <option value="">Sélectionner une spécialité</option>
                                        <option value="Médecine générale" {{ old('specialty', $doctor->specialty) == 'Médecine générale' ? 'selected' : '' }}>Médecine générale</option>
                                        <option value="Cardiologie" {{ old('specialty', $doctor->specialty) == 'Cardiologie' ? 'selected' : '' }}>Cardiologie</option>
                                        <option value="Dermatologie" {{ old('specialty', $doctor->specialty) == 'Dermatologie' ? 'selected' : '' }}>Dermatologie</option>
                                        <option value="Gynécologie" {{ old('specialty', $doctor->specialty) == 'Gynécologie' ? 'selected' : '' }}>Gynécologie</option>
                                        <option value="Pédiatrie" {{ old('specialty', $doctor->specialty) == 'Pédiatrie' ? 'selected' : '' }}>Pédiatrie</option>
                                        <option value="Neurologie" {{ old('specialty', $doctor->specialty) == 'Neurologie' ? 'selected' : '' }}>Neurologie</option>
                                        <option value="Orthopédie" {{ old('specialty', $doctor->specialty) == 'Orthopédie' ? 'selected' : '' }}>Orthopédie</option>
                                        <option value="Ophtalmologie" {{ old('specialty', $doctor->specialty) == 'Ophtalmologie' ? 'selected' : '' }}>Ophtalmologie</option>
                                        <option value="Psychiatrie" {{ old('specialty', $doctor->specialty) == 'Psychiatrie' ? 'selected' : '' }}>Psychiatrie</option>
                                        <option value="Radiologie" {{ old('specialty', $doctor->specialty) == 'Radiologie' ? 'selected' : '' }}>Radiologie</option>
                                        <option value="Autre" {{ old('specialty', $doctor->specialty) == 'Autre' ? 'selected' : '' }}>Autre</option>
                                    </select>
                                    @error('specialty')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">
                                        <i class="fas fa-toggle-on me-1"></i>
                                        Statut
                                    </label>
                                    <select class="form-select @error('status') is-invalid @enderror"
                                            id="status"
                                            name="status">
                                        <option value="active" {{ old('status', $doctor->status) == 'active' ? 'selected' : '' }}>Actif</option>
                                        <option value="inactive" {{ old('status', $doctor->status) == 'inactive' ? 'selected' : '' }}>Inactif</option>
                                        <option value="pending" {{ old('status', $doctor->status) == 'pending' ? 'selected' : '' }}>En attente</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Photo de profil -->
                        <div class="mb-3">
                            <label for="photo" class="form-label">
                                <i class="fas fa-image me-1"></i>
                                Photo de profil
                            </label>
                            @if($doctor->photo)
                                <div class="mb-3">
                                    <label class="form-label">Photo actuelle :</label>
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="{{ asset('storage/' . $doctor->photo) }}"
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

                        <!-- Description -->
                        <div class="mb-4">
                            <label for="description" class="form-label">
                                <i class="fas fa-align-left me-1"></i>
                                Description / Biographie
                            </label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description"
                                      name="description"
                                      rows="4"
                                      placeholder="Décrivez l'expérience et les compétences du médecin...">{{ old('description', $doctor->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Maximum 1000 caractères</div>
                        </div>

                        <!-- Boutons d'action -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.doctors.show', $doctor) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>
                                Annuler
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>
                                Mettre à jour
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Panneau d'aide -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Informations
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h6><i class="fas fa-lightbulb me-2"></i>Conseils</h6>
                        <ul class="mb-0">
                            <li>Les modifications sont immédiates</li>
                            <li>La photo doit être de bonne qualité</li>
                            <li>Le statut affecte la visibilité</li>
                            <li>L'email doit être unique</li>
                        </ul>
                    </div>

                    <div class="alert alert-warning">
                        <h6><i class="fas fa-exclamation-triangle me-2"></i>Important</h6>
                        <ul class="mb-0">
                            <li>Les champs marqués * sont obligatoires</li>
                            <li>Vérifiez les informations avant de sauvegarder</li>
                            <li>Les changements sont irréversibles</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Aperçu de l'image
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

// Supprimer l'aperçu
function removePreview() {
    document.getElementById('photo').value = '';
    document.getElementById('imagePreview').style.display = 'none';
    document.getElementById('previewImg').src = '';
}

// Supprimer la photo actuelle
function removeCurrentPhoto() {
    document.getElementById('removePhoto').value = '1';
    document.querySelector('.img-thumbnail').style.display = 'none';
    document.querySelector('.btn-outline-danger').style.display = 'none';
}

// Compteur de caractères pour la description
document.getElementById('description').addEventListener('input', function() {
    const maxLength = 1000;
    const currentLength = this.value.length;
    
    if (currentLength > maxLength) {
        this.value = this.value.substring(0, maxLength);
    }
});
</script>
@endsection
