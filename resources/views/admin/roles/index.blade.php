@extends('layouts.dashboard')

@section('title', 'Gestion des Rôles et Permissions - Admin')
@section('page-title', 'Rôles et Permissions')
@section('page-subtitle', 'Gérer les rôles et permissions de la plateforme')
@section('user-role', 'Administrateur')

@section('content')
<div class="container-fluid py-4">
    <!-- Statistiques rapides -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h4 class="mb-1">{{ $roles->total() }}</h4>
                    <small>Total des rôles</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h4 class="mb-1">{{ $permissions->flatten()->count() }}</h4>
                    <small>Total des permissions</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <h4 class="mb-1">{{ $permissions->count() }}</h4>
                    <small>Groupes de permissions</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body text-center">
                    <h4 class="mb-1">{{ \App\Models\User::with('roles')->get()->sum(fn($user) => $user->roles->count()) }}</h4>
                    <small>Assignations de rôles</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Liste des rôles -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-user-shield me-2"></i>
                        Rôles de la plateforme
                    </h5>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRoleModal">
                        <i class="fas fa-plus me-2"></i>Nouveau rôle
                    </button>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Nom</th>
                                    <th>Affichage</th>
                                    <th>Permissions</th>
                                    <th>Utilisateurs</th>
                                    <th>Type</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($roles as $role)
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ $role->name }}</div>
                                        @if($role->description)
                                            <small class="text-muted">{{ Str::limit($role->description, 50) }}</small>
                                        @endif
                                    </td>
                                    <td>{{ $role->display_name ?? $role->name }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ $role->permissions->count() }}</span>
                                        @if($role->permissions->count() > 0)
                                            <small class="text-muted d-block">
                                                {{ Str::limit($role->permissions->pluck('display_name')->join(', '), 30) }}
                                            </small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-success">{{ \App\Models\User::role($role->name, 'web')->count() }}</span>
                                    </td>
                                    <td>
                                        @php
                                            $systemRoles = ['Admin', 'Doctor', 'Secretary', 'Patient'];
                                            $isSystem = in_array($role->name, $systemRoles);
                                        @endphp
                                        @if($isSystem)
                                            <span class="badge bg-warning">Système</span>
                                        @else
                                            <span class="badge bg-secondary">Personnalisé</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('admin.roles.show', $role) }}" 
                                               class="btn btn-outline-primary" 
                                               title="Voir les détails">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.roles.edit', $role) }}" 
                                               class="btn btn-outline-warning" 
                                               title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if(!in_array($role->name, ['Admin', 'Doctor', 'Secretary', 'Patient']))
                                                <button class="btn btn-outline-danger" 
                                                        onclick="deleteRole({{ $role->id }}, '{{ $role->name }}')" 
                                                        title="Supprimer">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        <i class="fas fa-user-shield fa-3x mb-3"></i>
                                        <h5>Aucun rôle trouvé</h5>
                                        <p>Commencez par créer votre premier rôle.</p>
                                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRoleModal">
                                            <i class="fas fa-plus me-2"></i>Créer un rôle
                                        </button>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($roles->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $roles->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Panneau des permissions -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">
                        <i class="fas fa-key me-2"></i>
                        Permissions disponibles
                    </h6>
                    <a href="{{ route('admin.permissions') }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-cog me-1"></i>Gérer
                    </a>
                </div>
                <div class="card-body" style="max-height: 600px; overflow-y: auto;">
                    @forelse($permissions as $group => $groupPermissions)
                        <div class="mb-3">
                            <h6 class="text-muted mb-2">
                                <i class="fas fa-folder me-1"></i>
                                {{ $group ?: 'Sans groupe' }}
                                <span class="badge bg-light text-dark ms-2">{{ $groupPermissions->count() }}</span>
                            </h6>
                            <div class="list-group list-group-flush">
                                @foreach($groupPermissions as $permission)
                                    <div class="list-group-item px-2 py-1 d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="small fw-bold">{{ $permission->display_name }}</div>
                                            <div class="small text-muted">{{ $permission->name }}</div>
                                        </div>
                                        <span class="badge bg-info">{{ $permission->roles->count() }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-key fa-2x mb-2"></i>
                            <p class="mb-0">Aucune permission trouvée</p>
                            <small>Créez des permissions pour commencer</small>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de création de rôle -->
<div class="modal fade" id="addRoleModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Créer un nouveau rôle</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.roles.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nom du rôle *</label>
                                <input type="text" class="form-control" id="name" name="name" 
                                       placeholder="Ex: manager, supervisor" required>
                                <div class="form-text">Nom unique utilisé dans le code (minuscules, underscores)</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="display_name" class="form-label">Nom d'affichage *</label>
                                <input type="text" class="form-control" id="display_name" name="display_name" 
                                       placeholder="Ex: Gestionnaire, Superviseur" required>
                                <div class="form-text">Nom affiché dans l'interface</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3" 
                                  placeholder="Description du rôle et de ses responsabilités..."></textarea>
                    </div>

                    @if($permissions->count() > 0)
                        <div class="mb-3">
                            <label class="form-label">Permissions</label>
                            <div class="border rounded p-3" style="max-height: 300px; overflow-y: auto;">
                                @foreach($permissions as $group => $groupPermissions)
                                    <div class="mb-3">
                                        <h6 class="text-muted mb-2">
                                            <i class="fas fa-folder me-1"></i>
                                            {{ $group ?: 'Sans groupe' }}
                                        </h6>
                                        @foreach($groupPermissions as $permission)
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" 
                                                       id="permission_{{ $permission->id }}" 
                                                       name="permissions[]" 
                                                       value="{{ $permission->id }}">
                                                <label class="form-check-label" for="permission_{{ $permission->id }}">
                                                    {{ $permission->display_name }}
                                                    <small class="text-muted d-block">{{ $permission->name }}</small>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Créer le rôle</button>
                </div>
            </form>
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
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Attention !</strong> Cette action est irréversible.
                </div>
                <p>Êtes-vous sûr de vouloir supprimer le rôle <strong id="roleName"></strong> ?</p>
                <p class="text-muted">Tous les utilisateurs ayant ce rôle perdront leurs permissions associées.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form id="deleteRoleForm" method="POST" class="d-inline">
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
function deleteRole(roleId, roleName) {
    document.getElementById('roleName').textContent = roleName;
    document.getElementById('deleteRoleForm').action = `/admin/roles/${roleId}`;
    
    const modal = new bootstrap.Modal(document.getElementById('deleteRoleModal'));
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
