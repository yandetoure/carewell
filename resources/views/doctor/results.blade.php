@extends('layouts.doctor')

@section('title', 'Résultats du Service - Docteur')
@section('page-title', 'Résultats du Service')
@section('page-subtitle', 'Gestion des résultats d\'examens du service')
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

    <!-- Statistiques des résultats -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-primary">
                            <i class="fas fa-clipboard-list text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $results->count() }}</h4>
                            <p class="text-muted mb-0">Total résultats</p>
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
                            <h4 class="mb-1">{{ $results->where('status', 'normal')->count() }}</h4>
                            <p class="text-muted mb-0">Normaux</p>
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
                            <i class="fas fa-exclamation-triangle text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $results->where('status', 'abnormal')->count() }}</h4>
                            <p class="text-muted mb-0">Anormaux</p>
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
                            <h4 class="mb-1">{{ $results->where('created_at', '>=', now()->subDays(7))->count() }}</h4>
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
                            <i class="fas fa-clipboard-list me-2"></i>Résultats du service
                        </h5>
                        <div class="d-flex gap-2">
                            <a href="{{ route('doctor.exams') }}" class="btn btn-outline-primary">
                                <i class="fas fa-flask me-2"></i>Examens
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

    <!-- Liste des résultats -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if($results->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Patient</th>
                                        <th>Médecin</th>
                                        <th>Type d'examen</th>
                                        <th>Résultat</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($results as $result)
                                        <tr class="{{ $result->status == 'normal' ? 'table-success' : ($result->status == 'abnormal' ? 'table-warning' : 'table-info') }}">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-calendar text-primary me-2"></i>
                                                    {{ \Carbon\Carbon::parse($result->created_at)->format('d/m/Y') }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-user text-success me-2"></i>
                                                    <div>
                                                        <div class="fw-bold">{{ $result->patient->first_name ?? 'N/A' }} {{ $result->patient->last_name ?? 'N/A' }}</div>
                                                        <small class="text-muted">{{ $result->patient->phone_number ?? 'Tél. non renseigné' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-user-md text-primary me-2"></i>
                                                    {{ $result->doctor->first_name ?? 'N/A' }} {{ $result->doctor->last_name ?? 'N/A' }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-flask text-warning me-2"></i>
                                                    {{ $result->exam_type ?? 'Type non spécifié' }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-clipboard-list text-info me-2"></i>
                                                    {{ Str::limit($result->result ?? 'Résultat non disponible', 50) }}
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $result->status == 'normal' ? 'success' : ($result->status == 'abnormal' ? 'warning' : 'info') }}">
                                                    {{ ucfirst($result->status ?? 'Non défini') }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('doctor.medical-files.show', $result->patient) }}" 
                                                       class="btn btn-outline-primary" 
                                                       title="Voir le dossier">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('doctor.patients.show', $result->patient) }}" 
                                                       class="btn btn-outline-success" 
                                                       title="Voir le patient">
                                                        <i class="fas fa-user"></i>
                                                    </a>
                                                    @if($result->status == 'abnormal')
                                                        <button type="button" class="btn btn-outline-warning" 
                                                                onclick="alert('Résultat anormal détecté - Consultation recommandée')" 
                                                                title="Alerte">
                                                            <i class="fas fa-exclamation-triangle"></i>
                                                        </button>
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
                            <i class="fas fa-clipboard-list fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">Aucun résultat</h5>
                            <p class="text-muted">Aucun résultat d'examen n'a été trouvé pour ce service.</p>
                            <a href="{{ route('doctor.exams') }}" class="btn btn-primary">
                                <i class="fas fa-flask me-2"></i>Voir les examens
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
                        <i class="fas fa-chart-line me-2"></i>Résumé des résultats
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary">📊 Statistiques</h6>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-clipboard-list text-primary me-2"></i><strong>Total résultats:</strong> {{ $results->count() }}</li>
                                <li><i class="fas fa-check-circle text-success me-2"></i><strong>Résultats normaux:</strong> {{ $results->where('status', 'normal')->count() }}</li>
                                <li><i class="fas fa-exclamation-triangle text-warning me-2"></i><strong>Résultats anormaux:</strong> {{ $results->where('status', 'abnormal')->count() }}</li>
                                <li><i class="fas fa-calendar-check text-info me-2"></i><strong>Cette semaine:</strong> {{ $results->where('created_at', '>=', now()->subDays(7))->count() }}</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-success">💡 Bonnes pratiques</h6>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-lightbulb text-warning me-2"></i>Analysez attentivement tous les résultats</li>
                                <li><i class="fas fa-file-medical text-info me-2"></i>Comparez avec les résultats précédents</li>
                                <li><i class="fas fa-clock text-primary me-2"></i>Communiquez rapidement les résultats anormaux</li>
                                <li><i class="fas fa-notes-medical text-success me-2"></i>Expliquez clairement les résultats au patient</li>
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
