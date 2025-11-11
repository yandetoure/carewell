@extends('layouts.app')

@section('title', 'À propos - CareWell')

@section('content')
<!-- Hero Section -->
<section class="about-hero position-relative overflow-hidden text-white">
    <div class="hero-background" style="background-image: url('{{ asset('images/banner1.png') }}');"></div>
    <span class="hero-gradient"></span>
    <div class="container position-relative py-5">
        <div class="row align-items-center g-5">
            <div class="col-lg-7">
                <span class="about-hero-badge">
                    <i class="fas fa-hands-holding-heart me-2"></i>Notre engagement santé
                </span>
                <h1 class="about-hero-title">Nous construisons un accès aux soins plus humain et plus proche</h1>
                <p class="about-hero-subtitle">
                    CareWell connecte patients, familles et professionnels certifiés au sein d’un parcours digital sécurisé, simple et chaleureux.
                </p>

                <div class="hero-actions">
                    <a href="#mission" class="btn btn-light btn-lg">
                        <i class="fas fa-arrow-down me-2"></i>Découvrir CareWell
                    </a>
                    <a href="{{ route('contact') }}" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-envelope-open-text me-2"></i>Entrer en contact
                    </a>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="about-stats-card">
                    <div class="about-stat">
                        <span class="stat-value">50 000+</span>
                        <span class="stat-label">Patients accompagnés</span>
                    </div>
                    <div class="stat-divider"></div>
                    <div class="about-stat">
                        <span class="stat-value">500+</span>
                        <span class="stat-label">Professionnels certifiés</span>
                    </div>
                    <div class="stat-divider"></div>
                    <div class="about-stat">
                        <span class="stat-value">4.9/5</span>
                        <span class="stat-label">Satisfaction moyenne</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Mission & Vision -->
<section id="mission" class="about-mission py-5 position-relative">
    <div class="bg-gradient-split"></div>
    <div class="container position-relative">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <div class="mission-media">
                    <img src="{{ asset('images/portail.png') }}" alt="CareWell Mission" class="img-fluid rounded-4">
                    <span class="mission-badge">
                        <i class="fas fa-shield-heart me-2"></i>Santé connectée en toute confiance
                    </span>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="section-eyebrow mb-3">Notre raison d'être</div>
                <h2 class="section-title">Un écosystème de soins complet, sécurisé et chaleureux</h2>
                <p class="section-subtitle">
                    CareWell est né d’une ambition simple : offrir à chacun un accès fluide à des soins de qualité, en combinant expertise médicale et technologie intuitive.
                </p>

                <div class="mission-grid">
                    <div class="mission-card">
                        <div class="mission-icon">
                            <i class="fas fa-heartbeat"></i>
                        </div>
                        <div>
                            <h5>Santé accessible</h5>
                            <p>Des rendez-vous disponibles 24h/24 et des parcours personnalisés selon vos besoins.</p>
                        </div>
                    </div>
                    <div class="mission-card">
                        <div class="mission-icon">
                            <i class="fas fa-people-roof"></i>
                        </div>
                        <div>
                            <h5>Connexion humaine</h5>
                            <p>Une relation de confiance renforcée entre patients, proches et équipes médicales.</p>
                        </div>
                    </div>
                    <div class="mission-card">
                        <div class="mission-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <div>
                            <h5>Innovation sécurisée</h5>
                            <p>Une plateforme certifiée, conforme RGPD et pensée pour protéger vos données.</p>
                    </div>
                </div>
            </div>

                <div class="mission-cta">
                    <a href="{{ route('services') }}" class="btn btn-primary">
                        <i class="fas fa-heart-circle-plus me-2"></i>Explorer nos services
                    </a>
                    <a href="{{ route('articles') }}" class="btn btn-soft-primary">
                        <i class="fas fa-book-open me-2"></i>Lire nos conseils
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Values -->
<section class="about-values py-5">
    <div class="container">
        <div class="text-center mb-5">
            <div class="section-eyebrow">Nos valeurs</div>
            <h2 class="section-title">Les principes qui guident chaque interaction</h2>
            <p class="section-subtitle">Empathie, excellence et innovation sont au cœur de nos équipes, pour placer votre santé au centre de nos décisions.</p>
        </div>

        <div class="row g-4">
            <div class="col-xl-3 col-md-6">
                <article class="value-card gradient-blue h-100">
                    <div class="value-icon">
                        <i class="fas fa-hand-holding-heart"></i>
                    </div>
                    <h3>Empathie</h3>
                    <p>Nous comprenons les défis de chaque patient et adaptons nos accompagnements avec bienveillance.</p>
                </article>
            </div>
            <div class="col-xl-3 col-md-6">
                <article class="value-card gradient-green h-100">
                    <div class="value-icon">
                        <i class="fas fa-award"></i>
                    </div>
                    <h3>Excellence</h3>
                    <p>Nos équipes médicales et support s’engagent à délivrer un niveau de qualité constant.</p>
                </article>
            </div>
            <div class="col-xl-3 col-md-6">
                <article class="value-card gradient-orange h-100">
                    <div class="value-icon">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <h3>Confidentialité</h3>
                    <p>La sécurité de vos données médicales est assurée par des protocoles rigoureux et certifiés.</p>
                </article>
            </div>
            <div class="col-xl-3 col-md-6">
                <article class="value-card gradient-purple h-100">
                    <div class="value-icon">
                        <i class="fas fa-lightbulb"></i>
                    </div>
                    <h3>Innovation</h3>
                    <p>Nous développons des solutions digitales pour simplifier votre parcours de soin au quotidien.</p>
                </article>
            </div>
        </div>
    </div>
