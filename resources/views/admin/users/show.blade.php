@extends('layouts.dashboard')

@section('title', 'Détails Utilisateur - Admin')
@section('page-title', 'Détails de l\'utilisateur')
@section('page-subtitle', 'Informations complètes sur l\'utilisateur')
@section('user-role', 'Administrateur')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-lg-4">
            <!-- Profil utilisateur -->
            <div class="card">
                <div class="card-body text-center">
                    @if($user->photo)
                        <img src="{{ asset('storage/' . $user->photo) }}" 
                             alt="Photo de profil" 
                             class="rounded-circle mb-3" 
                             style="width: 150px; height: 150px; object-fit: cover;">
                    @else
                        <div class="bg-secondary rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" 
                             style="width: 150px; height: 150px;">
                            <i class="fas fa-user text-white fa-4x"></i>
                        </div>
                    @endif
                    
                    <h4 class="mb-1">{{ $user->first_name }} {{ $user->last_name }}</h4>
                    <p class="text-muted mb-2">{{ $user->email }}</p>
                    
                    @if($user->hasRole('Admin'))
                        <span class="badge bg-danger fs-6 mb-2">Administrateur</span>
                    @elseif($user->hasRole('Doctor'))
                        <span class="badge bg-primary fs-6 mb-2">Médecin</span>
                    @elseif($user->hasRole('Secretary'))
                        <span class="badge bg-warning fs-6 mb-2">Secrétaire</span>
                    @else
                        <span class="badge bg-success fs-6 mb-2">Patient</span>
                    @endif
                    
                    <div class="mt-3">
                        @if($user->email_verified_at)
                            <span class="badge bg-success">
                                <i class="fas fa-check-circle me-1"></i>Compte vérifié
                            </span>
                        @else
                            <span class="badge bg-warning">
                                <i class="fas fa-exclamation-triangle me-1"></i>En attente de vérification
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-bolt me-2"></i>
                        Actions rapides
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-outline-primary">
                            <i class="fas fa-edit me-2"></i>Modifier le profil
                        </a>
                        <button class="btn btn-outline-info" onclick="changeUserRole()">
                            <i class="fas fa-user-tag me-2"></i>Changer le rôle
                        </button>
                        @if($user->id !== Auth::id())
                            <button class="btn btn-outline-danger" onclick="deleteUser()">
                                <i class="fas fa-trash me-2"></i>Supprimer l'utilisateur
                            </button>
                        @endif
                        <a href="{{ route('admin.users') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Retour à la liste
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <!-- Informations détaillées -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Informations détaillées
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Informations personnelles</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Prénom:</strong></td>
                                    <td>{{ $user->first_name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Nom:</strong></td>
                                    <td>{{ $user->last_name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Email:</strong></td>
                                    <td>{{ $user->email }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Téléphone:</strong></td>
                                    <td>{{ $user->phone_number ?? 'Non renseigné' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Informations du compte</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Rôle:</strong></td>
                                    <td>
                                        @if($user->hasRole('Admin'))
                                            <span class="badge bg-danger">Administrateur</span>
                                        @elseif($user->hasRole('Doctor'))
                                            <span class="badge bg-primary">Médecin</span>
                                        @elseif($user->hasRole('Secretary'))
                                            <span class="badge bg-warning">Secrétaire</span>
                                        @else
                                            <span class="badge bg-success">Patient</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Statut:</strong></td>
                                    <td>
                                        @if($user->email_verified_at)
                                            <span class="badge bg-success">Actif</span>
                                        @else
                                            <span class="badge bg-warning">En attente</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Inscrit le:</strong></td>
                                    <td>{{ $user->created_at->format('d/m/Y à H:i') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Dernière connexion:</strong></td>
                                    <td>{{ $user->updated_at->diffForHumans() }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Activité récente -->
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-history me-2"></i>
                        Activité récente
                    </h6>
                </div>
                <div class="card-body">
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-clock fa-2x mb-2"></i>
                        <p class="mb-0">Aucune activité récente enregistrée</p>
                        <small>Cette fonctionnalité sera disponible dans une future version</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Change Role Modal -->
<div class="modal fade" id="changeRoleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Changer le rôle de l'utilisateur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.users.role', $user) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="role" class="form-label">Nouveau rôle</label>
                        <select class="form-select" id="role" name="role" required>
                            <option value="patient" {{ $user->hasRole('Patient') ? 'selected' : '' }}>Patient</option>
                            <option value="doctor" {{ $user->hasRole('Doctor') ? 'selected' : '' }}>Médecin</option>
                            <option value="secretary" {{ $user->hasRole('Secretary') ? 'selected' : '' }}>Secrétaire</option>
                            <option value="admin" {{ $user->hasRole('Admin') ? 'selected' : '' }}>Administrateur</option>
                        </select>
                    </div>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Attention :</strong> Changer le rôle d'un utilisateur peut affecter ses permissions d'accès.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-warning">Changer le rôle</button>
                </div>
            </form>
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
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Attention !</strong> Cette action est irréversible.
                </div>
                <p>Êtes-vous sûr de vouloir supprimer l'utilisateur <strong>"{{ $user->first_name }} {{ $user->last_name }}"</strong> ?</p>
                <p class="text-muted">Toutes les données associées à cet utilisateur seront également supprimées.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline">
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
function changeUserRole() {
    const modal = new bootstrap.Modal(document.getElementById('changeRoleModal'));
    modal.show();
}

function deleteUser() {
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}
</script>

<style>
.card {
    border: 1px solid #e3e6f0;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
}

.table-borderless td {
    border: none;
    padding: 0.5rem 0;
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
