@extends('layouts.doctor')

@section('title', 'Mes Disponibilités - Docteur')
@section('page-title', 'Mes Disponibilités')
@section('page-subtitle', 'Gestion de vos créneaux de disponibilité')
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

    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-primary">
                            <i class="fas fa-calendar-check text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $totalAvailabilities }}</h4>
                            <p class="text-muted mb-0">Total disponibilités</p>
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
                            <h4 class="mb-1">{{ $thisWeekAvailabilities }}</h4>
                            <p class="text-muted mb-0">Cette semaine</p>
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
                            <i class="fas fa-calendar-times text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $totalAbsences }}</h4>
                            <p class="text-muted mb-0">Absences</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-danger">
                            <i class="fas fa-clock text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $currentAbsences }}</h4>
                            <p class="text-muted mb-0">En cours</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des disponibilités -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-clock me-2"></i>Mes créneaux de disponibilité
                            </h5>
                            <div class="d-flex gap-2">
                                <a href="{{ route('doctor.availability.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>Nouveau créneau
                                </a>
                                <a href="{{ route('doctor.calendar') }}" class="btn btn-info">
                                    <i class="fas fa-calendar-alt me-2"></i>Voir le calendrier
                                </a>
                            </div>
                        </div>
                </div>
                <div class="card-body">
                    @if($availabilities->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Heure de début</th>
                                        <th>Heure de fin</th>
                                        <th>Service</th>
                                        <th>Durée RDV</th>
                                        <th>Récurrence</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($availabilities as $availability)
                                        <tr class="{{ $availability->available_date < now()->toDateString() ? 'table-secondary' : '' }}">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-calendar text-primary me-2"></i>
                                                    {{ \Carbon\Carbon::parse($availability->available_date)->format('d/m/Y') }}
                                                    @if($availability->available_date < now()->toDateString())
                                                        <span class="badge bg-secondary ms-2">Passé</span>
                                                    @elseif($availability->available_date == now()->toDateString())
                                                        <span class="badge bg-success ms-2">Aujourd'hui</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-clock text-info me-2"></i>
                                                    {{ \Carbon\Carbon::parse($availability->start_time)->format('H:i') }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-clock text-warning me-2"></i>
                                                    {{ \Carbon\Carbon::parse($availability->end_time)->format('H:i') }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-stethoscope text-success me-2"></i>
                                                    {{ $availability->service->name ?? 'Service non spécifié' }}
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ $availability->appointment_duration }} min</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $availability->recurrence_type == 'none' ? 'secondary' : ($availability->recurrence_type == 'daily' ? 'primary' : ($availability->recurrence_type == 'weekly' ? 'success' : 'info')) }}">
                                                    {{ ucfirst($availability->recurrence_type) }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('doctor.availability.edit', $availability) }}" 
                                                       class="btn btn-outline-primary" 
                                                       title="Modifier">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-outline-danger" 
                                                            onclick="deleteAvailability({{ $availability->id }})" 
                                                            title="Supprimer">
                                                        <i class="fas fa-trash"></i>
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
                            {{ $availabilities->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">Aucune disponibilité</h5>
                            <p class="text-muted">Vous n'avez pas encore créé de créneaux de disponibilité.</p>
                            <a href="{{ route('doctor.availability.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Créer votre premier créneau
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des absences -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-calendar-times me-2"></i>Mes absences et congés
                        </h5>
                        <div class="d-flex gap-2">
                            <a href="{{ route('doctor.calendar.create-absence') }}" class="btn btn-warning">
                                <i class="fas fa-plus me-2"></i>Nouvelle absence
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if($absences->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Titre</th>
                                        <th>Type</th>
                                        <th>Date de début</th>
                                        <th>Date de fin</th>
                                        <th>Durée</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($absences as $absence)
                                        <tr class="{{ $absence->start_date <= now()->toDateString() && $absence->end_date >= now()->toDateString() ? 'table-warning' : '' }}">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-calendar-times text-warning me-2"></i>
                                                    {{ $absence->title }}
                                                    @if($absence->start_date <= now()->toDateString() && $absence->end_date >= now()->toDateString())
                                                        <span class="badge bg-warning ms-2">En cours</span>
                                                    @elseif($absence->start_date > now()->toDateString())
                                                        <span class="badge bg-info ms-2">À venir</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $absence->type == 'congé' ? 'warning' : ($absence->type == 'formation' ? 'info' : ($absence->type == 'maladie' ? 'danger' : ($absence->type == 'personnel' ? 'secondary' : 'purple'))) }}">
                                                    {{ $absence->getFormattedType() }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-calendar text-primary me-2"></i>
                                                    {{ \Carbon\Carbon::parse($absence->start_date)->format('d/m/Y') }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-calendar text-danger me-2"></i>
                                                    {{ \Carbon\Carbon::parse($absence->end_date)->format('d/m/Y') }}
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ $absence->getDurationInDays() }} jour{{ $absence->getDurationInDays() > 1 ? 's' : '' }}</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $absence->status == 'planned' ? 'secondary' : ($absence->status == 'confirmed' ? 'success' : 'danger') }}">
                                                    {{ $absence->getFormattedStatus() }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('doctor.calendar.edit-absence', $absence) }}" 
                                                       class="btn btn-outline-primary" 
                                                       title="Modifier">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-outline-danger" 
                                                            onclick="deleteAbsence({{ $absence->id }})" 
                                                            title="Supprimer">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-check fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">Aucune absence planifiée</h5>
                            <p class="text-muted">Vous n'avez pas encore planifié d'absence ou de congé.</p>
                            <a href="{{ route('doctor.calendar.create-absence') }}" class="btn btn-warning">
                                <i class="fas fa-plus me-2"></i>Planifier une absence
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Conseils et informations -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-lightbulb me-2"></i>Conseils pour optimiser vos disponibilités
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary">💡 Bonnes pratiques</h6>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-check text-success me-2"></i>Créez des créneaux de 30-60 minutes</li>
                                <li><i class="fas fa-check text-success me-2"></i>Planifiez à l'avance (minimum 24h)</li>
                                <li><i class="fas fa-check text-success me-2"></i>Utilisez la récurrence pour les horaires réguliers</li>
                                <li><i class="fas fa-check text-success me-2"></i>Laissez du temps entre les RDV (15-30 min)</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-info">📊 Statistiques utiles</h6>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-info-circle text-info me-2"></i>Les patients peuvent réserver 24h à l'avance</li>
                                <li><i class="fas fa-info-circle text-info me-2"></i>Maximum 15 RDV par jour par service</li>
                                <li><i class="fas fa-info-circle text-info me-2"></i>Les créneaux passés ne sont plus visibles</li>
                                <li><i class="fas fa-info-circle text-info me-2"></i>Vous recevrez une notification pour chaque RDV</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmation de suppression -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmer la suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer ce créneau de disponibilité ?</p>
                <p class="text-muted">Cette action est irréversible et pourrait affecter les rendez-vous existants.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i>Supprimer
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmation de suppression d'absence -->
<div class="modal fade" id="deleteAbsenceModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmer la suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer cette absence ?</p>
                <p class="text-muted">Cette action est irréversible et pourrait affecter les rendez-vous en attente.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form id="deleteAbsenceForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i>Supprimer
                    </button>
                </form>
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

.table-secondary {
    opacity: 0.6;
}

.table-warning {
    background-color: rgba(255, 193, 7, 0.1);
}

.bg-purple {
    background-color: #6f42c1 !important;
}
</style>
@endpush

@push('scripts')
<script>
function deleteAvailability(availabilityId) {
    const form = document.getElementById('deleteForm');
    form.action = `/doctor/availability/${availabilityId}`;
    
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

function deleteAbsence(absenceId) {
    const form = document.getElementById('deleteAbsenceForm');
    form.action = `/doctor/calendar/absence/${absenceId}`;
    
    const modal = new bootstrap.Modal(document.getElementById('deleteAbsenceModal'));
    modal.show();
}
</script>
@endpush