</section>

<!-- Story Timeline -->
<section class="about-story py-5 position-relative">
    <div class="container position-relative">
        <div class="row align-items-center g-5">
            <div class="col-lg-4">
                <div class="section-eyebrow">Notre histoire</div>
                <h2 class="section-title">Des débuts visionnaires à une plateforme de référence</h2>
                <p class="section-subtitle">Depuis 2020, nous transformons l’expérience de soins grâce à une croissance maîtrisée et à l’écoute de nos utilisateurs.</p>
            </div>
            <div class="col-lg-8">
                <div class="timeline">
                    <div class="timeline-item">
                        <span class="timeline-year">2020</span>
                        <div class="timeline-card">
                            <h5>Naissance de l’idée</h5>
                            <p>Une équipe de médecins et d’experts tech imagine une plateforme pour répondre aux défis d’accès aux soins.</p>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <span class="timeline-year">2021</span>
                        <div class="timeline-card">
                            <h5>Développement collaboratif</h5>
                            <p>Conception de l’écosystème CareWell avec nos premiers établissements partenaires.</p>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <span class="timeline-year">2022</span>
                        <div class="timeline-card">
                            <h5>Lancement officiel</h5>
                            <p>Ouverture de la plateforme au grand public et déploiement national des premiers services.</p>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <span class="timeline-year">2023</span>
                        <div class="timeline-card">
                            <h5>Expansion des fonctionnalités</h5>
                            <p>Intégration de la télémédecine, du dossier patient et d’un suivi spécialisé.</p>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <span class="timeline-year">2024</span>
                        <div class="timeline-card">
                            <h5>Référence e-santé</h5>
                            <p>CareWell devient leader régional et obtient l’ensemble des certifications de sécurité et qualité.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Team -->
<section class="about-team py-5">
    <div class="container">
        <div class="text-center mb-5">
            <div class="section-eyebrow">Notre équipe</div>
            <h2 class="section-title">Les visages qui donnent vie à CareWell</h2>
            <p class="section-subtitle">Des expertises complémentaires pour garantir l’excellence médicale, la sécurité numérique et la fluidité d’expérience.</p>
        </div>

        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <article class="team-card h-100">
                    <div class="team-avatar gradient-blue">
                        <i class="fas fa-user-md"></i>
                    </div>
                    <h3>Dr. Marie Dubois</h3>
                    <span class="team-role">Directrice médicale</span>
                    <p>15 ans d’expérience en médecine générale et coordination hospitalière pour garantir une prise en charge globale.</p>
                </article>
            </div>
            <div class="col-lg-4 col-md-6">
                <article class="team-card h-100">
                    <div class="team-avatar gradient-green">
                        <i class="fas fa-laptop-code"></i>
                    </div>
                    <h3>Thomas Martin</h3>
                    <span class="team-role">Directeur technique</span>
                    <p>Expert en cybersécurité et cloud santé, il assure la fiabilité 24/7 de notre plateforme et l’intégrité des données.</p>
                </article>
            </div>
            <div class="col-lg-4 col-md-6">
                <article class="team-card h-100">
                    <div class="team-avatar gradient-orange">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3>Sophie Bernard</h3>
                    <span class="team-role">Directrice des opérations</span>
                    <p>Spécialiste en organisation des services de santé, elle pilote chaque parcours patient pour une expérience fluide.</p>
                </article>
            </div>
        </div>
    </div>
</section>

