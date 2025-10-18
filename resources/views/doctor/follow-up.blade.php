@extends('layouts.doctor')

@section('title', 'Suivi des Patients - Docteur')
@section('page-title', 'Suivi des Patients')
@section('page-subtitle', 'Gérez le suivi médical de vos patients')
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

    <!-- Statistiques du suivi -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-primary">
                            <i class="fas fa-users text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $totalPatients }}</h4>
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
                            <i class="fas fa-user-check text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $activePatients }}</h4>
                            <p class="text-muted mb-0">Patients actifs</p>
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
                            <i class="fas fa-exclamation-triangle text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $followUpNeeded }}</h4>
                            <p class="text-muted mb-0">Suivi requis</p>
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
                            <i class="fas fa-calendar-alt text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ now()->format('M Y') }}</h4>
                            <p class="text-muted mb-0">Mois actuel</p>
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
                            <i class="fas fa-user-friends me-2"></i>Liste des patients
                        </h5>
                        <div class="d-flex gap-2">
                            <a href="{{ route('doctor.patients') }}" class="btn btn-outline-primary">
                                <i class="fas fa-list me-2"></i>Tous les patients
                            </a>
                            <a href="{{ route('doctor.patients.new') }}" class="btn btn-outline-success">
                                <i class="fas fa-user-plus me-2"></i>Nouveau patient
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des patients -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if($patients->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Patient</th>
                                        <th>Contact</th>
                                        <th>Dernier RDV</th>
                                        <th>Prochain RDV</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($patients as $patient)
                                        @php
                                            $lastAppointment = $patient->appointments->first();
                                            $nextAppointment = $patient->appointments->where('appointment_date', '>=', now()->toDateString())->first();
                                            $isFollowUpNeeded = $lastAppointment && 
                                                $lastAppointment->status == 'completed' && 
                                                $lastAppointment->appointment_date <= now()->subDays(30)->toDateString() &&
                                                $lastAppointment->appointment_date >= now()->subDays(90)->toDateString();
                                        @endphp
                                        <tr class="{{ $isFollowUpNeeded ? 'table-warning' : '' }}">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="patient-avatar me-3">
                                                        <i class="fas fa-user-circle fa-2x text-primary"></i>
                                                    </div>
                                                    <div>
                                                        <div class="fw-bold">{{ $patient->first_name }} {{ $patient->last_name }}</div>
                                                        <small class="text-muted">{{ $patient->identification_number }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <div><i class="fas fa-envelope text-info me-1"></i>{{ $patient->email }}</div>
                                                    <div><i class="fas fa-phone text-success me-1"></i>{{ $patient->phone_number ?? 'Non renseigné' }}</div>
                                                </div>
                                            </td>
                                            <td>
                                                @if($lastAppointment)
                                                    <div>
                                                        <div class="fw-bold">{{ \Carbon\Carbon::parse($lastAppointment->appointment_date)->format('d/m/Y') }}</div>
                                                        <small class="text-muted">{{ \Carbon\Carbon::parse($lastAppointment->appointment_time)->format('H:i') }}</small>
                                                        <br>
                                                        <span class="badge bg-{{ $lastAppointment->status == 'completed' ? 'success' : 'info' }}">
                                                            {{ ucfirst($lastAppointment->status) }}
                                                        </span>
                                                    </div>
                                                @else
                                                    <span class="text-muted">Aucun RDV</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($nextAppointment)
                                                    <div>
                                                        <div class="fw-bold">{{ \Carbon\Carbon::parse($nextAppointment->appointment_date)->format('d/m/Y') }}</div>
                                                        <small class="text-muted">{{ \Carbon\Carbon::parse($nextAppointment->appointment_time)->format('H:i') }}</small>
                                                        <br>
                                                        <span class="badge bg-{{ $nextAppointment->status == 'confirmed' ? 'success' : 'warning' }}">
                                                            {{ ucfirst($nextAppointment->status) }}
                                                        </span>
                                                    </div>
                                                @else
                                                    <span class="text-muted">Aucun RDV prévu</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($isFollowUpNeeded)
                                                    <span class="badge bg-warning">
                                                        <i class="fas fa-exclamation-triangle me-1"></i>Suivi requis
                                                    </span>
                                                @elseif($patient->appointments->where('appointment_date', '>=', now()->subDays(30))->count() > 0)
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-check-circle me-1"></i>Actif
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary">
                                                        <i class="fas fa-clock me-1"></i>Inactif
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('doctor.patients.show', $patient) }}" 
                                                       class="btn btn-outline-primary" 
                                                       title="Voir le profil">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('doctor.patients.history', $patient) }}" 
                                                       class="btn btn-outline-info" 
                                                       title="Historique">
                                                        <i class="fas fa-history"></i>
                                                    </a>
                                                    <a href="{{ route('doctor.patients.appointments', $patient) }}" 
                                                       class="btn btn-outline-success" 
                                                       title="RDV">
                                                        <i class="fas fa-calendar"></i>
                                                    </a>
                                                    @if($isFollowUpNeeded)
                                                        <a href="{{ route('doctor.patients.new') }}?patient_id={{ $patient->id }}" 
                                                           class="btn btn-outline-warning" 
                                                           title="Planifier un suivi">
                                                            <i class="fas fa-calendar-plus"></i>
                                                        </a>
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
                            {{ $patients->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-user-friends fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">Aucun patient récent</h5>
                            <p class="text-muted">Vous n'avez pas de patients avec des rendez-vous récents.</p>
                            <a href="{{ route('doctor.patients') }}" class="btn btn-primary">
                                <i class="fas fa-users me-2"></i>Voir tous les patients
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Conseils pour le suivi -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-lightbulb me-2"></i>Conseils pour le suivi médical
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary">📋 Suivi recommandé</h6>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-check-circle text-success me-2"></i>Patients avec RDV dans les 30 derniers jours</li>
                                <li><i class="fas fa-exclamation-triangle text-warning me-2"></i>Patients nécessitant un suivi (30-90 jours)</li>
                                <li><i class="fas fa-clock text-info me-2"></i>Patients avec RDV confirmés à venir</li>
                                <li><i class="fas fa-user-check text-primary me-2"></i>Patients avec consultations terminées</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-success">💡 Bonnes pratiques</h6>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-calendar-plus text-primary me-2"></i>Planifiez les rendez-vous de suivi à l'avance</li>
                                <li><i class="fas fa-bell text-info me-2"></i>Activez les rappels pour les patients</li>
                                <li><i class="fas fa-file-medical text-success me-2"></i>Consultez régulièrement les dossiers médicaux</li>
                                <li><i class="fas fa-notes-medical text-warning me-2"></i>Prenez des notes sur l'évolution des patients</li>
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
// Auto-refresh de la page toutes les 10 minutes pour le suivi
setTimeout(function() {
    location.reload();
}, 600000);

// Confirmation pour les actions importantes
document.addEventListener('DOMContentLoaded', function() {
    const followUpButtons = document.querySelectorAll('a[title="Planifier un suivi"]');
    followUpButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm('Êtes-vous sûr de vouloir planifier un rendez-vous de suivi pour ce patient ?')) {
                e.preventDefault();
            }
        });
    });
});
</script>
@endpush
