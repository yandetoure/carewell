@extends('layouts.app')

@section('title', 'Mes Rendez-vous - CareWell')

@section('content')
<!-- Header Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="section-title">Mes Rendez-vous</h1>
                <p class="section-subtitle">Gérez tous vos rendez-vous médicaux en un seul endroit</p>
            </div>
        </div>
    </div>
</section>

<!-- Quick Actions -->
<section class="py-4">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <a href="{{ route('appointments.create') }}" class="btn btn-primary w-100">
                                    <i class="fas fa-calendar-plus me-2"></i>Prendre un nouveau RDV
                                </a>
                            </div>
                            <div class="col-md-6">
                                <button class="btn btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#filterModal">
                                    <i class="fas fa-filter me-2"></i>Filtrer les RDV
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Appointments List -->
<section class="py-5">
    <div class="container">
        @if($appointments->count() > 0)
            <div class="row">
                <div class="col-12">
                    <div class="appointments-timeline">
                        @foreach($appointments as $appointment)
                        <div class="appointment-item">
                            <div class="appointment-date">
                                <div class="date-badge">
                                    <span class="day">{{ $appointment->appointment_date->format('d') }}</span>
                                    <span class="month">{{ $appointment->appointment_date->format('M') }}</span>
                                    <span class="year">{{ $appointment->appointment_date->format('Y') }}</span>
                                </div>
                            </div>

                            <div class="appointment-content">
                                <div class="appointment-header">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h5 class="mb-1">
                                                @if($appointment->service)
                                                    {{ $appointment->service->name }}
                                                @else
                                                    Rendez-vous médical
                                                @endif
                                            </h5>
                                            <p class="text-muted mb-2">
                                                <i class="fas fa-clock me-2"></i>
                                                {{ $appointment->appointment_date->format('H:i') }}
                                                @if($appointment->duration)
                                                    - {{ $appointment->appointment_date->addMinutes($appointment->duration)->format('H:i') }}
                                                @endif
                                            </p>
                                        </div>

                                        <div class="appointment-status">
                                            <span class="badge bg-{{ $appointment->status == 'confirmed' ? 'success' : ($appointment->status == 'pending' ? 'warning' : ($appointment->status == 'cancelled' ? 'danger' : 'secondary')) }}">
                                                {{ ucfirst($appointment->status) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="appointment-details">
                                    @if($appointment->service)
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="mb-2">
                                                <strong>Service:</strong> {{ $appointment->service->name }}
                                            </p>
                                            <p class="mb-2">
                                                <strong>Prix:</strong> {{ number_format($appointment->service->price, 2) }} €
                                            </p>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="mb-2">
                                                <strong>Lieu:</strong>
                                                @if($appointment->location)
                                                    {{ $appointment->location }}
                                                @else
                                                    Cabinet médical
                                                @endif
                                            </p>
                                            <p class="mb-2">
                                                <strong>Type:</strong>
                                                @if($appointment->is_urgent)
                                                    <span class="badge bg-danger">Urgent</span>
                                                @else
                                                    <span class="badge bg-info">Standard</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                    @endif

                                    @if($appointment->notes)
                                    <div class="appointment-notes mt-3">
                                        <strong>Notes:</strong>
                                        <p class="mb-0 text-muted">{{ $appointment->notes }}</p>
                                    </div>
                                    @endif
                                </div>

                                <div class="appointment-actions">
                                    <div class="d-flex gap-2 flex-wrap">
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

                                        <a href="{{ route('appointments.show', $appointment->id) }}" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-eye me-1"></i>Détails
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if($appointments->hasPages())
                    <div class="d-flex justify-content-center mt-5">
                        {{ $appointments->links() }}
                    </div>
                    @endif
                </div>
            </div>
        @else
            <div class="text-center py-5">
                <div class="mb-4">
                    <i class="fas fa-calendar-times fa-4x text-muted"></i>
                </div>
                <h3>Aucun rendez-vous trouvé</h3>
                <p class="text-muted mb-4">Vous n'avez pas encore de rendez-vous programmés.</p>
                <a href="{{ route('appointments.create') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-calendar-plus me-2"></i>Prendre votre premier RDV
                </a>
            </div>
        @endif
    </div>
</section>

<!-- Statistics Section -->
@if($appointments->count() > 0)
<section class="py-5 bg-light">
    <div class="container">
        <div class="row text-center">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-card">
                    <div class="stat-icon mb-3">
                        <i class="fas fa-calendar-check fa-3x text-success"></i>
                    </div>
                    <h3 class="fw-bold">{{ $appointments->where('status', 'confirmed')->count() }}</h3>
                    <p class="mb-0">Confirmés</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-card">
                    <div class="stat-icon mb-3">
                        <i class="fas fa-clock fa-3x text-warning"></i>
                    </div>
                    <h3 class="fw-bold">{{ $appointments->where('status', 'pending')->count() }}</h3>
                    <p class="mb-0">En attente</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-card">
                    <div class="stat-icon mb-3">
                        <i class="fas fa-times fa-3x text-danger"></i>
                    </div>
                    <h3 class="fw-bold">{{ $appointments->where('status', 'cancelled')->count() }}</h3>
                    <p class="mb-0">Annulés</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-card">
                    <div class="stat-icon mb-3">
                        <i class="fas fa-exclamation-triangle fa-3x text-info"></i>
                    </div>
                    <h3 class="fw-bold">{{ $appointments->where('is_urgent', true)->count() }}</h3>
                    <p class="mb-0">Urgents</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endif

<!-- Filter Modal -->
<div class="modal fade" id="filterModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Filtrer les rendez-vous</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('appointments') }}" method="GET">
                    <div class="mb-3">
                        <label for="status" class="form-label">Statut</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">Tous les statuts</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                            <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmé</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Annulé</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Terminé</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="date_from" class="form-label">Date de début</label>
                        <input type="date" class="form-control" id="date_from" name="date_from" value="{{ request('date_from') }}">
                    </div>

                    <div class="mb-3">
                        <label for="date_to" class="form-label">Date de fin</label>
                        <input type="date" class="form-control" id="date_to" name="date_to" value="{{ request('date_to') }}">
                    </div>

                    <div class="mb-3">
                        <label for="service" class="form-label">Service</label>
                        <select class="form-select" id="service" name="service_id">
                            <option value="">Tous les services</option>
                            @foreach(\App\Models\Service::all() as $service)
                                <option value="{{ $service->id }}" {{ request('service_id') == $service->id ? 'selected' : '' }}>
                                    {{ $service->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Appliquer les filtres</button>
                        <a href="{{ route('appointments') }}" class="btn btn-outline-secondary">Réinitialiser</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .appointments-timeline {
        position: relative;
    }

    .appointments-timeline::before {
        content: '';
        position: absolute;
        left: 120px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: var(--primary-color);
    }

    .appointment-item {
        position: relative;
        margin-bottom: 2rem;
        display: flex;
        align-items: flex-start;
    }

    .appointment-date {
        flex-shrink: 0;
        margin-right: 2rem;
        z-index: 1;
    }

    .date-badge {
        background: white;
        border: 3px solid var(--primary-color);
        border-radius: 1rem;
        padding: 1rem;
        text-align: center;
        min-width: 80px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .date-badge .day {
        display: block;
        font-size: 1.5rem;
        font-weight: bold;
        color: var(--primary-color);
    }

    .date-badge .month,
    .date-badge .year {
        display: block;
        font-size: 0.8rem;
        color: var(--text-color);
    }

    .appointment-content {
        flex: 1;
        background: white;
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        border: 1px solid var(--border-color);
    }

    .appointment-header {
        margin-bottom: 1rem;
    }

    .appointment-details {
        margin-bottom: 1.5rem;
    }

    .appointment-notes {
        background: var(--light-color);
        padding: 1rem;
        border-radius: 0.5rem;
        border-left: 4px solid var(--primary-color);
    }

    .appointment-actions {
        border-top: 1px solid var(--border-color);
        padding-top: 1rem;
    }

    .stat-card {
        background: white;
        padding: 2rem;
        border-radius: 1rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    .stat-icon {
        color: var(--primary-color);
    }

    @media (max-width: 768px) {
        .appointments-timeline::before {
            left: 60px;
        }

        .appointment-item {
            flex-direction: column;
        }

        .appointment-date {
            margin-right: 0;
            margin-bottom: 1rem;
            align-self: flex-start;
        }

        .date-badge {
            min-width: 60px;
            padding: 0.5rem;
        }

        .date-badge .day {
            font-size: 1.2rem;
        }
    }
</style>
@endsection

@section('scripts')
<script>
function confirmAppointment(appointmentId) {
    if (confirm('Êtes-vous sûr de vouloir confirmer ce rendez-vous ?')) {
        // Ajoutez ici la logique pour confirmer le rendez-vous
        console.log('Confirmer le rendez-vous:', appointmentId);
    }
}

function rescheduleAppointment(appointmentId) {
    // Rediriger vers la page de reprogrammation
    window.location.href = `/appointments/${appointmentId}/reschedule`;
}

function cancelAppointment(appointmentId) {
    if (confirm('Êtes-vous sûr de vouloir annuler ce rendez-vous ? Cette action ne peut pas être annulée.')) {
        // Ajoutez ici la logique pour annuler le rendez-vous
        console.log('Annuler le rendez-vous:', appointmentId);
    }
}
</script>
@endsection
