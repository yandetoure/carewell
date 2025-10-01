@extends('layouts.admin')

@section('title', 'Tickets - Comptabilité')
@section('page-title', 'Gestion des Tickets')
@section('page-subtitle', 'Suivi des paiements et facturation')
@section('user-role', 'Comptable')

@section('content')
<div class="container-fluid py-4">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Statistiques des tickets -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-primary">
                            <i class="fas fa-ticket-alt text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $totalTickets }}</h4>
                            <p class="text-muted mb-0">Total tickets</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
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

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-danger">
                            <i class="fas fa-exclamation-circle text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $unpaidTickets }}</h4>
                            <p class="text-muted mb-0">Non payés</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-info">
                            <i class="fas fa-money-bill-wave text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ number_format($totalRevenue, 0, ',', ' ') }} FCFA</h4>
                            <p class="text-muted mb-0">Revenus collectés</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres et actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-end">
                        <div class="col-md-3">
                            <label for="filterStatus" class="form-label">Statut</label>
                            <select class="form-select" id="filterStatus" onchange="filterTickets()">
                                <option value="all">Tous les tickets</option>
                                <option value="paid">Payés</option>
                                <option value="unpaid">Non payés</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="filterDate" class="form-label">Date</label>
                            <input type="date" class="form-control" id="filterDate" value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="searchTicket" class="form-label">Rechercher</label>
                            <input type="text" class="form-control" id="searchTicket" placeholder="Nom du patient...">
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-primary w-100" onclick="exportTickets()">
                                <i class="fas fa-download me-2"></i>Exporter
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des tickets -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2"></i>
                        Liste des tickets
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Patient</th>
                                    <th>Service/Type</th>
                                    <th>Médecin</th>
                                    <th>Date</th>
                                    <th>Montant</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tickets as $ticket)
                                <tr>
                                    <td><strong>#{{ $ticket->id }}</strong></td>
                                    <td>
                                        @if($ticket->user)
                                            <div class="d-flex align-items-center">
                                                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-2"
                                                     style="width: 32px; height: 32px;">
                                                    <i class="fas fa-user text-white" style="font-size: 0.8em;"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-bold">{{ $ticket->user->first_name }} {{ $ticket->user->last_name }}</div>
                                                    <small class="text-muted">{{ $ticket->user->email }}</small>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($ticket->appointment)
                                            <span class="badge bg-primary">Rendez-vous</span><br>
                                            <small class="text-muted">{{ $ticket->appointment->service->name ?? 'N/A' }}</small>
                                        @elseif($ticket->prescription)
                                            <span class="badge bg-info">Prescription</span>
                                        @elseif($ticket->exam)
                                            <span class="badge bg-warning">Examen</span>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($ticket->doctor)
                                            Dr. {{ $ticket->doctor->first_name }} {{ $ticket->doctor->last_name }}
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div>{{ $ticket->created_at->format('d/m/Y') }}</div>
                                        <small class="text-muted">{{ $ticket->created_at->format('H:i') }}</small>
                                    </td>
                                    <td>
                                        @if($ticket->appointment && $ticket->appointment->service)
                                            <strong>{{ number_format($ticket->appointment->service->price ?? 0, 0, ',', ' ') }} FCFA</strong>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($ticket->is_paid)
                                            <span class="badge bg-success">
                                                <i class="fas fa-check-circle me-1"></i>Payé
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                <i class="fas fa-times-circle me-1"></i>Non payé
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            @if(!$ticket->is_paid)
                                                <button class="btn btn-success" onclick="markAsPaid({{ $ticket->id }})" title="Marquer comme payé">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            @endif
                                            <button class="btn btn-primary" onclick="printTicket({{ $ticket->id }})" title="Imprimer">
                                                <i class="fas fa-print"></i>
                                            </button>
                                            <a href="{{ route('tickets.show', $ticket->id) }}" class="btn btn-info" title="Détails">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">Aucun ticket trouvé</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $tickets->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function markAsPaid(ticketId) {
    if (confirm('Marquer ce ticket comme payé ?')) {
        // Créer un formulaire pour soumettre la mise à jour
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

function printTicket(ticketId) {
    window.open(`/tickets/${ticketId}/print`, '_blank');
}


function filterTickets() {
    const status = document.getElementById('filterStatus').value;
    const date = document.getElementById('filterDate').value;
    
    let url = new URL(window.location.href);
    if (status !== 'all') {
        url.searchParams.set('status', status);
    } else {
        url.searchParams.delete('status');
    }
    
    if (date) {
        url.searchParams.set('date', date);
    }
    
    window.location.href = url.toString();
}

function exportTickets() {
    alert('Export des tickets en cours de développement');
}

// Recherche en temps réel
document.getElementById('searchTicket').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    const rows = document.querySelectorAll('tbody tr');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? '' : 'none';
    });
});
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
</style>
@endsection

