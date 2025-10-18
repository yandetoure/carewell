@extends('layouts.doctor')

@section('title', 'Rendez-vous de la semaine - Docteur')
@section('page-title', 'Rendez-vous de la semaine')
@section('page-subtitle', 'Vos rendez-vous du ' . \Carbon\Carbon::parse($startOfWeek)->format('d/m/Y') . ' au ' . \Carbon\Carbon::parse($endOfWeek)->format('d/m/Y'))
@section('user-role', 'Médecin')

@push('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

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

    <!-- Statistiques de la semaine -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-primary">
                            <i class="fas fa-calendar-week text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $totalAppointments }}</h4>
                            <p class="text-muted mb-0">Total cette semaine</p>
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
                            <h4 class="mb-1">{{ $completedCount }}</h4>
                            <p class="text-muted mb-0">Terminés</p>
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
                            <h4 class="mb-1">{{ $pendingCount }}</h4>
                            <p class="text-muted mb-0">En attente</p>
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
                            <h4 class="mb-1">{{ $confirmedCount }}</h4>
                            <p class="text-muted mb-0">Confirmés</p>
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
                            <i class="fas fa-calendar-week me-2"></i>Rendez-vous de la semaine
                        </h5>
                        <div class="d-flex gap-2">
                            <a href="{{ route('doctor.appointments.today') }}" class="btn btn-outline-primary">
                                <i class="fas fa-calendar-day me-2"></i>Aujourd'hui
                            </a>
                            <a href="{{ route('doctor.appointments') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-list me-2"></i>Tous les RDV
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Rendez-vous par jour -->
    <div class="row">
        @if($appointmentsByDay->count() > 0)
            @foreach($appointmentsByDay as $date => $dayAppointments)
                <div class="col-12 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-calendar-day me-2"></i>
                                {{ \Carbon\Carbon::parse($date)->format('l d/m/Y') }}
                                <span class="badge bg-primary ms-2">{{ $dayAppointments->count() }} RDV</span>
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Heure</th>
                                            <th>Patient</th>
                                            <th>Service</th>
                                            <th>Statut</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($dayAppointments as $appointment)
                                            <tr class="{{ $appointment->status == 'completed' ? 'table-success' : ($appointment->status == 'cancelled' ? 'table-danger' : '') }}">
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <i class="fas fa-clock text-primary me-2"></i>
                                                        {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i') }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <i class="fas fa-user text-info me-2"></i>
                                                        {{ $appointment->user->first_name }} {{ $appointment->user->last_name }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <i class="fas fa-stethoscope text-success me-2"></i>
                                                        {{ $appointment->service->name ?? 'Service non spécifié' }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-{{ $appointment->status == 'completed' ? 'success' : ($appointment->status == 'confirmed' ? 'info' : ($appointment->status == 'pending' ? 'warning' : 'danger')) }}">
                                                        {{ ucfirst($appointment->status) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <a href="{{ route('appointments.show', $appointment) }}" 
                                                           class="btn btn-outline-primary" 
                                                           title="Voir">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        @if($appointment->status == 'pending')
                                                            <form method="POST" action="{{ route('doctor.appointments.status', $appointment) }}" style="display: inline;">
                                                                @csrf
                                                                @method('PATCH')
                                                                <input type="hidden" name="status" value="confirmed">
                                                                <button type="submit" class="btn btn-outline-success" title="Confirmer">
                                                                    <i class="fas fa-check"></i>
                                                                </button>
                                                            </form>
                                                        @endif
                                                        @if($appointment->status == 'confirmed')
                                                            <form method="POST" action="{{ route('doctor.appointments.status', $appointment) }}" style="display: inline;">
                                                                @csrf
                                                                @method('PATCH')
                                                                <input type="hidden" name="status" value="completed">
                                                                <button type="submit" class="btn btn-outline-success" title="Marquer comme terminé">
                                                                    <i class="fas fa-check-double"></i>
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
                        <h5 class="text-muted">Aucun rendez-vous cette semaine</h5>
                        <p class="text-muted">Vous n'avez pas de rendez-vous planifiés pour cette semaine.</p>
                        <a href="{{ route('doctor.appointments') }}" class="btn btn-primary">
                            <i class="fas fa-calendar-plus me-2"></i>Voir tous les rendez-vous
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Résumé de la semaine -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-bar me-2"></i>Résumé de la semaine
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary">📊 Statistiques</h6>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-calendar-week text-primary me-2"></i><strong>Période:</strong> {{ \Carbon\Carbon::parse($startOfWeek)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($endOfWeek)->format('d/m/Y') }}</li>
                                <li><i class="fas fa-users text-info me-2"></i><strong>Total RDV:</strong> {{ $totalAppointments }}</li>
                                <li><i class="fas fa-check-circle text-success me-2"></i><strong>Terminés:</strong> {{ $completedCount }}</li>
                                <li><i class="fas fa-clock text-warning me-2"></i><strong>En attente:</strong> {{ $pendingCount }}</li>
                                <li><i class="fas fa-calendar-check text-info me-2"></i><strong>Confirmés:</strong> {{ $confirmedCount }}</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-success">💡 Conseils</h6>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-lightbulb text-warning me-2"></i>Confirmez les rendez-vous en attente rapidement</li>
                                <li><i class="fas fa-clock text-info me-2"></i>Marquez les consultations terminées</li>
                                <li><i class="fas fa-calendar-plus text-success me-2"></i>Planifiez vos disponibilités à l'avance</li>
                                <li><i class="fas fa-bell text-primary me-2"></i>Activez les notifications pour les rappels</li>
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

.table-success {
    background-color: rgba(40, 167, 69, 0.1);
}

.table-danger {
    background-color: rgba(220, 53, 69, 0.1);
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
// Auto-refresh de la page toutes les 5 minutes
setTimeout(function() {
    location.reload();
}, 300000);

// Confirmation pour les actions de statut
document.addEventListener('DOMContentLoaded', function() {
    const statusForms = document.querySelectorAll('form[action*="status"]');
    statusForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const status = this.querySelector('input[name="status"]').value;
            const action = status === 'confirmed' ? 'confirmer' : 'marquer comme terminé';
            
            if (!confirm(`Êtes-vous sûr de vouloir ${action} ce rendez-vous ?`)) {
                e.preventDefault();
            }
        });
    });
});
</script>
@endpush
