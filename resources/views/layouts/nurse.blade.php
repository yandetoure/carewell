@extends('layouts.dashboard')

@section('title', 'Nurse Dashboard - CareWell')
@section('page-title', 'Nurse Dashboard')
@section('page-subtitle', 'Patient Care and Medical Management')
@section('user-role', 'Nurse')

@push('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('sidebar-content')
    <div class="nav-section">
        <div class="nav-section-title">Dashboard</div>
        <div class="nav-item">
            <a href="{{ route('nurse.dashboard') }}" class="nav-link {{ request()->routeIs('nurse.dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt"></i>
                <span>Overview</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('nurse.patients') }}" class="nav-link {{ request()->routeIs('nurse.patients*') ? 'active' : '' }}">
                <i class="fas fa-users"></i>
                <span>Patient Management</span>
            </a>
        </div>
    </div>

    <div class="nav-section">
        <div class="nav-section-title">Medical Care</div>
        <div class="nav-item">
            <a href="{{ route('nurse.medications') }}" class="nav-link {{ request()->routeIs('nurse.medications*') ? 'active' : '' }}">
                <i class="fas fa-pills"></i>
                <span>Medication Management</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('nurse.beds') }}" class="nav-link {{ request()->routeIs('nurse.beds*') ? 'active' : '' }}">
                <i class="fas fa-bed"></i>
                <span>Bed Management</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('nurse.patient-records') }}" class="nav-link {{ request()->routeIs('nurse.patient-records*') ? 'active' : '' }}">
                <i class="fas fa-notes-medical"></i>
                <span>Patient Records</span>
            </a>
        </div>
    </div>

    <div class="nav-section">
        <div class="nav-section-title">Monitoring</div>
        <div class="nav-item">
            <a href="{{ route('nurse.vital-signs') }}" class="nav-link {{ request()->routeIs('nurse.vital-signs*') ? 'active' : '' }}">
                <i class="fas fa-heartbeat"></i>
                <span>Vital Signs</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('nurse.appointments') }}" class="nav-link {{ request()->routeIs('nurse.appointments*') ? 'active' : '' }}">
                <i class="fas fa-calendar-check"></i>
                <span>Appointments</span>
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
        <div class="nav-section-title">Settings</div>
        <div class="nav-item">
            <a href="{{ route('nurse.profile') }}" class="nav-link {{ request()->routeIs('nurse.profile*') ? 'active' : '' }}">
                <i class="fas fa-user-edit"></i>
                <span>Profile</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('nurse.settings') }}" class="nav-link {{ request()->routeIs('nurse.settings*') ? 'active' : '' }}">
                <i class="fas fa-cog"></i>
                <span>Settings</span>
            </a>
        </div>
    </div>
@endsection
