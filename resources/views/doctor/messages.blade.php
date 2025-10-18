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
                            <h4 class="mb-1">{{ $totalMessages }}</h4>
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
                            <h4 class="mb-1">{{ $totalMessages - $unreadMessages }}</h4>
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
                            <h4 class="mb-1">{{ $unreadMessages }}</h4>
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
                            <h4 class="mb-1">{{ $todayMessages }}</h4>
                            <p class="text-muted mb-0">Aujourd'hui</p>
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
                            <a href="{{ route('doctor.messages.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Nouvelle conversation
                            </a>
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

    <!-- Liste des discussions -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if(count($result) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Patient</th>
                                        <th>Dernier message</th>
                                        <th>Heure</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($result as $discussion)
                                        <tr class="{{ $discussion['unread_count'] > 0 ? 'table-warning' : '' }}">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="patient-avatar me-3">
                                                        @if($discussion['user_photo'])
                                                            <img src="{{ $discussion['user_photo'] }}" alt="Photo" class="rounded-circle" width="40" height="40">
                                                        @else
                                                            <i class="fas fa-user-circle fa-2x text-primary"></i>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <div class="fw-bold">{{ $discussion['user_first_name'] }} {{ $discussion['user_last_name'] }}</div>
                                                        <small class="text-muted">Patient</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-envelope text-info me-2"></i>
                                                    {{ Str::limit($discussion['last_message'], 50) }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-clock text-secondary me-2"></i>
                                                    {{ $discussion['last_message_time'] }}
                                                </div>
                                            </td>
                                            <td>
                                                @if($discussion['unread_count'] > 0)
                                                    <span class="badge bg-warning">
                                                        {{ $discussion['unread_count'] }} non lu(s)
                                                    </span>
                                                @else
                                                    <span class="badge bg-success">Tous lus</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <button type="button" class="btn btn-outline-primary" 
                                                            onclick="openChat({{ $discussion['user_id'] }}, '{{ $discussion['user_first_name'] }} {{ $discussion['user_last_name'] }}')" 
                                                            title="Ouvrir la conversation">
                                                        <i class="fas fa-comments"></i>
                                                    </button>
                                                    <a href="{{ route('doctor.messages.create', $discussion['user_id']) }}" 
                                                       class="btn btn-outline-success" 
                                                       title="Nouveau message">
                                                        <i class="fas fa-plus"></i>
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
                            <h5 class="text-muted">Aucune conversation</h5>
                            <p class="text-muted">Aucune conversation n'a été trouvée avec les patients de votre service.</p>
                            <div class="d-flex gap-2 justify-content-center">
                                <a href="{{ route('doctor.messages.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>Nouvelle conversation
                                </a>
                                <a href="{{ route('doctor.patients') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-users me-2"></i>Voir mes patients
                                </a>
                            </div>
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
                                <li><i class="fas fa-envelope text-primary me-2"></i><strong>Total messages:</strong> {{ $totalMessages }}</li>
                                <li><i class="fas fa-check-circle text-success me-2"></i><strong>Messages lus:</strong> {{ $totalMessages - $unreadMessages }}</li>
                                <li><i class="fas fa-exclamation-circle text-warning me-2"></i><strong>Non lus:</strong> {{ $unreadMessages }}</li>
                                <li><i class="fas fa-calendar-check text-info me-2"></i><strong>Aujourd'hui:</strong> {{ $todayMessages }}</li>
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

.patient-avatar {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>
@endpush

@push('scripts')
<script>
function openChat(userId, userName) {
    // Rediriger vers une page de chat avec l'utilisateur spécifique
    window.location.href = `/doctor/messages/chat/${userId}`;
}

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
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    fetch(`/doctor/messages/${messageId}/mark-read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.status) {
            location.reload();
        } else {
            alert('Erreur lors de la mise à jour du statut');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Erreur lors de la mise à jour du statut');
    });
}
</script>
@endpush
