@extends('layouts.admin')

@section('title', 'Modifier Utilisateur - Admin')
@section('page-title', 'Modifier l\'utilisateur')
@section('page-subtitle', 'Modifier les informations de l\'utilisateur')
@section('user-role', 'Administrateur')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-edit me-2"></i>
                        Modifier : {{ $user->first_name }} {{ $user->last_name }}
                    </h5>
                    <a href="{{ route('admin.users.show', $user) }}" class="btn btn-outline-secondary">
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

                    <!-- Photo actuelle -->
                    <div class="text-center mb-4">
                        @if($user->photo)
                            <img src="{{ asset('storage/' . $user->photo) }}" 
                                 alt="Photo actuelle" 
                                 class="rounded-circle mb-3" 
                                 style="width: 100px; height: 100px; object-fit: cover;">
                        @else
                            <div class="bg-secondary rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" 
                                 style="width: 100px; height: 100px;">
                                <i class="fas fa-user text-white fa-2x"></i>
                            </div>
                        @endif
                    </div>

                    <form action="{{ route('admin.users.update', $user) }}" method="POST" enctype="multipart/form-data" id="userForm">
                        @csrf
                        @method('PUT')
                        
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
                                           value="{{ old('first_name', $user->first_name) }}" 
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
                                           value="{{ old('last_name', $user->last_name) }}" 
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
                                           value="{{ old('email', $user->email) }}" 
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
                                        Téléphone
                                    </label>
                                    <input type="tel" 
                                           class="form-control @error('phone_number') is-invalid @enderror" 
                                           id="phone_number" 
                                           name="phone_number" 
                                           value="{{ old('phone_number', $user->phone_number) }}"
                                           placeholder="Ex: +33 6 12 34 56 78">
                                    @error('phone_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
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
                                <label class="form-label">Nouvelle photo :</label>
                                <img id="previewImg" src="" alt="Aperçu" class="img-thumbnail" style="max-width: 150px; max-height: 150px;">
                                <div class="mt-2">
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removePreview()">
                                        <i class="fas fa-trash me-1"></i>Supprimer
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Informations du compte -->
                        <div class="card bg-light">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Informations du compte
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Rôle actuel</label>
                                            <div class="p-2 bg-white rounded border">
                                                @if($user->hasRole('Admin'))
                                                    <span class="badge bg-danger fs-6">Administrateur</span>
                                                @elseif($user->hasRole('Doctor'))
                                                    <span class="badge bg-primary fs-6">Médecin</span>
                                                @elseif($user->hasRole('Secretary'))
                                                    <span class="badge bg-warning fs-6">Secrétaire</span>
                                                @else
                                                    <span class="badge bg-success fs-6">Patient</span>
                                                @endif
                                            </div>
                                            <small class="text-muted">Pour changer le rôle, utilisez l'action "Changer le rôle" dans les détails de l'utilisateur.</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Statut du compte</label>
                                            <div class="p-2 bg-white rounded border">
                                                @if($user->email_verified_at)
                                                    <span class="badge bg-success fs-6">
                                                        <i class="fas fa-check-circle me-1"></i>Vérifié
                                                    </span>
                                                @else
                                                    <span class="badge bg-warning fs-6">
                                                        <i class="fas fa-exclamation-triangle me-1"></i>Non vérifié
                                                    </span>
                                                @endif
                                            </div>
                                            <small class="text-muted">Statut de vérification de l'email.</small>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Date d'inscription</label>
                                            <div class="p-2 bg-white rounded border">
                                                {{ $user->created_at->format('d/m/Y à H:i') }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Dernière modification</label>
                                            <div class="p-2 bg-white rounded border">
                                                {{ $user->updated_at->diffForHumans() }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="d-flex justify-content-between mt-4">
                            <div>
                                <a href="{{ route('admin.users.show', $user) }}" class="btn btn-outline-secondary me-2">
                                    <i class="fas fa-times me-2"></i>Annuler
                                </a>
                                <a href="{{ route('admin.users') }}" class="btn btn-outline-info">
                                    <i class="fas fa-list me-2"></i>Retour à la liste
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
document.getElementById('userForm').addEventListener('submit', function(e) {
    const submitBtn = document.getElementById('submitBtn');
    const firstName = document.getElementById('first_name').value.trim();
    const lastName = document.getElementById('last_name').value.trim();
    const email = document.getElementById('email').value.trim();
    
    if (!firstName || !lastName || !email) {
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
    
    // Show loading state
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Mise à jour en cours...';
    submitBtn.disabled = true;
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

.bg-primary, .bg-success, .bg-warning, .bg-info, .bg-danger {
    background-color: var(--bs-primary) !important;
}

.bg-success {
    background-color: var(--bs-success) !important;
}

.bg-warning {
    background-color: var(--bs-warning) !important;
}

.bg-info {
    background-color: var(--bs-info) !important;
}

.bg-danger {
    background-color: var(--bs-danger) !important;
}
</style>
@endsection
