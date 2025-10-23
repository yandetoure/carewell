@extends('layouts.secretary')

@section('title', 'Nouveau Patient - Secrétariat')
@section('page-title', 'Nouveau Patient')
@section('page-subtitle', 'Ajouter un nouveau patient au service')
@section('user-role', 'Secrétaire')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-user-plus me-2"></i>
                        Ajouter un nouveau patient
                    </h5>
                    <a href="{{ route('secretary.patients') }}" class="btn btn-outline-secondary">
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

                    <form action="{{ route('secretary.patients.store') }}" method="POST" enctype="multipart/form-data" id="patientForm">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="first_name" class="form-label">
                                        <i class="fas fa-user me-1"></i>
                                        Prénom *
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('first_name') is-invalid @enderror" 
                                           id="first_name" 
                                           name="first_name" 
                                           value="{{ old('first_name') }}" 
                                           required>
                                    @error('first_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="last_name" class="form-label">
                                        <i class="fas fa-user me-1"></i>
                                        Nom *
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('last_name') is-invalid @enderror" 
                                           id="last_name" 
                                           name="last_name" 
                                           value="{{ old('last_name') }}" 
                                           required>
                                    @error('last_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="email" class="form-label">
                                        <i class="fas fa-envelope me-1"></i>
                                        Email *
                                    </label>
                                    <input type="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email') }}" 
                                           required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="phone_number" class="form-label">
                                        <i class="fas fa-phone me-1"></i>
                                        Téléphone *
                                    </label>
                                    <input type="tel" 
                                           class="form-control @error('phone_number') is-invalid @enderror" 
                                           id="phone_number" 
                                           name="phone_number" 
                                           value="{{ old('phone_number') }}"
                                           placeholder="Ex: +221 77 123 45 67"
                                           required>
                                    @error('phone_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="day_of_birth" class="form-label">
                                        <i class="fas fa-calendar me-1"></i>
                                        Date de naissance *
                                    </label>
                                    <input type="date" 
                                           class="form-control @error('day_of_birth') is-invalid @enderror" 
                                           id="day_of_birth" 
                                           name="day_of_birth" 
                                           value="{{ old('day_of_birth') }}"
                                           max="{{ now()->subYears(1)->format('Y-m-d') }}"
                                           required>
                                    @error('day_of_birth')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="gender" class="form-label">
                                        <i class="fas fa-venus-mars me-1"></i>
                                        Genre *
                                    </label>
                                    <select class="form-select @error('gender') is-invalid @enderror" 
                                            id="gender" 
                                            name="gender" 
                                            required>
                                        <option value="">Sélectionnez le genre</option>
                                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Homme</option>
                                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Femme</option>
                                        <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Autre</option>
                                    </select>
                                    @error('gender')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="adress" class="form-label">
                                <i class="fas fa-map-marker-alt me-1"></i>
                                Adresse *
                            </label>
                            <textarea class="form-control @error('adress') is-invalid @enderror" 
                                      id="adress" 
                                      name="adress" 
                                      rows="3" 
                                      placeholder="Adresse complète du patient"
                                      required>{{ old('adress') }}</textarea>
                            @error('adress')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="photo" class="form-label">
                                <i class="fas fa-image me-1"></i>
                                Photo de profil
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
                            
                            <!-- Image preview -->
                            <div id="imagePreview" class="mt-3" style="display: none;">
                                <label class="form-label">Aperçu :</label>
                                <img id="previewImg" src="" alt="Aperçu" class="img-thumbnail" style="max-width: 150px; max-height: 150px;">
                                <div class="mt-2">
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removePreview()">
                                        <i class="fas fa-trash me-1"></i>Supprimer
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Informations médicales -->
                        <div class="card bg-light">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="fas fa-stethoscope me-2"></i>
                                    Informations médicales (optionnelles)
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="blood_type" class="form-label">Groupe sanguin</label>
                                            <select class="form-select" id="blood_type" name="blood_type">
                                                <option value="">Non renseigné</option>
                                                <option value="A+" {{ old('blood_type') == 'A+' ? 'selected' : '' }}>A+</option>
                                                <option value="A-" {{ old('blood_type') == 'A-' ? 'selected' : '' }}>A-</option>
                                                <option value="B+" {{ old('blood_type') == 'B+' ? 'selected' : '' }}>B+</option>
                                                <option value="B-" {{ old('blood_type') == 'B-' ? 'selected' : '' }}>B-</option>
                                                <option value="AB+" {{ old('blood_type') == 'AB+' ? 'selected' : '' }}>AB+</option>
                                                <option value="AB-" {{ old('blood_type') == 'AB-' ? 'selected' : '' }}>AB-</option>
                                                <option value="O+" {{ old('blood_type') == 'O+' ? 'selected' : '' }}>O+</option>
                                                <option value="O-" {{ old('blood_type') == 'O-' ? 'selected' : '' }}>O-</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="allergies" class="form-label">Allergies connues</label>
                                            <input type="text" 
                                                   class="form-control" 
                                                   id="allergies" 
                                                   name="allergies" 
                                                   value="{{ old('allergies') }}"
                                                   placeholder="Ex: Pollen, Médicaments...">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="medical_history" class="form-label">Antécédents médicaux</label>
                                    <textarea class="form-control" 
                                              id="medical_history" 
                                              name="medical_history" 
                                              rows="3" 
                                              placeholder="Antécédents médicaux importants...">{{ old('medical_history') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="d-flex justify-content-between mt-4">
                            <div>
                                <a href="{{ route('secretary.patients') }}" class="btn btn-outline-secondary me-2">
                                    <i class="fas fa-times me-2"></i>Annuler
                                </a>
                                <a href="{{ route('secretary.patients') }}" class="btn btn-outline-info">
                                    <i class="fas fa-list me-2"></i>Retour à la liste
                                </a>
                            </div>
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="fas fa-save me-2"></i>Créer le patient
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
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

// Form validation
document.getElementById('patientForm').addEventListener('submit', function(e) {
    const submitBtn = document.getElementById('submitBtn');
    const firstName = document.getElementById('first_name').value.trim();
    const lastName = document.getElementById('last_name').value.trim();
    const email = document.getElementById('email').value.trim();
    const phone = document.getElementById('phone_number').value.trim();
    const birthDate = document.getElementById('day_of_birth').value;
    const gender = document.getElementById('gender').value;
    const address = document.getElementById('adress').value.trim();
    
    if (!firstName || !lastName || !email || !phone || !birthDate || !gender || !address) {
        e.preventDefault();
        alert('Veuillez remplir tous les champs obligatoires.');
        return;
    }
    
    // Email validation
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        e.preventDefault();
        alert('Veuillez entrer une adresse email valide.');
        return;
    }
    
    // Phone validation (basic)
    const phoneRegex = /^[\+]?[0-9\s\-\(\)]{8,}$/;
    if (!phoneRegex.test(phone)) {
        e.preventDefault();
        alert('Veuillez entrer un numéro de téléphone valide.');
        return;
    }
    
    // Show loading state
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Création en cours...';
    submitBtn.disabled = true;
});
</script>
@endpush

@push('styles')
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
@endpush
