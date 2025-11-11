@extends('layouts.app')

@section('title', 'Contact - CareWell')

@section('content')
<!-- Hero -->
<section class="contact-hero position-relative overflow-hidden text-white">
    <div class="hero-background" style="background-image: url('{{ asset('images/banner1.png') }}');"></div>
    <span class="hero-gradient"></span>
    <div class="container position-relative py-5">
        <div class="row align-items-center g-5">
            <div class="col-lg-7">
                <span class="contact-hero-badge">
                    <i class="fas fa-headset me-2"></i>Support CareWell
                </span>
                <h1 class="contact-hero-title">Un accompagnement humain pour chaque question de santé</h1>
                <p class="contact-hero-subtitle">
                    Besoin d’aide pour votre dossier médical, un rendez-vous ou une urgence ? Notre équipe est disponible pour vous guider rapidement.
                </p>
                <div class="hero-actions">
                    <a href="#contact-form" class="btn btn-light btn-lg">
                        <i class="fas fa-envelope-open-text me-2"></i>Écrire au support
                    </a>
                    <a href="tel:+33123456789" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-phone me-2"></i>Appeler le +33 1 23 45 67 89
                    </a>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="contact-stats-card">
                    <div class="contact-stat">
                        <span class="stat-value">24h</span>
                        <span class="stat-label">Temps de réponse moyen</span>
                    </div>
                    <div class="stat-divider"></div>
                    <div class="contact-stat">
                        <span class="stat-value">95%</span>
                        <span class="stat-label">Demandes résolues au premier contact</span>
                    </div>
                    <div class="stat-divider"></div>
                    <div class="contact-stat">
                        <span class="stat-value">7j/7</span>
                        <span class="stat-label">Suivi des urgences et hospitalisations</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact Highlights -->
<section class="contact-support py-5 position-relative">
    <div class="bg-gradient-split"></div>
    <div class="container position-relative">
        <div class="row g-4">
            <div class="col-xl-3 col-md-6">
                <article class="contact-card gradient-blue h-100">
                    <span class="contact-icon"><i class="fas fa-map-marker-alt"></i></span>
                    <h3>Adresse</h3>
                    <p>123 Rue de la Santé<br>75001 Paris, France</p>
                </article>
            </div>
            <div class="col-xl-3 col-md-6">
                <article class="contact-card gradient-green h-100">
                    <span class="contact-icon"><i class="fas fa-phone"></i></span>
                    <h3>Téléphone</h3>
                    <p><a href="tel:+33123456789">+33 1 23 45 67 89</a><br><small>Lun-Ven 8h-20h • Sam 9h-17h</small></p>
                </article>
                            </div>
            <div class="col-xl-3 col-md-6">
                <article class="contact-card gradient-purple h-100">
                    <span class="contact-icon"><i class="fas fa-envelope"></i></span>
                    <h3>Email</h3>
                    <p><a href="mailto:contact@carewell.fr">contact@carewell.fr</a><br><small>Réponse sous 24h</small></p>
                </article>
                            </div>
            <div class="col-xl-3 col-md-6">
                <article class="contact-card gradient-orange h-100">
                    <span class="contact-icon"><i class="fas fa-clock"></i></span>
                    <h3>Horaires</h3>
                    <p>Lun-Ven 8h-20h<br>Sam 9h-17h<br><span class="text-muted">Dimanche : fermé</span></p>
                </article>
                        </div>
                    </div>

        <div class="row g-4 mt-4">
            <div class="col-lg-6">
                <div class="emergency-card">
                    <div class="emergency-icon">
                        <i class="fas fa-triangle-exclamation"></i>
                            </div>
                            <div>
                        <h4>Urgence médicale immédiate ?</h4>
                        <p>Appelez le <strong>15 (SAMU)</strong> – disponible 24h/24 et 7j/7 pour toute situation critique.</p>
                    </div>
                            </div>
                        </div>
            <div class="col-lg-6">
                <div class="social-card">
                    <h4>Suivez notre actualité</h4>
                    <p class="mb-3">Conseils santé, nouveautés et événements CareWell au quotidien.</p>
                    <div class="social-buttons">
                        <a href="#" class="social-btn facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-btn twitter"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-btn instagram"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-btn linkedin"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                            </div>
                        </div>
                    </div>
                        </div>
</section>

