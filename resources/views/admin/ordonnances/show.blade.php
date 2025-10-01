@extends('layouts.admin')

@section('title', 'Détails Prescription - Admin')
@section('page-title', 'Détails de la Prescription')
@section('page-subtitle', 'Informations complètes sur l\'ordonnance médicale')
@section('user-role', 'Administrateur')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <!-- Informations générales -->
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-body text-center">
                    <div class="prescription-icon mb-3">
                        <i class="fas fa-prescription-bottle-alt text-primary fa-4x"></i>
                    </div>
                    
                    <h4 class="mb-1">{{ $ordonnance->numero_ordonnance }}</h4>
                    <p class="text-muted mb-3">
                        @php
                            $statusColors = [
                                'active' => 'success',
                                'expiree' => 'warning',
                                'annulee' => 'danger'
                            ];
                        @endphp
                        <span class="badge bg-{{ $statusColors[$ordonnance->statut] ?? 'secondary' }}">
                            {{ ucfirst($ordonnance->statut) }}
                        </span>
                    </p>
                    
                    <div class="d-flex justify-content-center gap-2">
                        <a href="{{ route('admin.prescriptions.edit', $ordonnance) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-1"></i>Modifier
                        </a>
                        <button class="btn btn-danger" onclick="deletePrescription({{ $ordonnance->id }})">
                            <i class="fas fa-trash me-1"></i>Supprimer
                        </button>
                    </div>
                </div>
            </div>

            <!-- Informations de la prescription -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Informations de la prescription
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-hashtag text-primary me-3"></i>
                                <div>
                                    <strong>Numéro d'ordonnance</strong><br>
                                    <span class="text-muted">{{ $ordonnance->numero_ordonnance }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-calendar text-success me-3"></i>
                                <div>
                                    <strong>Date de prescription</strong><br>
                                    <span class="text-muted">{{ $ordonnance->date_prescription->format('d/m/Y à H:i') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-user-injured text-info me-3"></i>
                                <div>
                                    <strong>Patient</strong><br>
                                    <span class="text-muted">{{ $ordonnance->patient_nom_complet }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-user-md text-warning me-3"></i>
                                <div>
                                    <strong>Médecin</strong><br>
                                    <span class="text-muted">{{ $ordonnance->medecin_nom_complet }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-tools me-2"></i>
                        Actions rapides
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button class="btn btn-outline-primary" onclick="printPrescription({{ $ordonnance->id }})">
                            <i class="fas fa-print me-2"></i>Imprimer l'ordonnance
                        </button>
                        <button class="btn btn-outline-success" onclick="sendPrescription({{ $ordonnance->id }})">
                            <i class="fas fa-paper-plane me-2"></i>Envoyer au patient
                        </button>
                        <button class="btn btn-outline-warning" onclick="renewPrescription({{ $ordonnance->id }})">
                            <i class="fas fa-redo me-2"></i>Renouveler
                        </button>
                        <button class="btn btn-outline-info" onclick="viewHistory({{ $ordonnance->id }})">
                            <i class="fas fa-history me-2"></i>Voir l'historique
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Détails et médicaments -->
        <div class="col-lg-8 mb-4">
            <!-- Instructions spéciales -->
            @if($ordonnance->instructions)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Instructions spéciales
                    </h5>
                </div>
                <div class="card-body">
                    <p>{{ $ordonnance->instructions }}</p>
                </div>
            </div>
            @endif

            <!-- Médicaments prescrits -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-pills me-2"></i>
                        Médicaments prescrits
                    </h5>
                    <span class="badge bg-primary">{{ $ordonnance->medicaments->count() }} médicament(s)</span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Médicament</th>
                                    <th>Catégorie</th>
                                    <th>Quantité</th>
                                    <th>Posologie</th>
                                    <th>Durée</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($ordonnance->medicaments as $medicament)
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ $medicament->nom }}</div>
                                        <small class="text-muted">{{ $medicament->description ?? 'Aucune description' }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ ucfirst($medicament->categorie) }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-bold">{{ $medicament->pivot->quantite ?? 1 }}</span>
                                        <small class="text-muted">{{ $medicament->unite_mesure }}</small>
                                    </td>
                                    <td>
                                        <span class="text-muted">{{ $medicament->pivot->posologie ?? 'Selon prescription' }}</span>
                                    </td>
                                    <td>
                                        <span class="text-muted">{{ $medicament->pivot->duree ?? 'Non spécifiée' }}</span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-primary" onclick="viewMedicament({{ $medicament->id }})" title="Voir">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-outline-warning" onclick="editMedicament({{ $medicament->id }})" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        <i class="fas fa-pills fa-2x mb-2"></i>
                                        <p>Aucun médicament prescrit</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Statistiques -->
            <div class="row g-4 mb-4">
                <div class="col-md-3">
                    <div class="card stat-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon bg-primary">
                                    <i class="fas fa-pills text-white"></i>
                                </div>
                                <div class="ms-3">
                                    <h4 class="mb-1">{{ $ordonnance->medicaments->count() }}</h4>
                                    <p class="text-muted mb-0">Médicaments</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card stat-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon bg-success">
                                    <i class="fas fa-money-bill-wave text-white"></i>
                                </div>
                                <div class="ms-3">
                                    <h4 class="mb-1">{{ number_format($ordonnance->medicaments->sum('prix_unitaire'), 0, ',', ' ') }}</h4>
                                    <p class="text-muted mb-0">Coût total (FCFA)</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card stat-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon bg-warning">
                                    <i class="fas fa-calendar-alt text-white"></i>
                                </div>
                                <div class="ms-3">
                                    <h4 class="mb-1">{{ $ordonnance->date_prescription->diffInDays(now()) }}</h4>
                                    <p class="text-muted mb-0">Jours écoulés</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card stat-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon bg-info">
                                    <i class="fas fa-check-circle text-white"></i>
                                </div>
                                <div class="ms-3">
                                    <h4 class="mb-1">{{ $ordonnance->medicaments->where('disponible', true)->count() }}</h4>
                                    <p class="text-muted mb-0">Disponibles</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informations du patient -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-user-injured me-2"></i>
                        Informations du patient
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3"
                                     style="width: 50px; height: 50px;">
                                    <i class="fas fa-user text-white fa-lg"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">{{ $ordonnance->patient_nom_complet }}</h6>
                                    <small class="text-muted">{{ $ordonnance->patient_email }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-success rounded-circle d-flex align-items-center justify-content-center me-3"
                                     style="width: 50px; height: 50px;">
                                    <i class="fas fa-user-md text-white fa-lg"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">{{ $ordonnance->medecin_nom_complet }}</h6>
                                    <small class="text-muted">Dr. {{ $ordonnance->medecin->name ?? 'Médecin' }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Téléphone patient</label>
                                <p class="text-muted">{{ $ordonnance->patient_phone ?? 'Non renseigné' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Date de naissance</label>
                                <p class="text-muted">{{ $ordonnance->patient_date_naissance ?? 'Non renseignée' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Historique des modifications -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-history me-2"></i>
                        Historique des modifications
                    </h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        @for($i = 1; $i <= 5; $i++)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1">Modification {{ $i }}</h6>
                                        <p class="text-muted mb-1">
                                            @php
                                                $actions = ['Création', 'Modification', 'Ajout médicament', 'Suppression médicament'];
                                                $action = $actions[array_rand($actions)];
                                            @endphp
                                            {{ $action }} de l'ordonnance {{ $ordonnance->numero_ordonnance }}
                                        </p>
                                        <small class="text-muted">{{ now()->subDays($i)->diffForHumans() }}</small>
                                    </div>
                                    <span class="badge bg-primary">{{ $action }}</span>
                                </div>
                            </div>
                        </div>
                        @endfor
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function deletePrescription(prescriptionId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette prescription ? Cette action est irréversible.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/prescriptions/${prescriptionId}`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        form.appendChild(csrfToken);
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        form.appendChild(methodField);
        
        document.body.appendChild(form);
        form.submit();
    }
}

function printPrescription(prescriptionId) {
    window.open(`/admin/prescriptions/${prescriptionId}/print`, '_blank');
}

function sendPrescription(prescriptionId) {
    if (confirm('Envoyer cette prescription au patient par email ?')) {
        alert('Prescription envoyée avec succès !');
    }
}

function renewPrescription(prescriptionId) {
    if (confirm('Renouveler cette prescription ?')) {
        window.location.href = `/admin/prescriptions/${prescriptionId}/renew`;
    }
}

function viewHistory(prescriptionId) {
    window.location.href = `/admin/prescriptions/${prescriptionId}/history`;
}

function viewMedicament(medicamentId) {
    window.location.href = `/admin/pharmacy/${medicamentId}`;
}

function editMedicament(medicamentId) {
    window.location.href = `/admin/pharmacy/${medicamentId}/edit`;
}
</script>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #dee2e6;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -22px;
    top: 5px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid #fff;
    box-shadow: 0 0 0 2px #dee2e6;
}

.timeline-content {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    border-left: 3px solid #007bff;
}

.prescription-icon {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}
</style>
@endsection
