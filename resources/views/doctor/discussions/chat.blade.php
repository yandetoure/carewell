@extends('layouts.doctor')

@section('title', 'Discussion avec Dr. ' . $otherDoctor->first_name . ' ' . $otherDoctor->last_name . ' - Docteur')
@section('page-title', 'Discussion avec un médecin')
@section('page-subtitle', 'Dr. ' . $otherDoctor->first_name . ' ' . $otherDoctor->last_name)
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
                            <div class="doctor-avatar me-3">
                                @if($otherDoctor->photo)
                                    <img src="{{ asset('storage/' . $otherDoctor->photo) }}" 
                                         alt="Photo" 
                                         class="rounded-circle" 
                                         width="50" 
                                         height="50">
                                @else
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                         style="width: 50px; height: 50px;">
                                        <i class="fas fa-user-md"></i>
                                    </div>
                                @endif
                            </div>
                            <div>
                                <h5 class="mb-1">
                                    Dr. {{ $otherDoctor->first_name }} {{ $otherDoctor->last_name }}
                                </h5>
                                <small class="text-muted">
                                    <i class="fas fa-stethoscope me-1"></i>
                                    {{ $otherDoctor->service->name ?? 'Médecin' }}
                                </small>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('doctor.discussions') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Retour aux discussions
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Zone de conversation -->
    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card chat-container">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="fas fa-comments me-2"></i>Conversation
                        <span class="badge bg-primary ms-2">{{ $messages->count() }} messages</span>
                    </h6>
                </div>
                <div class="card-body chat-messages" id="chatMessages" style="max-height: 500px; overflow-y: auto;">
                    @if($messages->count() > 0)
                        @foreach($messages as $message)
                            <div class="message-item {{ $message->sender_id === $currentDoctor->id ? 'message-sent' : 'message-received' }}">
                                <div class="message-content">
                                    <div class="message-header">
                                        <div class="d-flex align-items-center">
                                            @if($message->sender_id === $currentDoctor->id)
                                                <i class="fas fa-user-md text-primary me-2"></i>
                                                <strong>Vous</strong>
                                            @else
                                                <i class="fas fa-user-md text-success me-2"></i>
                                                <strong>Dr. {{ $message->sender->first_name }} {{ $message->sender->last_name }}</strong>
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
                                    @if($message->is_read && $message->sender_id === $currentDoctor->id)
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
                    <form id="messageForm" action="{{ route('doctor.messages.send') }}" method="POST">
                        @csrf
                        <input type="hidden" name="receiver_id" value="{{ $otherDoctor->id }}">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-2">
                                    <input type="text" 
                                           class="form-control form-control-sm" 
                                           name="subject" 
                                           placeholder="Sujet (optionnel)">
                                </div>
                                <textarea class="form-control" 
                                          name="message" 
                                          rows="3" 
                                          placeholder="Tapez votre message..." 
                                          required></textarea>
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
</div>
@endsection

@push('styles')
<style>
.chat-container {
    border: none;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
}

.chat-messages {
    background-color: #f8f9fa;
    padding: 1.5rem;
}

.message-item {
    margin-bottom: 1.5rem;
}

.message-sent {
    text-align: right;
}

.message-received {
    text-align: left;
}

.message-content {
    display: inline-block;
    max-width: 70%;
    padding: 0.75rem 1rem;
    border-radius: 0.5rem;
    background-color: #fff;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.message-sent .message-content {
    background-color: #0d6efd;
    color: #fff;
}

.message-received .message-content {
    background-color: #fff;
    color: #333;
}

.message-header {
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
}

.message-subject {
    margin-top: 0.25rem;
    font-size: 0.8rem;
}

.message-text {
    word-wrap: break-word;
}

.message-status {
    margin-top: 0.5rem;
    font-size: 0.75rem;
}

.doctor-avatar {
    position: relative;
}
</style>
@endpush

@push('scripts')
<script>
// Scroll automatique vers le bas
document.addEventListener('DOMContentLoaded', function() {
    const chatMessages = document.getElementById('chatMessages');
    if (chatMessages) {
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
});

// Soumission du formulaire
document.getElementById('messageForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const form = this;
    const formData = new FormData(form);
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                  document.querySelector('input[name="_token"]')?.value;
    
    // Validation
    const message = formData.get('message');
    if (!message || message.trim() === '') {
        alert('Veuillez saisir un message');
        return;
    }
    
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Envoi...';
    
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': token || '',
            'Accept': 'application/json',
        }
    })
    .then(response => {
        return response.json().then(data => {
            if (!response.ok) {
                // Gérer les erreurs de validation
                if (data.errors) {
                    const errorMessages = Object.values(data.errors).flat().join('\n');
                    throw new Error(errorMessages);
                }
                throw new Error(data.message || 'Erreur lors de l\'envoi du message');
            }
            return data;
        });
    })
    .then(data => {
        if (data.status === true || data.success === true) {
            // Recharger la page pour afficher le nouveau message
            window.location.reload();
        } else {
            alert(data.message || 'Erreur lors de l\'envoi du message');
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert(error.message || 'Erreur lors de l\'envoi du message. Veuillez réessayer.');
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
});
</script>
@endpush


