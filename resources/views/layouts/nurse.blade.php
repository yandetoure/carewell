@extends('layouts.dashboard')

@section('title', 'Tableau de Bord Infirmière - CareWell')
@section('page-title', 'Tableau de Bord Infirmière')
@section('page-subtitle', 'Soins aux Patients et Gestion Médicale')
@section('user-role', 'Infirmière')

@push('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('sidebar-content')
    <div class="nav-section">
        <div class="nav-section-title">Tableau de Bord</div>
        <div class="nav-item">
            <a href="{{ route('nurse.dashboard') }}" class="nav-link {{ request()->routeIs('nurse.dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt"></i>
                <span>Aperçu</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('nurse.patients') }}" class="nav-link {{ request()->routeIs('nurse.patients*') ? 'active' : '' }}">
                <i class="fas fa-users"></i>
                <span>Gestion des Patients</span>
            </a>
        </div>
    </div>

    <div class="nav-section">
        <div class="nav-section-title">Soins Médicaux</div>
        <div class="nav-item">
            <a href="{{ route('nurse.medications') }}" class="nav-link {{ request()->routeIs('nurse.medications*') ? 'active' : '' }}">
                <i class="fas fa-pills"></i>
                <span>Gestion des Médicaments</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('nurse.beds') }}" class="nav-link {{ request()->routeIs('nurse.beds*') ? 'active' : '' }}">
                <i class="fas fa-bed"></i>
                <span>Gestion des Lits</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('nurse.patient-records') }}" class="nav-link {{ request()->routeIs('nurse.patient-records*') ? 'active' : '' }}">
                <i class="fas fa-notes-medical"></i>
                <span>Dossiers des Patients</span>
            </a>
        </div>
    </div>

    <div class="nav-section">
        <div class="nav-section-title">Surveillance</div>
        <div class="nav-item">
            <a href="{{ route('nurse.vital-signs') }}" class="nav-link {{ request()->routeIs('nurse.vital-signs*') ? 'active' : '' }}">
                <i class="fas fa-heartbeat"></i>
                <span>Signes Vitaux</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('nurse.appointments') }}" class="nav-link {{ request()->routeIs('nurse.appointments*') ? 'active' : '' }}">
                <i class="fas fa-calendar-check"></i>
                <span>Rendez-vous</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('nurse.prescriptions') }}" class="nav-link {{ request()->routeIs('nurse.prescriptions*') ? 'active' : '' }}">
                <i class="fas fa-clipboard-list"></i>
                <span>Prescriptions</span>
            </a>
        </div>
    </div>

    <div class="nav-section">
        <div class="nav-section-title">Paramètres</div>
        <div class="nav-item">
            <a href="{{ route('nurse.profile') }}" class="nav-link {{ request()->routeIs('nurse.profile*') ? 'active' : '' }}">
                <i class="fas fa-user-edit"></i>
                <span>Profil</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('nurse.settings') }}" class="nav-link {{ request()->routeIs('nurse.settings*') ? 'active' : '' }}">
                <i class="fas fa-cog"></i>
                <span>Paramètres</span>
            </a>
        </div>
    </div>
@endsection
