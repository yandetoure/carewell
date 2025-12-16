<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'CareWell - Plateforme de Santé')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    @vite(['resources/css/app.css'])
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #1e40af;
            --accent-color: #3b82f6;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --light-color: #f8fafc;
            --dark-color: #1e293b;
            --text-color: #334155;
            --border-color: #e2e8f0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            line-height: 1.6;
            color: var(--text-color);
            background-color: var(--light-color);
        }

        .navbar {
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 50%, #1e3a8a 100%);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            padding: 1rem 0;
            transition: all 0.3s ease;
        }

        .navbar.scrolled {
            padding: 0.5rem 0;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.75rem;
            color: white !important;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .navbar-brand:hover {
            transform: scale(1.05);
        }

        .navbar-brand img {
            height: 45px;
            width: auto;
            margin-right: 0.75rem;
            transition: all 0.3s ease;
        }

        .navbar-brand:hover img {
            transform: scale(1.1);
        }

        .navbar.scrolled .navbar-brand img {
            height: 35px;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.95) !important;
            font-weight: 500;
            padding: 0.5rem 1rem !important;
            margin: 0 0.25rem;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.1);
            transition: left 0.3s ease;
            z-index: -1;
        }

        .nav-link:hover::before {
            left: 0;
        }

        .nav-link:hover {
            color: white !important;
            transform: translateY(-2px);
            background: rgba(255, 255, 255, 0.15);
        }

        .nav-link.active {
            background: rgba(255, 255, 255, 0.2);
            color: white !important;
            font-weight: 600;
        }

        .navbar-toggler {
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 0.5rem;
            padding: 0.5rem 0.75rem;
        }

        .navbar-toggler:focus {
            box-shadow: 0 0 0 0.25rem rgba(255, 255, 255, 0.25);
        }

        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255, 255, 255, 1%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }

        .dropdown-menu {
            border: none;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            border-radius: 0.75rem;
            padding: 0.5rem;
            margin-top: 0.5rem;
        }

        .dropdown-item {
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            transition: all 0.2s ease;
            margin-bottom: 0.25rem;
        }

        .dropdown-item:hover {
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            color: white;
            transform: translateX(5px);
        }

        .dropdown-item i {
            width: 20px;
            text-align: center;
        }

        .btn-register {
            background: white !important;
            color: var(--primary-color) !important;
            border: 2px solid white;
            padding: 0.5rem 1.25rem !important;
            font-weight: 600;
            border-radius: 0.5rem;
            margin-left: 0.5rem;
            transition: all 0.3s ease;
        }

        .btn-register:hover {
            background: rgba(255, 255, 255, 0.9) !important;
            color: var(--secondary-color) !important;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 255, 255, 0.3);
        }

        .btn-primary {
            background: var(--primary-color);
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(37, 99, 235, 0.3);
        }

        .card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .hero-section {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 5rem 0;
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--dark-color);
        }

        .section-subtitle {
            font-size: 1.1rem;
            color: var(--text-color);
            margin-bottom: 3rem;
        }

        .footer {
            background: var(--dark-color);
            color: white;
            padding: 3rem 0 1rem;
        }

        .footer h5 {
            color: var(--accent-color);
            margin-bottom: 1rem;
        }

        .footer a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer a:hover {
            color: var(--accent-color);
        }

        .social-links a {
            display: inline-block;
            width: 40px;
            height: 40px;
            background: var(--accent-color);
            color: white;
            text-align: center;
            line-height: 40px;
            border-radius: 50%;
            margin-right: 0.5rem;
            transition: all 0.3s ease;
        }

        .social-links a:hover {
            background: var(--primary-color);
            transform: translateY(-3px);
        }

        .user-avatar-nav {
            display: inline-flex;
            align-items: center;
        }

        .user-avatar-nav img {
            border: 2px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
        }

        .user-avatar-nav:hover img {
            border-color: white;
            transform: scale(1.1);
        }

        @media (max-width: 768px) {
            .section-title {
                font-size: 2rem;
            }

            .hero-section {
                padding: 3rem 0;
            }

            .navbar-brand {
                font-size: 1.5rem;
            }

            .navbar-brand img {
                height: 35px !important;
            }

            .navbar.scrolled .navbar-brand img {
                height: 30px !important;
            }

            .nav-link {
                padding: 0.75rem 1rem !important;
                margin: 0.25rem 0;
            }

            .navbar-nav {
                padding: 1rem 0;
            }
        }

        /* Animation au scroll */
        @media (min-width: 992px) {
            .navbar {
                transition: padding 0.3s ease, box-shadow 0.3s ease;
            }
        }
    </style>

    @yield('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <img src="{{ asset('images/logo.png') }}" alt="CareWell Logo" class="logo-img">
                <span>CareWell</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('services') }}">Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('articles') }}">Articles</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('about') }}">À propos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('contact') }}">Contact</a>
                    </li>
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt me-1"></i>Connexion
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link btn-register" href="{{ route('register') }}">
                                <i class="fas fa-user-plus me-1"></i>Inscription
                            </a>
                        </li>
                    @else
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                                <div class="user-avatar-nav me-2">
                                    @if(Auth::user()->photo)
                                        <img src="{{ asset('storage/' . Auth::user()->photo) }}" alt="Avatar" class="rounded-circle" width="32" height="32">
                                    @else
                                        <div class="bg-white text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-weight: 600; font-size: 0.875rem;">
                                            {{ strtoupper(substr(Auth::user()->first_name, 0, 1)) }}
                                        </div>
                                    @endif
                                </div>
                                <span>{{ Auth::user()->first_name }}</span>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('dashboard') }}">
                                    <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('profile') }}">
                                    <i class="fas fa-user-edit me-2"></i>Profil
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('appointments') }}">
                                    <i class="fas fa-calendar me-2"></i>Rendez-vous
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('medical-files') }}">
                                    <i class="fas fa-file-medical me-2"></i>Dossier médical
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="fas fa-sign-out-alt me-2"></i>Déconnexion
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- Messages Flash -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show m-3" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            {{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show m-3" role="alert">
            <i class="fas fa-info-circle me-2"></i>
            {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="mb-3">
                        <img src="{{ asset('images/logo.png') }}" alt="CareWell Logo" style="height: 50px; width: auto; filter: brightness(0) invert(1);">
                    </div>
                    <p>Votre plateforme de santé de confiance, connectant patients et professionnels de santé pour des soins optimaux.</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5>Services</h5>
                    <ul class="list-unstyled">
                        <li><a href="#">Consultations</a></li>
                        <li><a href="#">Examens</a></li>
                        <li><a href="#">Prescriptions</a></li>
                        <li><a href="#">Suivi médical</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5>Ressources</h5>
                    <ul class="list-unstyled">
                        <li><a href="#">Articles santé</a></li>
                        <li><a href="#">Conseils</a></li>
                        <li><a href="#">FAQ</a></li>
                        <li><a href="#">Support</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <h5>Contact</h5>
                    <p><i class="fas fa-map-marker-alt me-2"></i>123 Rue de la Santé, 75001 Paris</p>
                    <p><i class="fas fa-phone me-2"></i>+33 1 23 45 67 89</p>
                    <p><i class="fas fa-envelope me-2"></i>contact@carewell.fr</p>
                </div>
            </div>
            <hr class="my-4">
            <div class="row">
                <div class="col-md-6">
                    <p>&copy; {{ date('Y') }} CareWell. Tous droits réservés.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="#" class="me-3">Mentions légales</a>
                    <a href="#" class="me-3">Politique de confidentialité</a>
                    <a href="#">Conditions d'utilisation</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Effet de scroll sur la navbar
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Marquer le lien actif
        document.addEventListener('DOMContentLoaded', function() {
            const currentPath = window.location.pathname;
            const navLinks = document.querySelectorAll('.nav-link');
            
            navLinks.forEach(link => {
                if (link.getAttribute('href') === currentPath || 
                    (currentPath !== '/' && link.getAttribute('href') !== '/' && currentPath.startsWith(link.getAttribute('href')))) {
                    link.classList.add('active');
                }
            });
        });
    </script>

    @yield('scripts')
</body>
</html>
