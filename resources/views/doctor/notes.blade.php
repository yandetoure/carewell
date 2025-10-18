@extends('layouts.doctor')

@section('title', 'Notes de Consultation - Docteur')
@section('page-title', 'Notes de Consultation')
@section('page-subtitle', 'Gestion des notes de consultation du service')
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

    <!-- Statistiques des notes -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-primary">
                            <i class="fas fa-edit text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $notes->count() }}</h4>
                            <p class="text-muted mb-0">Total notes</p>
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
                            <h4 class="mb-1">{{ $notes->where('created_at', '>=', now()->subDays(7))->count() }}</h4>
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
                        <div class="stat-icon bg-info">
                            <i class="fas fa-users text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $notes->pluck('patient_id')->unique()->count() }}</h4>
                            <p class="text-muted mb-0">Patients concernés</p>
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
                            <h4 class="mb-1">{{ $notes->where('created_at', '>=', now()->subDays(30))->count() }}</h4>
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
                            <i class="fas fa-edit me-2"></i>Notes de consultation du service
                        </h5>
                        <div class="d-flex gap-2">
                            <a href="{{ route('doctor.consultations') }}" class="btn btn-outline-primary">
                                <i class="fas fa-stethoscope me-2"></i>Consultations
                            </a>
                            <a href="{{ route('doctor.medical-files') }}" class="btn btn-outline-success">
                                <i class="fas fa-file-medical me-2"></i>Dossiers médicaux
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des notes -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if($notes->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Patient</th>
                                        <th>Médecin</th>
                                        <th>Type de consultation</th>
                                        <th>Note</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($notes as $note)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-calendar text-primary me-2"></i>
                                                    {{ \Carbon\Carbon::parse($note->created_at)->format('d/m/Y') }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-user text-success me-2"></i>
                                                    <div>
                                                        <div class="fw-bold">{{ $note->patient->first_name ?? 'N/A' }} {{ $note->patient->last_name ?? 'N/A' }}</div>
                                                        <small class="text-muted">{{ $note->patient->phone_number ?? 'Tél. non renseigné' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-user-md text-primary me-2"></i>
                                                    {{ $note->doctor->first_name ?? 'N/A' }} {{ $note->doctor->last_name ?? 'N/A' }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-stethoscope text-warning me-2"></i>
                                                    {{ $note->consultation_type ?? 'Type non spécifié' }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-edit text-info me-2"></i>
                                                    {{ Str::limit($note->note ?? 'Note non disponible', 100) }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('doctor.medical-files.show', $note->patient) }}" 
                                                       class="btn btn-outline-primary" 
                                                       title="Voir le dossier">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('doctor.patients.show', $note->patient) }}" 
                                                       class="btn btn-outline-success" 
                                                       title="Voir le patient">
                                                        <i class="fas fa-user"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-outline-info" 
                                                            onclick="showNoteDetails('{{ $note->note ?? 'Note non disponible' }}')" 
                                                            title="Voir la note complète">
                                                        <i class="fas fa-file-text"></i>
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
                            <i class="fas fa-edit fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">Aucune note de consultation</h5>
                            <p class="text-muted">Aucune note de consultation n'a été trouvée pour ce service.</p>
                            <a href="{{ route('doctor.consultations') }}" class="btn btn-primary">
                                <i class="fas fa-stethoscope me-2"></i>Voir les consultations
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
                        <i class="fas fa-chart-line me-2"></i>Résumé des notes de consultation
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary">📊 Statistiques</h6>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-edit text-primary me-2"></i><strong>Total notes:</strong> {{ $notes->count() }}</li>
                                <li><i class="fas fa-calendar-check text-success me-2"></i><strong>Cette semaine:</strong> {{ $notes->where('created_at', '>=', now()->subDays(7))->count() }}</li>
                                <li><i class="fas fa-users text-info me-2"></i><strong>Patients concernés:</strong> {{ $notes->pluck('patient_id')->unique()->count() }}</li>
                                <li><i class="fas fa-clock text-warning me-2"></i><strong>Ce mois:</strong> {{ $notes->where('created_at', '>=', now()->subDays(30))->count() }}</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-success">💡 Bonnes pratiques</h6>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-lightbulb text-warning me-2"></i>Prenez des notes détaillées pendant la consultation</li>
                                <li><i class="fas fa-file-medical text-info me-2"></i>Mentionnez les symptômes et observations importantes</li>
                                <li><i class="fas fa-clock text-primary me-2"></i>Notez les recommandations et conseils donnés</li>
                                <li><i class="fas fa-notes-medical text-success me-2"></i>Conservez un historique des évolutions</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour afficher les détails de la note -->
<div class="modal fade" id="noteDetailsModal" tabindex="-1" aria-labelledby="noteDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="noteDetailsModalLabel">
                    <i class="fas fa-file-text me-2"></i>Détails de la note
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="noteDetailsContent">
                    <!-- Le contenu sera inséré ici par JavaScript -->
                </div>
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
function showNoteDetails(noteContent) {
    document.getElementById('noteDetailsContent').innerHTML = `
        <div class="card">
            <div class="card-body">
                <h6 class="card-title">Contenu de la note :</h6>
                <div class="note-content" style="white-space: pre-wrap; line-height: 1.6;">
                    ${noteContent}
                </div>
            </div>
        </div>
    `;
    
    const modal = new bootstrap.Modal(document.getElementById('noteDetailsModal'));
    modal.show();
}
</script>
@endpush
