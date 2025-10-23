@extends('layouts.secretary')

@section('title', 'Facturation - Secrétariat')
@section('page-title', 'Facturation')
@section('page-subtitle', 'Gérer les tickets et le statut de paiement')
@section('user-role', 'Secrétaire')

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
            <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Statistiques de facturation -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-primary">
                            <i class="fas fa-receipt text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $totalTickets }}</h4>
                            <p class="text-muted mb-0">Total tickets</p>
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
                            <h4 class="mb-1">{{ $paidTickets }}</h4>
                            <p class="text-muted mb-0">Payés</p>
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
                            <h4 class="mb-1">{{ $unpaidTickets }}</h4>
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
                            <i class="fas fa-money-bill text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ number_format($totalRevenue, 0) }} FCFA</h4>
                            <p class="text-muted mb-0">Chiffre d'affaires</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres et recherche -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('secretary.billing') }}" class="row g-3">
                        <div class="col-md-3">
                            <input type="text" name="search" class="form-control" placeholder="Rechercher par patient..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <select name="status" class="form-select">
                                <option value="">Tous les statuts</option>
                                <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Payés</option>
                                <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>En attente</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="date_from" class="form-control" placeholder="Date début" value="{{ request('date_from') }}">
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="date_to" class="form-control" placeholder="Date fin" value="{{ request('date_to') }}">
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="fas fa-search me-1"></i>Filtrer
                            </button>
                            <a href="{{ route('secretary.billing') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-1"></i>Effacer
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-receipt me-2"></i>
                        Tickets de Facturation
                    </h5>
                    <div class="d-flex gap-2">
                        <button class="btn btn-success" onclick="markSelectedAsPaid()">
                            <i class="fas fa-check me-2"></i>Marquer comme payé
                        </button>
                        <button class="btn btn-warning" onclick="markSelectedAsUnpaid()">
                            <i class="fas fa-clock me-2"></i>Marquer en attente
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Debug: Afficher le nombre de tickets -->
                    <div class="alert alert-info mb-3">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Debug:</strong> {{ $tickets->count() }} ticket(s) trouvé(s) sur cette page
                    </div>
                    
                    @if($tickets->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>
                                            <input type="checkbox" id="selectAll" onchange="toggleSelectAll()">
                                        </th>
                                        <th>Patient</th>
                                        <th>Service</th>
                                        <th>Médecin</th>
                                        <th>Date RDV</th>
                                        <th>Montant</th>
                                        <th>Statut</th>
                                        <th>Date création</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($tickets as $ticket)
                                        <tr>
                                            <td>
                                                <input type="checkbox" class="ticket-checkbox" value="{{ $ticket->id }}">
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($ticket->appointment && $ticket->appointment->user)
                                                        @if($ticket->appointment->user->photo)
                                                            <img src="{{ asset('storage/' . $ticket->appointment->user->photo) }}" 
                                                                 alt="Photo" 
                                                                 class="rounded-circle me-3" 
                                                                 style="width: 40px; height: 40px; object-fit: cover;">
                                                        @else
                                                            <div class="bg-secondary rounded-circle me-3 d-flex align-items-center justify-content-center" 
                                                                 style="width: 40px; height: 40px;">
                                                                <i class="fas fa-user text-white"></i>
                                                            </div>
                                                        @endif
                                                        <div>
                                                            <strong>{{ $ticket->appointment->user->first_name }} {{ $ticket->appointment->user->last_name }}</strong>
                                                            <br><small class="text-muted">{{ $ticket->appointment->user->email }}</small>
                                                        </div>
                                                    @else
                                                        <span class="text-muted">Patient non trouvé</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                @if($ticket->appointment && $ticket->appointment->service)
                                                    <span class="badge bg-primary">{{ $ticket->appointment->service->name }}</span>
                                                @else
                                                    <span class="text-muted">Service non trouvé</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($ticket->doctor)
                                                    {{ $ticket->doctor->first_name }} {{ $ticket->doctor->last_name }}
                                                @else
                                                    <span class="text-muted">Non assigné</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($ticket->appointment)
                                                    <strong>{{ $ticket->appointment->appointment_date->format('d/m/Y') }}</strong>
                                                    <br><small class="text-muted">{{ $ticket->appointment->appointment_time }}</small>
                                                @else
                                                    <span class="text-muted">N/A</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($ticket->appointment && $ticket->appointment->service)
                                                    <strong>{{ number_format($ticket->appointment->service->price, 0) }} FCFA</strong>
                                                @else
                                                    <span class="text-muted">N/A</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($ticket->is_paid)
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-check me-1"></i>Payé
                                                    </span>
                                                @else
                                                    <span class="badge bg-warning">
                                                        <i class="fas fa-clock me-1"></i>En attente
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <small class="text-muted">{{ $ticket->created_at->format('d/m/Y H:i') }}</small>
                                            </td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <button type="button" class="btn btn-outline-primary btn-sm" 
                                                            onclick="viewTicket({{ $ticket->id }})" 
                                                            title="Voir le ticket">
                                                        <i class="fas fa-eye me-1"></i>Voir
                                                    </button>
                                                    @if($ticket->is_paid)
                                                        <button type="button" class="btn btn-outline-warning btn-sm" 
                                                                onclick="markAsUnpaid({{ $ticket->id }})" 
                                                                title="Marquer en attente">
                                                            <i class="fas fa-clock me-1"></i>En attente
                                                        </button>
                                                    @else
                                                        <button type="button" class="btn btn-outline-success btn-sm" 
                                                                onclick="markAsPaid({{ $ticket->id }})" 
                                                                title="Marquer comme payé">
                                                            <i class="fas fa-check me-1"></i>Payé
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if($tickets->hasPages())
                            <div class="d-flex justify-content-center mt-4">
                                {{ $tickets->appends(request()->query())->links('pagination::bootstrap-4') }}
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-receipt fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">Aucun ticket trouvé</h5>
                            <p class="text-muted">Il n'y a aucun ticket de facturation pour le moment.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour voir les détails du ticket -->
<div class="modal fade" id="ticketModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-receipt me-2"></i>Détails du Ticket
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="ticketDetails">
                <!-- Contenu dynamique -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Fermer
                </button>
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

/* Style personnalisé pour la pagination */
.pagination {
    margin-bottom: 0;
}

.pagination .page-link {
    color: #5a5c69;
    background-color: #fff;
    border: 1px solid #dee2e6;
    padding: 0.5rem 0.75rem;
    margin: 0 2px;
    border-radius: 0.375rem;
    transition: all 0.15s ease-in-out;
}

.pagination .page-link:hover {
    color: #fff;
    background-color: #5a5c69;
    border-color: #5a5c69;
}

.pagination .page-item.active .page-link {
    color: #fff;
    background-color: #4e73df;
    border-color: #4e73df;
}

.pagination .page-item.disabled .page-link {
    color: #6c757d;
    background-color: #fff;
    border-color: #dee2e6;
}

.pagination .page-link:focus {
    box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
}
</style>
@endpush

@push('scripts')
<script>
let selectedTicketIds = [];

// Sélectionner/désélectionner tous les tickets
function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.ticket-checkbox');
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
    
    updateSelectedTickets();
}

