@extends('layouts.nurse')

@section('title', 'Rendez-vous - CareWell')
@section('page-title', 'Gestion des Rendez-vous')
@section('page-subtitle', 'Gérer les Rendez-vous des Patients')
@section('user-role', 'Infirmière')

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

    <!-- Statistics -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-primary">
                            <i class="fas fa-calendar-day text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $todayAppointments }}</h4>
                            <p class="text-muted mb-0">Rendez-vous d'Aujourd'hui</p>
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
                            <i class="fas fa-check-circle text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $confirmedAppointments }}</h4>
                            <p class="text-muted mb-0">Confirmés</p>
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
                            <h4 class="mb-1">{{ $pendingAppointments }}</h4>
                            <p class="text-muted mb-0">En Attente</p>
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
                            <i class="fas fa-calendar-week text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $weekAppointments }}</h4>
                            <p class="text-muted mb-0">Cette Semaine</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="dateFilter">Date</label>
                                <input type="date" class="form-control" id="dateFilter" value="{{ today()->format('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="statusFilter">Statut</label>
                                <select class="form-control" id="statusFilter">
                                    <option value="">Tous les Statuts</option>
                                    <option value="pending">En Attente</option>
                                    <option value="confirmed">Confirmé</option>
                                    <option value="completed">Terminé</option>
                                    <option value="cancelled">Annulé</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="serviceFilter">Service</label>
                                <select class="form-control" id="serviceFilter">
                                    <option value="">Tous les Services</option>
                                    @foreach($services as $service)
                                        <option value="{{ $service->id }}">{{ $service->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <button type="button" class="btn btn-primary w-100">
                                    <i class="fas fa-filter me-1"></i>Filtrer
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Today's Schedule -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-calendar-day me-2"></i>Planning d'Aujourd'hui
                    </h5>
                </div>
                <div class="card-body">
                    @if($todaySchedule->count() > 0)
                        <div class="row">
                            @foreach($todaySchedule as $appointment)
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="card border-{{ $appointment->status == 'confirmed' ? 'success' : ($appointment->status == 'pending' ? 'warning' : 'secondary') }}">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <h6 class="card-title mb-0">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i') }}</h6>
                                                <span class="badge bg-{{ $appointment->status == 'confirmed' ? 'success' : ($appointment->status == 'pending' ? 'warning' : 'secondary') }}">
                                                    {{ ucfirst($appointment->status) }}
                                                </span>
                                            </div>
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="fas fa-user text-primary me-2"></i>
                                                <span class="fw-bold">{{ $appointment->user->first_name }} {{ $appointment->user->last_name }}</span>
                                            </div>
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="fas fa-stethoscope text-info me-2"></i>
                                                <span>{{ $appointment->service->name ?? 'Non disponible' }}</span>
                                            </div>
                                            <div class="d-flex align-items-center mb-3">
                                                <i class="fas fa-user-md text-success me-2"></i>
                                                <span>{{ $appointment->doctor->first_name ?? 'Non disponible' }} {{ $appointment->doctor->last_name ?? 'Non disponible' }}</span>
                                            </div>
                                            <div class="btn-group w-100">
                                                <button type="button" class="btn btn-outline-primary btn-sm" title="Voir Détails">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-success btn-sm" title="Commencer Soins">
                                                    <i class="fas fa-play"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-info btn-sm" title="Signes Vitaux">
                                                    <i class="fas fa-heartbeat"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-calendar-day fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Aucun Rendez-vous Aujourd'hui</h5>
                            <p class="text-muted">Aucun rendez-vous programmé pour aujourd'hui.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- All Appointments -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-calendar-alt me-2"></i>Tous les Rendez-vous
                    </h5>
                </div>
                <div class="card-body">
                    @if($appointments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Date & Heure</th>
                                        <th>Patient</th>
                                        <th>Service</th>
                                        <th>Médecin</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($appointments as $appointment)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-calendar text-primary me-2"></i>
                                                    <div>
                                                        <div class="fw-bold">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y') }}</div>
                                                        <small class="text-muted">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i') }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="patient-avatar me-3">
                                                        <i class="fas fa-user-circle fa-2x text-primary"></i>
                                                    </div>
                                                    <div>
                                                        <div class="fw-bold">{{ $appointment->user->first_name }} {{ $appointment->user->last_name }}</div>
                                                        <small class="text-muted">{{ $appointment->user->phone_number ?? 'Non disponible' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-stethoscope text-info me-2"></i>
                                                    {{ $appointment->service->name ?? 'Non disponible' }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-user-md text-success me-2"></i>
                                                    {{ $appointment->doctor->first_name ?? 'Non disponible' }} {{ $appointment->doctor->last_name ?? 'Non disponible' }}
                                                </div>
                                            </td>
                                            <td>
                                                @if($appointment->status == 'confirmed')
                                                    <span class="badge bg-success">Confirmé</span>
                                                @elseif($appointment->status == 'pending')
                                                    <span class="badge bg-warning">En Attente</span>
                                                @elseif($appointment->status == 'completed')
                                                    <span class="badge bg-info">Terminé</span>
                                                @elseif($appointment->status == 'cancelled')
                                                    <span class="badge bg-danger">Annulé</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ ucfirst($appointment->status) }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <button type="button" class="btn btn-outline-primary" title="Voir Détails">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    @if($appointment->status == 'confirmed')
                                                        <button type="button" class="btn btn-outline-success" title="Commencer Soins">
                                                            <i class="fas fa-play"></i>
                                                        </button>
                                                    @endif
                                                    <button type="button" class="btn btn-outline-info" title="Signes Vitaux">
                                                        <i class="fas fa-heartbeat"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $appointments->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-alt fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">Aucun Rendez-vous Trouvé</h5>
                            <p class="text-muted">Aucun rendez-vous n'a encore été programmé.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-bolt me-2"></i>Actions Rapides
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <button type="button" class="btn btn-outline-primary w-100">
                                <i class="fas fa-plus me-2"></i>Programmer Rendez-vous
                            </button>
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-outline-success w-100">
                                <i class="fas fa-calendar-week me-2"></i>Vue Hebdomadaire
                            </button>
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-outline-info w-100">
                                <i class="fas fa-download me-2"></i>Exporter Planning
                            </button>
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-outline-warning w-100">
                                <i class="fas fa-sync me-2"></i>Actualiser
                            </button>
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

.card.border-success {
    border-color: #28a745 !important;
}

.card.border-warning {
    border-color: #ffc107 !important;
}

.card.border-secondary {
    border-color: #6c757d !important;
}
</style>
@endpush
