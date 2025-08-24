@extends('layouts.app')

@section('title', 'À propos - CareWell')

@section('content')
<!-- Header Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="section-title">À propos de CareWell</h1>
                <p class="section-subtitle">Découvrez notre mission, nos valeurs et notre équipe dédiée à votre santé</p>
            </div>
        </div>
    </div>
</section>

<!-- Mission Section -->
<section class="py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h2 class="section-title">Notre Mission</h2>
                <p class="lead mb-4">CareWell a été créé avec une vision simple mais ambitieuse : démocratiser l'accès aux soins de santé de qualité en utilisant la technologie moderne.</p>

                <div class="mission-points">
                    <div class="d-flex align-items-start mb-3">
                        <div class="mission-icon me-3">
                            <i class="fas fa-heartbeat fa-2x text-primary"></i>
                        </div>
                        <div>
                            <h5>Santé accessible</h5>
                            <p class="text-muted">Rendre les soins médicaux accessibles à tous, partout et à tout moment.</p>
                        </div>
                    </div>

                    <div class="d-flex align-items-start mb-3">
                        <div class="mission-icon me-3">
                            <i class="fas fa-users fa-2x text-success"></i>
                        </div>
                        <div>
                            <h5>Connexion humaine</h5>
                            <p class="text-muted">Maintenir le lien humain entre patients et professionnels de santé.</p>
                        </div>
                    </div>

                    <div class="d-flex align-items-start mb-3">
                        <div class="mission-icon me-3">
                            <i class="fas fa-shield-alt fa-2x text-warning"></i>
                        </div>
                        <div>
                            <h5>Innovation sécurisée</h5>
                            <p class="text-muted">Utiliser les technologies les plus avancées tout en garantissant la sécurité des données.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="about-image">
                    <img src="{{ asset('images/portail.png') }}" alt="CareWell Mission" class="img-fluid rounded shadow-lg">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Values Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Nos Valeurs</h2>
            <p class="section-subtitle">Les principes qui guident chacune de nos actions</p>
        </div>

        <div class="row g-4">
            <div class="col-lg-3 col-md-6">
                <div class="card text-center h-100 value-card">
                    <div class="card-body">
                        <div class="mb-3">
                            <i class="fas fa-hand-holding-heart fa-3x text-primary"></i>
                        </div>
                        <h5>Empathie</h5>
                        <p class="text-muted">Nous mettons l'humain au cœur de nos préoccupations et comprenons les défis de chaque patient.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card text-center h-100 value-card">
                    <div class="card-body">
                        <div class="mb-3">
                            <i class="fas fa-award fa-3x text-success"></i>
                        </div>
                        <h5>Excellence</h5>
                        <p class="text-muted">Nous visons l'excellence dans tous nos services et dans la qualité des soins prodigués.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card text-center h-100 value-card">
                    <div class="card-body">
                        <div class="mb-3">
                            <i class="fas fa-lock fa-3x text-warning"></i>
                        </div>
                        <h5>Confidentialité</h5>
                        <p class="text-muted">La protection de vos données médicales est notre priorité absolue.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card text-center h-100 value-card">
                    <div class="card-body">
                        <div class="mb-3">
                            <i class="fas fa-lightbulb fa-3x text-info"></i>
                        </div>
                        <h5>Innovation</h5>
                        <p class="text-muted">Nous repoussons constamment les limites pour améliorer votre expérience de soins.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Story Section -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <div class="story-timeline">
                    <h2 class="text-center mb-5">Notre Histoire</h2>

                    <div class="timeline-item">
                        <div class="timeline-marker">2020</div>
                        <div class="timeline-content">
                            <h5>Naissance de l'idée</h5>
                            <p>Face aux défis de l'accès aux soins, une équipe de professionnels de santé et d'experts technologiques décide de créer une solution innovante.</p>
                        </div>
                    </div>

                    <div class="timeline-item">
                        <div class="timeline-marker">2021</div>
                        <div class="timeline-content">
                            <h5>Développement de la plateforme</h5>
                            <p>Plusieurs mois de développement et de tests pour créer une plateforme robuste et intuitive.</p>
                        </div>
                    </div>

                    <div class="timeline-item">
                        <div class="timeline-marker">2022</div>
                        <div class="timeline-content">
                            <h5>Lancement officiel</h5>
                            <p>CareWell voit le jour et commence à connecter patients et professionnels de santé.</p>
                        </div>
                    </div>

                    <div class="timeline-item">
                        <div class="timeline-marker">2023</div>
                        <div class="timeline-content">
                            <h5>Expansion et amélioration</h5>
                            <p>La plateforme s'enrichit de nouvelles fonctionnalités et étend sa couverture géographique.</p>
                        </div>
                    </div>

                    <div class="timeline-item">
                        <div class="timeline-marker">2024</div>
                        <div class="timeline-content">
                            <h5>Leader du secteur</h5>
                            <p>CareWell devient une référence dans le domaine de la santé digitale, reconnue par les patients et les professionnels.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Team Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Notre Équipe</h2>
            <p class="section-subtitle">Des experts passionnés qui travaillent pour votre santé</p>
        </div>

        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="card text-center team-card">
                    <div class="card-body">
                        <div class="team-avatar mb-3">
                            <i class="fas fa-user-md fa-4x text-primary"></i>
                        </div>
                        <h5>Dr. Marie Dubois</h5>
                        <p class="text-muted">Directrice Médicale</p>
                        <p class="small">Spécialiste en médecine générale avec plus de 15 ans d'expérience. Passionnée par l'innovation en santé.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="card text-center team-card">
                    <div class="card-body">
                        <div class="team-avatar mb-3">
                            <i class="fas fa-laptop-code fa-4x text-success"></i>
                        </div>
                        <h5>Thomas Martin</h5>
                        <p class="text-muted">Directeur Technique</p>
                        <p class="small">Expert en développement logiciel et en cybersécurité. Garantit la fiabilité de notre plateforme.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="card text-center team-card">
                    <div class="card-body">
                        <div class="team-avatar mb-3">
                            <i class="fas fa-chart-line fa-4x text-warning"></i>
                        </div>
                        <h5>Sophie Bernard</h5>
                        <p class="text-muted">Directrice des Opérations</p>
                        <p class="small">Spécialiste en gestion des services de santé. Optimise nos processus pour une meilleure expérience patient.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Statistics Section -->
