@extends('layouts.dashboard')

@section('title', 'Dashboard Médecin - CareWell')
@section('page-title', 'Dashboard Médecin')
@section('page-subtitle', 'Gestion de vos patients et consultations')
@section('user-role', 'Médecin')

@push('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('sidebar-content')
    <div class="nav-section">
        <div class="nav-section-title">Tableau de bord</div>
        <div class="nav-item">
            <a href="{{ route('doctor.dashboard') }}" class="nav-link {{ request()->routeIs('doctor.dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt"></i>
                <span>Vue d'ensemble</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('doctor.statistics') }}" class="nav-link {{ request()->routeIs('doctor.statistics') ? 'active' : '' }}">
                <i class="fas fa-chart-line"></i>
                <span>Mes statistiques</span>
            </a>
        </div>
    </div>

    <div class="nav-section">
        <div class="nav-section-title">Patients</div>
        <div class="nav-item">
            <a href="{{ route('doctor.patients') }}" class="nav-link {{ request()->routeIs('doctor.patients*') ? 'active' : '' }}">
                <i class="fas fa-users"></i>
                <span>Mes patients</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('doctor.patients.new') }}" class="nav-link {{ request()->routeIs('doctor.patients.new') ? 'active' : '' }}">
                <i class="fas fa-user-plus"></i>
                <span>Nouveau patient</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('doctor.medical-files') }}" class="nav-link {{ request()->routeIs('doctor.medical-files*') ? 'active' : '' }}">
                <i class="fas fa-file-medical"></i>
                <span>Dossiers médicaux</span>
            </a>
        </div>
    </div>

    <div class="nav-section">
        <div class="nav-section-title">Rendez-vous</div>
        <div class="nav-item">
            <a href="{{ route('doctor.appointments') }}" class="nav-link {{ request()->routeIs('doctor.appointments*') ? 'active' : '' }}">
                <i class="fas fa-calendar-alt"></i>
                <span>Mes rendez-vous</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('doctor.appointments.today') }}" class="nav-link {{ request()->routeIs('doctor.appointments.today') ? 'active' : '' }}">
                <i class="fas fa-calendar-day"></i>
                <span>RDV aujourd'hui</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('doctor.appointments.week') }}" class="nav-link {{ request()->routeIs('doctor.appointments.week') ? 'active' : '' }}">
                <i class="fas fa-calendar-week"></i>
                <span>Cette semaine</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('doctor.availability') }}" class="nav-link {{ request()->routeIs('doctor.availability*') ? 'active' : '' }}">
                <i class="fas fa-clock"></i>
                <span>Mes disponibilités</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('doctor.calendar') }}" class="nav-link {{ request()->routeIs('doctor.calendar*') ? 'active' : '' }}">
                <i class="fas fa-calendar-alt"></i>
                <span>Mon calendrier</span>
            </a>
        </div>
    </div>

    <div class="nav-section">
        <div class="nav-section-title">Consultations</div>
        <div class="nav-item">
            <a href="{{ route('doctor.consultations') }}" class="nav-link {{ request()->routeIs('doctor.consultations*') ? 'active' : '' }}">
                <i class="fas fa-stethoscope"></i>
                <span>Consultations</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('doctor.prescriptions') }}" class="nav-link {{ request()->routeIs('doctor.prescriptions*') ? 'active' : '' }}">
                <i class="fas fa-pills"></i>
                <span>Prescriptions</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('doctor.exams') }}" class="nav-link {{ request()->routeIs('doctor.exams*') ? 'active' : '' }}">
                <i class="fas fa-flask"></i>
                <span>Examens</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('doctor.results') }}" class="nav-link {{ request()->routeIs('doctor.results*') ? 'active' : '' }}">
                <i class="fas fa-clipboard-list"></i>
                <span>Résultats</span>
            </a>
        </div>
    </div>

    <div class="nav-section">
        <div class="nav-section-title">Suivi médical</div>
        <div class="nav-item">
            <a href="{{ route('doctor.medical-history') }}" class="nav-link {{ request()->routeIs('doctor.medical-history*') ? 'active' : '' }}">
                <i class="fas fa-history"></i>
                <span>Historique médical</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('doctor.notes') }}" class="nav-link {{ request()->routeIs('doctor.notes*') ? 'active' : '' }}">
                <i class="fas fa-edit"></i>
                <span>Notes de consultation</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('doctor.follow-up') }}" class="nav-link {{ request()->routeIs('doctor.follow-up*') ? 'active' : '' }}">
                <i class="fas fa-bed"></i>
                <span>Patients hospitalisés</span>
            </a>
        </div>
    </div>

    <div class="nav-section">
        <div class="nav-section-title">Communication</div>
        <div class="nav-item">
            <a href="{{ route('doctor.messages') }}" class="nav-link {{ request()->routeIs('doctor.messages*') ? 'active' : '' }}">
                <i class="fas fa-envelope"></i>
                <span>Messages</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('doctor.notifications') }}" class="nav-link {{ request()->routeIs('doctor.notifications*') ? 'active' : '' }}">
                <i class="fas fa-bell"></i>
                <span>Notifications</span>
            </a>
        </div>
    </div>

    <div class="nav-section">
        <div class="nav-section-title">Paramètres</div>
        <div class="nav-item">
            <a href="{{ route('doctor.profile') }}" class="nav-link {{ request()->routeIs('doctor.profile*') ? 'active' : '' }}">
                <i class="fas fa-user-edit"></i>
                <span>Mon profil</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('doctor.settings') }}" class="nav-link {{ request()->routeIs('doctor.settings*') ? 'active' : '' }}">
                <i class="fas fa-cog"></i>
                <span>Paramètres</span>
            </a>
        </div>
    </div>
@endsection
