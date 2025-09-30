@extends('layouts.admin')

@section('title', 'Gestion des Permissions - Admin')
@section('page-title', 'Permissions')
@section('page-subtitle', 'Gérer les permissions de la plateforme')
@section('user-role', 'Administrateur')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-key me-2"></i>
                        Permissions de la plateforme
                    </h5>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPermissionModal">
                        <i class="fas fa-plus me-2"></i>Nouvelle permission
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

                    <!-- Filtres et recherche -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="searchPermission" placeholder="Rechercher une permission...">
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="filterGroup">
                                <option value="">Tous les groupes</option>
                                @foreach($permissions->pluck('group')->unique()->filter() as $group)
                                    <option value="{{ $group }}">{{ $group }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="filterUsage">
                                <option value="">Toutes les permissions</option>
                                <option value="used">Utilisées par des rôles</option>
                                <option value="unused">Non utilisées</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-outline-secondary w-100" onclick="resetFilters()">
                                <i class="fas fa-undo me-1"></i>Réinitialiser
                            </button>
                        </div>
                    </div>

                    <!-- Statistiques rapides -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center">
                                    <h4 class="mb-1">{{ $permissions->total() }}</h4>
                                    <small>Total permissions</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h4 class="mb-1">{{ $permissions->where('roles_count', '>', 0)->count() }}</h4>
                                    <small>Utilisées par des rôles</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body text-center">
                                    <h4 class="mb-1">{{ $permissions->where('roles_count', 0)->count() }}</h4>
                                    <small>Non utilisées</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <h4 class="mb-1">{{ $permissions->pluck('group')->unique()->filter()->count() }}</h4>
                                    <small>Groupes</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover" id="permissionsTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Nom</th>
                                    <th>Affichage</th>
                                    <th>Groupe</th>
                                    <th>Rôles utilisant</th>
                                    <th>Description</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($permissions as $permission)
                                <tr data-group="{{ $permission->group }}" data-usage="{{ $permission->roles->count() > 0 ? 'used' : 'unused' }}">
                                    <td>
                                        <div class="fw-bold">{{ $permission->name }}</div>
                                    </td>
                                    <td>{{ $permission->display_name }}</td>
                                    <td>
                                        @if($permission->group)
                                            <span class="badge bg-info">{{ $permission->group }}</span>
                                        @else
                                            <span class="badge bg-secondary">Sans groupe</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-success">{{ $permission->roles->count() }}</span>
                                        @if($permission->roles->count() > 0)
                                            <small class="text-muted d-block">
                                                {{ Str::limit($permission->roles->pluck('name')->join(', '), 30) }}
                                            </small>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="text-truncate" style="max-width: 200px;" title="{{ $permission->description }}">
                                            {{ $permission->description ?? 'Aucune description' }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button class="btn btn-outline-primary" 
                                                    onclick="viewPermission({{ $permission->id }})" 
                                                    title="Voir les détails">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-outline-warning" 
                                                    onclick="editPermission({{ $permission->id }})" 
                                                    title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            @if($permission->roles->count() == 0)
                                                <button class="btn btn-outline-danger" 
                                                        onclick="deletePermission({{ $permission->id }}, '{{ $permission->display_name }}')" 
                                                        title="Supprimer">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-5">
                                        <i class="fas fa-key fa-3x mb-3"></i>
                                        <h5>Aucune permission trouvée</h5>
                                        <p>Commencez par créer votre première permission.</p>
                                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPermissionModal">
                                            <i class="fas fa-plus me-2"></i>Créer une permission
                                        </button>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($permissions->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $permissions->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de création de permission -->
<div class="modal fade" id="addPermissionModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Créer une nouvelle permission</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.permissions.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nom de la permission *</label>
                                <input type="text" class="form-control" id="name" name="name" 
                                       placeholder="Ex: users.create, articles.edit" required>
                                <div class="form-text">Nom unique utilisé dans le code (format: module.action)</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="display_name" class="form-label">Nom d'affichage *</label>
                                <input type="text" class="form-control" id="display_name" name="display_name" 
                                       placeholder="Ex: Créer des utilisateurs" required>
                                <div class="form-text">Nom affiché dans l'interface</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="group" class="form-label">Groupe *</label>
                                <select class="form-select" id="group" name="group" required>
                                    <option value="">Sélectionner un groupe</option>
                                    <option value="Utilisateurs">Utilisateurs</option>
                                    <option value="Articles">Articles</option>
                                    <option value="Services">Services</option>
                                    <option value="Rendez-vous">Rendez-vous</option>
                                    <option value="Dossiers médicaux">Dossiers médicaux</option>
                                    <option value="Prescriptions">Prescriptions</option>
                                    <option value="Examens">Examens</option>
                                    <option value="Système">Système</option>
                                    <option value="Rapports">Rapports</option>
                                </select>
                                <div class="form-text">Groupe d'organisation de la permission</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="custom_group" class="form-label">Ou créer un nouveau groupe</label>
                                <input type="text" class="form-control" id="custom_group" name="custom_group" 
                                       placeholder="Nom du nouveau groupe">
                                <div class="form-text">Laissez vide si vous utilisez le groupe ci-dessus</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3" 
                                  placeholder="Description de ce que permet cette permission..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Créer la permission</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de modification de permission -->
<div class="modal fade" id="editPermissionModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modifier la permission</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editPermissionForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_name" class="form-label">Nom de la permission</label>
                                <input type="text" class="form-control" id="edit_name" disabled>
                                <div class="form-text">Le nom ne peut pas être modifié</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_display_name" class="form-label">Nom d'affichage *</label>
                                <input type="text" class="form-control" id="edit_display_name" name="display_name" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_group" class="form-label">Groupe *</label>
                        <input type="text" class="form-control" id="edit_group" name="group" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_description" class="form-label">Description</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Mettre à jour</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de suppression de permission -->
<div class="modal fade" id="deletePermissionModal" tabindex="-1">
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
                <p>Êtes-vous sûr de vouloir supprimer la permission <strong id="permissionName"></strong> ?</p>
                <p class="text-muted">Cette permission sera retirée de tous les rôles qui l'utilisent.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form id="deletePermissionForm" method="POST" class="d-inline">
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
// Recherche et filtres
document.getElementById('searchPermission').addEventListener('input', filterPermissions);
document.getElementById('filterGroup').addEventListener('change', filterPermissions);
document.getElementById('filterUsage').addEventListener('change', filterPermissions);

function filterPermissions() {
    const searchTerm = document.getElementById('searchPermission').value.toLowerCase();
    const groupFilter = document.getElementById('filterGroup').value;
    const usageFilter = document.getElementById('filterUsage').value;
    
    const rows = document.querySelectorAll('#permissionsTable tbody tr');
    
    rows.forEach(row => {
        const name = row.cells[0].textContent.toLowerCase();
        const displayName = row.cells[1].textContent.toLowerCase();
        const group = row.dataset.group;
        const usage = row.dataset.usage;
        
        const matchesSearch = name.includes(searchTerm) || displayName.includes(searchTerm);
        const matchesGroup = !groupFilter || group === groupFilter;
        const matchesUsage = !usageFilter || usage === usageFilter;
        
        row.style.display = matchesSearch && matchesGroup && matchesUsage ? '' : 'none';
    });
}

function resetFilters() {
    document.getElementById('searchPermission').value = '';
    document.getElementById('filterGroup').value = '';
    document.getElementById('filterUsage').value = '';
    filterPermissions();
}

function viewPermission(permissionId) {
    // Implémenter la vue des détails si nécessaire
    alert('Fonctionnalité de vue des détails à implémenter');
}

function editPermission(permissionId) {
    // Charger les données de la permission via AJAX
    fetch(`/admin/permissions/${permissionId}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('edit_name').value = data.name;
            document.getElementById('edit_display_name').value = data.display_name;
            document.getElementById('edit_group').value = data.group;
            document.getElementById('edit_description').value = data.description || '';
            document.getElementById('editPermissionForm').action = `/admin/permissions/${permissionId}`;
            
            const modal = new bootstrap.Modal(document.getElementById('editPermissionModal'));
            modal.show();
        })
        .catch(error => {
            alert('Erreur lors du chargement de la permission');
        });
}

function deletePermission(permissionId, permissionName) {
    document.getElementById('permissionName').textContent = permissionName;
    document.getElementById('deletePermissionForm').action = `/admin/permissions/${permissionId}`;
    
    const modal = new bootstrap.Modal(document.getElementById('deletePermissionModal'));
    modal.show();
}

// Gestion des groupes personnalisés
document.getElementById('group').addEventListener('change', function() {
    const customGroup = document.getElementById('custom_group');
    if (this.value) {
        customGroup.value = '';
        customGroup.disabled = true;
    } else {
        customGroup.disabled = false;
    }
});

document.getElementById('custom_group').addEventListener('input', function() {
    const groupSelect = document.getElementById('group');
    if (this.value) {
        groupSelect.value = '';
    }
});
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

.text-truncate {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
</style>
@endsection
