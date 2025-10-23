@extends('layouts.secretary')

@section('title', 'Dossiers Médicaux - Secrétariat')
@section('page-title', 'Dossiers Médicaux')
@section('page-subtitle', 'Gérer les dossiers médicaux des patients du service')
@section('user-role', 'Secrétaire')

@section('content')
<div class="container-fluid py-4">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Statistiques des dossiers médicaux -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-primary">
                            <i class="fas fa-file-medical text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $totalFiles }}</h4>
                            <p class="text-muted mb-0">Total dossiers</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-success">
                            <i class="fas fa-calendar-day text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $recentFiles }}</h4>
                            <p class="text-muted mb-0">Nouveaux (7 jours)</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-info">
                            <i class="fas fa-users text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $patientsWithFiles }}</h4>
                            <p class="text-muted mb-0">Patients avec dossiers</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-warning">
                            <i class="fas fa-stethoscope text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $medicalFiles->count() }}</h4>
                            <p class="text-muted mb-0">Affichés</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-file-medical me-2"></i>
                        Dossiers Médicaux des Patients
                    </h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route('secretary.patients') }}" class="btn btn-outline-primary">
                            <i class="fas fa-users me-2"></i>Patients
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($medicalFiles->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Patient</th>
                                        <th>Date de création</th>
                                        <th>Dernière modification</th>
                                        <th>Notes</th>
                                        <th>Antécédents</th>
                                        <th>Prescriptions</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($medicalFiles as $medicalFile)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($medicalFile->user->photo)
                                                        <img src="{{ asset('storage/' . $medicalFile->user->photo) }}" 
                                                             alt="Photo" 
                                                             class="rounded-circle me-3" 
                                                             style="width: 40px; height: 40px; object-fit: cover;">
                                                    @else
                                                        <div class="bg-secondary rounded-circle me-3 d-flex align-items-center justify-content-center" 
                                                             style="width: 40px; height: 40px;">
                                                            <i class="fas fa-user text-white"></i>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <strong>{{ $medicalFile->user->first_name }} {{ $medicalFile->user->last_name }}</strong>
                                                        <br><small class="text-muted">{{ $medicalFile->user->email }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $medicalFile->created_at->format('d/m/Y à H:i') }}</td>
                                            <td>{{ $medicalFile->updated_at->format('d/m/Y à H:i') }}</td>
                                            <td>
                                                <span class="badge bg-info">
                                                    {{ $medicalFile->note->count() ?? 0 }} note(s)
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-warning">
                                                    {{ $medicalFile->medicalHistories->count() ?? 0 }} antécédent(s)
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-success">
                                                    {{ $medicalFile->medicalprescription->count() ?? 0 }} prescription(s)
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <button type="button" class="btn btn-outline-primary" 
                                                            onclick="viewMedicalFile({{ $medicalFile->id }})" 
                                                            title="Voir le dossier">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-info" 
                                                            onclick="viewPatient({{ $medicalFile->user_id }})" 
                                                            title="Voir le patient">
                                                        <i class="fas fa-user"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-success" 
                                                            onclick="createAppointment({{ $medicalFile->user_id }})" 
                                                            title="Créer un RDV">
                                                        <i class="fas fa-calendar-plus"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if($medicalFiles->hasPages())
                            <div class="d-flex justify-content-center mt-4">
                                {{ $medicalFiles->links() }}
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-file-medical fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">Aucun dossier médical trouvé</h5>
                            <p class="text-muted">Il n'y a aucun dossier médical pour les patients de votre service pour le moment.</p>
                            <a href="{{ route('secretary.patients') }}" class="btn btn-primary">
                                <i class="fas fa-users me-2"></i>Voir les patients
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour voir les détails du dossier médical -->
<div class="modal fade" id="medicalFileModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-file-medical me-2"></i>Dossier Médical
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="medicalFileDetails">
                <!-- Contenu dynamique -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Fermer
                </button>
                <button type="button" class="btn btn-primary" onclick="createAppointmentFromModal()">
                    <i class="fas fa-calendar-plus me-1"></i>Créer un RDV
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
}

.table th {
    border-top: none;
    font-weight: 600;
    color: #5a5c69;
}

.table td {
    vertical-align: middle;
}

.btn-group-sm .btn {
    padding: 0.25rem 0.5rem;
}
</style>
@endpush

@push('scripts')
<script>
let selectedPatientId = null;

// Voir les détails d'un dossier médical
function viewMedicalFile(medicalFileId) {
    document.getElementById('medicalFileDetails').innerHTML = `
        <div class="text-center py-3">
            <i class="fas fa-spinner fa-spin fa-2x text-primary"></i>
            <p class="mt-2">Chargement du dossier médical...</p>
        </div>
    `;
    
    const modal = new bootstrap.Modal(document.getElementById('medicalFileModal'));
    modal.show();
    
    // Simuler le chargement des détails (à remplacer par un appel AJAX réel)
    setTimeout(() => {
        document.getElementById('medicalFileDetails').innerHTML = `
            <div class="row">
                <div class="col-md-6">
                    <h6><i class="fas fa-user me-2"></i>Informations du patient</h6>
                    <div class="mb-3">
                        <strong>Nom complet:</strong><br>
                        [Nom du patient]
                    </div>
                    <div class="mb-3">
                        <strong>Date de naissance:</strong><br>
                        [Date de naissance]
                    </div>
                    <div class="mb-3">
                        <strong>Groupe sanguin:</strong><br>
                        [Groupe sanguin]
                    </div>
                </div>
                <div class="col-md-6">
                    <h6><i class="fas fa-file-medical me-2"></i>Détails du dossier</h6>
                    <div class="mb-3">
                        <strong>Date de création:</strong><br>
                        [Date de création]
                    </div>
                    <div class="mb-3">
                        <strong>Dernière modification:</strong><br>
                        [Dernière modification]
                    </div>
                    <div class="mb-3">
                        <strong>Nombre de notes:</strong><br>
                        [Nombre de notes]
                    </div>
                </div>
            </div>
        `;
    }, 1000);
}

// Voir les détails d'un patient
function viewPatient(patientId) {
    selectedPatientId = patientId;
    window.location.href = `{{ route('secretary.patients') }}?patient=${patientId}`;
}

// Créer un rendez-vous pour un patient
function createAppointment(patientId) {
    selectedPatientId = patientId;
    window.location.href = `{{ route('secretary.appointments.create') }}?patient=${patientId}`;
}

// Créer un rendez-vous depuis le modal
function createAppointmentFromModal() {
    if (selectedPatientId) {
        createAppointment(selectedPatientId);
    }
}
</script>
@endpush