<!-- Impact -->
<section class="about-impact py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-3 col-sm-6">
                <div class="impact-card">
                    <span class="impact-icon gradient-blue"><i class="fas fa-users"></i></span>
                    <span class="impact-value">50 000+</span>
                    <span class="impact-label">Patients accompagnés</span>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div class="impact-card">
                    <span class="impact-icon gradient-green"><i class="fas fa-user-nurse"></i></span>
                    <span class="impact-value">500+</span>
                    <span class="impact-label">Professionnels actifs</span>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div class="impact-card">
                    <span class="impact-icon gradient-orange"><i class="fas fa-calendar-check"></i></span>
                    <span class="impact-value">100 000+</span>
                    <span class="impact-label">Rendez-vous coordonnés</span>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div class="impact-card">
                    <span class="impact-icon gradient-purple"><i class="fas fa-star"></i></span>
                    <span class="impact-value">4.9/5</span>
                    <span class="impact-label">Satisfaction moyenne</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Certifications -->
<section class="about-certifications py-5">
    <div class="container">
        <div class="text-center mb-5">
            <div class="section-eyebrow">Nos garanties</div>
            <h2 class="section-title">Certifications et accréditations officielles</h2>
            <p class="section-subtitle">Nous respectons les standards internationaux de sécurité, de protection des données et de qualité médicale.</p>
        </div>

        <div class="row g-4 justify-content-center">
            <div class="col-xl-3 col-md-4 col-sm-6">
                <article class="certification-card h-100">
                    <div class="certification-icon gradient-blue">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h4>ISO 27001</h4>
                    <p>Sécurité et gestion des informations certifiées.</p>
                </article>
            </div>
            <div class="col-xl-3 col-md-4 col-sm-6">
                <article class="certification-card h-100">
                    <div class="certification-icon gradient-green">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <h4>Conformité RGPD</h4>
                    <p>Protection avancée des données personnelles sensibles.</p>
                </article>
            </div>
            <div class="col-xl-3 col-md-4 col-sm-6">
                <article class="certification-card h-100">
                    <div class="certification-icon gradient-orange">
                        <i class="fas fa-certificate"></i>
                    </div>
                    <h4>Accréditation HAS</h4>
                    <p>Respect des exigences de la Haute Autorité de Santé.</p>
                </article>
            </div>
            <div class="col-xl-3 col-md-4 col-sm-6">
                <article class="certification-card h-100">
                    <div class="certification-icon gradient-purple">
                        <i class="fas fa-award"></i>
                    </div>
                    <h4>Label e-santé</h4>
                    <p>Reconnaissance du Ministère de la Santé pour nos services digitaux.</p>
                </article>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="about-cta text-white py-5">
    <div class="container">
        <div class="row align-items-center g-4">
            <div class="col-lg-8">
                <div class="section-eyebrow text-white-50">Prêt à collaborer ?</div>
                <h2 class="cta-title">Rejoignez l’aventure CareWell et transformez le parcours de soins</h2>
                <p class="cta-subtitle">Professionnels, établissements ou nouveaux talents : construisons ensemble un futur où la santé est plus simple, plus rapide et plus humaine.</p>
            </div>
            <div class="col-lg-4">
                <div class="d-flex flex-column flex-md-row flex-lg-column gap-3 justify-content-lg-end">
                    <a href="{{ route('register') }}" class="btn btn-light btn-lg">
                        <i class="fas fa-user-plus me-2"></i>Rejoindre CareWell
                    </a>
                    <a href="{{ route('contact') }}" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-comments me-2"></i>Planifier un échange
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('styles')
<style>
    .about-hero {
        padding: 6rem 0 5rem;
        background: linear-gradient(135deg, rgba(30, 64, 175, 0.92), rgba(37, 99, 235, 0.92));
    }

    .about-hero .hero-background {
        position: absolute;
        inset: 0;
        background-size: cover;
        background-position: center;
        opacity: 0.35;
        filter: blur(2px);
        transform: scale(1.05);
    }

    .about-hero .hero-gradient {
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, rgba(15, 23, 42, 0) 0%, rgba(15, 23, 42, 0.65) 100%);
        mix-blend-mode: multiply;
    }

    .about-hero-badge {
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

    .about-hero-title {
        font-size: clamp(2.4rem, 4vw, 3.2rem);
        font-weight: 700;
        line-height: 1.15;
        margin-bottom: 1.5rem;
    }

    .about-hero-subtitle {
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

    .about-stats-card {
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

    .about-stat {
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
        margin: 0 auto;
    }

    .mission-media {
        position: relative;
        border-radius: 2rem;
        overflow: hidden;
    }

    .mission-media img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .mission-badge {
        position: absolute;
        bottom: 1.5rem;
        left: 1.5rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(15, 23, 42, 0.75);
        color: #fff;
        padding: 0.65rem 1.2rem;
        border-radius: 999px;
        font-weight: 600;
        backdrop-filter: blur(6px);
    }

    .mission-grid {
        display: grid;
        gap: 1.15rem;
        margin-top: 2rem;
    }

    .mission-card {
        display: flex;
        gap: 1rem;
        align-items: flex-start;
        padding: 1.35rem 1.5rem;
        border-radius: 1.25rem;
        background: #fff;
        box-shadow: 0 20px 45px -25px rgba(15, 23, 42, 0.25);
        border: 1px solid rgba(148, 163, 184, 0.18);
    }

    .mission-icon {
        width: 56px;
        height: 56px;
        border-radius: 1rem;
        display: grid;
        place-items: center;
        background: rgba(37, 99, 235, 0.12);
        color: var(--primary-color);
        font-size: 1.4rem;
        flex-shrink: 0;
    }

    .mission-cta {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        margin-top: 2rem;
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
        padding: 0.75rem 1.35rem;
        text-decoration: none;
        transition: all 0.3s ease;
        border: none;
    }

    .btn-soft-primary:hover {
        background: rgba(37, 99, 235, 0.22);
        color: var(--secondary-color);
    }

    .about-values {
        position: relative;
    }

    .value-card {
        position: relative;
        border-radius: 1.5rem;
        padding: 2.25rem;
        background: #fff;
        box-shadow: 0 25px 50px -30px rgba(15, 23, 42, 0.35);
        overflow: hidden;
        transition: transform 0.35s ease, box-shadow 0.35s ease;
    }

    .value-card::before {
        content: "";
        position: absolute;
        inset: 0;
        border-radius: inherit;
        padding: 1px;
        background: var(--card-gradient, linear-gradient(135deg, rgba(37, 99, 235, 0.45), rgba(59, 130, 246, 0.12)));
        -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
        -webkit-mask-composite: xor;
        mask-composite: exclude;
        opacity: 0.75;
        pointer-events: none;
        transition: opacity 0.35s ease;
    }

    .value-card:hover {
        transform: translateY(-12px);
        box-shadow: 0 35px 70px -40px rgba(37, 99, 235, 0.45);
    }

    .value-card:hover::before {
        opacity: 1;
    }

    .value-icon {
        width: 70px;
        height: 70px;
        border-radius: 1.2rem;
        display: grid;
        place-items: center;
        background: rgba(37, 99, 235, 0.12);
        color: var(--primary-color);
        font-size: 1.8rem;
        margin-bottom: 1.5rem;
    }

    .value-card h3 {
        font-size: 1.3rem;
        font-weight: 700;
        margin-bottom: 0.75rem;
        color: var(--dark-color);
    }

    .value-card p {
        color: #475569;
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

    .about-story .timeline {
        position: relative;
        padding-left: 2rem;
        border-left: 2px dashed rgba(37, 99, 235, 0.3);
    }

    .timeline-item {
        position: relative;
        padding-left: 2.5rem;
        margin-bottom: 2.5rem;
    }

    .timeline-item:last-child {
        margin-bottom: 0;
    }

    .timeline-year {
        position: absolute;
        left: -3.1rem;
        top: 0.35rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 72px;
        height: 32px;
        border-radius: 999px;
        background: rgba(37, 99, 235, 0.12);
        color: var(--primary-color);
        font-weight: 700;
        font-size: 0.9rem;
    }

    .timeline-card {
        background: #fff;
        padding: 1.4rem 1.6rem;
        border-radius: 1.2rem;
        box-shadow: 0 18px 40px -30px rgba(15, 23, 42, 0.35);
        border: 1px solid rgba(148, 163, 184, 0.16);
    }

    .timeline-card h5 {
        font-weight: 700;
        margin-bottom: 0.5rem;
        color: var(--dark-color);
    }

    .timeline-card p {
        margin-bottom: 0;
        color: #475569;
    }

    .about-team .team-card {
        position: relative;
        text-align: center;
        border-radius: 1.5rem;
        padding: 2.3rem 1.8rem;
        background: #fff;
        box-shadow: 0 22px 50px -30px rgba(15, 23, 42, 0.25);
        border: 1px solid rgba(148, 163, 184, 0.16);
        transition: transform 0.35s ease, box-shadow 0.35s ease;
        height: 100%;
    }

    .team-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 35px 70px -45px rgba(37, 99, 235, 0.35);
    }

    .team-avatar {
        width: 96px;
        height: 96px;
        border-radius: 1.2rem;
        display: grid;
        place-items: center;
        color: #fff;
        font-size: 2rem;
        margin: 0 auto 1.5rem;
    }

    .team-card h3 {
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: 0.35rem;
        color: var(--dark-color);
    }

    .team-role {
        display: block;
        font-weight: 600;
        color: var(--primary-color);
        margin-bottom: 0.75rem;
    }

    .team-card p {
        color: #475569;
    }

    .about-impact .impact-card {
        background: #fff;
        border-radius: 1.5rem;
        padding: 2rem 1.75rem;
        text-align: center;
        border: 1px solid rgba(148, 163, 184, 0.16);
        box-shadow: 0 18px 45px -30px rgba(15, 23, 42, 0.25);
    }

    .impact-icon {
        width: 64px;
        height: 64px;
        border-radius: 1.1rem;
        display: grid;
        place-items: center;
        color: #fff;
        font-size: 1.6rem;
        margin: 0 auto 1rem;
    }

    .impact-value {
        display: block;
        font-size: 1.7rem;
        font-weight: 700;
        color: var(--dark-color);
    }

    .impact-label {
        color: #475569;
        font-weight: 500;
    }

    .about-certifications {
        position: relative;
        overflow: hidden;
    }

    .about-certifications::before {
        content: "";
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at top left, rgba(59, 130, 246, 0.12), rgba(59, 130, 246, 0));
        pointer-events: none;
    }

    .certification-card {
        position: relative;
        text-align: center;
        border-radius: 1.4rem;
        padding: 2rem 1.6rem;
        background: #fff;
        border: 1px solid rgba(148, 163, 184, 0.16);
        box-shadow: 0 20px 45px -30px rgba(15, 23, 42, 0.25);
        transition: transform 0.35s ease, box-shadow 0.35s ease;
    }

    .certification-card:hover {
        transform: translateY(-12px);
        box-shadow: 0 35px 70px -45px rgba(37, 99, 235, 0.35);
    }

    .certification-icon {
        width: 70px;
        height: 70px;
        border-radius: 1.2rem;
        display: grid;
        place-items: center;
        color: #fff;
        font-size: 1.8rem;
        margin: 0 auto 1.25rem;
    }

    .certification-card h4 {
        font-size: 1.2rem;
        font-weight: 700;
        margin-bottom: 0.6rem;
        color: var(--dark-color);
    }

    .certification-card p {
        color: #475569;
        margin-bottom: 0;
    }

    .about-cta {
        position: relative;
        overflow: hidden;
        border-radius: 2.5rem 2.5rem 0 0;
        background: linear-gradient(125deg, rgba(30, 64, 175, 0.95), rgba(37, 99, 235, 0.9));
    }

    .about-cta::before {
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
        font-size: clamp(2rem, 3.4vw, 2.7rem);
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

    @media (max-width: 1200px) {
        .about-stats-card {
            grid-template-columns: repeat(2, 1fr);
        }

        .stat-divider:nth-of-type(3) {
            display: none;
        }
    }

    @media (max-width: 992px) {
        .about-hero {
            padding: 5rem 0 4.5rem;
        }

        .hero-actions {
            flex-direction: column;
            align-items: flex-start;
        }

        .about-stats-card {
            grid-template-columns: 1fr;
            text-align: center;
        }

        .stat-divider {
            display: none;
        }

        .mission-media {
            margin-bottom: 2rem;
        }

        .mission-cta {
            flex-direction: column;
            align-items: stretch;
        }

        .timeline-item {
            padding-left: 2rem;
        }

        .timeline-year {
            left: -2.5rem;
        }

        .about-cta {
            border-radius: 2rem 2rem 0 0;
        }
    }

    @media (max-width: 768px) {
        .about-hero {
            padding: 4.5rem 0 4rem;
        }

        .about-hero-subtitle {
            font-size: 1rem;
        }

        .mission-card {
            flex-direction: column;
            text-align: center;
        }

        .mission-icon {
            margin: 0 auto;
        }

        .timeline {
            border-left: none;
            padding-left: 0;
        }

        .timeline-item {
            padding-left: 0;
        }

        .timeline-year {
            position: relative;
            left: 0;
            margin-bottom: 0.5rem;
        }

        .timeline-card {
            padding: 1.3rem 1.4rem;
        }

        .about-impact .impact-card {
            padding: 1.75rem 1.5rem;
        }

        .about-cta {
            text-align: center;
        }

        .about-cta .btn-lg {
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
