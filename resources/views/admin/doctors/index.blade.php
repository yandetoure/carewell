@extends('layouts.admin')

@section('title', 'Gestion des Médecins')

@section('content')
<div class="container-fluid">
    <!-- En-tête de la page -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-user-md me-2"></i>
                        Gestion des Médecins
                    </h1>
                    <p class="text-muted mb-0">Gérez les médecins de votre établissement</p>
                </div>
                <div>
                    <a href="{{ route('admin.doctors.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>
                        Ajouter un Médecin
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques rapides -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $totalDoctors }}</h4>
                            <p class="mb-0">Total Médecins</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-user-md fa-2x"></i>
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
                            <h4 class="mb-0">{{ $activeDoctors }}</h4>
                            <p class="mb-0">Médecins Actifs</p>
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
                            <p class="mb-0">Nouveaux ce Mois</p>
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
                            <h4 class="mb-0">{{ $withServices }}</h4>
                            <p class="mb-0">Avec Services</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-stethoscope fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau des médecins -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-list me-2"></i>
                        Liste des Médecins
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="doctorsTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Photo</th>
                                    <th>Nom & Email</th>
                                    <th>Services</th>
                                    <th>Rendez-vous</th>
                                    <th>Statut</th>
                                    <th>Date d'inscription</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($doctors as $doctor)
                                <tr>
                                    <td>
                                        @if($doctor->photo)
                                            <img src="{{ asset('storage/' . $doctor->photo) }}"
                                                 alt="{{ $doctor->name }}"
                                                 class="rounded-circle"
                                                 style="width: 50px; height: 50px; object-fit: cover;">
                                        @else
                                            <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center"
                                                 style="width: 50px; height: 50px;">
                                                <i class="fas fa-user text-white"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $doctor->name }}</div>
                                        <small class="text-muted">{{ $doctor->email }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-success">
                                            {{ $doctor->services_count ?? 0 }} services
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">
                                            {{ $doctor->appointments_count ?? 0 }} RDV
                                        </span>
                                    </td>
                                    <td>
                                        @if($doctor->status === 'active')
                                            <span class="badge bg-success">Actif</span>
                                        @elseif($doctor->status === 'inactive')
                                            <span class="badge bg-danger">Inactif</span>
                                        @else
                                            <span class="badge bg-warning">En attente</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div>{{ $doctor->created_at->format('d/m/Y') }}</div>
                                        <small class="text-muted">{{ $doctor->created_at->diffForHumans() }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button class="btn btn-outline-primary"
                                                    onclick="viewDoctor({{ $doctor->id }})"
                                                    title="Voir les détails">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <a href="{{ route('admin.doctors.edit', $doctor) }}"
                                               class="btn btn-outline-warning"
                                               title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button class="btn btn-outline-info"
                                                    onclick="viewServices({{ $doctor->id }})"
                                                    title="Voir les services">
                                                <i class="fas fa-stethoscope"></i>
                                            </button>
                                            <button class="btn btn-outline-danger"
                                                    onclick="deleteDoctor({{ $doctor->id }})"
                                                    title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-5">
                                        <i class="fas fa-user-md fa-3x mb-3"></i>
                                        <h5>Aucun médecin trouvé</h5>
                                        <p>Commencez par ajouter votre premier médecin.</p>
                                        <a href="{{ route('admin.doctors.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus me-2"></i>Ajouter un médecin
                                        </a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    @if(isset($doctors) && $doctors->hasPages())
    <div class="row">
        <div class="col-12">
            <!-- Pagination Info -->
            <div class="pagination-info">
                <i class="fas fa-info-circle me-2"></i>
                Affichage de {{ $doctors->firstItem() }} à {{ $doctors->lastItem() }} sur {{ $doctors->total() }} résultats
            </div>
            
            <!-- Pagination Links -->
            <div class="d-flex justify-content-center">
                {{ $doctors->links() }}
            </div>
        </div>
    </div>
    @endif
</div>

<script>
// Supprimer un médecin
function deleteDoctor(doctorId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce médecin ? Cette action est irréversible.')) {
        // Créer un formulaire pour la suppression
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/doctors/${doctorId}`;
        
        // Ajouter le token CSRF
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        form.appendChild(csrfToken);
        
        // Ajouter la méthode DELETE
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        form.appendChild(methodField);
        
        // Ajouter le formulaire au DOM et le soumettre
        document.body.appendChild(form);
        form.submit();
    }
}

// Voir les détails d'un médecin
function viewDoctor(doctorId) {
    window.location.href = `/admin/doctors/${doctorId}`;
}

// Voir les services d'un médecin
function viewServices(doctorId) {
    // TODO: Implémenter la vue des services
    alert('Fonctionnalité à venir');
}
</script>
@endsection
