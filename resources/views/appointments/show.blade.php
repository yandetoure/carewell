@extends('layouts.app')

@section('title', 'Détails du Rendez-vous')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('appointments') }}">Mes Rendez-vous</a></li>
                    <li class="breadcrumb-item active">Détails</li>
                </ol>
            </nav>

            <!-- Appointment Details Card -->
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-calendar-check me-2"></i>
                            Détails du Rendez-vous
                        </h5>
                        <div class="d-flex gap-2">
                            <a href="{{ route('appointments.edit', $appointment->id) }}" 
                               class="btn btn-outline-warning btn-sm">
                                <i class="fas fa-edit me-1"></i>Modifier
                            </a>
                            <a href="{{ route('appointments') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-arrow-left me-1"></i>Retour
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-8">
                            <!-- Appointment Info -->
                            <div class="appointment-info mb-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="appointment-status me-3">
                                        <span class="badge bg-{{ $appointment->status == 'confirmed' ? 'success' : ($appointment->status == 'pending' ? 'warning' : ($appointment->status == 'cancelled' ? 'danger' : 'secondary')) }} fs-6">
                                            {{ ucfirst($appointment->status) }}
                                        </span>
                                    </div>
                                    <div class="appointment-number">
                                        <small class="text-muted">#{{ $appointment->id }}</small>
                                    </div>
                                </div>

                                <h4 class="mb-3">
                                    @if($appointment->service)
                                        {{ $appointment->service->name }}
                                    @else
                                        Rendez-vous médical
                                    @endif
                                </h4>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="info-item">
                                            <i class="fas fa-calendar text-primary me-2"></i>
                                            <strong>Date:</strong> 
                                            {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y') }}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-item">
                                            <i class="fas fa-clock text-primary me-2"></i>
                                            <strong>Heure:</strong> 
                                            {{ $appointment->appointment_time }}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-item">
                                            <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                            <strong>Lieu:</strong> 
                                            {{ $appointment->location ?? 'Non spécifié' }}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-item">
                                            <i class="fas fa-tag text-primary me-2"></i>
                                            <strong>Type:</strong> 
                                            @if($appointment->is_urgent)
                                                <span class="badge bg-danger">Urgent</span>
                                            @else
                                                <span class="badge bg-secondary">Standard</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                @if($appointment->notes)
                                <div class="mt-4">
                                    <h6><i class="fas fa-sticky-note text-primary me-2"></i>Notes</h6>
                                    <div class="bg-light p-3 rounded">
                                        {{ $appointment->notes }}
                                    </div>
                                </div>
                                @endif
                            </div>

                            <!-- Service Details -->
                            @if($appointment->service)
                            <div class="service-details mb-4">
                                <h6><i class="fas fa-stethoscope text-primary me-2"></i>Service</h6>
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col-md-2">
                                                @if($appointment->service->photo)
                                                    <img src="{{ asset('storage/' . $appointment->service->photo) }}" 
                                                         alt="{{ $appointment->service->name }}" 
                                                         class="img-fluid rounded">
                                                @else
                                                    <div class="bg-secondary rounded d-flex align-items-center justify-content-center" 
                                                         style="width: 60px; height: 60px;">
                                                        <i class="fas fa-stethoscope text-white"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="col-md-8">
                                                <h6 class="mb-1">{{ $appointment->service->name }}</h6>
                                                <p class="text-muted mb-0">{{ Str::limit($appointment->service->description, 100) }}</p>
                                            </div>
                                            <div class="col-md-2 text-end">
                                                <span class="badge bg-primary fs-6">{{ number_format($appointment->service->price, 2) }} €</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>

                        <div class="col-md-4">
                            <!-- Quick Actions -->
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h6 class="mb-0"><i class="fas fa-tools me-2"></i>Actions</h6>
                                </div>
                                <div class="card-body">
                                    <div class="d-grid gap-2">
                                        @if($appointment->status == 'pending')
                                            <button class="btn btn-success btn-sm" onclick="confirmAppointment({{ $appointment->id }})">
                                                <i class="fas fa-check me-1"></i>Confirmer
                                            </button>
                                        @endif
                                        
                                        @if($appointment->status != 'cancelled')
                                            <button class="btn btn-warning btn-sm" onclick="rescheduleAppointment({{ $appointment->id }})">
                                                <i class="fas fa-calendar-alt me-1"></i>Reprogrammer
                                            </button>
                                            
                                            <button class="btn btn-danger btn-sm" onclick="cancelAppointment({{ $appointment->id }})">
                                                <i class="fas fa-times me-1"></i>Annuler
                                            </button>
                                        @endif
                                        
                                        <a href="#" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-print me-1"></i>Imprimer
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Appointment Timeline -->
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0"><i class="fas fa-history me-2"></i>Historique</h6>
                                </div>
                                <div class="card-body">
                                    <div class="timeline">
                                        <div class="timeline-item">
                                            <div class="timeline-marker bg-primary"></div>
                                            <div class="timeline-content">
                                                <small class="text-muted">{{ $appointment->created_at->format('d/m/Y H:i') }}</small>
                                                <p class="mb-0">Rendez-vous créé</p>
                                            </div>
                                        </div>
                                        
                                        @if($appointment->status == 'confirmed')
                                        <div class="timeline-item">
                                            <div class="timeline-marker bg-success"></div>
                                            <div class="timeline-content">
                                                <small class="text-muted">{{ $appointment->updated_at->format('d/m/Y H:i') }}</small>
                                                <p class="mb-0">Rendez-vous confirmé</p>
                                            </div>
                                        </div>
                                        @endif
                                        
                                        @if($appointment->status == 'cancelled')
                                        <div class="timeline-item">
                                            <div class="timeline-marker bg-danger"></div>
                                            <div class="timeline-content">
                                                <small class="text-muted">{{ $appointment->updated_at->format('d/m/Y H:i') }}</small>
                                                <p class="mb-0">Rendez-vous annulé</p>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.appointment-info .info-item {
    margin-bottom: 0.5rem;
}

.timeline {
    position: relative;
    padding-left: 20px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 9px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e9ecef;
}

.timeline-item {
    position: relative;
    margin-bottom: 1rem;
}

.timeline-marker {
    position: absolute;
    left: -20px;
    top: 0;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    border: 3px solid white;
    box-shadow: 0 0 0 3px #e9ecef;
}

.timeline-content {
    padding-left: 10px;
}

.timeline-content p {
    font-size: 0.9rem;
    margin-top: 0.25rem;
}
</style>

<script>
function confirmAppointment(appointmentId) {
    if (confirm('Êtes-vous sûr de vouloir confirmer ce rendez-vous ?')) {
        // Ici vous pouvez ajouter la logique pour confirmer le rendez-vous
        alert('Rendez-vous confirmé !');
    }
}

function rescheduleAppointment(appointmentId) {
    // Rediriger vers la page de modification
    window.location.href = `/appointments/${appointmentId}/edit`;
}

function cancelAppointment(appointmentId) {
    if (confirm('Êtes-vous sûr de vouloir annuler ce rendez-vous ?')) {
        // Ici vous pouvez ajouter la logique pour annuler le rendez-vous
        alert('Rendez-vous annulé !');
    }
}
</script>
@endsection
