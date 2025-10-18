@extends('layouts.doctor')

@section('title', 'Nouvelle conversation - Docteur')
@section('page-title', 'Nouvelle conversation')
@section('page-subtitle', 'Envoyer un message à un patient')
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

    <!-- Navigation -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-plus me-2"></i>Nouvelle conversation
                        </h5>
                        <div class="d-flex gap-2">
                            <a href="{{ route('doctor.messages') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Retour aux messages
                            </a>
                            <a href="{{ route('doctor.patients') }}" class="btn btn-outline-primary">
                                <i class="fas fa-users me-2"></i>Mes patients
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulaire de nouveau message -->
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-envelope me-2"></i>Composer un nouveau message
                    </h5>
                </div>
                <div class="card-body">
                    <form id="newMessageForm">
                        @csrf
                        
                        <!-- Sélection du destinataire -->
                        <div class="mb-4">
                            <label for="patientSelect" class="form-label">
                                <i class="fas fa-user me-2"></i>Destinataire
                            </label>
                            <select class="form-select" id="patientSelect" name="receiver_id" required>
                                <option value="">Sélectionner un patient</option>
                                @foreach($patients as $patient)
                                    <option value="{{ $patient->id }}" 
                                            {{ $selectedPatient && $selectedPatient->id == $patient->id ? 'selected' : '' }}>
                                        {{ $patient->first_name }} {{ $patient->last_name }} 
                                        @if($patient->identification_number)
                                            ({{ $patient->identification_number }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text">Sélectionnez le patient à qui vous souhaitez envoyer un message</div>
                        </div>

                        <!-- Sujet du message -->
                        <div class="mb-4">
                            <label for="messageSubject" class="form-label">
                                <i class="fas fa-tag me-2"></i>Sujet (optionnel)
                            </label>
                            <input type="text" class="form-control" id="messageSubject" name="subject" 
                                   placeholder="Ex: Suivi de consultation, Résultats d'examens, etc.">
                        </div>

                        <!-- Contenu du message -->
                        <div class="mb-4">
                            <label for="messageContent" class="form-label">
                                <i class="fas fa-edit me-2"></i>Message
                            </label>
                            <textarea class="form-control" id="messageContent" name="message" rows="8" 
                                      placeholder="Tapez votre message ici..." required></textarea>
                            <div class="form-text">
                                <span id="charCount">0</span> / 5000 caractères
                            </div>
                        </div>

                        <!-- Boutons d'action -->
                        <div class="d-flex gap-2 justify-content-end">
                            <a href="{{ route('doctor.messages') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Annuler
                            </a>
                            <button type="submit" class="btn btn-primary" id="sendButton">
                                <i class="fas fa-paper-plane me-2"></i>Envoyer le message
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Conseils pour la communication -->
    <div class="row mt-4">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-lightbulb me-2"></i>Conseils pour une bonne communication
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary">✅ Bonnes pratiques</h6>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-check-circle text-success me-2"></i>Utilisez un langage clair et professionnel</li>
                                <li><i class="fas fa-check-circle text-success me-2"></i>Respectez la confidentialité médicale</li>
                                <li><i class="fas fa-check-circle text-success me-2"></i>Soyez précis dans vos instructions</li>
                                <li><i class="fas fa-check-circle text-success me-2"></i>Évitez les termes techniques complexes</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-warning">⚠️ À éviter</h6>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-exclamation-triangle text-warning me-2"></i>Diagnostics par message</li>
                                <li><i class="fas fa-exclamation-triangle text-warning me-2"></i>Informations sensibles non chiffrées</li>
                                <li><i class="fas fa-exclamation-triangle text-warning me-2"></i>Messages trop longs ou confus</li>
                                <li><i class="fas fa-exclamation-triangle text-warning me-2"></i>Promesses de traitement par message</li>
                            </ul>
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
.form-label {
    font-weight: 600;
    color: #495057;
}

.form-control:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
}

.form-select:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
}

.card-header h5 {
    color: #495057;
}

#charCount {
    font-weight: 600;
    color: #6c757d;
}

.btn-primary {
    background-color: #0d6efd;
    border-color: #0d6efd;
}

.btn-primary:hover {
    background-color: #0b5ed7;
    border-color: #0a58ca;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const messageContent = document.getElementById('messageContent');
    const charCount = document.getElementById('charCount');
    const sendButton = document.getElementById('sendButton');
    const form = document.getElementById('newMessageForm');

    // Compteur de caractères
    messageContent.addEventListener('input', function() {
        const length = this.value.length;
        charCount.textContent = length;
        
        if (length > 5000) {
            charCount.style.color = '#dc3545';
            sendButton.disabled = true;
        } else if (length > 4500) {
            charCount.style.color = '#fd7e14';
            sendButton.disabled = false;
        } else {
            charCount.style.color = '#6c757d';
            sendButton.disabled = false;
        }
    });

    // Soumission du formulaire
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // Validation
        const receiverId = formData.get('receiver_id');
        const message = formData.get('message');
        
        if (!receiverId) {
            alert('Veuillez sélectionner un destinataire');
            return;
        }
        
        if (!message.trim()) {
            alert('Veuillez saisir un message');
            return;
        }
        
        if (message.length > 5000) {
            alert('Le message ne peut pas dépasser 5000 caractères');
            return;
        }

        // Désactiver le bouton d'envoi
        sendButton.disabled = true;
        sendButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Envoi en cours...';

        // Envoi du message
        fetch('/doctor/messages/send', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                receiver_id: receiverId,
                message: message,
                subject: formData.get('subject') || null
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status) {
                alert('Message envoyé avec succès !');
                window.location.href = '{{ route("doctor.messages") }}';
            } else {
                alert('Erreur lors de l\'envoi: ' + data.message);
                sendButton.disabled = false;
                sendButton.innerHTML = '<i class="fas fa-paper-plane me-2"></i>Envoyer le message';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erreur lors de l\'envoi du message');
            sendButton.disabled = false;
            sendButton.innerHTML = '<i class="fas fa-paper-plane me-2"></i>Envoyer le message';
        });
    });
});
</script>
@endpush
