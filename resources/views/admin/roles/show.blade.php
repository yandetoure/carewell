@extends('layouts.dashboard')

@section('title', 'Détails du Rôle - Admin')
@section('page-title', 'Détails du rôle')
@section('page-subtitle', 'Informations complètes sur le rôle')
@section('user-role', 'Administrateur')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <!-- Informations du rôle -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-user-shield fa-4x text-primary"></i>
                    </div>
                    <h4 class="mb-1">{{ $role->display_name ?? $role->name }}</h4>
                    <p class="text-muted mb-2">{{ $role->name }}</p>
                    
                    @php
                        $systemRoles = ['Admin', 'Doctor', 'Secretary', 'Patient'];
                        $isSystem = in_array($role->name, $systemRoles);
                    @endphp
                    
                    @if($isSystem)
                        <span class="badge bg-warning fs-6 mb-3">Rôle Système</span>
                    @else
                        <span class="badge bg-secondary fs-6 mb-3">Rôle Personnalisé</span>
                    @endif
                    
                    @if($role->description)
                        <p class="text-muted">{{ $role->description }}</p>
                    @endif
                    
                    <div class="row text-center mt-3">
                        <div class="col-6">
                            <div class="h5 text-primary mb-1">{{ $role->permissions->count() }}</div>
                            <small class="text-muted">Permissions</small>
                        </div>
                        <div class="col-6">
                            <div class="h5 text-success mb-1">{{ \App\Models\User::role($role->name, 'web')->count() }}</div>
                            <small class="text-muted">Utilisateurs</small>
                        </div>
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
                        <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-outline-primary">
                            <i class="fas fa-edit me-2"></i>Modifier le rôle
                        </a>
                        <button class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#assignPermissionsModal">
                            <i class="fas fa-key me-2"></i>Gérer les permissions
                        </button>
                        @if(!in_array($role->name, ['Admin', 'Doctor', 'Secretary', 'Patient']))
                            <button class="btn btn-outline-danger" onclick="deleteRole()">
                                <i class="fas fa-trash me-2"></i>Supprimer le rôle
                            </button>
                        @endif
                        <a href="{{ route('admin.roles') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Retour à la liste
                        </a>
                    </div>
                </div>
            </div>

            <!-- Statistiques -->
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-pie me-2"></i>
                        Statistiques
                    </h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                            <span>Créé le</span>
                            <small class="text-muted">{{ $role->created_at->format('d/m/Y à H:i') }}</small>
                        </div>
                        <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                            <span>Dernière modification</span>
                            <small class="text-muted">{{ $role->updated_at->diffForHumans() }}</small>
                        </div>
                        <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                            <span>Permissions assignées</span>
                            <span class="badge bg-info">{{ $role->permissions->count() }}</span>
                        </div>
                        <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                            <span>Utilisateurs avec ce rôle</span>
                            <span class="badge bg-success">{{ $users->total() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <!-- Permissions du rôle -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-key me-2"></i>
                        Permissions assignées
                    </h5>
                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#assignPermissionsModal">
                        <i class="fas fa-plus me-1"></i>Gérer
                    </button>
                </div>
                <div class="card-body">
                    @if($role->permissions->count() > 0)
                        <div class="row">
                            @foreach($role->permissions->groupBy('group') as $group => $groupPermissions)
                                <div class="col-md-6 mb-4">
                                    <h6 class="text-muted mb-3">
                                        <i class="fas fa-folder me-1"></i>
                                        {{ $group ?: 'Sans groupe' }}
                                        <span class="badge bg-light text-dark ms-2">{{ $groupPermissions->count() }}</span>
                                    </h6>
                                    <div class="list-group list-group-flush">
                                        @foreach($groupPermissions as $permission)
                                            <div class="list-group-item px-2 py-2 d-flex justify-content-between align-items-center">
                                                <div>
                                                    <div class="fw-bold">{{ $permission->display_name }}</div>
                                                    <small class="text-muted">{{ $permission->name }}</small>
                                                </div>
                                                <div class="btn-group btn-group-sm">
                                                    <button class="btn btn-outline-danger btn-sm" 
                                                            onclick="revokePermission({{ $permission->id }}, '{{ $permission->display_name }}')"
                                                            title="Révoquer cette permission">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-key fa-3x mb-3"></i>
                            <h5>Aucune permission assignée</h5>
                            <p>Ce rôle n'a pas encore de permissions assignées.</p>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#assignPermissionsModal">
                                <i class="fas fa-plus me-2"></i>Assigner des permissions
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Utilisateurs avec ce rôle -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-users me-2"></i>
                        Utilisateurs avec ce rôle ({{ $users->total() }})
                    </h5>
                </div>
                <div class="card-body">
                    @if($users->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Photo</th>
                                        <th>Nom</th>
                                        <th>Email</th>
                                        <th>Statut</th>
                                        <th>Inscrit le</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                    <tr>
                                        <td>
                                            @if($user->photo)
                                                <img src="{{ asset('storage/' . $user->photo) }}" 
                                                     alt="Photo de profil" 
                                                     class="rounded-circle" 
                                                     style="width: 40px; height: 40px; object-fit: cover;">
                                            @else
                                                <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center" 
                                                     style="width: 40px; height: 40px;">
                                                    <i class="fas fa-user text-white"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="fw-bold">{{ $user->first_name }} {{ $user->last_name }}</div>
                                            <small class="text-muted">{{ $user->phone_number ?? 'N/A' }}</small>
                                        </td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            @if($user->email_verified_at)
                                                <span class="badge bg-success">Actif</span>
                                            @else
                                                <span class="badge bg-warning">En attente</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div>{{ $user->created_at->format('d/m/Y') }}</div>
                                            <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if($users->hasPages())
                            <div class="d-flex justify-content-center mt-4">
                                {{ $users->links() }}
                            </div>
                        @endif
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-users fa-3x mb-3"></i>
                            <h5>Aucun utilisateur</h5>
                            <p>Aucun utilisateur n'a ce rôle pour le moment.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal d'assignation des permissions -->
<div class="modal fade" id="assignPermissionsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Gérer les permissions du rôle</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.roles.permissions.assign', $role) }}" method="POST">
                @csrf
                <div class="modal-body" style="max-height: 400px; overflow-y: auto;">
                    @if($permissions->count() > 0)
                        @foreach($permissions as $group => $groupPermissions)
                            <div class="mb-4">
                                <h6 class="text-muted mb-2">
                                    <i class="fas fa-folder me-1"></i>
                                    {{ $group ?: 'Sans groupe' }}
                                    <span class="badge bg-light text-dark ms-2">{{ $groupPermissions->count() }}</span>
                                </h6>
                                <div class="row">
                                    @foreach($groupPermissions as $permission)
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" 
                                                       id="permission_{{ $permission->id }}" 
                                                       name="permissions[]" 
                                                       value="{{ $permission->id }}"
                                                       {{ $role->permissions->contains($permission->id) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="permission_{{ $permission->id }}">
                                                    <div class="fw-bold">{{ $permission->display_name }}</div>
                                                    <small class="text-muted">{{ $permission->name }}</small>
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
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Mettre à jour les permissions</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de révocation de permission -->
<div class="modal fade" id="revokePermissionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Révoquer la permission</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Attention !</strong> Cette action révoquera la permission du rôle.
                </div>
                <p>Êtes-vous sûr de vouloir révoquer la permission <strong id="permissionName"></strong> du rôle <strong>{{ $role->display_name ?? $role->name }}</strong> ?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form id="revokePermissionForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times me-2"></i>Révoquer
                    </button>
                </form>
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
                <p>Êtes-vous sûr de vouloir supprimer le rôle <strong>{{ $role->display_name ?? $role->name }}</strong> ?</p>
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

function revokePermission(permissionId, permissionName) {
    document.getElementById('permissionName').textContent = permissionName;
    document.getElementById('revokePermissionForm').action = `/admin/roles/{{ $role->id }}/permissions/${permissionId}`;
    
    const modal = new bootstrap.Modal(document.getElementById('revokePermissionModal'));
    modal.show();
}
</script>

<style>
.card {
    border: 1px solid #e3e6f0;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
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

.list-group-item {
    border: none;
    border-bottom: 1px solid #e3e6f0;
}

.list-group-item:last-child {
    border-bottom: none;
}

.form-check-label {
    font-size: 0.9rem;
}
</style>
@endsection
