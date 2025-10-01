@extends('layouts.doctor')

@section('title', 'Gestion RDV Patient - Docteur')
@section('page-title', 'Gestion des rendez-vous')
@section('page-subtitle', 'Gérer les rendez-vous du patient')
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

    <!-- En-tête du patient -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="patient-avatar me-3">
                                <div class="avatar bg-primary text-white">
                                    <i class="fas fa-user"></i>
                                </div>
                            </div>
                            <div>
                                <h4 class="mb-1">{{ $patient->first_name }} {{ $patient->last_name }}</h4>
                                <p class="text-muted mb-2">
                                    <i class="fas fa-envelope me-1"></i>{{ $patient->email }}
                                    <span class="mx-2">•</span>
                                    <i class="fas fa-phone me-1"></i>{{ $patient->phone }}
                                </p>
                                <div class="d-flex align-items-center gap-3">
                                    <span class="badge bg-info">
                                        <i class="fas fa-birthday-cake me-1"></i>
                                        {{ \Carbon\Carbon::parse($patient->date_of_birth)->age }} ans
                                    </span>
                                    <span class="badge bg-secondary">
                                        <i class="fas fa-{{ $patient->gender == 'male' ? 'mars' : 'venus' }} me-1"></i>
                                        {{ $patient->gender == 'male' ? 'Homme' : 'Femme' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('doctor.patients.show', $patient) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-eye me-1"></i>Détails
                            </a>
                            <a href="{{ route('doctor.patients.history', $patient) }}" class="btn btn-outline-info">
                                <i class="fas fa-history me-1"></i>Historique
                            </a>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newAppointmentModal">
                                <i class="fas fa-calendar-plus me-1"></i>Nouveau RDV
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Liste des rendez-vous -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-calendar-alt me-2"></i>Rendez-vous du patient
                        </h5>
                        <div class="d-flex gap-2">
                            <select class="form-select form-select-sm" id="statusFilter" onchange="filterAppointments()">
                                <option value="">Tous les statuts</option>
                                <option value="pending">En attente</option>
                                <option value="confirmed">Confirmés</option>
                                <option value="cancelled">Annulés</option>
                                <option value="completed">Terminés</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if($appointments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover" id="appointmentsTable">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Heure</th>
                                        <th>Service</th>
                                        <th>Statut</th>
                                        <th>Notes</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($appointments as $appointment)
                                        <tr class="appointment-row" data-status="{{ $appointment->status }}">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-calendar text-primary me-2"></i>
                                                    {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y') }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-clock text-secondary me-2"></i>
                                                    {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('H:i') }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-stethoscope text-info me-2"></i>
                                                    {{ $appointment->service->name ?? 'Service non spécifié' }}
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $appointment->status == 'confirmed' ? 'success' : ($appointment->status == 'pending' ? 'warning' : ($appointment->status == 'completed' ? 'info' : 'danger')) }}">
                                                    <i class="fas fa-{{ $appointment->status == 'confirmed' ? 'check' : ($appointment->status == 'pending' ? 'clock' : ($appointment->status == 'completed' ? 'check-double' : 'times')) }} me-1"></i>
                                                    {{ ucfirst($appointment->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($appointment->notes)
                                                    <span class="text-truncate d-inline-block" style="max-width: 200px;" 
                                                          title="{{ $appointment->notes }}">
                                                        {{ $appointment->notes }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">Aucune note</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <button type="button" class="btn btn-outline-info" 
                                                            onclick="viewAppointment({{ $appointment->id }})" 
                                                            title="Voir les détails">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-primary" 
                                                            onclick="editAppointment({{ $appointment->id }})" 
                                                            title="Modifier">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    @if($appointment->status == 'pending')
                                                        <button type="button" class="btn btn-outline-success" 
                                                                onclick="confirmAppointment({{ $appointment->id }})" 
                                                                title="Confirmer">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    @endif
                                                    @if($appointment->status != 'cancelled' && $appointment->status != 'completed')
                                                        <button type="button" class="btn btn-outline-danger" 
                                                                onclick="cancelAppointment({{ $appointment->id }})" 
                                                                title="Annuler">
                                                            <i class="fas fa-times"></i>
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
                            {{ $appointments->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">Aucun rendez-vous trouvé</h5>
                            <p class="text-muted">Ce patient n'a pas encore de rendez-vous dans votre service.</p>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newAppointmentModal">
                                <i class="fas fa-calendar-plus me-2"></i>Planifier le premier rendez-vous
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Statistiques et actions rapides -->
        <div class="col-md-4">
            <!-- Statistiques -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-bar me-2"></i>Statistiques
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <h4 class="text-primary mb-1">{{ $appointments->where('status', 'confirmed')->count() }}</h4>
                                <small class="text-muted">Confirmés</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h4 class="text-warning mb-1">{{ $appointments->where('status', 'pending')->count() }}</h4>
                            <small class="text-muted">En attente</small>
                        </div>
                    </div>
                    <hr>
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <h4 class="text-success mb-1">{{ $appointments->where('status', 'completed')->count() }}</h4>
                                <small class="text-muted">Terminés</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h4 class="text-danger mb-1">{{ $appointments->where('status', 'cancelled')->count() }}</h4>
                            <small class="text-muted">Annulés</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-bolt me-2"></i>Actions rapides
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newAppointmentModal">
                            <i class="fas fa-calendar-plus me-2"></i>Nouveau RDV
                        </button>
                        <a href="{{ route('doctor.patients.history', $patient) }}" class="btn btn-outline-info">
                            <i class="fas fa-history me-2"></i>Voir l'historique
                        </a>
                        <a href="{{ route('medical-files.show', $patient->id) }}" class="btn btn-outline-warning">
                            <i class="fas fa-file-medical me-2"></i>Dossier médical
                        </a>
                        <a href="mailto:{{ $patient->email }}" class="btn btn-outline-secondary">
                            <i class="fas fa-envelope me-2"></i>Envoyer email
                        </a>
                        <a href="tel:{{ $patient->phone }}" class="btn btn-outline-secondary">
                            <i class="fas fa-phone me-2"></i>Appeler
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour nouveau rendez-vous -->
<div class="modal fade" id="newAppointmentModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-calendar-plus me-2"></i>Nouveau rendez-vous
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('doctor.appointments.store') }}" method="POST">
                @csrf
                <input type="hidden" name="patient_id" value="{{ $patient->id }}">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="service_id" class="form-label">Service <span class="text-danger">*</span></label>
                                <select class="form-select @error('service_id') is-invalid @enderror" 
                                        id="service_id" name="service_id" required>
                                    <option value="">Sélectionner un service...</option>
                                    @foreach($services as $service)
                                        <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                            {{ $service->name }} - {{ number_format($service->price, 0, ',', ' ') }} FCFA
                                        </option>
                                    @endforeach
                                </select>
                                @error('service_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="appointment_date" class="form-label">Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('appointment_date') is-invalid @enderror" 
                                       id="appointment_date" name="appointment_date" 
                                       value="{{ old('appointment_date') }}" 
                                       min="{{ date('Y-m-d') }}" required>
                                @error('appointment_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="appointment_time" class="form-label">Heure <span class="text-danger">*</span></label>
                                <input type="time" class="form-control @error('appointment_time') is-invalid @enderror" 
                                       id="appointment_time" name="appointment_time" 
                                       value="{{ old('appointment_time') }}" required>
                                @error('appointment_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="status" class="form-label">Statut</label>
                                <select class="form-select @error('status') is-invalid @enderror" 
                                        id="status" name="status">
                                    <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                                    <option value="confirmed" {{ old('status') == 'confirmed' ? 'selected' : '' }}>Confirmé</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group mb-3">
                                <label for="notes" class="form-label">Notes</label>
                                <textarea class="form-control @error('notes') is-invalid @enderror" 
                                          id="notes" name="notes" rows="3" 
                                          placeholder="Notes additionnelles pour ce rendez-vous...">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Créer le rendez-vous
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal pour voir les détails d'un rendez-vous -->
<div class="modal fade" id="appointmentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Détails du rendez-vous</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="appointmentDetails">
                <!-- Contenu dynamique -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
}

.patient-avatar .avatar {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.table tr:hover {
    background-color: rgba(0, 123, 255, 0.05);
}

.appointment-row.hidden {
    display: none;
}
</style>
@endpush

@push('scripts')
<script>
// Filtrage des rendez-vous
function filterAppointments() {
    const status = document.getElementById('statusFilter').value;
    const rows = document.querySelectorAll('.appointment-row');
    
    rows.forEach(row => {
        if (status === '' || row.dataset.status === status) {
            row.classList.remove('hidden');
        } else {
            row.classList.add('hidden');
        }
    });
}

// Fonctions pour les actions
function viewAppointment(appointmentId) {
    document.getElementById('appointmentDetails').innerHTML = `
        <div class="text-center py-3">
            <i class="fas fa-spinner fa-spin fa-2x text-primary"></i>
            <p class="mt-2">Chargement des détails...</p>
        </div>
    `;
    
    const modal = new bootstrap.Modal(document.getElementById('appointmentModal'));
    modal.show();
}

function editAppointment(appointmentId) {
    // Ici vous pouvez ajouter une requête AJAX pour éditer
    console.log('Éditer RDV:', appointmentId);
}

function confirmAppointment(appointmentId) {
    if (confirm('Êtes-vous sûr de vouloir confirmer ce rendez-vous ?')) {
        // Ici vous pouvez ajouter une requête AJAX pour confirmer
        console.log('Confirmer RDV:', appointmentId);
    }
}

function cancelAppointment(appointmentId) {
    if (confirm('Êtes-vous sûr de vouloir annuler ce rendez-vous ?')) {
        // Ici vous pouvez ajouter une requête AJAX pour annuler
        console.log('Annuler RDV:', appointmentId);
    }
}

// Validation du formulaire
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('#newAppointmentModal form');
    const appointmentDate = document.getElementById('appointment_date');
    const appointmentTime = document.getElementById('appointment_time');
    
    // Validation de la date (ne pas permettre les dates passées)
    appointmentDate.addEventListener('change', function() {
        const selectedDate = new Date(this.value);
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        
        if (selectedDate < today) {
            alert('Vous ne pouvez pas planifier un rendez-vous dans le passé.');
            this.value = '';
        }
    });
    
    // Validation de l'heure (heures de travail)
    appointmentTime.addEventListener('change', function() {
        const time = this.value;
        const hour = parseInt(time.split(':')[0]);
        
        if (hour < 8 || hour > 18) {
            alert('Les rendez-vous sont disponibles entre 8h et 18h.');
            this.value = '';
        }
    });
});
</script>
@endpush
