@extends('layouts.doctor')

@section('title', 'Mes Consultations - Docteur')
@section('page-title', 'Mes Consultations')
@section('page-subtitle', 'Historique de vos consultations et rendez-vous')
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

    <!-- Statistiques des consultations -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-primary">
                            <i class="fas fa-stethoscope text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $totalConsultations }}</h4>
                            <p class="text-muted mb-0">Total consultations</p>
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
                            <h4 class="mb-1">{{ $completedConsultations }}</h4>
                            <p class="text-muted mb-0">Terminées</p>
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
                            <h4 class="mb-1">{{ $confirmedConsultations }}</h4>
                            <p class="text-muted mb-0">Confirmées</p>
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
                            <h4 class="mb-1">{{ $recentConsultations }}</h4>
                            <p class="text-muted mb-0">Cette semaine</p>
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
                            <i class="fas fa-stethoscope me-2"></i>Historique des consultations
                        </h5>
                        <div class="d-flex gap-2">
                            <a href="{{ route('doctor.appointments') }}" class="btn btn-outline-primary">
                                <i class="fas fa-calendar me-2"></i>Tous les RDV
                            </a>
                            <a href="{{ route('doctor.appointments.today') }}" class="btn btn-outline-success">
                                <i class="fas fa-calendar-day me-2"></i>Aujourd'hui
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des consultations -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if($consultations->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Heure</th>
                                        <th>Patient</th>
                                        <th>Service</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($consultations as $consultation)
                                        <tr class="{{ $consultation->status == 'completed' ? 'table-success' : 'table-info' }}">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-calendar text-primary me-2"></i>
                                                    {{ \Carbon\Carbon::parse($consultation->appointment_date)->format('d/m/Y') }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-clock text-info me-2"></i>
                                                    {{ \Carbon\Carbon::parse($consultation->appointment_time)->format('H:i') }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-user text-success me-2"></i>
                                                    <div>
                                                        <div class="fw-bold">{{ $consultation->user->first_name }} {{ $consultation->user->last_name }}</div>
                                                        <small class="text-muted">{{ $consultation->user->phone_number ?? 'Tél. non renseigné' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-stethoscope text-warning me-2"></i>
                                                    {{ $consultation->service->name ?? 'Service non spécifié' }}
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $consultation->status == 'completed' ? 'success' : 'info' }}">
                                                    {{ $consultation->status == 'completed' ? 'Terminée' : 'Confirmée' }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('appointments.show', $consultation) }}" 
                                                       class="btn btn-outline-primary" 
                                                       title="Voir les détails">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('doctor.patients.show', $consultation->user) }}" 
                                                       class="btn btn-outline-success" 
                                                       title="Voir le patient">
                                                        <i class="fas fa-user"></i>
                                                    </a>
                                                    @if($consultation->status == 'confirmed')
                                                        <form method="POST" action="{{ route('doctor.appointments.status', $consultation) }}" style="display: inline;">
                                                            @csrf
                                                            @method('PATCH')
                                                            <input type="hidden" name="status" value="completed">
                                                            <button type="submit" class="btn btn-outline-success" title="Marquer comme terminée">
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

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $consultations->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-stethoscope fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">Aucune consultation</h5>
                            <p class="text-muted">Vous n'avez pas encore de consultations enregistrées.</p>
                            <a href="{{ route('doctor.appointments') }}" class="btn btn-primary">
                                <i class="fas fa-calendar-plus me-2"></i>Voir tous les rendez-vous
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Résumé et conseils -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-line me-2"></i>Résumé des consultations
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary">📊 Statistiques</h6>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-stethoscope text-primary me-2"></i><strong>Total consultations:</strong> {{ $totalConsultations }}</li>
                                <li><i class="fas fa-check-circle text-success me-2"></i><strong>Consultations terminées:</strong> {{ $completedConsultations }}</li>
                                <li><i class="fas fa-calendar-check text-info me-2"></i><strong>Consultations confirmées:</strong> {{ $confirmedConsultations }}</li>
                                <li><i class="fas fa-clock text-warning me-2"></i><strong>Cette semaine:</strong> {{ $recentConsultations }}</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-success">💡 Bonnes pratiques</h6>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-lightbulb text-warning me-2"></i>Marquez les consultations comme terminées après chaque RDV</li>
                                <li><i class="fas fa-file-medical text-info me-2"></i>Consultez le dossier médical du patient avant la consultation</li>
                                <li><i class="fas fa-clock text-primary me-2"></i>Respectez les horaires pour éviter les retards</li>
                                <li><i class="fas fa-notes-medical text-success me-2"></i>Prenez des notes importantes pendant la consultation</li>
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

.table-info {
    background-color: rgba(23, 162, 184, 0.1);
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
// Confirmation pour les actions de statut
document.addEventListener('DOMContentLoaded', function() {
    const statusForms = document.querySelectorAll('form[action*="status"]');
    statusForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!confirm('Êtes-vous sûr de vouloir marquer cette consultation comme terminée ?')) {
                e.preventDefault();
            }
        });
    });
});
</script>
@endpush
