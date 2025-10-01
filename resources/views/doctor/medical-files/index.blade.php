@extends('layouts.doctor')

@section('title', 'Dossiers Médicaux - Docteur')
@section('page-title', 'Dossiers Médicaux')
@section('page-subtitle', 'Gestion des dossiers médicaux de vos patients')
@section('user-role', 'Médecin')

@section('content')
<div class="container-fluid py-4">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-primary">
                            <i class="fas fa-file-medical text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $totalFiles ?? 0 }}</h4>
                            <p class="text-muted mb-0">Dossiers médicaux</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-success">
                            <i class="fas fa-calendar-week text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $recentFiles ?? 0 }}</h4>
                            <p class="text-muted mb-0">Dossiers récents (7j)</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-info">
                            <i class="fas fa-users text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $medicalFiles->count() ?? 0 }}</h4>
                            <p class="text-muted mb-0">Patients actifs</p>
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
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2"></i>
                        Dossiers médicaux de vos patients
                    </h5>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-primary" onclick="refreshData()">
                            <i class="fas fa-sync-alt me-2"></i>Actualiser
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    @if($medicalFiles->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Patient</th>
                                        <th>Informations</th>
                                        <th>Dernière consultation</th>
                                        <th>Notes récentes</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($medicalFiles as $medicalFile)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($medicalFile->user && $medicalFile->user->photo)
                                                    <img src="{{ asset('storage/' . $medicalFile->user->photo) }}" 
                                                         alt="Photo patient" 
                                                         class="rounded-circle me-2" 
                                                         style="width: 40px; height: 40px; object-fit: cover;">
                                                @else
                                                    <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center me-2" 
                                                         style="width: 40px; height: 40px;">
                                                        <i class="fas fa-user text-white"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="fw-bold">{{ $medicalFile->user->first_name ?? 'N/A' }} {{ $medicalFile->user->last_name ?? 'N/A' }}</div>
                                                    <small class="text-muted">{{ $medicalFile->user->email ?? 'N/A' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <small class="text-muted">Âge: {{ $medicalFile->user->age ?? 'N/A' }} ans</small><br>
                                                <small class="text-muted">Tél: {{ $medicalFile->user->phone_number ?? 'N/A' }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            @if($medicalFile->user->appointments->count() > 0)
                                                @php
                                                    $lastAppointment = $medicalFile->user->appointments->sortByDesc('appointment_date')->first();
                                                @endphp
                                                <div>
                                                    <strong>{{ \Carbon\Carbon::parse($lastAppointment->appointment_date)->format('d/m/Y') }}</strong><br>
                                                    <small class="text-muted">{{ $lastAppointment->appointment_time }}</small>
                                                </div>
                                            @else
                                                <span class="text-muted">Aucune consultation</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($medicalFile->note && $medicalFile->note->count() > 0)
                                                @php
                                                    $lastNote = $medicalFile->note->sortByDesc('created_at')->first();
                                                @endphp
                                                <div class="text-truncate" style="max-width: 200px;" title="{{ $lastNote->content }}">
                                                    {{ \Str::limit($lastNote->content, 50) }}
                                                </div>
                                                <small class="text-muted">{{ $lastNote->created_at->diffForHumans() }}</small>
                                            @else
                                                <span class="text-muted">Aucune note</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($medicalFile->user->appointments->where('status', 'confirmed')->count() > 0)
                                                <span class="badge bg-success">Actif</span>
                                            @elseif($medicalFile->user->appointments->where('status', 'pending')->count() > 0)
                                                <span class="badge bg-warning">En attente</span>
                                            @else
                                                <span class="badge bg-secondary">Inactif</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('medical-files.show', $medicalFile->id) }}" 
                                                   class="btn btn-info" 
                                                   title="Voir le dossier">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <button class="btn btn-primary" 
                                                        onclick="addNote({{ $medicalFile->id }})" 
                                                        title="Ajouter une note">
                                                    <i class="fas fa-sticky-note"></i>
                                                </button>
                                                <button class="btn btn-success" 
                                                        onclick="addPrescription({{ $medicalFile->id }})" 
                                                        title="Ajouter prescription">
                                                    <i class="fas fa-pills"></i>
                                                </button>
                                                <button class="btn btn-warning" 
                                                        onclick="addExam({{ $medicalFile->id }})" 
                                                        title="Prescrire examen">
                                                    <i class="fas fa-flask"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-4">
                            {{ $medicalFiles->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-file-medical fa-4x text-muted mb-4"></i>
                            <h5 class="text-muted">Aucun dossier médical trouvé</h5>
                            <p class="text-muted">Vous n'avez pas encore de patients avec des dossiers médicaux.</p>
                            <a href="{{ route('doctor.patients') }}" class="btn btn-primary">
                                <i class="fas fa-users me-2"></i>Voir mes patients
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour ajouter une note -->
<div class="modal fade" id="addNoteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajouter une note médicale</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="noteForm">
                    <div class="mb-3">
                        <label for="noteContent" class="form-label">Contenu de la note</label>
                        <textarea class="form-control" id="noteContent" rows="5" required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" onclick="saveNote()">Enregistrer</button>
            </div>
        </div>
    </div>
</div>

<script>
let currentMedicalFileId = null;

function refreshData() {
    location.reload();
}

function addNote(medicalFileId) {
    currentMedicalFileId = medicalFileId;
    const modal = new bootstrap.Modal(document.getElementById('addNoteModal'));
    modal.show();
}

function saveNote() {
    const content = document.getElementById('noteContent').value;
    
    if (!content.trim()) {
        alert('Veuillez saisir le contenu de la note');
        return;
    }
    
    fetch(`/medical-files/${currentMedicalFileId}/addnote`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            content: content
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.message) {
            alert('Note ajoutée avec succès');
            location.reload();
        } else {
            alert('Erreur lors de l\'ajout de la note');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Erreur lors de l\'ajout de la note');
    });
}

function addPrescription(medicalFileId) {
    alert('Fonctionnalité de prescription en cours de développement');
}

function addExam(medicalFileId) {
    alert('Fonctionnalité de prescription d\'examen en cours de développement');
}
</script>

<style>
.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.table th {
    border-top: none;
    font-weight: 600;
    color: #5a5c69;
}

.btn-group-sm .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
}

.card {
    border: none;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 2rem 0 rgba(58, 59, 69, 0.2);
}
</style>
@endsection
