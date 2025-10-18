@extends('layouts.doctor')

@section('title', 'Messages - Docteur')
@section('page-title', 'Messages')
@section('page-subtitle', 'Communication avec les patients du service')
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

    <!-- Statistiques des messages -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-primary">
                            <i class="fas fa-envelope text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $messages->count() }}</h4>
                            <p class="text-muted mb-0">Total messages</p>
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
                            <h4 class="mb-1">{{ $messages->where('is_read', true)->count() }}</h4>
                            <p class="text-muted mb-0">Messages lus</p>
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
                            <i class="fas fa-exclamation-circle text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $messages->where('is_read', false)->count() }}</h4>
                            <p class="text-muted mb-0">Non lus</p>
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
                            <h4 class="mb-1">{{ $messages->where('created_at', '>=', now()->subDays(7))->count() }}</h4>
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
                            <i class="fas fa-envelope me-2"></i>Messages du service
                        </h5>
                        <div class="d-flex gap-2">
                            <a href="{{ route('doctor.patients') }}" class="btn btn-outline-primary">
                                <i class="fas fa-users me-2"></i>Mes patients
                            </a>
                            <a href="{{ route('doctor.notifications') }}" class="btn btn-outline-success">
                                <i class="fas fa-bell me-2"></i>Notifications
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des messages -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if($messages->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Expéditeur</th>
                                        <th>Destinataire</th>
                                        <th>Sujet</th>
                                        <th>Message</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($messages as $message)
                                        <tr class="{{ $message->is_read ? '' : 'table-warning' }}">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-calendar text-primary me-2"></i>
                                                    {{ \Carbon\Carbon::parse($message->created_at)->format('d/m/Y H:i') }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-user text-success me-2"></i>
                                                    <div>
                                                        <div class="fw-bold">{{ $message->sender->first_name ?? 'N/A' }} {{ $message->sender->last_name ?? 'N/A' }}</div>
                                                        <small class="text-muted">{{ $message->sender->email ?? 'Email non renseigné' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-user-md text-primary me-2"></i>
                                                    <div>
                                                        <div class="fw-bold">{{ $message->recipient->first_name ?? 'N/A' }} {{ $message->recipient->last_name ?? 'N/A' }}</div>
                                                        <small class="text-muted">{{ $message->recipient->email ?? 'Email non renseigné' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-tag text-warning me-2"></i>
                                                    {{ $message->subject ?? 'Sujet non spécifié' }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-envelope text-info me-2"></i>
                                                    {{ Str::limit($message->message ?? 'Message non disponible', 50) }}
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $message->is_read ? 'success' : 'warning' }}">
                                                    {{ $message->is_read ? 'Lu' : 'Non lu' }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <button type="button" class="btn btn-outline-primary" 
                                                            onclick="showMessageDetails({{ $message->id }}, '{{ $message->subject ?? 'Sujet non spécifié' }}', '{{ $message->message ?? 'Message non disponible' }}', '{{ $message->sender->first_name ?? 'N/A' }} {{ $message->sender->last_name ?? 'N/A' }}', '{{ \Carbon\Carbon::parse($message->created_at)->format('d/m/Y H:i') }}')" 
                                                            title="Voir le message">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    @if(!$message->is_read)
                                                        <button type="button" class="btn btn-outline-success" 
                                                                onclick="markAsRead({{ $message->id }})" 
                                                                title="Marquer comme lu">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    @endif
                                                    <a href="{{ route('doctor.messages.create', $message->sender) }}" 
                                                       class="btn btn-outline-info" 
                                                       title="Répondre">
                                                        <i class="fas fa-reply"></i>
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
                            <i class="fas fa-envelope fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">Aucun message</h5>
                            <p class="text-muted">Aucun message n'a été trouvé pour ce service.</p>
                            <a href="{{ route('doctor.patients') }}" class="btn btn-primary">
                                <i class="fas fa-users me-2"></i>Voir mes patients
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
                        <i class="fas fa-chart-line me-2"></i>Résumé des messages
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary">📊 Statistiques</h6>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-envelope text-primary me-2"></i><strong>Total messages:</strong> {{ $messages->count() }}</li>
                                <li><i class="fas fa-check-circle text-success me-2"></i><strong>Messages lus:</strong> {{ $messages->where('is_read', true)->count() }}</li>
                                <li><i class="fas fa-exclamation-circle text-warning me-2"></i><strong>Non lus:</strong> {{ $messages->where('is_read', false)->count() }}</li>
                                <li><i class="fas fa-calendar-check text-info me-2"></i><strong>Cette semaine:</strong> {{ $messages->where('created_at', '>=', now()->subDays(7))->count() }}</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-success">💡 Bonnes pratiques</h6>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-lightbulb text-warning me-2"></i>Répondez rapidement aux messages des patients</li>
                                <li><i class="fas fa-file-medical text-info me-2"></i>Utilisez un langage clair et professionnel</li>
                                <li><i class="fas fa-clock text-primary me-2"></i>Marquez les messages comme lus</li>
                                <li><i class="fas fa-notes-medical text-success me-2"></i>Conservez une trace des communications importantes</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour afficher les détails du message -->
<div class="modal fade" id="messageDetailsModal" tabindex="-1" aria-labelledby="messageDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="messageDetailsModalLabel">
                    <i class="fas fa-envelope me-2"></i>Détails du message
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="messageDetailsContent">
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

.table-warning {
    background-color: rgba(255, 193, 7, 0.1);
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
function showMessageDetails(id, subject, message, sender, date) {
    document.getElementById('messageDetailsContent').innerHTML = `
        <div class="card">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <h6 class="text-primary">Expéditeur :</h6>
                        <p class="mb-0">${sender}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-primary">Date :</h6>
                        <p class="mb-0">${date}</p>
                    </div>
                </div>
                <div class="mb-3">
                    <h6 class="text-primary">Sujet :</h6>
                    <p class="mb-0">${subject}</p>
                </div>
                <div>
                    <h6 class="text-primary">Message :</h6>
                    <div class="message-content" style="white-space: pre-wrap; line-height: 1.6; background-color: #f8f9fa; padding: 15px; border-radius: 5px;">
                        ${message}
                    </div>
                </div>
            </div>
        </div>
    `;
    
    const modal = new bootstrap.Modal(document.getElementById('messageDetailsModal'));
    modal.show();
}

function markAsRead(messageId) {
    // Ici vous pouvez ajouter une requête AJAX pour marquer le message comme lu
    // Pour l'instant, on recharge la page
    if (confirm('Marquer ce message comme lu ?')) {
        location.reload();
    }
}
</script>
@endpush