// Mettre à jour la liste des tickets sélectionnés
function updateSelectedTickets() {
    selectedTicketIds = Array.from(document.querySelectorAll('.ticket-checkbox:checked'))
        .map(checkbox => checkbox.value);
}

// Ajouter un événement à chaque checkbox
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.ticket-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectedTickets);
    });
});

// Voir les détails d'un ticket
function viewTicket(ticketId) {
    document.getElementById('ticketDetails').innerHTML = `
        <div class="text-center py-3">
            <i class="fas fa-spinner fa-spin fa-2x text-primary"></i>
            <p class="mt-2">Chargement des détails du ticket...</p>
        </div>
    `;
    
    const modal = new bootstrap.Modal(document.getElementById('ticketModal'));
    modal.show();
    
    // Appel AJAX pour récupérer les vraies données
    fetch(`/secretary/billing/ticket/${ticketId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const ticket = data.ticket;
                let detailsHtml = '';
                
                // Informations générales du ticket
                detailsHtml += `
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6><i class="fas fa-receipt me-2"></i>Informations du ticket</h6>
                            <div class="mb-3">
                                <strong>ID Ticket:</strong><br>
                                #${ticket.id}
                            </div>
                            <div class="mb-3">
                                <strong>Statut:</strong><br>
                                ${ticket.is_paid ? '<span class="badge bg-success">Payé</span>' : '<span class="badge bg-warning">En attente</span>'}
                            </div>
                            <div class="mb-3">
                                <strong>Date création:</strong><br>
                                ${new Date(ticket.created_at).toLocaleDateString('fr-FR')} à ${new Date(ticket.created_at).toLocaleTimeString('fr-FR')}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6><i class="fas fa-user me-2"></i>Informations du patient</h6>
                            <div class="mb-3">
                                <strong>Nom complet:</strong><br>
                                ${ticket.appointment ? ticket.appointment.user.first_name + ' ' + ticket.appointment.user.last_name : 'N/A'}
                            </div>
                            <div class="mb-3">
                                <strong>Email:</strong><br>
                                ${ticket.appointment ? ticket.appointment.user.email : 'N/A'}
                            </div>
                            <div class="mb-3">
                                <strong>Téléphone:</strong><br>
                                ${ticket.appointment ? (ticket.appointment.user.phone_number || 'Non renseigné') : 'N/A'}
                            </div>
                        </div>
                    </div>
                `;
                
                // Informations du rendez-vous
                if (ticket.appointment) {
                    detailsHtml += `
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h6><i class="fas fa-calendar me-2"></i>Détails du rendez-vous</h6>
                                <div class="mb-3">
                                    <strong>Service:</strong><br>
                                    ${ticket.appointment.service ? ticket.appointment.service.name : 'N/A'}
                                </div>
                                <div class="mb-3">
                                    <strong>Date:</strong><br>
                                    ${ticket.appointment.appointment_date ? new Date(ticket.appointment.appointment_date).toLocaleDateString('fr-FR') : 'N/A'}
                                </div>
                                <div class="mb-3">
                                    <strong>Heure:</strong><br>
                                    ${ticket.appointment.appointment_time || 'N/A'}
                                </div>
                                <div class="mb-3">
                                    <strong>Statut RDV:</strong><br>
                                    <span class="badge bg-${getStatusColor(ticket.appointment.status)}">${ticket.appointment.status}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6><i class="fas fa-user-md me-2"></i>Informations médicales</h6>
                                <div class="mb-3">
                                    <strong>Médecin:</strong><br>
                                    ${ticket.doctor ? ticket.doctor.first_name + ' ' + ticket.doctor.last_name : 'Non assigné'}
                                </div>
                                <div class="mb-3">
                                    <strong>Motif:</strong><br>
                                    ${ticket.appointment.reason || 'Non renseigné'}
                                </div>
                                <div class="mb-3">
                                    <strong>Symptômes:</strong><br>
                                    ${ticket.appointment.symptoms || 'Non renseignés'}
                                </div>
                                <div class="mb-3">
                                    <strong>Urgent:</strong><br>
                                    ${ticket.appointment.is_urgent ? '<span class="badge bg-danger">Oui</span>' : '<span class="badge bg-secondary">Non</span>'}
                                </div>
                            </div>
                        </div>
                    `;
                }
                
                // Informations financières
                detailsHtml += `
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6><i class="fas fa-money-bill me-2"></i>Informations financières</h6>
                            <div class="mb-3">
                                <strong>Montant du service:</strong><br>
                                <span class="h5 text-primary">${ticket.appointment && ticket.appointment.service ? 
                                    new Intl.NumberFormat('fr-FR').format(ticket.appointment.service.price) + ' FCFA' : 'N/A'}</span>
                            </div>
                            <div class="mb-3">
                                <strong>Prix du RDV:</strong><br>
                                ${ticket.appointment && ticket.appointment.price ? 
                                    new Intl.NumberFormat('fr-FR').format(ticket.appointment.price) + ' FCFA' : 'Inclus dans le service'}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6><i class="fas fa-file-medical me-2"></i>Type de prestation</h6>
                `;
                
                // Détails selon le type de prestation
                if (ticket.prescription) {
                    detailsHtml += `
                        <div class="alert alert-info">
                            <h6><i class="fas fa-prescription-bottle-alt me-2"></i>Prescription médicale</h6>
                            <div class="mb-2">
                                <strong>Médicaments prescrits:</strong><br>
                                ${ticket.prescription.medicaments || 'Non renseigné'}
                            </div>
                            <div class="mb-2">
                                <strong>Instructions:</strong><br>
                                ${ticket.prescription.instructions || 'Non renseignées'}
                            </div>
                            <div class="mb-2">
                                <strong>Durée du traitement:</strong><br>
                                ${ticket.prescription.duration || 'Non renseignée'}
                            </div>
                        </div>
                    `;
                } else if (ticket.exam) {
                    detailsHtml += `
                        <div class="alert alert-warning">
                            <h6><i class="fas fa-microscope me-2"></i>Examen médical</h6>
                            <div class="mb-2">
                                <strong>Type d'examen:</strong><br>
                                ${ticket.exam.type || 'Non renseigné'}
                            </div>
                            <div class="mb-2">
                                <strong>Description:</strong><br>
                                ${ticket.exam.description || 'Non renseignée'}
                            </div>
                            <div class="mb-2">
                                <strong>Instructions:</strong><br>
                                ${ticket.exam.instructions || 'Non renseignées'}
                            </div>
                        </div>
                    `;
                } else {
                    detailsHtml += `
                        <div class="alert alert-primary">
                            <h6><i class="fas fa-stethoscope me-2"></i>Consultation médicale</h6>
                            <div class="mb-2">
                                <strong>Type:</strong> Consultation générale
                            </div>
                            <div class="mb-2">
                                <strong>Visite effectuée:</strong><br>
                                ${ticket.appointment && ticket.appointment.is_visited ? 
                                    '<span class="badge bg-success">Oui</span>' : '<span class="badge bg-warning">Non</span>'}
                            </div>
                        </div>
                    `;
                }
                
                detailsHtml += `
                        </div>
                    </div>
                `;
                
                document.getElementById('ticketDetails').innerHTML = detailsHtml;
            } else {
                document.getElementById('ticketDetails').innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Erreur lors du chargement des détails du ticket.
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            document.getElementById('ticketDetails').innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Erreur de connexion. Veuillez réessayer.
                </div>
            `;
        });
}

