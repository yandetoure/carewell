@extends('layouts.doctor')

@section('title', 'Conversation avec ' . $user->first_name . ' ' . $user->last_name . ' - Docteur')
@section('page-title', 'Conversation')
@section('page-subtitle', 'Discussion avec ' . $user->first_name . ' ' . $user->last_name)
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

    <!-- En-tête de la conversation -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="patient-avatar me-3">
                                @if($user->photo)
                                    <img src="{{ asset('storage/' . $user->photo) }}" alt="Photo" class="rounded-circle" width="50" height="50">
                                @else
                                    <i class="fas fa-user-circle fa-3x text-primary"></i>
                                @endif
                            </div>
                            <div>
                                <h5 class="mb-1">{{ $user->first_name }} {{ $user->last_name }}</h5>
                                <small class="text-muted">
                                    @if($user->identification_number)
                                        {{ $user->identification_number }}
                                    @else
                                        Patient
                                    @endif
                                </small>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('doctor.messages') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Retour aux messages
                            </a>
                            <a href="{{ route('doctor.messages.create', $user->id) }}" class="btn btn-outline-primary">
                                <i class="fas fa-plus me-2"></i>Nouveau message
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Zone de conversation -->
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card chat-container">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="fas fa-comments me-2"></i>Conversation
                        <span class="badge bg-primary ms-2">{{ $messages->count() }} messages</span>
                    </h6>
                </div>
                <div class="card-body chat-messages" id="chatMessages">
                    @if($messages->count() > 0)
                        @foreach($messages as $message)
                            <div class="message-item {{ $message->sender_id === $doctor->id ? 'message-sent' : 'message-received' }}">
                                <div class="message-content">
                                    <div class="message-header">
                                        <div class="d-flex align-items-center">
                                            @if($message->sender_id === $doctor->id)
                                                <i class="fas fa-user-md text-primary me-2"></i>
                                                <strong>Vous</strong>
                                            @else
                                                <i class="fas fa-user text-success me-2"></i>
                                                <strong>{{ $message->sender->first_name }} {{ $message->sender->last_name }}</strong>
                                            @endif
                                            <small class="text-muted ms-auto">
                                                {{ \Carbon\Carbon::parse($message->created_at)->format('d/m/Y H:i') }}
                                            </small>
                                        </div>
                                        @if($message->subject)
                                            <div class="message-subject">
                                                <i class="fas fa-tag text-warning me-1"></i>
                                                <em>{{ $message->subject }}</em>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="message-text">
                                        {{ $message->message }}
                                    </div>
                                    @if($message->is_read && $message->sender_id === $doctor->id)
                                        <div class="message-status">
                                            <i class="fas fa-check-circle text-success"></i>
                                            <small>Lu</small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                            <h6 class="text-muted">Aucun message</h6>
                            <p class="text-muted">Commencez la conversation en envoyant un message.</p>
                        </div>
                    @endif
                </div>
                
                <!-- Zone de saisie -->
                <div class="card-footer bg-light">
                    <form id="quickMessageForm">
                        @csrf
                        <input type="hidden" name="receiver_id" value="{{ $user->id }}">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-2">
                                    <input type="text" class="form-control form-control-sm" name="subject" 
                                           placeholder="Sujet (optionnel)">
                                </div>
                                <textarea class="form-control" name="message" rows="3" 
                                          placeholder="Tapez votre message..." required></textarea>
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-paper-plane me-2"></i>Envoyer
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions rapides -->
    <div class="row mt-4">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-bolt me-2"></i>Actions rapides
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <button type="button" class="btn btn-outline-primary w-100" onclick="sendQuickMessage('Bonjour, comment allez-vous ?')">
                                <i class="fas fa-hand-wave me-2"></i>Salutation
                            </button>
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-outline-success w-100" onclick="sendQuickMessage('Vos résultats d\'examens sont disponibles.')">
                                <i class="fas fa-clipboard-check me-2"></i>Résultats
                            </button>
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-outline-info w-100" onclick="sendQuickMessage('N\'oubliez pas votre prochain rendez-vous.')">
                                <i class="fas fa-calendar-check me-2"></i>Rappel RDV
                            </button>
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-outline-warning w-100" onclick="sendQuickMessage('Avez-vous des questions concernant votre traitement ?')">
                                <i class="fas fa-question-circle me-2"></i>Questions
                            </button>
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
.chat-container {
    height: 600px;
    display: flex;
    flex-direction: column;
}

.chat-messages {
    flex: 1;
    overflow-y: auto;
    max-height: 400px;
    padding: 1rem;
    background-color: #f8f9fa;
}

.message-item {
    margin-bottom: 1rem;
    display: flex;
}

.message-sent {
    justify-content: flex-end;
}

.message-received {
    justify-content: flex-start;
}

.message-content {
    max-width: 70%;
    padding: 0.75rem 1rem;
    border-radius: 15px;
    position: relative;
}

.message-sent .message-content {
    background-color: #007bff;
    color: white;
    border-bottom-right-radius: 5px;
}

.message-received .message-content {
    background-color: white;
    color: #333;
    border: 1px solid #e9ecef;
    border-bottom-left-radius: 5px;
}

.message-header {
    margin-bottom: 0.5rem;
    font-size: 0.85rem;
}

.message-subject {
    font-size: 0.8rem;
    margin-bottom: 0.5rem;
    opacity: 0.8;
}

.message-text {
    line-height: 1.4;
    word-wrap: break-word;
}

.message-status {
    text-align: right;
    margin-top: 0.5rem;
    font-size: 0.75rem;
    opacity: 0.8;
}

.patient-avatar {
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.card-header h6 {
    color: #495057;
}

.btn-outline-primary:hover,
.btn-outline-success:hover,
.btn-outline-info:hover,
.btn-outline-warning:hover {
    transform: translateY(-1px);
    transition: transform 0.2s ease;
}

/* Scrollbar personnalisée */
.chat-messages::-webkit-scrollbar {
    width: 6px;
}

.chat-messages::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.chat-messages::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

.chat-messages::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('quickMessageForm');
    const chatMessages = document.getElementById('chatMessages');
    
    // Auto-scroll vers le bas
    chatMessages.scrollTop = chatMessages.scrollHeight;

    // Soumission du formulaire
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // Validation
        const message = formData.get('message');
        if (!message.trim()) {
            alert('Veuillez saisir un message');
            return;
        }

        // Envoi du message
        fetch('{{ route("doctor.messages.send") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                receiver_id: formData.get('receiver_id'),
                message: message,
                subject: formData.get('subject') || null
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status) {
                // Recharger la page pour afficher le nouveau message
                location.reload();
            } else {
                alert('Erreur lors de l\'envoi: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erreur lors de l\'envoi du message');
        });
    });
});

function sendQuickMessage(messageText) {
    const messageTextarea = document.querySelector('textarea[name="message"]');
    messageTextarea.value = messageText;
    messageTextarea.focus();
}

// Auto-refresh toutes les 30 secondes pour les nouveaux messages
setInterval(function() {
    // Optionnel: ajouter un système de polling pour les nouveaux messages
    // fetch('/doctor/messages/chat/{{ $user->id }}/check-new')
    //     .then(response => response.json())
    //     .then(data => {
    //         if (data.hasNewMessages) {
    //             location.reload();
    //         }
    //     });
}, 30000);
</script>
@endpush
