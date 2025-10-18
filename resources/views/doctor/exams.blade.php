@extends('layouts.doctor')

@section('title', 'Examens du Service - Docteur')
@section('page-title', 'Examens du Service')
@section('page-subtitle', 'Gestion des examens médicaux du service')
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

    <!-- Statistiques des examens -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-primary">
                            <i class="fas fa-flask text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $exams->count() }}</h4>
                            <p class="text-muted mb-0">Total examens</p>
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
                            <h4 class="mb-1">{{ $exams->where('status', 'completed')->count() }}</h4>
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
                            <h4 class="mb-1">{{ $exams->where('status', 'pending')->count() }}</h4>
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
                            <h4 class="mb-1">{{ $exams->where('created_at', '>=', now()->subDays(7))->count() }}</h4>
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
                            <i class="fas fa-flask me-2"></i>Examens du service
                        </h5>
                        <div class="d-flex gap-2">
                            <a href="{{ route('doctor.results') }}" class="btn btn-outline-primary">
                                <i class="fas fa-clipboard-list me-2"></i>Résultats
                            </a>
                            <a href="{{ route('doctor.prescriptions') }}" class="btn btn-outline-success">
                                <i class="fas fa-pills me-2"></i>Prescriptions
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des examens -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if($exams->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Patient</th>
                                        <th>Médecin</th>
                                        <th>Type d'examen</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($exams as $exam)
                                        <tr class="{{ $exam->status == 'completed' ? 'table-success' : ($exam->status == 'pending' ? 'table-warning' : 'table-info') }}">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-calendar text-primary me-2"></i>
                                                    {{ \Carbon\Carbon::parse($exam->created_at)->format('d/m/Y') }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-user text-success me-2"></i>
                                                    <div>
                                                        <div class="fw-bold">{{ $exam->patient->first_name ?? 'N/A' }} {{ $exam->patient->last_name ?? 'N/A' }}</div>
                                                        <small class="text-muted">{{ $exam->patient->phone_number ?? 'Tél. non renseigné' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-user-md text-primary me-2"></i>
                                                    {{ $exam->doctor->first_name ?? 'N/A' }} {{ $exam->doctor->last_name ?? 'N/A' }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-flask text-warning me-2"></i>
                                                    {{ $exam->exam_type ?? 'Type non spécifié' }}
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $exam->status == 'completed' ? 'success' : ($exam->status == 'pending' ? 'warning' : 'info') }}">
                                                    {{ ucfirst($exam->status ?? 'Non défini') }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('doctor.medical-files.show', $exam->patient) }}" 
                                                       class="btn btn-outline-primary" 
                                                       title="Voir le dossier">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('doctor.patients.show', $exam->patient) }}" 
                                                       class="btn btn-outline-success" 
                                                       title="Voir le patient">
                                                        <i class="fas fa-user"></i>
                                                    </a>
                                                    @if($exam->status == 'completed')
                                                        <a href="{{ route('doctor.results') }}" 
                                                           class="btn btn-outline-info" 
                                                           title="Voir les résultats">
                                                            <i class="fas fa-clipboard-list"></i>
                                                        </a>
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
                            <i class="fas fa-flask fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">Aucun examen</h5>
                            <p class="text-muted">Aucun examen n'a été trouvé pour ce service.</p>
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
                        <i class="fas fa-chart-line me-2"></i>Résumé des examens
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary">📊 Statistiques</h6>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-flask text-primary me-2"></i><strong>Total examens:</strong> {{ $exams->count() }}</li>
                                <li><i class="fas fa-check-circle text-success me-2"></i><strong>Examens terminés:</strong> {{ $exams->where('status', 'completed')->count() }}</li>
                                <li><i class="fas fa-clock text-warning me-2"></i><strong>En attente:</strong> {{ $exams->where('status', 'pending')->count() }}</li>
                                <li><i class="fas fa-calendar-check text-info me-2"></i><strong>Cette semaine:</strong> {{ $exams->where('created_at', '>=', now()->subDays(7))->count() }}</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-success">💡 Bonnes pratiques</h6>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-lightbulb text-warning me-2"></i>Prescrivez les examens nécessaires selon les symptômes</li>
                                <li><i class="fas fa-file-medical text-info me-2"></i>Consultez l'historique médical du patient</li>
                                <li><i class="fas fa-clock text-primary me-2"></i>Suivez les résultats des examens prescrits</li>
                                <li><i class="fas fa-notes-medical text-success me-2"></i>Expliquez l'importance de l'examen au patient</li>
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

.table-warning {
    background-color: rgba(255, 193, 7, 0.1);
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
