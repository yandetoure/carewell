@extends('layouts.admin')

@section('title', 'Modifier le Patient - Admin')
@section('page-title', 'Modifier le Patient')
@section('page-subtitle', 'Modifier les informations du patient')
@section('user-role', 'Administrateur')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user-edit me-2"></i>
                        Modifier le Patient : {{ $patient->name }}
                    </h5>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Erreurs détectées :</strong>
                            <ul class="mb-0 mt-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('admin.patients.update', $patient) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Informations personnelles -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-user me-2"></i>Informations Personnelles
                                </h6>
                            </div>
                        </div>

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
                                           value="{{ old('first_name', $patient->first_name) }}" 
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
                                           value="{{ old('last_name', $patient->last_name) }}" 
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
                                        Email *
                                    </label>
                                    <input type="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email', $patient->email) }}" 
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
                                        Téléphone
                                    </label>
                                    <input type="tel" 
                                           class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" 
                                           name="phone" 
                                           value="{{ old('phone', $patient->phone) }}" 
                                           placeholder="+33 1 23 45 67 89">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="day_of_birth" class="form-label">
                                        <i class="fas fa-calendar me-1"></i>
                                        Date de naissance
                                    </label>
                                    <input type="date" 
                                           class="form-control @error('day_of_birth') is-invalid @enderror" 
                                           id="day_of_birth" 
                                           name="day_of_birth" 
                                           value="{{ old('day_of_birth', $patient->day_of_birth) }}">
                                    @error('day_of_birth')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="adress" class="form-label">
                                        <i class="fas fa-map-marker-alt me-1"></i>
                                        Adresse
                                    </label>
                                    <textarea class="form-control @error('adress') is-invalid @enderror" 
                                              id="adress" 
                                              name="adress" 
                                              rows="3" 
                                              placeholder="123 Rue de la Paix, 75001 Paris">{{ old('adress', $patient->adress) }}</textarea>
                                    @error('adress')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Photo de profil -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-camera me-2"></i>Photo de Profil
                                </h6>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                @if($patient->photo)
                                    <div class="mb-3">
                                        <label class="form-label">Photo actuelle :</label>
                                        <div class="d-flex align-items-center gap-3">
                                            <img src="{{ asset('storage/' . $patient->photo) }}"
                                                 alt="Photo actuelle"
                                                 class="img-thumbnail"
                                                 style="max-width: 150px; max-height: 150px; object-fit: cover;">
                                            <div>
                                                <small class="text-muted d-block">Photo actuelle</small>
                                                <a href="{{ asset('storage/' . $patient->photo) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-external-link-alt me-1"></i>Voir
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="photo" class="form-label">
                                        <i class="fas fa-upload me-1"></i>
                                        {{ $patient->photo ? 'Nouvelle photo' : 'Photo de profil' }}
                                    </label>
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
                                </div>
                            </div>
                        </div>

                        <!-- Aperçu de la nouvelle image -->
                        <div id="imagePreview" class="row mb-4" style="display: none;">
                            <div class="col-12">
                                <label class="form-label">Aperçu de la nouvelle photo :</label>
                                <div class="d-flex align-items-center gap-3">
                                    <img id="previewImg" src="" alt="Aperçu" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                                    <div>
                                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="removePreview()">
                                            <i class="fas fa-trash me-1"></i>Supprimer
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Informations système (lecture seule) -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-secondary mb-3">
                                    <i class="fas fa-info-circle me-2"></i>Informations Système
                                </h6>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">
                                        <i class="fas fa-id-card me-1"></i>
                                        Numéro d'identification
                                    </label>
                                    <input type="text" 
                                           class="form-control" 
                                           value="{{ $patient->identification_number }}" 
                                           readonly>
                                    <div class="form-text">Généré automatiquement</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">
                                        <i class="fas fa-calendar-plus me-1"></i>
                                        Date d'inscription
                                    </label>
                                    <input type="text" 
                                           class="form-control" 
                                           value="{{ $patient->created_at->format('d/m/Y à H:i') }}" 
                                           readonly>
                                </div>
                            </div>
                        </div>

                        <!-- Boutons d'action -->
                        <div class="row">
                            <div class="col-12">
                                <hr>
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('admin.patients.show', $patient) }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left me-2"></i>Annuler
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Enregistrer les modifications
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
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

// Validation côté client
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const firstName = document.getElementById('first_name');
    const lastName = document.getElementById('last_name');
    const email = document.getElementById('email');
    
    form.addEventListener('submit', function(e) {
        let isValid = true;
        
        // Validation du prénom
        if (firstName.value.trim().length < 2) {
            showFieldError(firstName, 'Le prénom doit contenir au moins 2 caractères');
            isValid = false;
        } else {
            clearFieldError(firstName);
        }
        
        // Validation du nom
        if (lastName.value.trim().length < 2) {
            showFieldError(lastName, 'Le nom doit contenir au moins 2 caractères');
            isValid = false;
        } else {
            clearFieldError(lastName);
        }
        
        // Validation de l'email
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email.value)) {
            showFieldError(email, 'Veuillez entrer une adresse email valide');
            isValid = false;
        } else {
            clearFieldError(email);
        }
        
        if (!isValid) {
            e.preventDefault();
        }
    });
    
    function showFieldError(field, message) {
        field.classList.add('is-invalid');
        let feedback = field.parentNode.querySelector('.invalid-feedback');
        if (!feedback) {
            feedback = document.createElement('div');
            feedback.className = 'invalid-feedback';
            field.parentNode.appendChild(feedback);
        }
        feedback.textContent = message;
    }
    
    function clearFieldError(field) {
        field.classList.remove('is-invalid');
        const feedback = field.parentNode.querySelector('.invalid-feedback');
        if (feedback) {
            feedback.remove();
        }
    }
});
</script>
@endsection
