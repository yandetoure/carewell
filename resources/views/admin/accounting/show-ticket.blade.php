@extends('layouts.admin')

@section('title', 'Détails Ticket - Comptabilité')
@section('page-title', 'Détails du Ticket #' . $ticket->id)
@section('page-subtitle', 'Informations de paiement et facturation')
@section('user-role', 'Comptable')

@section('content')
<div class="container-fluid py-4">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Informations du ticket -->
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-receipt me-2"></i>
                        Informations du ticket
                    </h5>
                    <div>
                        @if($ticket->is_paid)
                            <span class="badge bg-success fs-6">
                                <i class="fas fa-check-circle me-1"></i>Payé
                            </span>
                        @else
                            <span class="badge bg-danger fs-6">
                                <i class="fas fa-times-circle me-1"></i>Non payé
                            </span>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-3">Informations générales</h6>
                            <div class="mb-3">
                                <label class="text-muted small">Numéro de ticket</label>
                                <div class="fw-bold">#{{ $ticket->id }}</div>
                            </div>
                            <div class="mb-3">
                                <label class="text-muted small">Date de création</label>
                                <div class="fw-bold">{{ $ticket->created_at->format('d/m/Y à H:i') }}</div>
                            </div>
                            <div class="mb-3">
                                <label class="text-muted small">Type de service</label>
                                <div>
                                    @if($ticket->appointment)
                                        <span class="badge bg-primary">Rendez-vous</span>
                                    @elseif($ticket->prescription)
                                        <span class="badge bg-info">Prescription</span>
                                    @elseif($ticket->exam)
                                        <span class="badge bg-warning">Examen</span>
                                    @else
                                        <span class="badge bg-secondary">Non défini</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-3">Montant</h6>
                            <div class="text-center p-4 bg-light rounded">
                                <div class="text-muted small mb-2">Montant à payer</div>
                                <h2 class="mb-0 text-primary">
                                    @if($ticket->appointment && $ticket->appointment->service)
                                        {{ number_format($ticket->appointment->service->price ?? 0, 0, ',', ' ') }} FCFA
                                    @else
                                        N/A
                                    @endif
                                </h2>
                            </div>
                        </div>
                    </div>

                    <!-- Patient -->
                    @if($ticket->user)
                    <div class="mb-4">
                        <h6 class="text-muted mb-3">Patient</h6>
                        <div class="d-flex align-items-center p-3 bg-light rounded">
                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3"
                                 style="width: 50px; height: 50px;">
                                <i class="fas fa-user text-white fa-lg"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ $ticket->user->first_name }} {{ $ticket->user->last_name }}</h6>
                                <div class="text-muted small">
                                    <i class="fas fa-envelope me-1"></i>{{ $ticket->user->email }}<br>
                                    <i class="fas fa-phone me-1"></i>{{ $ticket->user->phone_number ?? 'N/A' }}
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Médecin -->
                    @if($ticket->doctor)
                    <div class="mb-4">
                        <h6 class="text-muted mb-3">Médecin</h6>
                        <div class="d-flex align-items-center p-3 bg-light rounded">
                            <div class="bg-success rounded-circle d-flex align-items-center justify-content-center me-3"
                                 style="width: 50px; height: 50px;">
                                <i class="fas fa-user-md text-white fa-lg"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">Dr. {{ $ticket->doctor->first_name }} {{ $ticket->doctor->last_name }}</h6>
                                <div class="text-muted small">
                                    @if($ticket->doctor->specialite)
                                        <i class="fas fa-stethoscope me-1"></i>{{ $ticket->doctor->specialite }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Détails du rendez-vous -->
                    @if($ticket->appointment)
                    <div class="mb-4">
                        <h6 class="text-muted mb-3">Détails du rendez-vous</h6>
                        <div class="p-3 bg-light rounded">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small">Service</label>
                                    <div class="fw-bold">{{ $ticket->appointment->service->name ?? 'N/A' }}</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small">Date du rendez-vous</label>
                                    <div class="fw-bold">{{ \Carbon\Carbon::parse($ticket->appointment->appointment_date)->format('d/m/Y') }}</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small">Heure</label>
                                    <div class="fw-bold">{{ \Carbon\Carbon::parse($ticket->appointment->appointment_time)->format('H:i') }}</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small">Statut</label>
                                    <div>
                                        <span class="badge bg-{{ $ticket->appointment->status === 'completed' ? 'success' : 'warning' }}">
                                            {{ ucfirst($ticket->appointment->status) }}
                                        </span>
                                    </div>
                                </div>
                                @if($ticket->appointment->reason)
                                <div class="col-12">
                                    <label class="text-muted small">Raison de consultation</label>
                                    <div>{{ $ticket->appointment->reason }}</div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Boutons d'action -->
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.tickets') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Retour à la liste
                        </a>
                        <div>
                            <button class="btn btn-outline-primary" onclick="printInvoice()">
                                <i class="fas fa-print me-2"></i>Imprimer la facture
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card de validation de paiement -->
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header bg-{{ $ticket->is_paid ? 'success' : 'danger' }} text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-money-bill-wave me-2"></i>
                        Validation du paiement
                    </h5>
                </div>
                <div class="card-body">
                    @if($ticket->is_paid)
                        <div class="text-center py-4">
                            <i class="fas fa-check-circle text-success fa-4x mb-3"></i>
                            <h5 class="text-success">Paiement validé</h5>
                            <p class="text-muted">
                                Ce ticket a été marqué comme payé le {{ $ticket->updated_at->format('d/m/Y à H:i') }}
                            </p>
                        </div>
                        
                        <div class="border-top pt-3">
                            <h6 class="mb-3">Informations de paiement</h6>
                            <div class="mb-2">
                                <label class="text-muted small">Date de paiement</label>
                                <div class="fw-bold">{{ $ticket->updated_at->format('d/m/Y à H:i') }}</div>
                            </div>
                            <div class="mb-2">
                                <label class="text-muted small">Montant payé</label>
                                <div class="fw-bold text-success">
                                    {{ number_format($ticket->appointment->service->price ?? 0, 0, ',', ' ') }} FCFA
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Ce ticket est en attente de paiement
                        </div>

                        <div class="mb-4">
                            <h6 class="mb-3">Montant à percevoir</h6>
                            <div class="text-center p-3 bg-light rounded mb-3">
                                <h3 class="mb-0 text-danger">
                                    {{ number_format($ticket->appointment->service->price ?? 0, 0, ',', ' ') }} FCFA
                                </h3>
                            </div>

                            <div class="mb-3">
                                <label for="payment_method" class="form-label">Méthode de paiement</label>
                                <select class="form-select" id="payment_method">
                                    <option value="cash">Espèces</option>
                                    <option value="card">Carte bancaire</option>
                                    <option value="mobile">Mobile Money</option>
                                    <option value="check">Chèque</option>
                                    <option value="transfer">Virement</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="payment_reference" class="form-label">Référence de paiement</label>
                                <input type="text" class="form-control" id="payment_reference" 
                                       placeholder="Numéro de transaction (optionnel)">
                            </div>

                            <div class="mb-3">
                                <label for="payment_notes" class="form-label">Notes</label>
                                <textarea class="form-control" id="payment_notes" rows="2" 
                                          placeholder="Remarques sur le paiement..."></textarea>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button class="btn btn-success btn-lg" onclick="validatePayment({{ $ticket->id }})">
                                <i class="fas fa-check-circle me-2"></i>Valider le paiement
                            </button>
                        </div>

                        <div class="text-center mt-3">
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Le paiement sera enregistré immédiatement
                            </small>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Historique -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-history me-2"></i>
                        Historique
                    </h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <div class="small text-muted">{{ $ticket->created_at->format('d/m/Y H:i') }}</div>
                                <div class="fw-bold">Ticket créé</div>
                                <div class="small">Ticket #{{ $ticket->id }} généré</div>
                            </div>
                        </div>

                        @if($ticket->appointment)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-info"></div>
                            <div class="timeline-content">
                                <div class="small text-muted">{{ \Carbon\Carbon::parse($ticket->appointment->created_at)->format('d/m/Y H:i') }}</div>
                                <div class="fw-bold">Rendez-vous créé</div>
                                <div class="small">{{ $ticket->appointment->service->name ?? 'Service' }}</div>
                            </div>
                        </div>
                        @endif

                        @if($ticket->is_paid)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <div class="small text-muted">{{ $ticket->updated_at->format('d/m/Y H:i') }}</div>
                                <div class="fw-bold">Paiement validé</div>
                                <div class="small text-success">
                                    {{ number_format($ticket->appointment->service->price ?? 0, 0, ',', ' ') }} FCFA
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions et statistiques -->
        <div class="col-lg-4">
            <!-- Actions rapides -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-bolt me-2"></i>
                        Actions rapides
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button class="btn btn-outline-primary" onclick="printReceipt()">
                            <i class="fas fa-receipt me-2"></i>Imprimer le reçu
                        </button>
                        <button class="btn btn-outline-info" onclick="sendEmailReceipt()">
                            <i class="fas fa-envelope me-2"></i>Envoyer par email
                        </button>
                        @if($ticket->appointment)
                        <a href="#" class="btn btn-outline-secondary">
                            <i class="fas fa-calendar me-2"></i>Voir le rendez-vous
                        </a>
                        @endif
                        @if($ticket->user)
                        <a href="{{ route('admin.patients.show', $ticket->user->id) }}" class="btn btn-outline-secondary">
                            <i class="fas fa-user me-2"></i>Voir le patient
                        </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Informations complémentaires -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Informations
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-muted small">ID du ticket</label>
                        <div class="fw-bold">#{{ $ticket->id }}</div>
                    </div>
                    @if($ticket->appointment)
                    <div class="mb-3">
                        <label class="text-muted small">ID du rendez-vous</label>
                        <div class="fw-bold">#{{ $ticket->appointment->id }}</div>
                    </div>
                    @endif
                    <div class="mb-3">
                        <label class="text-muted small">Créé le</label>
                        <div>{{ $ticket->created_at->format('d/m/Y à H:i') }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small">Dernière mise à jour</label>
                        <div>{{ $ticket->updated_at->format('d/m/Y à H:i') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function validatePayment(ticketId) {
    const paymentMethod = document.getElementById('payment_method').value;
    const paymentReference = document.getElementById('payment_reference').value;
    const paymentNotes = document.getElementById('payment_notes').value;
    
    if (confirm('Confirmer le paiement de ce ticket ?\n\nMéthode: ' + paymentMethod)) {
        // Créer un formulaire pour soumettre la validation
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/tickets/${ticketId}`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        form.appendChild(csrfToken);
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'PUT';
        form.appendChild(methodField);
        
        const isPaidField = document.createElement('input');
        isPaidField.type = 'hidden';
        isPaidField.name = 'is_paid';
        isPaidField.value = '1';
        form.appendChild(isPaidField);
        
        document.body.appendChild(form);
        form.submit();
    }
}

function printReceipt() {
    window.print();
}

function printInvoice() {
    window.print();
}

function sendEmailReceipt() {
    alert('Envoi du reçu par email...\n\nFonctionnalité en cours de développement.');
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
    padding: 12px;
    border-radius: 8px;
}

@media print {
    .btn, .card-header, nav, .sidebar {
        display: none !important;
    }
}
</style>
@endsection

