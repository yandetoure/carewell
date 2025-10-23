@extends('layouts.secretary')

@section('title', 'Patients Hospitalisés - Secrétariat')
@section('page-title', 'Patients Hospitalisés')
@section('page-subtitle', 'Gérer les patients hospitalisés du service')
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

    <!-- Statistiques des patients hospitalisés -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-primary">
                            <i class="fas fa-bed text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $totalHospitalized }}</h4>
                            <p class="text-muted mb-0">Actuellement hospitalisés</p>
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
                            <h4 class="mb-1">{{ $recentAdmissions }}</h4>
                            <p class="text-muted mb-0">Nouvelles admissions (7 jours)</p>
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
                            <i class="fas fa-calendar-day text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ round($averageStay ?? 0, 1) }}</h4>
                            <p class="text-muted mb-0">Durée moyenne (jours)</p>
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
                            <i class="fas fa-hospital text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $hospitalizedPatients->count() }}</h4>
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
                        <i class="fas fa-bed me-2"></i>
                        Patients Hospitalisés
                    </h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route('secretary.patients') }}" class="btn btn-outline-primary">
                            <i class="fas fa-users me-2"></i>Tous les Patients
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($hospitalizedPatients->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Patient</th>
                                        <th>Lit</th>
                                        <th>Date d'admission</th>
                                        <th>Durée de séjour</th>
                                        <th>Date de sortie prévue</th>
                                        <th>Admis par</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($hospitalizedPatients as $admission)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($admission->patient && $admission->patient->photo)
                                                        <img src="{{ asset('storage/' . $admission->patient->photo) }}" 
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
                                                        @if($admission->patient)
                                                            <strong>{{ $admission->patient->first_name }} {{ $admission->patient->last_name }}</strong>
                                                            <br><small class="text-muted">{{ $admission->patient->email }}</small>
                                                        @else
                                                            <span class="text-muted">Patient non trouvé</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @if($admission->bed)
                                                    <span class="badge bg-info">{{ $admission->bed->room_number ?? 'N/A' }} - {{ $admission->bed->bed_number ?? 'N/A' }}</span>
                                                @else
                                                    <span class="text-muted">Lit non assigné</span>
                                                @endif
                                            </td>
                                            <td>
                                                <strong>{{ $admission->admission_date->format('d/m/Y') }}</strong>
                                                <br><small class="text-muted">{{ $admission->admission_date->format('H:i') }}</small>
                                            </td>
                                            <td>
                                                <span class="badge bg-primary">{{ $admission->duration }} jour(s)</span>
                                            </td>
                                            <td>
                                                @if($admission->expected_discharge_date)
                                                    <strong>{{ $admission->expected_discharge_date->format('d/m/Y') }}</strong>
                                                    @if($admission->expected_discharge_date->isPast())
                                                        <br><small class="text-danger">En retard</small>
                                                    @elseif($admission->expected_discharge_date->isToday())
                                                        <br><small class="text-warning">Aujourd'hui</small>
                                                    @endif
                                                @else
                                                    <span class="text-muted">Non définie</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($admission->admittedByUser)
                                                    {{ $admission->admittedByUser->first_name }} {{ $admission->admittedByUser->last_name }}
                                                @else
                                                    <span class="text-muted">Non renseigné</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($admission->isActive())
                                                    <span class="badge bg-success">Hospitalisé</span>
                                                @else
                                                    <span class="badge bg-secondary">Sorti</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    @if($admission->patient)
                                                        <button type="button" class="btn btn-outline-primary" 
                                                                onclick="viewPatient({{ $admission->patient->id }})" 
                                                                title="Voir le patient">
                                                            <i class="fas fa-user"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-outline-info" 
                                                                onclick="viewMedicalFile({{ $admission->patient->id }})" 
                                                                title="Dossier médical">
                                                            <i class="fas fa-file-medical"></i>
                                                        </button>
                                                    @endif
                                                    <button type="button" class="btn btn-outline-success" 
                                                            onclick="viewAdmission({{ $admission->id }})" 
                                                            title="Voir l'admission">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if($hospitalizedPatients->hasPages())
                            <div class="d-flex justify-content-center mt-4">
                                {{ $hospitalizedPatients->links() }}
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-bed fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">Aucun patient hospitalisé</h5>
                            <p class="text-muted">Il n'y a actuellement aucun patient hospitalisé dans votre service.</p>
                            <a href="{{ route('secretary.patients') }}" class="btn btn-primary">
                                <i class="fas fa-users me-2"></i>Voir tous les patients
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour voir les détails de l'admission -->
<div class="modal fade" id="admissionModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-bed me-2"></i>Détails de l'Admission
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="admissionDetails">
                <!-- Contenu dynamique -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Fermer
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
// Voir les détails d'un patient
function viewPatient(patientId) {
    window.location.href = `{{ route('secretary.patients') }}?patient=${patientId}`;
}

// Voir le dossier médical
function viewMedicalFile(patientId) {
    window.location.href = `{{ route('secretary.medical-files') }}?patient=${patientId}`;
}

// Voir les détails d'une admission
function viewAdmission(admissionId) {
    document.getElementById('admissionDetails').innerHTML = `
        <div class="text-center py-3">
            <i class="fas fa-spinner fa-spin fa-2x text-primary"></i>
            <p class="mt-2">Chargement des détails de l'admission...</p>
        </div>
    `;
    
    const modal = new bootstrap.Modal(document.getElementById('admissionModal'));
    modal.show();
    
    // Simuler le chargement des détails (à remplacer par un appel AJAX réel)
    setTimeout(() => {
        document.getElementById('admissionDetails').innerHTML = `
            <div class="row">
                <div class="col-md-6">
                    <h6><i class="fas fa-user me-2"></i>Informations du patient</h6>
                    <div class="mb-3">
                        <strong>Nom complet:</strong><br>
                        [Nom du patient]
                    </div>
                    <div class="mb-3">
                        <strong>Date d'admission:</strong><br>
                        [Date d'admission]
                    </div>
                    <div class="mb-3">
                        <strong>Durée de séjour:</strong><br>
                        [Durée] jours
                    </div>
                </div>
                <div class="col-md-6">
                    <h6><i class="fas fa-bed me-2"></i>Détails de l'admission</h6>
                    <div class="mb-3">
                        <strong>Lit assigné:</strong><br>
                        [Numéro de lit]
                    </div>
                    <div class="mb-3">
                        <strong>Date de sortie prévue:</strong><br>
                        [Date de sortie]
                    </div>
                    <div class="mb-3">
                        <strong>Admis par:</strong><br>
                        [Nom du médecin]
                    </div>
                </div>
            </div>
        `;
    }, 1000);
}
</script>
@endpush
