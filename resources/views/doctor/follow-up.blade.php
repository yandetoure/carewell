@extends('layouts.doctor')

@section('title', 'Patients Hospitalisés - Docteur')
@section('page-title', 'Patients Hospitalisés')
@section('page-subtitle', 'Surveillez les patients hospitalisés de votre service')
@section('user-role', 'Médecin')

@section('content')
<div class="container-fluid py-4">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
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
                            <p class="text-muted mb-0">Patients hospitalisés</p>
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
                            <h4 class="mb-1">{{ $newAdmissions }}</h4>
                            <p class="text-muted mb-0">Nouvelles admissions</p>
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
                            <i class="fas fa-clock text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $longStayPatients }}</h4>
                            <p class="text-muted mb-0">Séjours longs (>30j)</p>
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
                            <h4 class="mb-1">{{ $expectedDischarges }}</h4>
                            <p class="text-muted mb-0">Sorties prévues</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-bed me-2"></i>Patients hospitalisés
                        </h5>
                        <div class="d-flex gap-2">
                            <a href="{{ route('doctor.appointments') }}" class="btn btn-outline-primary">
                                <i class="fas fa-calendar me-2"></i>Rendez-vous
                            </a>
                            <a href="{{ route('doctor.consultations') }}" class="btn btn-outline-success">
                                <i class="fas fa-stethoscope me-2"></i>Consultations
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des patients hospitalisés -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if($hospitalizedPatients->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Patient</th>
                                        <th>Lit</th>
                                        <th>Date d'admission</th>
                                        <th>Sortie prévue</th>
                                        <th>Durée</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($hospitalizedPatients as $bed)
                                        @php
                                            $patient = $bed->medicalFile->user ?? null;
                                            $daysAdmitted = $bed->days_admitted;
                                            $isLongStay = $daysAdmitted > 30;
                                            $isDischargeSoon = $bed->expected_discharge_date && 
                                                $bed->expected_discharge_date <= now()->addDays(3) &&
                                                $bed->expected_discharge_date >= now();
                                        @endphp
                                        <tr class="{{ $isLongStay ? 'table-warning' : ($isDischargeSoon ? 'table-info' : '') }}">
                                            <td>
                                                @if($patient)
                                                    <div class="d-flex align-items-center">
                                                        <div class="patient-avatar me-3">
                                                            <i class="fas fa-user-circle fa-2x text-primary"></i>
                                                        </div>
                                                        <div>
                                                            <div class="fw-bold">{{ $patient->first_name }} {{ $patient->last_name }}</div>
                                                            <small class="text-muted">{{ $patient->identification_number }}</small>
                                                        </div>
                                                    </div>
                                                @else
                                                    <span class="text-muted">Patient non assigné</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-bed text-primary me-2"></i>
                                                    <div>
                                                        <div class="fw-bold">Lit {{ $bed->bed_number }}</div>
                                                        <small class="text-muted">Chambre {{ $bed->room_number }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <div class="fw-bold">{{ \Carbon\Carbon::parse($bed->admission_date)->format('d/m/Y') }}</div>
                                                    <small class="text-muted">{{ \Carbon\Carbon::parse($bed->admission_date)->format('H:i') }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                @if($bed->expected_discharge_date)
                                                    <div>
                                                        <div class="fw-bold">{{ \Carbon\Carbon::parse($bed->expected_discharge_date)->format('d/m/Y') }}</div>
                                                        <small class="text-muted">{{ \Carbon\Carbon::parse($bed->expected_discharge_date)->diffForHumans() }}</small>
                                                    </div>
                                                @else
                                                    <span class="text-muted">Non définie</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div>
                                                    <div class="fw-bold">{{ $daysAdmitted }} jours</div>
                                                    <small class="text-muted">{{ \Carbon\Carbon::parse($bed->admission_date)->diffForHumans() }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                @if($isLongStay)
                                                    <span class="badge bg-warning">
                                                        <i class="fas fa-clock me-1"></i>Séjour long
                                                    </span>
                                                @elseif($isDischargeSoon)
                                                    <span class="badge bg-info">
                                                        <i class="fas fa-calendar-check me-1"></i>Sortie prévue
                                                    </span>
                                                @else
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-check-circle me-1"></i>Hospitalisé
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    @if($patient)
                                                        <a href="{{ route('doctor.medical-files.show', $patient) }}" 
                                                           class="btn btn-outline-primary" 
                                                           title="Voir le dossier médical">
                                                            <i class="fas fa-file-medical"></i>
                                                        </a>
                                                        <a href="{{ route('doctor.patients.show', $patient) }}" 
                                                           class="btn btn-outline-info" 
                                                           title="Voir le profil">
                                                            <i class="fas fa-user"></i>
                                                        </a>
                                                    @endif
                                                    <button type="button" class="btn btn-outline-success" 
                                                            onclick="viewBedDetails({{ $bed->id }})" 
                                                            title="Détails du lit">
                                                        <i class="fas fa-bed"></i>
                                                    </button>
                                                    @if($isDischargeSoon)
                                                        <button type="button" class="btn btn-outline-warning" 
                                                                onclick="prepareDischarge({{ $bed->id }})" 
                                                                title="Préparer la sortie">
                                                            <i class="fas fa-sign-out-alt"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $hospitalizedPatients->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-bed fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">Aucun patient hospitalisé</h5>
                            <p class="text-muted">Aucun patient n'est actuellement hospitalisé dans votre service.</p>
                            <a href="{{ route('doctor.appointments') }}" class="btn btn-primary">
                                <i class="fas fa-calendar me-2"></i>Voir les rendez-vous
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Conseils pour le suivi hospitalier -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-lightbulb me-2"></i>Conseils pour le suivi hospitalier
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary">📋 Surveillance recommandée</h6>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-exclamation-triangle text-warning me-2"></i>Patients avec séjours longs (>30 jours)</li>
                                <li><i class="fas fa-calendar-check text-info me-2"></i>Patients avec sorties prévues dans les 3 jours</li>
                                <li><i class="fas fa-user-plus text-success me-2"></i>Nouvelles admissions (dernière semaine)</li>
                                <li><i class="fas fa-bed text-primary me-2"></i>Surveillance continue des signes vitaux</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-success">💡 Bonnes pratiques</h6>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-file-medical text-primary me-2"></i>Consultez régulièrement les dossiers médicaux</li>
                                <li><i class="fas fa-clock text-info me-2"></i>Planifiez les visites de routine</li>
                                <li><i class="fas fa-sign-out-alt text-warning me-2"></i>Préparez les sorties à l'avance</li>
                                <li><i class="fas fa-notes-medical text-success me-2"></i>Documentez l'évolution des patients</li>
                            </ul>
                        </div>
                    </div>
                </div>
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

.table tr:hover {
    background-color: rgba(0, 123, 255, 0.05);
}

.table-warning {
    background-color: rgba(255, 193, 7, 0.1);
}

.table-info {
    background-color: rgba(23, 162, 184, 0.1);
}

.patient-avatar {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.card-header h5 {
    color: #495057;
}

.badge {
    font-size: 0.75rem;
}

.btn-group-sm .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}
</style>
@endpush

@push('scripts')
<script>
// Auto-refresh de la page toutes les 5 minutes pour le suivi hospitalier
setTimeout(function() {
    location.reload();
}, 300000);

// Fonction pour voir les détails du lit
function viewBedDetails(bedId) {
    // Pour l'instant, afficher une alerte simple
    // Plus tard, on pourra créer un modal pour afficher les détails complets
    alert('Fonctionnalité de visualisation des détails du lit en cours de développement pour le lit ID: ' + bedId);
}

// Fonction pour préparer la sortie d'un patient
function prepareDischarge(bedId) {
    if (confirm('Êtes-vous sûr de vouloir préparer la sortie de ce patient ?')) {
        // Logique de préparation de sortie
        alert('Fonctionnalité de préparation de sortie en cours de développement pour le lit ID: ' + bedId);
    }
}

// Confirmation pour les actions importantes
document.addEventListener('DOMContentLoaded', function() {
    const dischargeButtons = document.querySelectorAll('button[title="Préparer la sortie"]');
    dischargeButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm('Êtes-vous sûr de vouloir préparer la sortie de ce patient ?')) {
                e.preventDefault();
            }
        });
    });
});
</script>
@endpush