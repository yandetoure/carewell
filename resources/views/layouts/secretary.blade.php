@extends('layouts.dashboard')

@section('title', 'Dashboard Secrétariat - CareWell')
@section('page-title', 'Dashboard Secrétariat')
@section('page-subtitle', 'Gestion des rendez-vous et accueil des patients')
@section('user-role', 'Secrétaire')

@section('sidebar-content')
    <div class="nav-section">
        <div class="nav-section-title">Tableau de bord</div>
        <div class="nav-item">
            <a href="{{ route('secretary.dashboard') }}" class="nav-link {{ request()->routeIs('secretary.dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt"></i>
                <span>Vue d'ensemble</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('secretary.statistics') }}" class="nav-link {{ request()->routeIs('secretary.statistics') ? 'active' : '' }}">
                <i class="fas fa-chart-bar"></i>
                <span>Statistiques</span>
            </a>
        </div>
    </div>

    <div class="nav-section">
        <div class="nav-section-title">Rendez-vous</div>
        <div class="nav-item">
            <a href="{{ route('secretary.appointments') }}" class="nav-link {{ request()->routeIs('secretary.appointments*') ? 'active' : '' }}">
                <i class="fas fa-calendar-alt"></i>
                <span>Tous les RDV</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('secretary.appointments.today') }}" class="nav-link {{ request()->routeIs('secretary.appointments.today') ? 'active' : '' }}">
                <i class="fas fa-calendar-day"></i>
                <span>RDV aujourd'hui</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('secretary.appointments.week') }}" class="nav-link {{ request()->routeIs('secretary.appointments.week') ? 'active' : '' }}">
                <i class="fas fa-calendar-week"></i>
                <span>Cette semaine</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('secretary.appointments.create') }}" class="nav-link {{ request()->routeIs('secretary.appointments.create') ? 'active' : '' }}">
                <i class="fas fa-calendar-plus"></i>
                <span>Nouveau RDV</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('secretary.schedule') }}" class="nav-link {{ request()->routeIs('secretary.schedule*') ? 'active' : '' }}">
                <i class="fas fa-calendar-check"></i>
                <span>Planning général</span>
            </a>
        </div>
    </div>

    <div class="nav-section">
        <div class="nav-section-title">Patients</div>
        <div class="nav-item">
            <a href="{{ route('secretary.patients') }}" class="nav-link {{ request()->routeIs('secretary.patients*') ? 'active' : '' }}">
                <i class="fas fa-users"></i>
                <span>Liste des patients</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('secretary.patients.new') }}" class="nav-link {{ request()->routeIs('secretary.patients.new') ? 'active' : '' }}">
                <i class="fas fa-user-plus"></i>
                <span>Nouveau patient</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('secretary.patients.search') }}" class="nav-link {{ request()->routeIs('secretary.patients.search') ? 'active' : '' }}">
                <i class="fas fa-search"></i>
                <span>Rechercher patient</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('secretary.medical-files') }}" class="nav-link {{ request()->routeIs('secretary.medical-files*') ? 'active' : '' }}">
                <i class="fas fa-file-medical"></i>
                <span>Dossiers médicaux</span>
            </a>
        </div>
    </div>

    <div class="nav-section">
        <div class="nav-section-title">Médecins</div>
        <div class="nav-item">
            <a href="{{ route('secretary.doctors') }}" class="nav-link {{ request()->routeIs('secretary.doctors*') ? 'active' : '' }}">
                <i class="fas fa-user-md"></i>
                <span>Liste des médecins</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('secretary.doctors.availability') }}" class="nav-link {{ request()->routeIs('secretary.doctors.availability*') ? 'active' : '' }}">
                <i class="fas fa-clock"></i>
                <span>Disponibilités</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('secretary.doctors.schedule') }}" class="nav-link {{ request()->routeIs('secretary.doctors.schedule*') ? 'active' : '' }}">
                <i class="fas fa-calendar-week"></i>
                <span>Planning médecins</span>
            </a>
        </div>
    </div>

    <div class="nav-section">
        <div class="nav-section-title">Services</div>
        <div class="nav-item">
            <a href="{{ route('secretary.services') }}" class="nav-link {{ request()->routeIs('secretary.services*') ? 'active' : '' }}">
                <i class="fas fa-stethoscope"></i>
                <span>Services disponibles</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('secretary.services.categories') }}" class="nav-link {{ request()->routeIs('secretary.services.categories*') ? 'active' : '' }}">
                <i class="fas fa-tags"></i>
                <span>Catégories</span>
            </a>
        </div>
    </div>

    <div class="nav-section">
        <div class="nav-section-title">Accueil</div>
        <div class="nav-item">
            <a href="{{ route('secretary.reception') }}" class="nav-link {{ request()->routeIs('secretary.reception*') ? 'active' : '' }}">
                <i class="fas fa-door-open"></i>
                <span>Accueil patients</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('secretary.waiting-list') }}" class="nav-link {{ request()->routeIs('secretary.waiting-list*') ? 'active' : '' }}">
                <i class="fas fa-list-ol"></i>
                <span>Liste d'attente</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('secretary.check-in') }}" class="nav-link {{ request()->routeIs('secretary.check-in*') ? 'active' : '' }}">
                <i class="fas fa-check-circle"></i>
                <span>Check-in patients</span>
            </a>
        </div>
    </div>

    <div class="nav-section">
        <div class="nav-section-title">Communication</div>
        <div class="nav-item">
            <a href="{{ route('secretary.messages') }}" class="nav-link {{ request()->routeIs('secretary.messages*') ? 'active' : '' }}">
                <i class="fas fa-envelope"></i>
                <span>Messages</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('secretary.notifications') }}" class="nav-link {{ request()->routeIs('secretary.notifications*') ? 'active' : '' }}">
                <i class="fas fa-bell"></i>
                <span>Notifications</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('secretary.reminders') }}" class="nav-link {{ request()->routeIs('secretary.reminders*') ? 'active' : '' }}">
                <i class="fas fa-phone"></i>
                <span>Rappels RDV</span>
            </a>
        </div>
    </div>

    <div class="nav-section">
        <div class="nav-section-title">Administration</div>
        <div class="nav-item">
            <a href="{{ route('secretary.reports') }}" class="nav-link {{ request()->routeIs('secretary.reports*') ? 'active' : '' }}">
                <i class="fas fa-file-alt"></i>
                <span>Rapports</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('secretary.billing') }}" class="nav-link {{ request()->routeIs('secretary.billing*') ? 'active' : '' }}">
                <i class="fas fa-receipt"></i>
                <span>Facturation</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('secretary.inventory') }}" class="nav-link {{ request()->routeIs('secretary.inventory*') ? 'active' : '' }}">
                <i class="fas fa-boxes"></i>
                <span>Inventaire</span>
            </a>
        </div>
    </div>

    <div class="nav-section">
        <div class="nav-section-title">Paramètres</div>
        <div class="nav-item">
            <a href="{{ route('secretary.profile') }}" class="nav-link {{ request()->routeIs('secretary.profile*') ? 'active' : '' }}">
                <i class="fas fa-user-edit"></i>
                <span>Mon profil</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('secretary.settings') }}" class="nav-link {{ request()->routeIs('secretary.settings*') ? 'active' : '' }}">
                <i class="fas fa-cog"></i>
                <span>Paramètres</span>
            </a>
        </div>
    </div>
@endsection
