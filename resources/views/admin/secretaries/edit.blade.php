@extends('layouts.admin')

@section('title', 'Modifier Secrétaire - Admin')
@section('page-title', 'Modifier la Secrétaire')
@section('page-subtitle', 'Modifier les informations de la secrétaire')
@section('user-role', 'Administrateur')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-user-edit me-2"></i>
                        Modifier les informations de {{ $secretary->name }}
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
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('admin.secretaries.update', $secretary) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <!-- Photo de profil -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <label class="form-label">Photo de profil</label>
                                <div class="d-flex align-items-center">
                                    @if($secretary->photo)
                                        <img src="{{ asset('storage/' . $secretary->photo) }}" 
                                             alt="{{ $secretary->name }}" 
                                             class="rounded-circle me-3" 
                                             style="width: 80px; height: 80px; object-fit: cover;">
                                    @else
                                        <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center me-3"
                                             style="width: 80px; height: 80px;">
                                            <i class="fas fa-user-tie text-white fa-2x"></i>
                                        </div>
                                    @endif
                                    <div class="flex-grow-1">
                                        <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
                                        <small class="form-text text-muted">Formats acceptés: JPG, PNG, GIF. Taille max: 2MB</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Informations personnelles -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-user me-2"></i>Informations personnelles
                                </h6>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="first_name" class="form-label">Prénom *</label>
                                    <input type="text" class="form-control" id="first_name" name="first_name" 
                                           value="{{ old('first_name', $secretary->first_name) }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="last_name" class="form-label">Nom *</label>
                                    <input type="text" class="form-control" id="last_name" name="last_name" 
                                           value="{{ old('last_name', $secretary->last_name) }}" required>
                                </div>
                            </div>
                        </div>

                        <!-- Informations de contact -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-address-card me-2"></i>Informations de contact
                                </h6>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email *</label>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           value="{{ old('email', $secretary->email) }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Téléphone</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" 
                                           value="{{ old('phone', $secretary->phone) }}">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="adress" class="form-label">Adresse</label>
                                    <textarea class="form-control" id="adress" name="adress" rows="3">{{ old('adress', $secretary->adress) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Informations de sécurité -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-shield-alt me-2"></i>Informations de sécurité
                                </h6>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password" class="form-label">Nouveau mot de passe</label>
                                    <input type="password" class="form-control" id="password" name="password">
                                    <small class="form-text text-muted">Laissez vide pour conserver le mot de passe actuel</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                                </div>
                            </div>
                        </div>

                        <!-- Statut et permissions -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-cog me-2"></i>Statut et permissions
                                </h6>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Statut *</label>
                                    <select class="form-select" id="status" name="status" required>
                                        <option value="active" {{ old('status', $secretary->status) === 'active' ? 'selected' : '' }}>Actif</option>
                                        <option value="inactive" {{ old('status', $secretary->status) === 'inactive' ? 'selected' : '' }}>Inactif</option>
                                        <option value="suspended" {{ old('status', $secretary->status) === 'suspended' ? 'selected' : '' }}>Suspendu</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email_verified_at" class="form-label">Email vérifié</label>
                                    <select class="form-select" id="email_verified_at" name="email_verified_at">
                                        <option value="1" {{ $secretary->email_verified_at ? 'selected' : '' }}>Oui</option>
                                        <option value="0" {{ !$secretary->email_verified_at ? 'selected' : '' }}>Non</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Informations supplémentaires -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-info-circle me-2"></i>Informations supplémentaires
                                </h6>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="day_of_birth" class="form-label">Date de naissance</label>
                                    <input type="date" class="form-control" id="day_of_birth" name="day_of_birth" 
                                           value="{{ old('day_of_birth', $secretary->day_of_birth) }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="gender" class="form-label">Genre</label>
                                    <select class="form-select" id="gender" name="gender">
                                        <option value="">Sélectionner</option>
                                        <option value="male" {{ old('gender', $secretary->gender) === 'male' ? 'selected' : '' }}>Masculin</option>
                                        <option value="female" {{ old('gender', $secretary->gender) === 'female' ? 'selected' : '' }}>Féminin</option>
                                        <option value="other" {{ old('gender', $secretary->gender) === 'other' ? 'selected' : '' }}>Autre</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Boutons d'action -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('admin.secretaries.show', $secretary) }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left me-2"></i>Retour
                                    </a>
                                    <div>
                                        <button type="button" class="btn btn-outline-danger me-2" onclick="deleteSecretary({{ $secretary->id }})">
                                            <i class="fas fa-trash me-2"></i>Supprimer
                                        </button>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save me-2"></i>Enregistrer les modifications
                                        </button>
                                    </div>
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
function deleteSecretary(secretaryId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette secrétaire ? Cette action est irréversible.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/secretaries/${secretaryId}`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        form.appendChild(csrfToken);
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        form.appendChild(methodField);
        
        document.body.appendChild(form);
        form.submit();
    }
}

// Validation du formulaire
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const password = document.getElementById('password');
    const passwordConfirmation = document.getElementById('password_confirmation');
    
    form.addEventListener('submit', function(e) {
        if (password.value && password.value !== passwordConfirmation.value) {
            e.preventDefault();
            alert('Les mots de passe ne correspondent pas.');
            passwordConfirmation.focus();
        }
    });
    
    // Mise à jour automatique du nom complet
    const firstName = document.getElementById('first_name');
    const lastName = document.getElementById('last_name');
    
    function updateFullName() {
        const fullName = `${firstName.value} ${lastName.value}`.trim();
        // Vous pouvez ajouter un champ caché pour le nom complet si nécessaire
    }
    
    firstName.addEventListener('input', updateFullName);
    lastName.addEventListener('input', updateFullName);
});
</script>
@endsection
