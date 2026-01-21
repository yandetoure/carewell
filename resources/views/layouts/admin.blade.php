@extends('layouts.dashboard')

@php
    $user = auth()->user();
    $isSuperAdmin = $user->hasRole('Super Admin');
    $hasSelectedClinic = session('selected_clinic_id');
    $selectedClinic = $hasSelectedClinic ? \App\Models\Clinic::find(session('selected_clinic_id')) : null;
@endphp

@section('title', $isSuperAdmin && !$hasSelectedClinic ? 'Super Administrateur - CareWell' : 'Dashboard Administrateur - CareWell')
@section('page-title', $isSuperAdmin && !$hasSelectedClinic ? 'Super Administrateur' : 'Dashboard Administrateur')
@section('page-subtitle', $isSuperAdmin && !$hasSelectedClinic ? 'Gestion des cliniques' : 'Gestion complète de la plateforme')
@section('user-role', $isSuperAdmin ? 'Super Administrateur' : 'Administrateur')

@section('sidebar-content')
    @if($isSuperAdmin && !$hasSelectedClinic)
        {{-- Sidebar simplifiée pour Super Admin sans clinique sélectionnée --}}
        <div class="nav-section">
            <div class="nav-section-title">Gestion des Cliniques</div>
            <div class="nav-item">
                <a href="{{ route('admin.clinics.select') }}" class="nav-link {{ request()->routeIs('admin.clinics.select') ? 'active' : '' }}">
                    <i class="fas fa-building"></i>
                    <span>Sélectionner une clinique</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ route('admin.clinics.index') }}" class="nav-link {{ request()->routeIs('admin.clinics.index') || (request()->routeIs('admin.clinics.*') && !request()->routeIs('admin.clinics.select')) ? 'active' : '' }}">
                    <i class="fas fa-list"></i>
                    <span>Liste des cliniques</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ route('admin.clinics.create') }}" class="nav-link {{ request()->routeIs('admin.clinics.create') ? 'active' : '' }}">
                    <i class="fas fa-plus-circle"></i>
                    <span>Créer une clinique</span>
                </a>
            </div>
        </div>

        <div class="nav-section">
            <div class="nav-section-title">Système</div>
            <div class="nav-item">
                <a href="{{ route('admin.settings') }}" class="nav-link {{ request()->routeIs('admin.settings*') ? 'active' : '' }}">
                    <i class="fas fa-cog"></i>
                    <span>Paramètres</span>
                </a>
            </div>
        </div>
    @else
        {{-- Sidebar complète Admin (Super Admin avec clinique sélectionnée ou Admin normal) --}}
        @if($isSuperAdmin && $selectedClinic)
            <div class="nav-section">
                <div class="nav-section-title">Clinique Active</div>
                <div class="nav-item" style="padding: 0.75rem 1.5rem; background: rgba(255, 255, 255, 0.1); border-radius: 0.5rem; margin: 0.5rem 1rem;">
                    <div style="font-size: 0.75rem; color: rgba(255, 255, 255, 0.7); margin-bottom: 0.25rem;">Clinique active :</div>
                    <div style="font-weight: 600; font-size: 0.875rem;">{{ $selectedClinic->name }}</div>
                    <div class="mt-2 d-flex gap-1">
                        <a href="{{ route('admin.clinics.select') }}" style="background: rgba(255, 255, 255, 0.2); border: none; color: white; padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-size: 0.75rem; cursor: pointer; text-decoration: none; flex: 1; text-align: center;">
                            <i class="fas fa-exchange-alt"></i> Changer
                        </a>
                        <form action="{{ route('admin.clinics.clear-selected') }}" method="POST" style="flex: 1;">
                            @csrf
                            <button type="submit" style="background: rgba(255, 255, 255, 0.2); border: none; color: white; padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-size: 0.75rem; cursor: pointer; width: 100%;">
                                <i class="fas fa-times"></i> Vue globale
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endif

        <div class="nav-section">
            <div class="nav-section-title">Tableau de bord</div>
            <div class="nav-item">
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Vue d'ensemble</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ route('admin.statistics') }}" class="nav-link {{ request()->routeIs('admin.statistics') ? 'active' : '' }}">
                    <i class="fas fa-chart-bar"></i>
                    <span>Statistiques</span>
                </a>
            </div>
        </div>

    <div class="nav-section">
        <div class="nav-section-title">Gestion utilisateurs</div>
        <div class="nav-item">
            <a href="{{ route('admin.users') }}" class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                <i class="fas fa-users"></i>
                <span>Utilisateurs</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('admin.roles') }}" class="nav-link {{ request()->routeIs('admin.roles*') ? 'active' : '' }}">
                <i class="fas fa-user-shield"></i>
                <span>Rôles & Permissions</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('admin.doctors') }}" class="nav-link {{ request()->routeIs('admin.doctors*') ? 'active' : '' }}">
                <i class="fas fa-user-md"></i>
                <span>Médecins</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('admin.patients') }}" class="nav-link {{ request()->routeIs('admin.patients*') ? 'active' : '' }}">
                <i class="fas fa-user-injured"></i>
                <span>Patients</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('admin.secretaries') }}" class="nav-link {{ request()->routeIs('admin.secretaries*') ? 'active' : '' }}">
                <i class="fas fa-user-tie"></i>
                <span>Secrétaires</span>
            </a>
        </div>
    </div>

    <div class="nav-section">
        <div class="nav-section-title">Services & Contenu</div>
        <div class="nav-item">
            <a href="{{ route('admin.services') }}" class="nav-link {{ request()->routeIs('admin.services*') ? 'active' : '' }}">
                <i class="fas fa-stethoscope"></i>
                <span>Services médicaux</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('admin.articles') }}" class="nav-link {{ request()->routeIs('admin.articles*') ? 'active' : '' }}">
                <i class="fas fa-newspaper"></i>
                <span>Articles santé</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('admin.categories') }}" class="nav-link {{ request()->routeIs('admin.categories*') ? 'active' : '' }}">
                <i class="fas fa-tags"></i>
                <span>Catégories</span>
            </a>
        </div>
    </div>

    <div class="nav-section">
        <div class="nav-section-title">Rendez-vous</div>
        <div class="nav-item">
            <a href="{{ route('admin.appointments') }}" class="nav-link {{ request()->routeIs('admin.appointments*') ? 'active' : '' }}">
                <i class="fas fa-calendar-alt"></i>
                <span>Tous les RDV</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('admin.schedule') }}" class="nav-link {{ request()->routeIs('admin.schedule*') ? 'active' : '' }}">
                <i class="fas fa-calendar-week"></i>
                <span>Planning général</span>
            </a>
        </div>
    </div>

    <div class="nav-section">
        <div class="nav-section-title">Médical</div>
        <div class="nav-item">
            <a href="{{ route('admin.prescriptions') }}" class="nav-link {{ request()->routeIs('admin.prescriptions*') ? 'active' : '' }}">
                <i class="fas fa-file-medical"></i>
                <span>Soins médicaux</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('admin.ordonnances') }}" class="nav-link {{ request()->routeIs('admin.ordonnances*') ? 'active' : '' }}">
                <i class="fas fa-prescription-bottle-alt"></i>
                <span>Ordonnances</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('admin.exams') }}" class="nav-link {{ request()->routeIs('admin.exams*') ? 'active' : '' }}">
                <i class="fas fa-vials"></i>
                <span>Examens</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('admin.results') }}" class="nav-link {{ request()->routeIs('admin.results*') ? 'active' : '' }}">
                <i class="fas fa-file-medical-alt"></i>
                <span>Résultats</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('admin.pharmacy') }}" class="nav-link {{ request()->routeIs('admin.pharmacy*') ? 'active' : '' }}">
                <i class="fas fa-pills"></i>
                <span>Stock pharmacie</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('admin.beds') }}" class="nav-link {{ request()->routeIs('admin.beds*') ? 'active' : '' }}">
                <i class="fas fa-bed"></i>
                <span>Gestion des lits</span>
            </a>
        </div>
    </div>

    <div class="nav-section">
        <div class="nav-section-title">Finance</div>
        <div class="nav-item">
            <a href="{{ route('admin.accounting') }}" class="nav-link {{ request()->routeIs('admin.accounting') ? 'active' : '' }}">
                <i class="fas fa-calculator"></i>
                <span>Comptabilité</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('admin.tickets') }}" class="nav-link {{ request()->routeIs('admin.tickets*') ? 'active' : '' }}">
                <i class="fas fa-receipt"></i>
                <span>Tickets</span>
            </a>
        </div>
    </div>

    <div class="nav-section">
        <div class="nav-section-title">Système</div>
        <div class="nav-item">
            <a href="{{ route('admin.settings') }}" class="nav-link {{ request()->routeIs('admin.settings*') ? 'active' : '' }}">
                <i class="fas fa-cog"></i>
                <span>Paramètres</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('admin.logs') }}" class="nav-link {{ request()->routeIs('admin.logs*') ? 'active' : '' }}">
                <i class="fas fa-file-alt"></i>
                <span>Logs système</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('admin.backup') }}" class="nav-link {{ request()->routeIs('admin.backup*') ? 'active' : '' }}">
                <i class="fas fa-database"></i>
                <span>Sauvegardes</span>
            </a>
        </div>
    </div>

        <div class="nav-section">
            <div class="nav-section-title">Support</div>
            <div class="nav-item">
                <a href="{{ route('admin.faq') }}" class="nav-link {{ request()->routeIs('admin.faq*') ? 'active' : '' }}">
                    <i class="fas fa-question-circle"></i>
                    <span>FAQ</span>
                </a>
            </div>
        </div>
    @endif
@endsection
