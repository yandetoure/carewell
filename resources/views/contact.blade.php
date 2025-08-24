@extends('layouts.app')

@section('title', 'Contact - CareWell')

@section('content')
<!-- Header Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="section-title">Contactez-nous</h1>
                <p class="section-subtitle">Nous sommes là pour vous aider et répondre à toutes vos questions</p>
            </div>
        </div>
    </div>
</section>

<!-- Contact Information and Form -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <!-- Contact Information -->
            <div class="col-lg-4 mb-5">
                <div class="contact-info">
                    <h3 class="mb-4">Informations de contact</h3>

                    <div class="contact-item mb-4">
                        <div class="d-flex align-items-start">
                            <div class="contact-icon me-3">
                                <i class="fas fa-map-marker-alt fa-2x text-primary"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">Adresse</h6>
                                <p class="mb-0 text-muted">
                                    123 Rue de la Santé<br>
                                    75001 Paris, France
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="contact-item mb-4">
                        <div class="d-flex align-items-start">
                            <div class="contact-icon me-3">
                                <i class="fas fa-phone fa-2x text-success"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">Téléphone</h6>
                                <p class="mb-0 text-muted">
                                    <a href="tel:+33123456789" class="text-decoration-none">+33 1 23 45 67 89</a><br>
                                    <small>Lun-Ven: 8h-20h, Sam: 9h-17h</small>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="contact-item mb-4">
                        <div class="d-flex align-items-start">
                            <div class="contact-icon me-3">
                                <i class="fas fa-envelope fa-2x text-warning"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">Email</h6>
                                <p class="mb-0 text-muted">
                                    <a href="mailto:contact@carewell.fr" class="text-decoration-none">contact@carewell.fr</a><br>
                                    <small>Réponse sous 24h</small>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="contact-item mb-4">
                        <div class="d-flex align-items-start">
                            <div class="contact-icon me-3">
                                <i class="fas fa-clock fa-2x text-info"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">Horaires d'ouverture</h6>
                                <p class="mb-0 text-muted">
                                    <strong>Lundi - Vendredi:</strong> 8h00 - 20h00<br>
                                    <strong>Samedi:</strong> 9h00 - 17h00<br>
                                    <strong>Dimanche:</strong> Fermé
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Emergency Contact -->
                    <div class="alert alert-danger">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-exclamation-triangle fa-2x me-3"></i>
                            <div>
                                <h6 class="mb-1">Urgences médicales</h6>
                                <p class="mb-1">En cas d'urgence, appelez le 15 (SAMU)</p>
                                <small>24h/24 et 7j/7</small>
                            </div>
                        </div>
                    </div>

                    <!-- Social Media -->
                    <div class="social-links mt-4">
                        <h6 class="mb-3">Suivez-nous</h6>
                        <div class="d-flex gap-2">
                            <a href="#" class="btn btn-outline-primary">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#" class="btn btn-outline-info">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" class="btn btn-outline-danger">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="#" class="btn btn-outline-primary">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="col-lg-8">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">
                            <i class="fas fa-envelope me-2"></i>Envoyez-nous un message
                        </h4>
                    </div>

                    <div class="card-body p-4">
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

                        <form method="POST" action="{{ route('contact.send') }}">
                            @csrf

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="first_name" class="form-label">
                                        <i class="fas fa-user me-2"></i>Prénom *
                                    </label>
                                    <input type="text" class="form-control @error('first_name') is-invalid @enderror"
                                           id="first_name" name="first_name" value="{{ old('first_name') }}" required>
                                    @error('first_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="last_name" class="form-label">
                                        <i class="fas fa-user me-2"></i>Nom *
                                    </label>
                                    <input type="text" class="form-control @error('last_name') is-invalid @enderror"
                                           id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                                    @error('last_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">
                                        <i class="fas fa-envelope me-2"></i>Email *
                                    </label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                           id="email" name="email" value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">
                                        <i class="fas fa-phone me-2"></i>Téléphone
                                    </label>
                                    <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                           id="phone" name="phone" value="{{ old('phone') }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="subject" class="form-label">
                                    <i class="fas fa-tag me-2"></i>Sujet *
                                </label>
                                <select class="form-select @error('subject') is-invalid @enderror" id="subject" name="subject" required>
                                    <option value="">Sélectionner un sujet</option>
                                    <option value="general" {{ old('subject') == 'general' ? 'selected' : '' }}>Question générale</option>
                                    <option value="appointment" {{ old('subject') == 'appointment' ? 'selected' : '' }}>Rendez-vous</option>
                                    <option value="technical" {{ old('subject') == 'technical' ? 'selected' : '' }}>Support technique</option>
                                    <option value="billing" {{ old('subject') == 'billing' ? 'selected' : '' }}>Facturation</option>
                                    <option value="feedback" {{ old('subject') == 'feedback' ? 'selected' : '' }}>Retour d'expérience</option>
                                    <option value="other" {{ old('subject') == 'other' ? 'selected' : '' }}>Autre</option>
                                </select>
                                @error('subject')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="message" class="form-label">
                                    <i class="fas fa-comment me-2"></i>Message *
                                </label>
                                <textarea class="form-control @error('message') is-invalid @enderror"
                                          id="message" name="message" rows="5"
                                          placeholder="Décrivez votre demande en détail..." required>{{ old('message') }}</textarea>
                                @error('message')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="newsletter" name="newsletter">
                                    <label class="form-check-label" for="newsletter">
                                        Je souhaite recevoir la newsletter avec les actualités santé
                                    </label>
                                </div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-paper-plane me-2"></i>Envoyer le message
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Map Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h3 class="text-center mb-5">Notre localisation</h3>

                <div class="card">
                    <div class="card-body p-0">
                        <div class="map-container" style="height: 400px; background-color: #e9ecef;">
                            <div class="d-flex align-items-center justify-content-center h-100">
                                <div class="text-center">
                                    <i class="fas fa-map fa-4x text-muted mb-3"></i>
                                    <h5 class="text-muted">Carte interactive</h5>
                                    <p class="text-muted">Intégrez ici votre carte Google Maps ou OpenStreetMap</p>
                                    <a href="https://maps.google.com" target="_blank" class="btn btn-outline-primary">
                                        <i class="fas fa-external-link-alt me-2"></i>Voir sur Google Maps
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <h3 class="text-center mb-5">Questions fréquentes</h3>

                <div class="accordion" id="contactFAQ">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faq1">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1">
                                Comment prendre rendez-vous en ligne ?
                            </button>
                        </h2>
                        <div id="collapse1" class="accordion-collapse collapse show" data-bs-parent="#contactFAQ">
                            <div class="accordion-body">
                                Connectez-vous à votre espace patient, sélectionnez le service souhaité et choisissez un créneau disponible. La confirmation vous sera envoyée par email.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faq2">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2">
                                Puis-je annuler ou modifier mon rendez-vous ?
                            </button>
                        </h2>
                        <div id="collapse2" class="accordion-collapse collapse" data-bs-parent="#contactFAQ">
                            <div class="accordion-body">
                                Oui, vous pouvez modifier ou annuler votre rendez-vous jusqu'à 24h avant la consultation via votre espace patient ou en nous contactant.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faq3">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse3">
                                Comment accéder à mon dossier médical ?
                            </button>
                        </h2>
                        <div id="collapse3" class="accordion-collapse collapse" data-bs-parent="#contactFAQ">
                            <div class="accordion-body">
                                Votre dossier médical est accessible depuis votre espace patient connecté. Toutes vos informations sont sécurisées et confidentielles.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faq4">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse4">
                                Quels sont les moyens de paiement acceptés ?
                            </button>
                        </h2>
                        <div id="collapse4" class="accordion-collapse collapse" data-bs-parent="#contactFAQ">
                            <div class="accordion-body">
                                Nous acceptons les cartes bancaires, les chèques, les espèces et la carte vitale. Le paiement peut être effectué en ligne ou sur place.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('styles')
<style>
    .contact-info {
        background: white;
        padding: 2rem;
        border-radius: 1rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .contact-item {
        padding: 1rem;
        border-radius: 0.5rem;
        transition: all 0.3s ease;
    }

    .contact-item:hover {
        background-color: #f8f9fa;
        transform: translateX(5px);
    }

    .contact-icon {
        flex-shrink: 0;
    }

    .social-links .btn {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .map-container {
        border-radius: 0.5rem;
        overflow: hidden;
    }

    .accordion-button:not(.collapsed) {
        background-color: var(--primary-color);
        color: white;
    }

    .accordion-button:focus {
        box-shadow: 0 0 0 0.25rem rgba(37, 99, 235, 0.25);
    }

    .form-control, .form-select {
        border-radius: 0.5rem;
        border: 2px solid var(--border-color);
        transition: all 0.3s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.25);
    }

    .btn-lg {
        padding: 0.75rem 1.5rem;
        border-radius: 0.5rem;
        font-weight: 600;
    }
</style>
@endsection
