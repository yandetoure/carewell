@extends('layouts.admin')

@section('title', 'Gestion des Rendez-vous - Admin')
@section('page-title', 'Tous les Rendez-vous')
@section('page-subtitle', 'Consulter et gérer l\'ensemble des rendez-vous de la clinique')
@section('user-role', 'Administrateur')

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

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Alertes pour les rendez-vous urgents -->
    @if($urgentAppointments > 0)
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Attention !</strong> Il y a {{ $urgentAppointments }} rendez-vous urgent(s) en attente.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-primary-soft text-primary">
                            <i class="fas fa-calendar-day"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-0">{{ $todayAppointments }}</h4>
                            <p class="text-muted small mb-0">Aujourd'hui</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-warning-soft text-warning">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-0">{{ $pendingAppointments }}</h4>
                            <p class="text-muted small mb-0">En attente</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-success-soft text-success">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-0">{{ $confirmedAppointments }}</h4>
                            <p class="text-muted small mb-0">Confirmés</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-danger-soft text-danger">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-0">{{ $urgentAppointments }}</h4>
                            <p class="text-muted small mb-0">Urgents</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-list me-2"></i>Liste Globale des Rendez-vous
                        </h5>
                        <div class="d-flex gap-2">
                            <select class="form-select form-select-sm" id="statusFilter" onchange="filterAppointments()">
                                <option value="">Tous les statuts</option>
                                <option value="pending">En attente</option>
                                <option value="confirmed">Confirmés</option>
                                <option value="cancelled">Annulés</option>
                                <option value="completed">Terminés</option>
                            </select>
                            <button class="btn btn-light btn-sm border" onclick="refreshAppointments()">
                                <i class="fas fa-sync-alt"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="appointmentsTable">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Patient</th>
                                    <th>Date & Heure</th>
                                    <th>Service</th>
                                    <th>Médecin</th>
                                    <th>Statut</th>
                                    <th class="text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($appointments as $appointment)
                                    <tr class="appointment-row {{ $appointment->is_urgent ? 'bg-urgent-light' : '' }}" 
                                        data-status="{{ $appointment->status }}">
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar bg-primary text-white me-3">
                                                    {{ strtoupper(substr($appointment->user->first_name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="fw-bold text-dark">{{ $appointment->user->first_name }} {{ $appointment->user->last_name }}</div>
                                                    <small class="text-muted">{{ $appointment->user->phone_number }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="mb-1">
                                                <i class="far fa-calendar-alt text-primary me-2"></i>
                                                {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y') }}
                                            </div>
                                            <small class="text-muted">
                                                <i class="far fa-clock me-2"></i>
                                                {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i') }}
                                            </small>
                                        </td>
                                        <td>
                                            <span class="badge bg-info-soft text-info">
                                                <i class="fas fa-stethoscope me-1"></i>
                                                {{ $appointment->service->name ?? 'N/A' }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($appointment->doctor)
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-xs bg-success-soft text-success me-2">
                                                        <i class="fas fa-user-md"></i>
                                                    </div>
                                                    <span>{{ $appointment->doctor->first_name }} {{ $appointment->doctor->last_name }}</span>
                                                </div>
                                            @else
                                                <span class="text-muted italic">Non assigné</span>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $statusClasses = [
                                                    'pending' => 'bg-warning-soft text-warning',
                                                    'confirmed' => 'bg-success-soft text-success',
                                                    'completed' => 'bg-info-soft text-info',
                                                    'cancelled' => 'bg-danger-soft text-danger'
                                                ];
                                                $class = $statusClasses[$appointment->status] ?? 'bg-secondary-soft text-secondary';
                                            @endphp
                                            <span class="badge {{ $class }} px-3 py-2">
                                                {{ ucfirst($appointment->status) }}
                                            </span>
                                        </td>
                                        <td class="text-end pe-4">
                                            <button class="btn btn-sm btn-light border" onclick="viewAppointment({{ $appointment->id }})">
                                                <i class="fas fa-eye text-primary"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <img src="{{ asset('img/no-data.svg') }}" alt="No data" style="width: 150px;" class="mb-3 opacity-50">
                                            <h5 class="text-muted">Aucun rendez-vous trouvé</h5>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white border-top-0 py-3">
                    <div class="d-flex justify-content-center">
                        {{ $appointments->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Détails -->
<div class="modal fade" id="appointmentModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 bg-primary text-white">
                <h5 class="modal-title">Détails du Rendez-vous</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="appointmentDetails">
                <!-- Ajax content -->
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    .stat-icon { width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; border-radius: 12px; font-size: 1.25rem; }
    .bg-primary-soft { background-color: rgba(13, 110, 253, 0.1); }
    .bg-success-soft { background-color: rgba(25, 135, 84, 0.1); }
    .bg-warning-soft { background-color: rgba(255, 193, 7, 0.1); }
    .bg-danger-soft { background-color: rgba(220, 53, 69, 0.1); }
    .bg-info-soft { background-color: rgba(13, 202, 240, 0.1); }
    .avatar { width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 0.8rem; }
    .avatar-xs { width: 24px; height: 24px; border-radius: 4px; display: flex; align-items: center; justify-content: center; font-size: 0.7rem; }
    .bg-urgent-light { background-color: rgba(220, 53, 69, 0.05); }
    .appointment-row.hidden { display: none; }
</style>
@endpush

@push('scripts')
<script>
    function filterAppointments() {
        const status = document.getElementById('statusFilter').value;
        const rows = document.querySelectorAll('.appointment-row');
        rows.forEach(row => {
            if (status === '' || row.dataset.status === status) row.classList.remove('hidden');
            else row.classList.add('hidden');
        });
    }

    function refreshAppointments() { location.reload(); }

    function viewAppointment(id) {
        document.getElementById('appointmentDetails').innerHTML = '<div class="text-center p-5"><i class="fas fa-spinner fa-spin fa-2x text-primary"></i></div>';
        const modal = new bootstrap.Modal(document.getElementById('appointmentModal'));
        modal.show();

        fetch(`/doctor/appointments/${id}`, {
            headers: { 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(data => {
            if (data.status) {
                const a = data.data;
                document.getElementById('appointmentDetails').innerHTML = `
                    <div class="row g-4">
                        <div class="col-md-6">
                            <h6 class="text-uppercase text-muted fw-bold mb-3 small">Patient</h6>
                            <p class="mb-1"><strong>Nom:</strong> ${a.user.first_name} ${a.user.last_name}</p>
                            <p class="mb-1"><strong>Tel:</strong> ${a.user.phone_number || 'N/A'}</p>
                            <p class="mb-0"><strong>Email:</strong> ${a.user.email}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-uppercase text-muted fw-bold mb-3 small">Session</h6>
                            <p class="mb-1"><strong>Date:</strong> ${new Date(a.appointment_date).toLocaleDateString()}</p>
                            <p class="mb-1"><strong>Heure:</strong> ${a.appointment_time}</p>
                            <p class="mb-0"><strong>Service:</strong> ${a.service ? a.service.name : 'N/A'}</p>
                        </div>
                        <div class="col-12">
                            <h6 class="text-uppercase text-muted fw-bold mb-3 small">Observations</h6>
                            <p class="bg-light p-3 rounded">${a.reason || 'Aucun motif renseigné'}</p>
                        </div>
                    </div>
                `;
            }
        });
    }
</script>
@endpush
