@extends('layouts.doctor')

@section('title', 'Prescriptions du Service - Docteur')
@section('page-title', 'Prescriptions du Service')
@section('page-subtitle', 'Gestion des prescriptions médicales du service')
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

    <!-- Statistiques des prescriptions -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-primary">
                            <i class="fas fa-pills text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $prescriptions->count() }}</h4>
                            <p class="text-muted mb-0">Total prescriptions</p>
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
                            <h4 class="mb-1">{{ $prescriptions->where('is_done', true)->count() }}</h4>
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
                            <h4 class="mb-1">{{ $prescriptions->where('created_at', '>=', now()->subDays(7))->count() }}</h4>
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
                            <i class="fas fa-clock text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $prescriptions->where('created_at', '>=', now()->subDays(30))->count() }}</h4>
                            <p class="text-muted mb-0">Ce mois</p>
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
                            <i class="fas fa-pills me-2"></i>Prescriptions du service
                        </h5>
                        <div class="d-flex gap-2">
                            <a href="{{ route('doctor.medical-files') }}" class="btn btn-outline-primary">
                                <i class="fas fa-file-medical me-2"></i>Dossiers médicaux
                            </a>
                            <a href="{{ route('doctor.patients') }}" class="btn btn-outline-success">
                                <i class="fas fa-users me-2"></i>Mes patients
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des prescriptions -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if($prescriptions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Patient</th>
                                        <th>Médecin</th>
                                        <th>Médicament/Soin</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($prescriptions as $prescription)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-calendar text-primary me-2"></i>
                                                    {{ \Carbon\Carbon::parse($prescription->created_at)->format('d/m/Y') }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-user text-success me-2"></i>
                                                    <div>
                                                        <div class="fw-bold">{{ $prescription->medicalFile->user->first_name ?? 'N/A' }} {{ $prescription->medicalFile->user->last_name ?? 'N/A' }}</div>
                                                        <small class="text-muted">{{ $prescription->medicalFile->user->phone_number ?? 'Tél. non renseigné' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-user-md text-primary me-2"></i>
                                                    {{ $prescription->prescription->doctor->first_name ?? 'N/A' }} {{ $prescription->prescription->doctor->last_name ?? 'N/A' }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-pills text-warning me-2"></i>
                                                    {{ $prescription->prescription->name ?? 'Médicament non spécifié' }}
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $prescription->is_done ? 'success' : 'warning' }}">
                                                    {{ $prescription->is_done ? 'Terminée' : 'En cours' }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('doctor.medical-files.show', $prescription->medicalFile->user) }}" 
                                                       class="btn btn-outline-primary" 
                                                       title="Voir le dossier">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('doctor.patients.show', $prescription->medicalFile->user) }}" 
                                                       class="btn btn-outline-success" 
                                                       title="Voir le patient">
                                                        <i class="fas fa-user"></i>
                                                    </a>
                                                    @if($prescription->prescription->service_id == $doctor->service_id)
                                                        @if(!$prescription->is_done)
                                                            <button type="button" class="btn btn-outline-success btn-sm" 
                                                                    onclick="markPrescriptionAsDone({{ $prescription->id }})" 
                                                                    title="Marquer comme terminée">
                                                                <i class="fas fa-check"></i>
                                                            </button>
                                                        @else
                                                            <button type="button" class="btn btn-outline-warning btn-sm" 
                                                                    onclick="markPrescriptionAsInProgress({{ $prescription->id }})" 
                                                                    title="Marquer comme en cours">
                                                                <i class="fas fa-clock"></i>
                                                            </button>
                                                        @endif
                                                    @else
                                                        <span class="text-muted small">Autre service</span>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-pills fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">Aucune prescription</h5>
                            <p class="text-muted">Aucune prescription n'a été trouvée pour ce service.</p>
                            <a href="{{ route('doctor.medical-files') }}" class="btn btn-primary">
                                <i class="fas fa-file-medical me-2"></i>Voir les dossiers médicaux
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
                        <i class="fas fa-chart-line me-2"></i>Résumé des prescriptions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary">📊 Statistiques</h6>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-pills text-primary me-2"></i><strong>Total prescriptions:</strong> {{ $prescriptions->count() }}</li>
                                <li><i class="fas fa-check-circle text-success me-2"></i><strong>Prescriptions actives:</strong> {{ $prescriptions->where('status', 'active')->count() }}</li>
                                <li><i class="fas fa-calendar-check text-info me-2"></i><strong>Cette semaine:</strong> {{ $prescriptions->where('created_at', '>=', now()->subDays(7))->count() }}</li>
                                <li><i class="fas fa-clock text-warning me-2"></i><strong>Ce mois:</strong> {{ $prescriptions->where('created_at', '>=', now()->subDays(30))->count() }}</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-success">💡 Bonnes pratiques</h6>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-lightbulb text-warning me-2"></i>Vérifiez les allergies du patient avant de prescrire</li>
                                <li><i class="fas fa-file-medical text-info me-2"></i>Consultez l'historique médical du patient</li>
                                <li><i class="fas fa-clock text-primary me-2"></i>Respectez les posologies recommandées</li>
                                <li><i class="fas fa-notes-medical text-success me-2"></i>Expliquez clairement le traitement au patient</li>
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
// Fonctions pour les actions des prescriptions
function markPrescriptionAsDone(prescriptionId) {
    if (confirm('Marquer cette prescription comme terminée ?')) {
        updatePrescriptionStatus(prescriptionId, true);
    }
}

function markPrescriptionAsInProgress(prescriptionId) {
    if (confirm('Marquer cette prescription comme en cours ?')) {
        updatePrescriptionStatus(prescriptionId, false);
    }
}

function updatePrescriptionStatus(prescriptionId, isDone) {
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    fetch(`/doctor/prescriptions/${prescriptionId}/status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json',
            'X-HTTP-Method-Override': 'PUT'
        },
        body: JSON.stringify({
            is_done: isDone
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Erreur lors de la mise à jour: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Erreur lors de la mise à jour');
    });
}
</script>
@endpush
