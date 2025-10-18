@extends('layouts.doctor')

@section('title', 'Historique Médical du Service - Docteur')
@section('page-title', 'Historique Médical du Service')
@section('page-subtitle', 'Consultation de l\'historique médical des patients du service')
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

    <!-- Statistiques de l'historique médical -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-primary">
                            <i class="fas fa-history text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $medicalFiles->count() }}</h4>
                            <p class="text-muted mb-0">Dossiers médicaux</p>
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
                            <i class="fas fa-calendar-check text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $medicalFiles->where('updated_at', '>=', now()->subDays(30))->count() }}</h4>
                            <p class="text-muted mb-0">Mis à jour ce mois</p>
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
                            <i class="fas fa-users text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $medicalFiles->pluck('patient_id')->unique()->count() }}</h4>
                            <p class="text-muted mb-0">Patients uniques</p>
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
                            <h4 class="mb-1">{{ $medicalFiles->where('updated_at', '>=', now()->subDays(7))->count() }}</h4>
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
                            <i class="fas fa-history me-2"></i>Historique médical du service
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

    <!-- Liste des dossiers médicaux -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if($medicalFiles->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Patient</th>
                                        <th>Médecin</th>
                                        <th>Date de création</th>
                                        <th>Dernière mise à jour</th>
                                        <th>Type de consultation</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($medicalFiles as $file)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-user text-success me-2"></i>
                                                    <div>
                                                        <div class="fw-bold">{{ $file->patient->first_name ?? 'N/A' }} {{ $file->patient->last_name ?? 'N/A' }}</div>
                                                        <small class="text-muted">{{ $file->patient->phone_number ?? 'Tél. non renseigné' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-user-md text-primary me-2"></i>
                                                    {{ $file->doctor->first_name ?? 'N/A' }} {{ $file->doctor->last_name ?? 'N/A' }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-calendar text-primary me-2"></i>
                                                    {{ \Carbon\Carbon::parse($file->created_at)->format('d/m/Y') }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-clock text-info me-2"></i>
                                                    {{ \Carbon\Carbon::parse($file->updated_at)->format('d/m/Y H:i') }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-stethoscope text-warning me-2"></i>
                                                    {{ $file->consultation_type ?? 'Type non spécifié' }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('doctor.medical-files.show', $file->patient) }}" 
                                                       class="btn btn-outline-primary" 
                                                       title="Voir le dossier complet">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('doctor.patients.show', $file->patient) }}" 
                                                       class="btn btn-outline-success" 
                                                       title="Voir le patient">
                                                        <i class="fas fa-user"></i>
                                                    </a>
                                                    <a href="{{ route('doctor.patients.history', $file->patient) }}" 
                                                       class="btn btn-outline-info" 
                                                       title="Historique complet">
                                                        <i class="fas fa-history"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-history fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">Aucun historique médical</h5>
                            <p class="text-muted">Aucun dossier médical n'a été trouvé pour ce service.</p>
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
                        <i class="fas fa-chart-line me-2"></i>Résumé de l'historique médical
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary">📊 Statistiques</h6>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-history text-primary me-2"></i><strong>Total dossiers:</strong> {{ $medicalFiles->count() }}</li>
                                <li><i class="fas fa-calendar-check text-success me-2"></i><strong>Mis à jour ce mois:</strong> {{ $medicalFiles->where('updated_at', '>=', now()->subDays(30))->count() }}</li>
                                <li><i class="fas fa-users text-info me-2"></i><strong>Patients uniques:</strong> {{ $medicalFiles->pluck('patient_id')->unique()->count() }}</li>
                                <li><i class="fas fa-clock text-warning me-2"></i><strong>Cette semaine:</strong> {{ $medicalFiles->where('updated_at', '>=', now()->subDays(7))->count() }}</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-success">💡 Bonnes pratiques</h6>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-lightbulb text-warning me-2"></i>Consultez toujours l'historique avant une consultation</li>
                                <li><i class="fas fa-file-medical text-info me-2"></i>Mettez à jour les dossiers après chaque consultation</li>
                                <li><i class="fas fa-clock text-primary me-2"></i>Suivez l'évolution des traitements</li>
                                <li><i class="fas fa-notes-medical text-success me-2"></i>Notez tous les changements importants</li>
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
