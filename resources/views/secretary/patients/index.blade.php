@extends('layouts.secretary')

@section('title', 'Gestion des Patients - Secrétariat')
@section('page-title', 'Gestion des Patients')
@section('page-subtitle', 'Gérer les patients du service')
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

    <!-- Statistiques des patients -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-primary">
                            <i class="fas fa-users text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $patients->count() }}</h4>
                            <p class="text-muted mb-0">Total patients</p>
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
                            <i class="fas fa-user-plus text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $patients->where('created_at', '>=', now()->startOfMonth())->count() }}</h4>
                            <p class="text-muted mb-0">Nouveaux ce mois</p>
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
                            <i class="fas fa-calendar-check text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $patientsWithAppointments }}</h4>
                            <p class="text-muted mb-0">Avec rendez-vous</p>
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
                            <i class="fas fa-file-medical text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $patientsWithMedicalFiles }}</h4>
                            <p class="text-muted mb-0">Dossiers médicaux</p>
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
                        <i class="fas fa-users me-2"></i>
                        Liste des Patients
                    </h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route('secretary.patients.new') }}" class="btn btn-primary">
                            <i class="fas fa-user-plus me-2"></i>Nouveau Patient
                        </a>
                        <a href="{{ route('secretary.patients.search') }}" class="btn btn-outline-primary">
                            <i class="fas fa-search me-2"></i>Rechercher
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($patients->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Photo</th>
                                        <th>Nom complet</th>
                                        <th>Email</th>
                                        <th>Téléphone</th>
                                        <th>Date d'inscription</th>
                                        <th>Dernier RDV</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($patients as $patient)
                                        <tr>
                                            <td>
                                                @if($patient->photo)
                                                    <img src="{{ asset('storage/' . $patient->photo) }}" 
                                                         alt="Photo" 
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
                                                <div>
                                                    <strong>{{ $patient->first_name }} {{ $patient->last_name }}</strong>
                                                    @if($patient->day_of_birth)
                                                        <br><small class="text-muted">
                                                            {{ \Carbon\Carbon::parse($patient->day_of_birth)->age }} ans
                                                        </small>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>{{ $patient->email }}</td>
                                            <td>{{ $patient->phone_number ?? 'Non renseigné' }}</td>
                                            <td>{{ $patient->created_at->format('d/m/Y') }}</td>
                                            <td>
                                                @php
                                                    $lastAppointment = \App\Models\Appointment::where('user_id', $patient->id)
                                                        ->where('service_id', Auth::user()->service_id)
                                                        ->orderBy('appointment_date', 'desc')
                                                        ->first();
                                                @endphp
                                                @if($lastAppointment)
                                                    {{ \Carbon\Carbon::parse($lastAppointment->appointment_date)->format('d/m/Y') }}
                                                    <br><small class="text-muted">{{ $lastAppointment->status }}</small>
                                                @else
                                                    <span class="text-muted">Aucun RDV</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <button type="button" class="btn btn-outline-primary" 
                                                            onclick="viewPatient({{ $patient->id }})" 
                                                            title="Voir le profil">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-success" 
                                                            onclick="createAppointment({{ $patient->id }})" 
                                                            title="Créer un RDV">
                                                        <i class="fas fa-calendar-plus"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-info" 
                                                            onclick="viewMedicalFile({{ $patient->id }})" 
                                                            title="Dossier médical">
                                                        <i class="fas fa-file-medical"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if($patients->hasPages())
                            <div class="d-flex justify-content-center mt-4">
                                {{ $patients->links() }}
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-users fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">Aucun patient trouvé</h5>
                            <p class="text-muted">Il n'y a aucun patient dans votre service pour le moment.</p>
                            <a href="{{ route('secretary.patients.new') }}" class="btn btn-primary">
                                <i class="fas fa-user-plus me-2"></i>Ajouter le premier patient
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour voir les détails du patient -->
<div class="modal fade" id="patientModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-user me-2"></i>Détails du Patient
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="patientDetails">
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

// Voir les détails d'un patient
function viewPatient(patientId) {
    selectedPatientId = patientId;
    
    document.getElementById('patientDetails').innerHTML = `
        <div class="text-center py-3">
            <i class="fas fa-spinner fa-spin fa-2x text-primary"></i>
            <p class="mt-2">Chargement des détails...</p>
        </div>
    `;
    
    const modal = new bootstrap.Modal(document.getElementById('patientModal'));
    modal.show();
    
    // Simuler le chargement des détails (à remplacer par un appel AJAX réel)
    setTimeout(() => {
        document.getElementById('patientDetails').innerHTML = `
            <div class="row">
                <div class="col-md-6">
                    <h6><i class="fas fa-user me-2"></i>Informations personnelles</h6>
                    <div class="mb-3">
                        <strong>Nom complet:</strong><br>
                        [Nom du patient]
                    </div>
                    <div class="mb-3">
                        <strong>Email:</strong><br>
                        [Email du patient]
                    </div>
                    <div class="mb-3">
                        <strong>Téléphone:</strong><br>
                        [Téléphone du patient]
                    </div>
                </div>
                <div class="col-md-6">
                    <h6><i class="fas fa-calendar me-2"></i>Historique médical</h6>
                    <div class="mb-3">
                        <strong>Dernier rendez-vous:</strong><br>
                        [Date du dernier RDV]
                    </div>
                    <div class="mb-3">
                        <strong>Nombre de RDV:</strong><br>
                        [Nombre total de RDV]
                    </div>
                    <div class="mb-3">
                        <strong>Dossier médical:</strong><br>
                        [Statut du dossier]
                    </div>
                </div>
            </div>
        `;
    }, 1000);
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

// Voir le dossier médical
function viewMedicalFile(patientId) {
    window.location.href = `{{ route('secretary.medical-files') }}?patient=${patientId}`;
}
</script>
@endpush
