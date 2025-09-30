@extends('layouts.admin')

@section('title', 'Modifier le Rôle - Admin')
@section('page-title', 'Modifier le rôle')
@section('page-subtitle', 'Modifier les informations du rôle')
@section('user-role', 'Administrateur')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-edit me-2"></i>
                        Modifier le rôle : {{ $role->display_name ?? $role->name }}
                    </h5>
                    <a href="{{ route('admin.roles.show', $role) }}" class="btn btn-outline-secondary">
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

                    <!-- Informations du rôle actuel -->
                    <div class="card bg-light mb-4">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-3">
                                    <i class="fas fa-user-shield fa-3x text-primary"></i>
                                </div>
                                <div class="col-md-9">
                                    <h6 class="mb-1">{{ $role->display_name ?? $role->name }}</h6>
                                    <p class="text-muted mb-1">{{ $role->name }}</p>
                                    <div class="d-flex gap-3">
                                        @php
                                            $systemRoles = ['Admin', 'Doctor', 'Secretary', 'Patient'];
                                            $isSystem = in_array($role->name, $systemRoles);
                                        @endphp
                                        @if($isSystem)
                                            <span class="badge bg-warning">Rôle Système</span>
                                        @else
                                            <span class="badge bg-secondary">Rôle Personnalisé</span>
                                        @endif
                                        <small class="text-muted">
                                            <i class="fas fa-key me-1"></i>{{ $role->permissions->count() }} permissions
                                        </small>
                                        <small class="text-muted">
                                            <i class="fas fa-users me-1"></i>{{ \App\Models\User::role($role->name, 'web')->count() }} utilisateurs
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('admin.roles.update', $role) }}" method="POST" id="roleForm">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="display_name" class="form-label">
                                        <i class="fas fa-tag me-1"></i>
                                        Nom d'affichage *
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('display_name') is-invalid @enderror" 
                                           id="display_name" 
                                           name="display_name" 
                                           value="{{ old('display_name', $role->display_name ?? $role->name) }}" 
                                           placeholder="Ex: Gestionnaire, Superviseur"
                                           required>
                                    @error('display_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Nom affiché dans l'interface utilisateur</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="name" class="form-label">
                                        <i class="fas fa-code me-1"></i>
                                        Nom technique
                                    </label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="name" 
                                           value="{{ $role->name }}" 
                                           disabled>
                                    <div class="form-text">Le nom technique ne peut pas être modifié</div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label">
                                <i class="fas fa-align-left me-1"></i>
                                Description du rôle
                            </label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="4" 
                                      placeholder="Description du rôle et de ses responsabilités...">{{ old('description', $role->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Maximum 1000 caractères</div>
                        </div>

                        <!-- Permissions -->
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="fas fa-key me-2"></i>
                                    Permissions du rôle
                                </h6>
                            </div>
                            <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                                @if($permissions->count() > 0)
                                    @foreach($permissions as $group => $groupPermissions)
                                        <div class="mb-4">
                                            <h6 class="text-muted mb-3">
                                                <i class="fas fa-folder me-1"></i>
                                                {{ $group ?: 'Sans groupe' }}
                                                <span class="badge bg-light text-dark ms-2">{{ $groupPermissions->count() }}</span>
                                            </h6>
                                            <div class="row">
                                                @foreach($groupPermissions as $permission)
                                                    <div class="col-md-6 mb-2">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" 
                                                                   id="permission_{{ $permission->id }}" 
                                                                   name="permissions[]" 
                                                                   value="{{ $permission->id }}"
                                                                   {{ $role->permissions->contains($permission->id) ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="permission_{{ $permission->id }}">
                                                                <div class="fw-bold">{{ $permission->display_name }}</div>
                                                                <small class="text-muted">{{ $permission->name }}</small>
                                                                @if($permission->description)
                                                                    <br><small class="text-muted">{{ $permission->description }}</small>
                                                                @endif
                                                            </label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="text-center text-muted py-4">
                                        <i class="fas fa-key fa-2x mb-2"></i>
                                        <p>Aucune permission disponible</p>
                                        <a href="{{ route('admin.permissions') }}" class="btn btn-outline-primary">
                                            <i class="fas fa-plus me-2"></i>Créer des permissions
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Statistiques du rôle -->
                        <div class="card bg-info text-white mt-4">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="fas fa-chart-bar me-2"></i>
                                    Statistiques du rôle
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row text-center">
                                    <div class="col-md-3">
                                        <h4 class="mb-1">{{ $role->permissions->count() }}</h4>
                                        <small>Permissions assignées</small>
                                    </div>
                                    <div class="col-md-3">
                                        <h4 class="mb-1">{{ \App\Models\User::role($role->name, 'web')->count() }}</h4>
                                        <small>Utilisateurs avec ce rôle</small>
                                    </div>
                                    <div class="col-md-3">
                                        <h4 class="mb-1">{{ $role->created_at->diffInDays(now()) }}</h4>
                                        <small>Jours depuis création</small>
                                    </div>
                                    <div class="col-md-3">
                                        <h4 class="mb-1">{{ $role->updated_at->diffForHumans() }}</h4>
                                        <small>Dernière modification</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="d-flex justify-content-between mt-4">
                            <div>
                                <a href="{{ route('admin.roles.show', $role) }}" class="btn btn-outline-secondary me-2">
                                    <i class="fas fa-times me-2"></i>Annuler
                                </a>
                                <a href="{{ route('admin.roles') }}" class="btn btn-outline-info">
                                    <i class="fas fa-list me-2"></i>Retour à la liste
                                </a>
                            </div>
                            <div>
                                @if(!in_array($role->name, ['Admin', 'Doctor', 'Secretary', 'Patient']))
                                    <button type="button" class="btn btn-outline-danger me-2" onclick="deleteRole()">
                                        <i class="fas fa-trash me-2"></i>Supprimer
                                    </button>
                                @endif
                                <button type="submit" class="btn btn-primary" id="submitBtn">
                                    <i class="fas fa-save me-2"></i>Mettre à jour
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de suppression de rôle -->
<div class="modal fade" id="deleteRoleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmer la suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Attention !</strong> Cette action est irréversible.
                </div>
                <p>Êtes-vous sûr de vouloir supprimer le rôle <strong>"{{ $role->display_name ?? $role->name }}"</strong> ?</p>
                <p class="text-muted">Tous les utilisateurs ayant ce rôle perdront leurs permissions associées.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" class="d-inline">
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
function deleteRole() {
    const modal = new bootstrap.Modal(document.getElementById('deleteRoleModal'));
    modal.show();
}

// Form validation
document.getElementById('roleForm').addEventListener('submit', function(e) {
    const submitBtn = document.getElementById('submitBtn');
    const displayName = document.getElementById('display_name').value.trim();
    
    if (!displayName) {
        e.preventDefault();
        alert('Veuillez remplir le nom d\'affichage.');
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

// Select all permissions in a group
function toggleGroupPermissions(groupName) {
    const checkboxes = document.querySelectorAll(`input[name="permissions[]"][data-group="${groupName}"]`);
    const allChecked = Array.from(checkboxes).every(cb => cb.checked);
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = !allChecked;
    });
}
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

.is-invalid {
    border-color: #dc3545;
}

.invalid-feedback {
    display: block;
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

.form-check-label {
    font-size: 0.9rem;
    cursor: pointer;
}

.form-check-input {
    cursor: pointer;
}
</style>
@endsection