<!-- Form -->
<section id="contact-form" class="contact-form-section py-5">
    <div class="container">
        <div class="row g-5 align-items-start">
            <div class="col-lg-5">
                <div class="form-intro-card">
                    <span class="section-eyebrow">Formulaire sécurisé</span>
                    <h2 class="section-title">Ecrivez-nous, notre équipe vous répond sous 24h</h2>
                    <p class="section-subtitle">Sélectionnez la thématique de votre demande pour être mis en relation directement avec le spécialiste CareWell adapté.</p>
                    <ul class="form-benefits">
                        <li><i class="fas fa-shield-check"></i>Données chiffrées et hébergées en France</li>
                        <li><i class="fas fa-user-md"></i>Transmission directe à nos équipes médicales</li>
                        <li><i class="fas fa-bolt"></i>Confirmation immédiate par email</li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="contact-form-card">
                    <h3 class="form-title">
                        <i class="fas fa-envelope-open-text me-2"></i>Envoyez-nous un message
                    </h3>

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
                        <div class="row g-3">
                            <div class="col-md-6">
                                    <label for="first_name" class="form-label">
                                        <i class="fas fa-user me-2"></i>Prénom *
                                    </label>
                                    <input type="text" class="form-control @error('first_name') is-invalid @enderror"
                                           id="first_name" name="first_name" value="{{ old('first_name') }}" required>
                                    @error('first_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            <div class="col-md-6">
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

                        <div class="row g-3 mt-1">
                            <div class="col-md-6">
                                    <label for="email" class="form-label">
                                        <i class="fas fa-envelope me-2"></i>Email *
                                    </label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                           id="email" name="email" value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            <div class="col-md-6">
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

                        <div class="mt-3">
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

                        <div class="mt-3">
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

                        <div class="mt-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="newsletter" name="newsletter">
                                    <label class="form-check-label" for="newsletter">
                                        Je souhaite recevoir la newsletter avec les actualités santé
                                    </label>
                                </div>
                            </div>

                        <div class="mt-4 d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-paper-plane me-2"></i>Envoyer le message
                                </button>
                            </div>
                        </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Map -->
<section class="contact-map py-5 position-relative">
    <div class="container position-relative">
        <div class="contact-map-card">
            <div class="row g-0 align-items-center">
                <div class="col-lg-5 p-4 p-lg-5">
                    <span class="section-eyebrow">Nous trouver</span>
                    <h3 class="section-title">Un accueil physique au cœur de Paris</h3>
                    <p class="section-subtitle">Notre équipe vous reçoit sur rendez-vous pour vos examens, conseils et accompagnements personnalisés.</p>
                    <ul class="map-details">
                        <li><i class="fas fa-train"></i>Accès métro : Lignes 1 et 7 (Station Palais Royal)</li>
                        <li><i class="fas fa-bus"></i>Bus : 21 • 27 • 95</li>
                        <li><i class="fas fa-parking"></i>Parking public Vinci Louvre à 3 min</li>
                    </ul>
                    <a href="https://maps.google.com" target="_blank" class="btn btn-soft-primary mt-3">
                        <i class="fas fa-map-location-dot me-2"></i>Ouvrir dans Google Maps
                                    </a>
                                </div>
                <div class="col-lg-7">
                    <div class="map-placeholder">
                        <div class="map-overlay">
                            <i class="fas fa-map fa-3x mb-3"></i>
                            <h4>Carte interactive à intégrer</h4>
                            <p class="mb-0">Intégrez votre module Google Maps ou OpenStreetMap dans cette zone.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ -->
<section class="contact-faq py-5">
    <div class="container">
        <div class="text-center mb-5">
            <span class="section-eyebrow">Questions fréquentes</span>
            <h2 class="section-title">Tout ce qu’il faut savoir avant de nous écrire</h2>
            <p class="section-subtitle">Les réponses aux questions les plus courantes concernant nos services, votre dossier patient ou la gestion des rendez-vous.</p>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="accordion contact-accordion" id="contactFAQ">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faq1">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1">
                                Comment prendre rendez-vous en ligne ?
                            </button>
                        </h2>
                        <div id="collapse1" class="accordion-collapse collapse show" data-bs-parent="#contactFAQ">
                            <div class="accordion-body">
                                Connectez-vous à votre espace patient, sélectionnez le service souhaité et choisissez un créneau disponible. Une confirmation vous est envoyée automatiquement par email et SMS.
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
                                Oui, vous pouvez modifier ou annuler votre rendez-vous jusqu’à 24h avant la consultation via votre espace patient ou en contactant nos équipes par téléphone.
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
                                Votre dossier médical est disponible dans votre espace patient sécurisé. Vous pouvez y consulter vos ordonnances, résultats d’examens et comptes rendus à tout moment.
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
                                Nous acceptons les cartes bancaires, chèques, espèces et la carte Vitale. Le paiement peut être réalisé en ligne lors de la prise de rendez-vous ou sur place lors de votre venue.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="contact-cta text-white py-5">
    <div class="container">
        <div class="row align-items-center g-4">
            <div class="col-lg-8">
                <div class="section-eyebrow text-white-50">Besoin d’un suivi personnalisé ?</div>
                <h2 class="cta-title">Nos conseillers vous accompagnent pour construire un parcours adapté</h2>
                <p class="cta-subtitle">Planifiez un appel avec un coordinateur CareWell pour explorer nos services de télémédecine, hospitalisation ou suivi à domicile.</p>
            </div>
            <div class="col-lg-4">
                <div class="d-flex flex-column flex-md-row flex-lg-column gap-3 justify-content-lg-end">
                    <a href="{{ route('appointments.create') }}" class="btn btn-light btn-lg">
                        <i class="fas fa-calendar-plus me-2"></i>Prendre un rendez-vous
                    </a>
                    <a href="{{ route('services') }}" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-heartbeat me-2"></i>Découvrir nos services
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('styles')
<style>
    .contact-hero {
        padding: 6rem 0 5rem;
        background: linear-gradient(135deg, rgba(30, 64, 175, 0.92), rgba(37, 99, 235, 0.92));
    }

    .contact-hero .hero-background {
        position: absolute;
        inset: 0;
        background-position: center;
        background-size: cover;
        opacity: 0.35;
        filter: blur(2px);
        transform: scale(1.05);
    }

    .contact-hero .hero-gradient {
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, rgba(15, 23, 42, 0) 0%, rgba(15, 23, 42, 0.65) 100%);
        mix-blend-mode: multiply;
    }

    .contact-hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(255, 255, 255, 0.16);
        padding: 0.5rem 1.3rem;
        border-radius: 999px;
        font-weight: 600;
        letter-spacing: 0.02em;
        margin-bottom: 1.5rem;
    }

    .contact-hero-title {
        font-size: clamp(2.4rem, 4vw, 3.2rem);
        font-weight: 700;
        line-height: 1.15;
        margin-bottom: 1.5rem;
    }

    .contact-hero-subtitle {
        font-size: 1.1rem;
        max-width: 660px;
        opacity: 0.9;
        margin-bottom: 2.2rem;
    }

    .hero-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .hero-actions .btn-lg {
        padding: 0.9rem 1.9rem;
        border-radius: 0.85rem;
        font-weight: 600;
    }

    .contact-stats-card {
        background: rgba(15, 23, 42, 0.55);
        border-radius: 1.75rem;
        padding: 2.2rem;
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.12);
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        align-items: center;
        gap: 1.2rem;
    }

    .contact-stat {
        text-align: center;
    }

    .stat-value {
        display: block;
        font-size: 1.9rem;
        font-weight: 700;
    }

    .stat-label {
        font-size: 0.95rem;
        opacity: 0.75;
    }

    .stat-divider {
        width: 1px;
        height: 64px;
        background: rgba(255, 255, 255, 0.25);
        justify-self: center;
    }

    .bg-gradient-split {
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at top right, rgba(59, 130, 246, 0.12), rgba(59, 130, 246, 0));
        pointer-events: none;
    }

    .contact-card {
        position: relative;
        border-radius: 1.5rem;
        padding: 2.2rem 2rem;
        background: #fff;
        box-shadow: 0 25px 50px -35px rgba(15, 23, 42, 0.45);
        border: 1px solid rgba(148, 163, 184, 0.18);
        overflow: hidden;
        transition: transform 0.35s ease, box-shadow 0.35s ease;
    }

    .contact-card::before {
        content: "";
        position: absolute;
        inset: 0;
        border-radius: inherit;
        padding: 1px;
        background: var(--card-gradient, linear-gradient(135deg, rgba(37, 99, 235, 0.45), rgba(59, 130, 246, 0.12)));
        -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
        -webkit-mask-composite: xor;
        mask-composite: exclude;
        opacity: 0.7;
        pointer-events: none;
        transition: opacity 0.35s ease;
    }

    .contact-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 35px 70px -40px rgba(37, 99, 235, 0.4);
    }

    .contact-card:hover::before {
        opacity: 1;
    }

    .contact-card h3 {
        font-size: 1.3rem;
        font-weight: 700;
        margin-bottom: 0.75rem;
        color: var(--dark-color);
    }

    .contact-card p {
        margin-bottom: 0;
        color: #475569;
    }

    .contact-card a {
        color: inherit;
        text-decoration: none;
        font-weight: 600;
    }

    .contact-icon {
        width: 70px;
        height: 70px;
        border-radius: 1.2rem;
        display: grid;
        place-items: center;
        background: rgba(255, 255, 255, 0.15);
        color: #fff;
        font-size: 1.8rem;
        margin-bottom: 1.4rem;
    }

    .emergency-card {
        display: flex;
        gap: 1.5rem;
        align-items: center;
        padding: 2rem;
        border-radius: 1.5rem;
        background: radial-gradient(circle at top left, rgba(239, 68, 68, 0.12), rgba(239, 68, 68, 0));
        border: 1px solid rgba(248, 113, 113, 0.25);
    }

    .emergency-icon {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        display: grid;
        place-items: center;
        background: rgba(239, 68, 68, 0.12);
        color: #ef4444;
        font-size: 1.8rem;
        flex-shrink: 0;
    }

    .social-card {
        border-radius: 1.5rem;
        background: linear-gradient(135deg, rgba(30, 64, 175, 0.08), rgba(37, 99, 235, 0.1));
        padding: 2rem;
        border: 1px solid rgba(37, 99, 235, 0.18);
    }

    .social-buttons {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .social-btn {
        width: 46px;
        height: 46px;
        border-radius: 50%;
        display: grid;
        place-items: center;
        color: #fff;
        font-size: 1.1rem;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .social-btn.facebook { background: #1877f2; }
    .social-btn.twitter { background: #1da1f2; }
    .social-btn.instagram { background: linear-gradient(135deg, #f09433 0%, #bc1888 100%); }
    .social-btn.linkedin { background: #0a66c2; }

    .social-btn:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 25px -12px rgba(15, 23, 42, 0.35);
    }

    .form-intro-card {
        border-radius: 1.75rem;
        padding: 2.5rem;
        background: linear-gradient(140deg, rgba(255, 255, 255, 0.98), rgba(226, 232, 240, 0.92));
        border: 1px solid rgba(148, 163, 184, 0.22);
        box-shadow: 0 25px 55px -40px rgba(15, 23, 42, 0.35);
    }

    .section-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.35rem 1rem;
        border-radius: 999px;
        background: rgba(37, 99, 235, 0.12);
        color: var(--primary-color);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        font-size: 0.75rem;
    }

    .section-title {
        font-size: clamp(1.9rem, 3vw, 2.6rem);
        font-weight: 700;
        margin-top: 1rem;
        margin-bottom: 0.75rem;
        color: var(--dark-color);
    }

    .section-subtitle {
        color: #475569;
        max-width: 640px;
    }

    .form-benefits {
        margin: 2rem 0 0;
        padding: 0;
        list-style: none;
        display: grid;
        gap: 0.75rem;
    }

    .form-benefits li {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        color: #1f2937;
        font-weight: 500;
    }

    .form-benefits i {
        color: var(--primary-color);
    }

    .contact-form-card {
        border-radius: 1.75rem;
        padding: 2.5rem;
        background: #fff;
        border: 1px solid rgba(148, 163, 184, 0.22);
        box-shadow: 0 25px 55px -40px rgba(15, 23, 42, 0.35);
    }

    .form-title {
        font-size: 1.45rem;
        font-weight: 700;
        color: var(--dark-color);
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .form-control,
    .form-select {
        border-radius: 0.95rem;
        border: 1px solid rgba(148, 163, 184, 0.35);
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: rgba(37, 99, 235, 0.75);
        box-shadow: 0 0 0 0.18rem rgba(37, 99, 235, 0.18);
    }

    .contact-map {
        background: rgba(241, 245, 249, 0.6);
    }

    .contact-map-card {
        border-radius: 2rem;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 30px 70px -45px rgba(15, 23, 42, 0.35);
        border: 1px solid rgba(148, 163, 184, 0.18);
    }

    .map-details {
        padding: 0;
        margin: 1.5rem 0 0;
        list-style: none;
        display: grid;
        gap: 0.6rem;
        color: #1f2937;
        font-weight: 500;
    }

    .map-details i {
        color: var(--primary-color);
        margin-right: 0.65rem;
    }

    .map-placeholder {
        min-height: 320px;
        background: linear-gradient(135deg, rgba(226, 232, 240, 0.8), rgba(203, 213, 225, 0.9));
        display: grid;
        place-items: center;
        padding: 3rem;
    }

    .map-overlay {
        text-align: center;
        color: #1f2937;
        max-width: 360px;
    }

    .map-overlay i {
        color: var(--primary-color);
    }

    .btn-soft-primary {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        background: rgba(37, 99, 235, 0.12);
        color: var(--primary-color);
        font-weight: 600;
        border-radius: 0.85rem;
        padding: 0.75rem 1.4rem;
        text-decoration: none;
        transition: all 0.3s ease;
        border: none;
    }

    .btn-soft-primary:hover {
        background: rgba(37, 99, 235, 0.22);
        color: var(--secondary-color);
    }

    .contact-accordion .accordion-item {
        border: none;
        margin-bottom: 1rem;
        border-radius: 1rem;
        overflow: hidden;
        box-shadow: 0 16px 40px -35px rgba(15, 23, 42, 0.5);
        border: 1px solid rgba(148, 163, 184, 0.16);
    }

    .contact-accordion .accordion-button {
        padding: 1.1rem 1.4rem;
        font-weight: 600;
        color: var(--dark-color);
    }

    .contact-accordion .accordion-button:not(.collapsed) {
        background: linear-gradient(135deg, rgba(37, 99, 235, 0.12), rgba(59, 130, 246, 0.08));
        color: var(--dark-color);
        box-shadow: none;
    }

    .contact-accordion .accordion-body {
        padding: 1.2rem 1.4rem 1.5rem;
        color: #475569;
    }

    .contact-cta {
        position: relative;
        overflow: hidden;
        border-radius: 2.5rem 2.5rem 0 0;
        background: linear-gradient(125deg, rgba(30, 64, 175, 0.95), rgba(37, 99, 235, 0.9));
    }

    .contact-cta::before {
        content: "";
        position: absolute;
        top: -25%;
        right: -10%;
        width: 55%;
        height: 150%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.22), rgba(255, 255, 255, 0));
        opacity: 0.7;
    }

    .cta-title {
        font-size: clamp(2rem, 3.3vw, 2.7rem);
        font-weight: 700;
        margin-top: 1rem;
        margin-bottom: 1rem;
    }

    .cta-subtitle {
        font-size: 1.05rem;
        max-width: 620px;
        opacity: 0.88;
        margin-bottom: 0;
    }

    .gradient-blue {
        --card-gradient: linear-gradient(135deg, rgba(37, 99, 235, 0.45), rgba(59, 130, 246, 0.15));
    }

    .gradient-green {
        --card-gradient: linear-gradient(135deg, rgba(16, 185, 129, 0.45), rgba(34, 197, 94, 0.15));
    }

    .gradient-purple {
        --card-gradient: linear-gradient(135deg, rgba(124, 58, 237, 0.45), rgba(168, 85, 247, 0.12));
    }

    .gradient-orange {
        --card-gradient: linear-gradient(135deg, rgba(249, 115, 22, 0.45), rgba(251, 191, 36, 0.18));
    }

    @media (max-width: 1200px) {
        .contact-stats-card {
            grid-template-columns: repeat(2, 1fr);
        }

        .stat-divider:nth-of-type(3) {
            display: none;
        }
    }

    @media (max-width: 992px) {
        .contact-hero {
            padding: 5rem 0 4.5rem;
        }

        .hero-actions {
            flex-direction: column;
            align-items: flex-start;
        }

        .contact-stats-card {
            grid-template-columns: 1fr;
            text-align: center;
        }

        .stat-divider {
            display: none;
        }

        .form-intro-card {
            text-align: center;
        }

        .form-benefits li {
            justify-content: center;
        }

        .contact-map-card {
            border-radius: 2rem;
        }

        .contact-cta {
            border-radius: 2rem 2rem 0 0;
        }
    }

    @media (max-width: 768px) {
        .contact-hero {
            padding: 4.5rem 0 4rem;
        }

        .contact-hero-subtitle {
            font-size: 1rem;
        }

        .contact-card {
            padding: 2rem 1.75rem;
        }

        .emergency-card,
        .social-card {
            flex-direction: column;
            text-align: center;
        }

        .emergency-icon {
            margin-bottom: 0.75rem;
        }

        .form-intro-card,
        .contact-form-card {
            padding: 2rem;
        }

        .map-placeholder {
            padding: 2.5rem;
        }

        .contact-accordion .accordion-button {
            padding: 1rem 1.1rem;
        }

        .contact-cta {
            text-align: center;
        }

        .contact-cta .btn-lg {
            width: 100%;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        *, *::before, *::after {
            animation-duration: 0.01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: 0.01ms !important;
            scroll-behavior: auto !important;
        }
    }
</style>
@endsection
