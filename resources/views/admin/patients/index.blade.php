@extends('layouts.admin')

@section('title', 'Gestion des Patients')

@section('content')
<div class="container-fluid">
    <!-- En-tête de la page -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-users me-2"></i>
                        Gestion des Patients
                    </h1>
                    <p class="text-muted mb-0">Gérez les patients de votre établissement</p>
                </div>
                <div>
                    <a href="#" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>
                        Ajouter un Patient
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
                            <h4 class="mb-0">{{ $totalPatients }}</h4>
                            <p class="mb-0">Total Patients</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-users fa-2x"></i>
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
                            <h4 class="mb-0">{{ $activePatients }}</h4>
                            <p class="mb-0">Patients Actifs</p>
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

    <!-- Tableau des patients -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-list me-2"></i>
                        Liste des Patients
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="patientsTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Photo</th>
                                    <th>Nom & Email</th>
                                    <th>Téléphone</th>
                                    <th>Statut</th>
                                    <th>Rendez-vous</th>
                                    <th>Dossier Médical</th>
                                    <th>Date d'inscription</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($patients as $patient)
                                <tr>
                                    <td>
                                        @if($patient->photo)
                                            <img src="{{ asset('storage/' . $patient->photo) }}"
                                                 alt="{{ $patient->first_name }} {{ $patient->last_name }}"
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
                                        <div class="fw-bold">{{ $patient->first_name }} {{ $patient->last_name }}</div>
                                        <small class="text-muted">{{ $patient->email }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $patient->phone_number ?? 'N/A' }}</span>
                                    </td>
                                    <td>
                                        @if($patient->status === 'active')
                                            <span class="badge bg-success">Actif</span>
                                        @elseif($patient->status === 'inactive')
                                            <span class="badge bg-danger">Inactif</span>
                                        @else
                                            <span class="badge bg-warning">En attente</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">
                                            {{ $patient->appointments_count ?? 0 }} RDV
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-success btn-sm"
                                                onclick="viewMedicalFile({{ $patient->id }})"
                                                title="Voir le dossier médical"
                                                style="position: relative;">
                                            <i class="fas fa-folder-medical me-1"></i>
                                            Dossier
                                            @if(($patient->medical_files_count ?? 0) > 0)
                                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" 
                                                      style="font-size: 0.6em; padding: 2px 4px;">
                                                    {{ $patient->medical_files_count }}
                                                </span>
                                            @endif
                                        </button>
                                    </td>
                                    <td>
                                        <div>{{ $patient->created_at->format('d/m/Y') }}</div>
                                        <small class="text-muted">{{ $patient->created_at->diffForHumans() }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button class="btn btn-outline-primary"
                                                    onclick="viewPatient({{ $patient->id }})"
                                                    title="Voir les détails">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <a href="{{ route('admin.patients.edit', $patient) }}"
                                               class="btn btn-outline-warning"
                                               title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button class="btn btn-outline-danger"
                                                    onclick="deletePatient({{ $patient->id }})"
                                                    title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-5">
                                        <i class="fas fa-users fa-3x mb-3"></i>
                                        <h5>Aucun patient trouvé</h5>
                                        <p>Commencez par ajouter votre premier patient.</p>
                                        <a href="#" class="btn btn-primary">
                                            <i class="fas fa-plus me-2"></i>Ajouter un patient
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
    @if(isset($patients) && $patients->hasPages())
    <div class="row">
        <div class="col-12">
            <!-- Pagination Info -->
            <div class="pagination-info">
                <i class="fas fa-info-circle me-2"></i>
                Affichage de {{ $patients->firstItem() }} à {{ $patients->lastItem() }} sur {{ $patients->total() }} résultats
            </div>
            
            <!-- Pagination Links -->
            <div class="d-flex justify-content-center">
                {{ $patients->links() }}
            </div>
        </div>
    </div>
    @endif
</div>

<script>
// Voir les détails d'un patient
function viewPatient(patientId) {
    window.location.href = `/admin/patients/${patientId}`;
}

// Voir le dossier médical
function viewMedicalFile(patientId) {
    window.location.href = `/admin/patients/${patientId}/medical-file`;
}

// Supprimer un patient
function deletePatient(patientId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce patient ? Cette action est irréversible.')) {
        // Créer un formulaire pour la suppression
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/patients/${patientId}`;
        
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
</script>
@endsection

@section('styles')
<style>
/* Style pour le bouton du dossier médical */
.btn-success {
    background-color: #28a745;
    border-color: #28a745;
    color: white;
    transition: all 0.3s ease;
    font-weight: 500;
}

.btn-success:hover {
    background-color: #218838;
    border-color: #1e7e34;
    color: white;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(40, 167, 69, 0.3);
}

.btn-success .fas {
    font-size: 1em;
}

/* Badge de compteur */
.badge.bg-danger {
    background-color: #dc3545 !important;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.1);
    }
    100% {
        transform: scale(1);
    }
}

/* Amélioration des boutons d'action */
.btn-group .btn {
    margin: 0 1px;
    border-radius: 0.375rem;
}

.btn-group .btn:first-child {
    border-top-left-radius: 0.375rem;
    border-bottom-left-radius: 0.375rem;
}

.btn-group .btn:last-child {
    border-top-right-radius: 0.375rem;
    border-bottom-right-radius: 0.375rem;
}

/* Effet hover pour tous les boutons d'action */
.btn-outline-primary:hover,
.btn-outline-warning:hover,
.btn-outline-danger:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}
</style>
@endsection
