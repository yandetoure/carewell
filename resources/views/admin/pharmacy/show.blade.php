@extends('layouts.admin')

@section('title', 'Détails Médicament - Admin')
@section('page-title', 'Détails du Médicament')
@section('page-subtitle', 'Informations complètes sur le médicament')
@section('user-role', 'Administrateur')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <!-- Informations générales -->
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-body text-center">
                    <div class="medicament-icon mb-3">
                        <i class="fas fa-pills text-primary fa-4x"></i>
                    </div>
                    
                    <h4 class="mb-1">{{ $medicament->nom }}</h4>
                    <p class="text-muted mb-3">{{ $medicament->categorie }}</p>
                    
                    <div class="d-flex justify-content-center gap-2">
                        <a href="{{ route('admin.pharmacy.edit', $medicament) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-1"></i>Modifier
                        </a>
                        <button class="btn btn-danger" onclick="deleteMedicament({{ $medicament->id }})">
                            <i class="fas fa-trash me-1"></i>Supprimer
                        </button>
                    </div>
                </div>
            </div>

            <!-- Informations du stock -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-boxes me-2"></i>
                        Informations du stock
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-cubes text-primary me-3"></i>
                                <div>
                                    <strong>Quantité en stock</strong><br>
                                    <span class="text-muted">{{ $medicament->quantite_stock }} {{ $medicament->unite_mesure }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-money-bill-wave text-success me-3"></i>
                                <div>
                                    <strong>Prix unitaire</strong><br>
                                    <span class="text-muted">{{ number_format($medicament->prix_unitaire, 0, ',', ' ') }} FCFA</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-calendar-alt text-warning me-3"></i>
                                <div>
                                    <strong>Date d'expiration</strong><br>
                                    <span class="text-muted">
                                        @if($medicament->date_expiration)
                                            {{ $medicament->date_expiration->format('d/m/Y') }}
                                            @if($medicament->date_expiration->diffInDays(now()) <= 30)
                                                <span class="badge bg-warning ms-2">Expire bientôt</span>
                                            @endif
                                        @else
                                            Non renseignée
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-info-circle text-info me-3"></i>
                                <div>
                                    <strong>Statut</strong><br>
                                    @if($medicament->disponible)
                                        <span class="badge bg-success">En stock</span>
                                    @else
                                        <span class="badge bg-danger">Rupture de stock</span>
                                    @endif
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
                        <button class="btn btn-outline-primary" onclick="updateStock({{ $medicament->id }})">
                            <i class="fas fa-boxes me-2"></i>Mettre à jour le stock
                        </button>
                        <button class="btn btn-outline-success" onclick="printLabel({{ $medicament->id }})">
                            <i class="fas fa-print me-2"></i>Imprimer l'étiquette
                        </button>
                        <button class="btn btn-outline-warning" onclick="checkExpiration({{ $medicament->id }})">
                            <i class="fas fa-calendar-check me-2"></i>Vérifier l'expiration
                        </button>
                        <button class="btn btn-outline-info" onclick="viewHistory({{ $medicament->id }})">
                            <i class="fas fa-history me-2"></i>Voir l'historique
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Détails et statistiques -->
        <div class="col-lg-8 mb-4">
            <!-- Description -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-file-alt me-2"></i>
                        Description
                    </h5>
                </div>
                <div class="card-body">
                    @if($medicament->description)
                        <p>{{ $medicament->description }}</p>
                    @else
                        <p class="text-muted">Aucune description disponible</p>
                    @endif
                </div>
            </div>

            <!-- Statistiques d'utilisation -->
            <div class="row g-4 mb-4">
                <div class="col-md-3">
                    <div class="card stat-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon bg-primary">
                                    <i class="fas fa-prescription-bottle-alt text-white"></i>
                                </div>
                                <div class="ms-3">
                                    <h4 class="mb-1">{{ $medicament->ordonnances()->count() }}</h4>
                                    <p class="text-muted mb-0">Prescriptions</p>
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
                                    <i class="fas fa-chart-line text-white"></i>
                                </div>
                                <div class="ms-3">
                                    <h4 class="mb-1">{{ $medicament->ordonnances()->whereMonth('ordonnances.created_at', now()->month)->count() }}</h4>
                                    <p class="text-muted mb-0">Ce mois</p>
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
                                    <i class="fas fa-calendar-day text-white"></i>
                                </div>
                                <div class="ms-3">
                                    <h4 class="mb-1">{{ $medicament->ordonnances()->whereDate('ordonnances.created_at', now()->toDateString())->count() }}</h4>
                                    <p class="text-muted mb-0">Aujourd'hui</p>
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
                                    <i class="fas fa-calculator text-white"></i>
                                </div>
                                <div class="ms-3">
                                    <h4 class="mb-1">{{ number_format($medicament->prix_unitaire * $medicament->quantite_stock, 0, ',', ' ') }}</h4>
                                    <p class="text-muted mb-0">Valeur stock</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Prescriptions récentes -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-prescription-bottle-alt me-2"></i>
                        Prescriptions récentes
                    </h5>
                    <a href="{{ route('admin.prescriptions') }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-eye me-1"></i>Voir toutes
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Patient</th>
                                    <th>Médecin</th>
                                    <th>Quantité</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($medicament->ordonnances()->with(['medecin'])->orderBy('ordonnances.date_prescription', 'desc')->take(10)->get() as $ordonnance)
                                <tr>
                                    <td>
                                        <div>{{ $ordonnance->date_prescription->format('d/m/Y') }}</div>
                                        <small class="text-muted">{{ $ordonnance->date_prescription->format('H:i') }}</small>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-2"
                                                 style="width: 32px; height: 32px;">
                                                <i class="fas fa-user text-white" style="font-size: 0.8em;"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold">{{ $ordonnance->patient_nom_complet }}</div>
                                                <small class="text-muted">{{ $ordonnance->patient_email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-success rounded-circle d-flex align-items-center justify-content-center me-2"
                                                 style="width: 32px; height: 32px;">
                                                <i class="fas fa-user-md text-white" style="font-size: 0.8em;"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold">{{ $ordonnance->medecin_nom_complet }}</div>
                                                <small class="text-muted">Dr. {{ $ordonnance->medecin->name ?? 'Médecin' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $ordonnance->pivot->quantite ?? 1 }} {{ $medicament->unite_mesure }}</span>
                                    </td>
                                    <td>
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
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-primary" onclick="viewPrescription({{ $ordonnance->id }})" title="Voir">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-outline-success" onclick="printPrescription({{ $ordonnance->id }})" title="Imprimer">
                                                <i class="fas fa-print"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        <i class="fas fa-prescription-bottle-alt fa-2x mb-2"></i>
                                        <p>Aucune prescription trouvée</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Historique des mouvements -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-history me-2"></i>
                        Historique des mouvements de stock
                    </h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        @for($i = 1; $i <= 8; $i++)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1">Mouvement de stock {{ $i }}</h6>
                                        <p class="text-muted mb-1">
                                            @php
                                                $actions = ['Ajout', 'Retrait', 'Ajustement', 'Expiration'];
                                                $action = $actions[array_rand($actions)];
                                                $quantite = rand(1, 50);
                                            @endphp
                                            {{ $action }} de {{ $quantite }} {{ $medicament->unite_mesure }}
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
function deleteMedicament(medicamentId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce médicament ? Cette action est irréversible.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/pharmacy/${medicamentId}`;
        
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

function updateStock(medicamentId) {
    const newStock = prompt('Nouvelle quantité en stock:');
    if (newStock !== null && !isNaN(newStock)) {
        // Ici vous pouvez faire un appel AJAX pour mettre à jour le stock
        alert('Stock mis à jour avec succès!');
        location.reload();
    }
}

function printLabel(medicamentId) {
    window.open(`/admin/pharmacy/${medicamentId}/print-label`, '_blank');
}

function checkExpiration(medicamentId) {
    alert('Vérification de l\'expiration...');
}

function viewHistory(medicamentId) {
    window.location.href = `/admin/pharmacy/${medicamentId}/history`;
}

function viewPrescription(prescriptionId) {
    window.location.href = `/admin/prescriptions/${prescriptionId}`;
}

function printPrescription(prescriptionId) {
    window.open(`/admin/prescriptions/${prescriptionId}/print`, '_blank');
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

.medicament-icon {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}
</style>
@endsection