// Fonction utilitaire pour obtenir la couleur du statut
function getStatusColor(status) {
    switch(status) {
        case 'confirmed': return 'success';
        case 'pending': return 'warning';
        case 'completed': return 'primary';
        case 'cancelled': return 'danger';
        default: return 'secondary';
    }
}

// Marquer un ticket comme payé
function markAsPaid(ticketId) {
    if (confirm('Êtes-vous sûr de vouloir marquer ce ticket comme payé ?')) {
        updateTicketStatus(ticketId, true);
    }
}

// Marquer un ticket comme non payé
function markAsUnpaid(ticketId) {
    if (confirm('Êtes-vous sûr de vouloir marquer ce ticket comme non payé ?')) {
        updateTicketStatus(ticketId, false);
    }
}

// Fonction pour mettre à jour le statut d'un ticket
function updateTicketStatus(ticketId, isPaid) {
    fetch(`/secretary/billing/ticket/${ticketId}/status`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            is_paid: isPaid
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Afficher un message de succès
            showAlert('success', data.message);
            // Recharger la page pour mettre à jour l'affichage
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            showAlert('error', data.message || 'Erreur lors de la mise à jour');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        showAlert('error', 'Erreur de connexion. Veuillez réessayer.');
    });
}

// Marquer les tickets sélectionnés comme payés
function markSelectedAsPaid() {
    if (selectedTicketIds.length === 0) {
        alert('Veuillez sélectionner au moins un ticket.');
        return;
    }
    
    if (confirm(`Êtes-vous sûr de vouloir marquer ${selectedTicketIds.length} ticket(s) comme payé(s) ?`)) {
        updateMultipleTicketStatus(selectedTicketIds, true);
    }
}

