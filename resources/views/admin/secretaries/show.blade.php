@extends('layouts.admin')

@section('title', 'Détails Secrétaire - Admin')
@section('page-title', 'Détails de la Secrétaire')
@section('page-subtitle', 'Informations complètes sur la secrétaire')
@section('user-role', 'Administrateur')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <!-- Informations générales -->
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-body text-center">
                    @if($secretary->photo)
                        <img src="{{ asset('storage/' . $secretary->photo) }}" 
                             alt="{{ $secretary->name }}" 
                             class="rounded-circle mb-3" 
                             style="width: 120px; height: 120px; object-fit: cover;">
                    @else
                        <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3"
                             style="width: 120px; height: 120px;">
                            <i class="fas fa-user-tie text-white fa-3x"></i>
                        </div>
                    @endif
                    
                    <h4 class="mb-1">{{ $secretary->name }}</h4>
                    <p class="text-muted mb-3">{{ $secretary->email }}</p>
                    
                    <div class="d-flex justify-content-center gap-2">
                        <a href="{{ route('admin.secretaries.edit', $secretary) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-1"></i>Modifier
                        </a>
                        <button class="btn btn-danger" onclick="deleteSecretary({{ $secretary->id }})">
                            <i class="fas fa-trash me-1"></i>Supprimer
                        </button>
                    </div>
                </div>
            </div>

            <!-- Informations de contact -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-address-card me-2"></i>
                        Informations de contact
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-envelope text-primary me-3"></i>
                                <div>
                                    <strong>Email</strong><br>
                                    <span class="text-muted">{{ $secretary->email }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-phone text-success me-3"></i>
                                <div>
                                    <strong>Téléphone</strong><br>
                                    <span class="text-muted">{{ $secretary->phone ?? 'Non renseigné' }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-map-marker-alt text-danger me-3"></i>
                                <div>
                                    <strong>Adresse</strong><br>
                                    <span class="text-muted">{{ $secretary->adress ?? 'Non renseignée' }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-calendar text-info me-3"></i>
                                <div>
                                    <strong>Date d'inscription</strong><br>
                                    <span class="text-muted">{{ $secretary->created_at->format('d/m/Y à H:i') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistiques et activités -->
        <div class="col-lg-8 mb-4">
            <!-- Statistiques -->
            <div class="row g-4 mb-4">
                <div class="col-md-3">
                    <div class="card stat-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon bg-primary">
                                    <i class="fas fa-calendar-check text-white"></i>
                                </div>
                                <div class="ms-3">
                                    <h4 class="mb-1">{{ $secretary->appointments_count ?? 0 }}</h4>
                                    <p class="text-muted mb-0">Rendez-vous</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card stat-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon bg-success">
                                    <i class="fas fa-check-circle text-white"></i>
                                </div>
                                <div class="ms-3">
                                    <h4 class="mb-1">{{ $secretary->appointments()->where('status', 'confirmed')->count() }}</h4>
                                    <p class="text-muted mb-0">Confirmés</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card stat-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon bg-warning">
                                    <i class="fas fa-clock text-white"></i>
                                </div>
                                <div class="ms-3">
                                    <h4 class="mb-1">{{ $secretary->appointments()->where('status', 'pending')->count() }}</h4>
                                    <p class="text-muted mb-0">En attente</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card stat-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon bg-info">
                                    <i class="fas fa-calendar-day text-white"></i>
                                </div>
                                <div class="ms-3">
                                    <h4 class="mb-1">{{ $secretary->appointments()->whereDate('appointment_date', now()->toDateString())->count() }}</h4>
                                    <p class="text-muted mb-0">Aujourd'hui</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rendez-vous récents -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar-alt me-2"></i>
                        Rendez-vous récents
                    </h5>
                    <a href="{{ route('admin.appointments') }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-eye me-1"></i>Voir tous
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Date/Heure</th>
                                    <th>Patient</th>
                                    <th>Service</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($secretary->appointments()->with(['patient', 'service'])->orderBy('appointment_date', 'desc')->take(10)->get() as $appointment)
                                <tr>
                                    <td>
                                        <div>{{ $appointment->appointment_date->format('d/m/Y') }}</div>
                                        <small class="text-muted">{{ $appointment->appointment_date->format('H:i') }}</small>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-2"
                                                 style="width: 32px; height: 32px;">
                                                <i class="fas fa-user text-white" style="font-size: 0.8em;"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold">{{ $appointment->patient->name ?? 'Patient' }}</div>
                                                <small class="text-muted">{{ $appointment->patient->email ?? 'N/A' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $appointment->service->name ?? 'Service' }}</span>
                                    </td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'confirmed' => 'success',
                                                'pending' => 'warning',
                                                'cancelled' => 'danger'
                                            ];
                                        @endphp
                                        <span class="badge bg-{{ $statusColors[$appointment->status] ?? 'secondary' }}">
                                            {{ ucfirst($appointment->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-primary" onclick="viewAppointment({{ $appointment->id }})" title="Voir">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-outline-warning" onclick="editAppointment({{ $appointment->id }})" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        <i class="fas fa-calendar-times fa-2x mb-2"></i>
                                        <p>Aucun rendez-vous trouvé</p>
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

    <!-- Activité récente -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-history me-2"></i>
                        Activité récente
                    </h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        @for($i = 1; $i <= 8; $i++)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1">Action {{ $i }}</h6>
                                        <p class="text-muted mb-1">Description de l'action effectuée par la secrétaire</p>
                                        <small class="text-muted">{{ now()->subHours($i)->diffForHumans() }}</small>
                                    </div>
                                    <span class="badge bg-primary">Rendez-vous</span>
                                </div>
                            </div>
                        </div>
                        @endfor
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
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

function viewAppointment(appointmentId) {
    window.location.href = `/admin/appointments/${appointmentId}`;
}

function editAppointment(appointmentId) {
    window.location.href = `/admin/appointments/${appointmentId}/edit`;
}
</script>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #dee2e6;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -22px;
    top: 5px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid #fff;
    box-shadow: 0 0 0 2px #dee2e6;
}

.timeline-content {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    border-left: 3px solid #007bff;
}
</style>
@endsection