<section class="py-5 bg-primary text-white">
    <div class="container">
        <div class="row text-center">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="mb-3">
                    <i class="fas fa-users fa-3x"></i>
                </div>
                <h3 class="fw-bold">50,000+</h3>
                <p class="mb-0">Patients satisfaits</p>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="mb-3">
                    <i class="fas fa-user-md fa-3x"></i>
                </div>
                <h3 class="fw-bold">500+</h3>
                <p class="mb-0">Professionnels de santé</p>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="mb-3">
                    <i class="fas fa-calendar-check fa-3x"></i>
                </div>
                <h3 class="fw-bold">100,000+</h3>
                <p class="mb-0">Rendez-vous réalisés</p>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="mb-3">
                    <i class="fas fa-star fa-3x"></i>
                </div>
                <h3 class="fw-bold">4.9/5</h3>
                <p class="mb-0">Note moyenne</p>
            </div>
        </div>
    </div>
</section>

<!-- Certifications Section -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Nos Certifications et Accréditations</h2>
            <p class="section-subtitle">Des garanties de qualité et de sécurité</p>
        </div>

        <div class="row g-4 justify-content-center">
            <div class="col-lg-3 col-md-4 col-6">
                <div class="certification-item text-center">
                    <div class="certification-icon mb-3">
                        <i class="fas fa-shield-alt fa-3x text-primary"></i>
                    </div>
                    <h6>ISO 27001</h6>
                    <p class="small text-muted">Sécurité de l'information</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-4 col-6">
                <div class="certification-item text-center">
                    <div class="certification-icon mb-3">
                        <i class="fas fa-user-shield fa-3x text-success"></i>
                    </div>
                    <h6>RGPD</h6>
                    <p class="small text-muted">Protection des données</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-4 col-6">
                <div class="certification-item text-center">
                    <div class="certification-icon mb-3">
                        <i class="fas fa-certificate fa-3x text-warning"></i>
                    </div>
                    <h6>HAS</h6>
                    <p class="small text-muted">Haute Autorité de Santé</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-4 col-6">
                <div class="certification-item text-center">
                    <div class="certification-icon mb-3">
                        <i class="fas fa-award fa-3x text-info"></i>
                    </div>
                    <h6>Label e-santé</h6>
                    <p class="small text-muted">Ministère de la Santé</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <h2>Rejoignez l'aventure CareWell</h2>
                <p class="lead mb-4">Découvrez comment nous transformons l'expérience de soins pour des millions de patients.</p>
                <div class="d-flex flex-wrap justify-content-center gap-3">
                    <a href="{{ route('register') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-user-plus me-2"></i>Rejoindre CareWell
                    </a>
                    <a href="{{ route('contact') }}" class="btn btn-outline-primary btn-lg">
                        <i class="fas fa-envelope me-2"></i>Nous contacter
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('styles')
<style>
    .mission-icon {
        flex-shrink: 0;
        width: 60px;
        text-align: center;
    }

    .about-image img {
        max-height: 400px;
        object-fit: cover;
    }

    .value-card {
        transition: all 0.3s ease;
        border: 1px solid var(--border-color);
    }

    .value-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
    }

    .story-timeline {
        position: relative;
    }

    .story-timeline::before {
        content: '';
        position: absolute;
        left: 50%;
        top: 0;
        bottom: 0;
        width: 2px;
        background: var(--primary-color);
        transform: translateX(-50%);
    }

    .timeline-item {
        position: relative;
        margin-bottom: 3rem;
        display: flex;
        align-items: center;
    }

    .timeline-item:nth-child(odd) {
        flex-direction: row;
    }

    .timeline-item:nth-child(even) {
        flex-direction: row-reverse;
    }

    .timeline-marker {
        background: var(--primary-color);
        color: white;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        z-index: 1;
        flex-shrink: 0;
    }

    .timeline-content {
        background: white;
        padding: 1.5rem;
        border-radius: 0.5rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        margin: 0 2rem;
        flex: 1;
    }

    .team-card {
        transition: all 0.3s ease;
        border: 1px solid var(--border-color);
    }

    .team-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
    }

    .team-avatar {
        width: 100px;
        height: 100px;
        background: var(--light-color);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
    }

    .certification-item {
        padding: 1.5rem;
        border-radius: 0.5rem;
        transition: all 0.3s ease;
    }

    .certification-item:hover {
        transform: translateY(-5px);
        background: var(--light-color);
    }

    @media (max-width: 768px) {
        .story-timeline::before {
            left: 30px;
        }

        .timeline-item {
            flex-direction: column !important;
            align-items: flex-start;
            margin-left: 30px;
        }

        .timeline-content {
            margin: 1rem 0 0 0;
            width: 100%;
        }
    }
</style>
@endsection