// Marquer les tickets sélectionnés comme non payés
function markSelectedAsUnpaid() {
    if (selectedTicketIds.length === 0) {
        alert('Veuillez sélectionner au moins un ticket.');
        return;
    }
    
    if (confirm(`Êtes-vous sûr de vouloir marquer ${selectedTicketIds.length} ticket(s) comme non payé(s) ?`)) {
        updateMultipleTicketStatus(selectedTicketIds, false);
    }
}

// Fonction pour mettre à jour le statut de plusieurs tickets
function updateMultipleTicketStatus(ticketIds, isPaid) {
    fetch('/secretary/billing/tickets/status', {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            ticket_ids: ticketIds,
            is_paid: isPaid
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Afficher un message de succès
            showAlert('success', data.message);
            // Recharger la page pour mettre à jour l'affichage
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            showAlert('error', data.message || 'Erreur lors de la mise à jour');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        showAlert('error', 'Erreur de connexion. Veuillez réessayer.');
    });
}

// Fonction pour afficher des alertes
function showAlert(type, message) {
    const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    const iconClass = type === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-triangle';
    
    const alertHtml = `
        <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
            <i class="${iconClass} me-2"></i>${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    // Insérer l'alerte en haut de la page
    const container = document.querySelector('.container-fluid');
    container.insertAdjacentHTML('afterbegin', alertHtml);
    
    // Supprimer automatiquement l'alerte après 5 secondes
    setTimeout(() => {
        const alert = container.querySelector('.alert');
        if (alert) {
            alert.remove();
        }
    }, 5000);
}
</script>
@endpush