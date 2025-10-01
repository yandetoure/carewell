@extends('layouts.admin')

@section('title', 'Gestion des Secrétaires - Admin')
@section('page-title', 'Gestion des Secrétaires')
@section('page-subtitle', 'Gérer tous les secrétaires de la plateforme')
@section('user-role', 'Administrateur')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-user-tie me-2"></i>
                        Gestion des Secrétaires
                    </h5>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSecretaryModal">
                        <i class="fas fa-user-plus me-2"></i>Nouvelle Secrétaire
                    </button>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Statistiques rapides -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4 class="mb-0">{{ $totalSecretaries }}</h4>
                                            <p class="mb-0">Total</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-user-tie fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4 class="mb-0">{{ $activeSecretaries }}</h4>
                                            <p class="mb-0">Actives</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-check-circle fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4 class="mb-0">{{ $newThisMonth }}</h4>
                                            <p class="mb-0">Ce mois</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-calendar-plus fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4 class="mb-0">{{ $withAppointments }}</h4>
                                            <p class="mb-0">Avec RDV</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-calendar-check fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tableau des secrétaires -->
                    <div class="table-responsive">
                        <table class="table table-hover" id="secretariesTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Photo</th>
                                    <th>Nom & Email</th>
                                    <th>Téléphone</th>
                                    <th>Rendez-vous</th>
                                    <th>Date d'inscription</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($secretaries as $secretary)
                                <tr>
                                    <td>
                                        @if($secretary->photo)
                                            <img src="{{ asset('storage/' . $secretary->photo) }}"
                                                 alt="{{ $secretary->name }}"
                                                 class="rounded-circle"
                                                 style="width: 40px; height: 40px; object-fit: cover;">
                                        @else
                                            <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center"
                                                 style="width: 40px; height: 40px;">
                                                <i class="fas fa-user-tie text-white"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $secretary->name }}</div>
                                        <small class="text-muted">{{ $secretary->email }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $secretary->phone ?? 'N/A' }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">
                                            {{ $secretary->appointments_count ?? 0 }} RDV
                                        </span>
                                    </td>
                                    <td>
                                        <div>{{ $secretary->created_at->format('d/m/Y') }}</div>
                                        <small class="text-muted">{{ $secretary->created_at->diffForHumans() }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button class="btn btn-outline-primary"
                                                    onclick="viewSecretary({{ $secretary->id }})"
                                                    title="Voir les détails">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <a href="{{ route('admin.secretaries.edit', $secretary) }}"
                                               class="btn btn-outline-warning"
                                               title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button class="btn btn-outline-danger"
                                                    onclick="deleteSecretary({{ $secretary->id }})"
                                                    title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-5">
                                        <i class="fas fa-user-tie fa-3x mb-3"></i>
                                        <h5>Aucune secrétaire trouvée</h5>
                                        <p>Commencez par ajouter votre première secrétaire.</p>
                                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSecretaryModal">
                                            <i class="fas fa-plus me-2"></i>Ajouter une secrétaire
                                        </button>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if(isset($secretaries) && $secretaries->hasPages())
                    <div class="row">
                        <div class="col-12">
                            <!-- Pagination Info -->
                            <div class="pagination-info">
                                <i class="fas fa-info-circle me-2"></i>
                                Affichage de {{ $secretaries->firstItem() }} à {{ $secretaries->lastItem() }} sur {{ $secretaries->total() }} résultats
                            </div>
                            
                            <!-- Pagination Links -->
                            <div class="d-flex justify-content-center">
                                {{ $secretaries->links() }}
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal d'ajout de secrétaire -->
<div class="modal fade" id="addSecretaryModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajouter une nouvelle secrétaire</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.secretaries.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="first_name" class="form-label">Prénom *</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="last_name" class="form-label">Nom *</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email *</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="phone" class="form-label">Téléphone</label>
                                <input type="tel" class="form-control" id="phone" name="phone">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="password" class="form-label">Mot de passe *</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="photo" class="form-label">Photo de profil</label>
                                <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Créer la secrétaire</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function viewSecretary(secretaryId) {
    window.location.href = `/admin/secretaries/${secretaryId}`;
}

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
</script>
@endsection
