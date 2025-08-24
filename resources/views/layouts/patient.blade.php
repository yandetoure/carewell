@extends('layouts.dashboard')

@section('title', 'Dashboard Patient - CareWell')
@section('page-title', 'Dashboard Patient')
@section('page-subtitle', 'Suivi de votre santé et gestion de vos rendez-vous')
@section('user-role', 'Patient')

@section('sidebar-content')
    <div class="nav-section">
        <div class="nav-section-title">Tableau de bord</div>
        <div class="nav-item">
            <a href="{{ route('patient.dashboard') }}" class="nav-link {{ request()->routeIs('patient.dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt"></i>
                <span>Vue d'ensemble</span>
            </a>
        </div>
    </div>

    <div class="nav-section">
        <div class="nav-section-title">Rendez-vous</div>
        <div class="nav-item">
            <a href="{{ route('patient.appointments') }}" class="nav-link {{ request()->routeIs('patient.appointments') ? 'active' : '' }}">
                <i class="fas fa-calendar-alt"></i>
                <span>Mes rendez-vous</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('patient.appointments.create') }}" class="nav-link {{ request()->routeIs('patient.appointments.create') ? 'active' : '' }}">
                <i class="fas fa-calendar-plus"></i>
                <span>Prendre RDV</span>
            </a>
        </div>
    </div>

    <div class="nav-section">
        <div class="nav-section-title">Dossier médical</div>
        <div class="nav-item">
            <a href="{{ route('patient.medical-file') }}" class="nav-link {{ request()->routeIs('patient.medical-file') ? 'active' : '' }}">
                <i class="fas fa-file-medical"></i>
                <span>Mon dossier médical</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('patient.prescriptions') }}" class="nav-link {{ request()->routeIs('patient.prescriptions') ? 'active' : '' }}">
                <i class="fas fa-pills"></i>
                <span>Mes prescriptions</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('patient.exams') }}" class="nav-link {{ request()->routeIs('patient.exams') ? 'active' : '' }}">
                <i class="fas fa-stethoscope"></i>
                <span>Mes examens</span>
            </a>
        </div>
    </div>

    <div class="nav-section">
        <div class="nav-section-title">Santé & Bien-être</div>
        <div class="nav-item">
            <a href="{{ route('patient.vital-signs') }}" class="nav-link {{ request()->routeIs('patient.vital-signs') ? 'active' : '' }}">
                <i class="fas fa-heart"></i>
                <span>Signes vitaux</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('patient.health-summary') }}" class="nav-link {{ request()->routeIs('patient.health-summary') ? 'active' : '' }}">
                <i class="fas fa-heartbeat"></i>
                <span>Résumé santé</span>
            </a>
        </div>
    </div>

    <div class="nav-section">
        <div class="nav-section-title">Services</div>
        <div class="nav-item">
            <a href="{{ route('patient.services') }}" class="nav-link {{ request()->routeIs('patient.services*') ? 'active' : '' }}">
                <i class="fas fa-stethoscope"></i>
                <span>Services disponibles</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('patient.articles') }}" class="nav-link {{ request()->routeIs('patient.articles*') ? 'active' : '' }}">
                <i class="fas fa-newspaper"></i>
                <span>Articles santé</span>
            </a>
        </div>
    </div>

    <div class="nav-section">
        <div class="nav-section-title">Paramètres</div>
        <div class="nav-item">
            <a href="{{ route('profile') }}" class="nav-link {{ request()->routeIs('profile') ? 'active' : '' }}">
                <i class="fas fa-user-edit"></i>
                <span>Mon profil</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('contact') }}" class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}">
                <i class="fas fa-phone"></i>
                <span>Contact</span>
            </a>
        </div>
    </div>
@endsection
