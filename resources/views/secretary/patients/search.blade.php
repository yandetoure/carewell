@extends('layouts.secretary')

@section('title', 'Recherche de Patients - Secrétariat')
@section('page-title', 'Recherche de Patients')
@section('page-subtitle', 'Rechercher et filtrer les patients')
@section('user-role', 'Secrétaire')

@section('content')
<div class="container-fluid py-4">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Formulaire de recherche -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-search me-2"></i>
                        Recherche de Patients
                    </h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('secretary.patients.search') }}" id="searchForm">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="search" class="form-label">Recherche</label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="search" 
                                           name="search" 
                                           value="{{ request('search') }}"
                                           placeholder="Nom, prénom, email ou téléphone...">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="gender" class="form-label">Genre</label>
                                    <select class="form-select" id="gender" name="gender">
                                        <option value="">Tous</option>
                                        <option value="male" {{ request('gender') == 'male' ? 'selected' : '' }}>Homme</option>
                                        <option value="female" {{ request('gender') == 'female' ? 'selected' : '' }}>Femme</option>
                                        <option value="other" {{ request('gender') == 'other' ? 'selected' : '' }}>Autre</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="age_range" class="form-label">Tranche d'âge</label>
                                    <select class="form-select" id="age_range" name="age_range">
                                        <option value="">Toutes</option>
                                        <option value="0-18" {{ request('age_range') == '0-18' ? 'selected' : '' }}>0-18 ans</option>
                                        <option value="19-35" {{ request('age_range') == '19-35' ? 'selected' : '' }}>19-35 ans</option>
                                        <option value="36-50" {{ request('age_range') == '36-50' ? 'selected' : '' }}>36-50 ans</option>
                                        <option value="51-65" {{ request('age_range') == '51-65' ? 'selected' : '' }}>51-65 ans</option>
                                        <option value="65+" {{ request('age_range') == '65+' ? 'selected' : '' }}>65+ ans</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label for="has_appointments" class="form-label">Avec RDV</label>
                                    <select class="form-select" id="has_appointments" name="has_appointments">
                                        <option value="">Tous</option>
                                        <option value="yes" {{ request('has_appointments') == 'yes' ? 'selected' : '' }}>Oui</option>
                                        <option value="no" {{ request('has_appointments') == 'no' ? 'selected' : '' }}>Non</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="date_from" class="form-label">Inscrit à partir de</label>
                                    <input type="date" 
                                           class="form-control" 
                                           id="date_from" 
                                           name="date_from" 
                                           value="{{ request('date_from') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="date_to" class="form-label">Inscrit jusqu'au</label>
                                    <input type="date" 
                                           class="form-control" 
                                           id="date_to" 
                                           name="date_to" 
                                           value="{{ request('date_to') }}">
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search me-2"></i>Rechercher
                            </button>
                            <a href="{{ route('secretary.patients.search') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Effacer
                            </a>
                            <a href="{{ route('secretary.patients') }}" class="btn btn-outline-info">
                                <i class="fas fa-list me-2"></i>Voir tous
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Résultats de recherche -->
    @if(request()->hasAny(['search', 'gender', 'age_range', 'has_appointments', 'date_from', 'date_to']))
        <div class="row mb-3">
            <div class="col-12">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Résultats de recherche :</strong> {{ $patients->count() }} patient(s) trouvé(s)
                    @if(request('search'))
                        pour "{{ request('search') }}"
                    @endif
                </div>
            </div>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-users me-2"></i>
                        Patients Trouvés
                    </h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route('secretary.patients.new') }}" class="btn btn-primary">
                            <i class="fas fa-user-plus me-2"></i>Nouveau Patient
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($patients->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Photo</th>
                                        <th>Nom complet</th>
                                        <th>Email</th>
                                        <th>Téléphone</th>
                                        <th>Âge</th>
                                        <th>Genre</th>
                                        <th>Inscrit le</th>
                                        <th>Dernier RDV</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($patients as $patient)
                                        <tr>
                                            <td>
                                                @if($patient->photo)
                                                    <img src="{{ asset('storage/' . $patient->photo) }}" 
                                                         alt="Photo" 
                                                         class="rounded-circle" 
                                                         style="width: 40px; height: 40px; object-fit: cover;">
                                                @else
                                                    <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center" 
                                                         style="width: 40px; height: 40px;">
                                                        <i class="fas fa-user text-white"></i>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <div>
                                                    <strong>{{ $patient->first_name }} {{ $patient->last_name }}</strong>
                                                    @if($patient->identification_number)
                                                        <br><small class="text-muted">ID: {{ $patient->identification_number }}</small>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>{{ $patient->email }}</td>
                                            <td>{{ $patient->phone_number ?? 'Non renseigné' }}</td>
                                            <td>
                                                @if($patient->day_of_birth)
                                                    {{ \Carbon\Carbon::parse($patient->day_of_birth)->age }} ans
                                                @else
                                                    <span class="text-muted">Non renseigné</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($patient->gender)
                                                    @switch($patient->gender)
                                                        @case('male')
                                                            <span class="badge bg-primary">Homme</span>
                                                            @break
                                                        @case('female')
                                                            <span class="badge bg-pink">Femme</span>
                                                            @break
                                                        @default
                                                            <span class="badge bg-secondary">Autre</span>
                                                    @endswitch
                                                @else
                                                    <span class="text-muted">Non renseigné</span>
                                                @endif
                                            </td>
                                            <td>{{ $patient->created_at->format('d/m/Y') }}</td>
                                            <td>
                                                @php
                                                    $lastAppointment = \App\Models\Appointment::where('user_id', $patient->id)
                                                        ->where('service_id', Auth::user()->service_id)
                                                        ->orderBy('appointment_date', 'desc')
                                                        ->first();
                                                @endphp
                                                @if($lastAppointment)
                                                    {{ \Carbon\Carbon::parse($lastAppointment->appointment_date)->format('d/m/Y') }}
                                                    <br><small class="text-muted">{{ $lastAppointment->status }}</small>
                                                @else
                                                    <span class="text-muted">Aucun RDV</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <button type="button" class="btn btn-outline-primary" 
                                                            onclick="viewPatient({{ $patient->id }})" 
                                                            title="Voir le profil">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-success" 
                                                            onclick="createAppointment({{ $patient->id }})" 
                                                            title="Créer un RDV">
                                                        <i class="fas fa-calendar-plus"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-info" 
                                                            onclick="viewMedicalFile({{ $patient->id }})" 
                                                            title="Dossier médical">
                                                        <i class="fas fa-file-medical"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if($patients->hasPages())
                            <div class="d-flex justify-content-center mt-4">
                                {{ $patients->appends(request()->query())->links() }}
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-search fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">Aucun patient trouvé</h5>
                            <p class="text-muted">
                                @if(request()->hasAny(['search', 'gender', 'age_range', 'has_appointments', 'date_from', 'date_to']))
                                    Aucun patient ne correspond à vos critères de recherche.
                                @else
                                    Il n'y a aucun patient dans votre service pour le moment.
                                @endif
                            </p>
                            <a href="{{ route('secretary.patients.new') }}" class="btn btn-primary">
                                <i class="fas fa-user-plus me-2"></i>Ajouter un patient
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour voir les détails du patient -->
<div class="modal fade" id="patientModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-user me-2"></i>Détails du Patient
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="patientDetails">
                <!-- Contenu dynamique -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Fermer
                </button>
                <button type="button" class="btn btn-primary" onclick="createAppointmentFromModal()">
                    <i class="fas fa-calendar-plus me-1"></i>Créer un RDV
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.table th {
    border-top: none;
    font-weight: 600;
    color: #5a5c69;
}

.table td {
    vertical-align: middle;
}

.btn-group-sm .btn {
    padding: 0.25rem 0.5rem;
}

.bg-pink {
    background-color: #e91e63 !important;
}
</style>
@endpush

@push('scripts')
<script>
let selectedPatientId = null;

// Voir les détails d'un patient
function viewPatient(patientId) {
    selectedPatientId = patientId;
    
    document.getElementById('patientDetails').innerHTML = `
        <div class="text-center py-3">
            <i class="fas fa-spinner fa-spin fa-2x text-primary"></i>
            <p class="mt-2">Chargement des détails...</p>
        </div>
    `;
    
    const modal = new bootstrap.Modal(document.getElementById('patientModal'));
    modal.show();
    
    // Simuler le chargement des détails (à remplacer par un appel AJAX réel)
    setTimeout(() => {
        document.getElementById('patientDetails').innerHTML = `
            <div class="row">
                <div class="col-md-6">
                    <h6><i class="fas fa-user me-2"></i>Informations personnelles</h6>
                    <div class="mb-3">
                        <strong>Nom complet:</strong><br>
                        [Nom du patient]
                    </div>
                    <div class="mb-3">
                        <strong>Email:</strong><br>
                        [Email du patient]
                    </div>
                    <div class="mb-3">
                        <strong>Téléphone:</strong><br>
                        [Téléphone du patient]
                    </div>
                </div>
                <div class="col-md-6">
                    <h6><i class="fas fa-calendar me-2"></i>Historique médical</h6>
                    <div class="mb-3">
                        <strong>Dernier rendez-vous:</strong><br>
                        [Date du dernier RDV]
                    </div>
                    <div class="mb-3">
                        <strong>Nombre de RDV:</strong><br>
                        [Nombre total de RDV]
                    </div>
                    <div class="mb-3">
                        <strong>Dossier médical:</strong><br>
                        [Statut du dossier]
                    </div>
                </div>
            </div>
        `;
    }, 1000);
}

// Créer un rendez-vous pour un patient
function createAppointment(patientId) {
    selectedPatientId = patientId;
    window.location.href = `{{ route('secretary.appointments.create') }}?patient=${patientId}`;
}

// Créer un rendez-vous depuis le modal
function createAppointmentFromModal() {
    if (selectedPatientId) {
        createAppointment(selectedPatientId);
    }
}

// Voir le dossier médical
function viewMedicalFile(patientId) {
    window.location.href = `{{ route('secretary.medical-files') }}?patient=${patientId}`;
}

// Auto-submit form on enter in search field
document.getElementById('search').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        document.getElementById('searchForm').submit();
    }
});
</script>
@endpush
