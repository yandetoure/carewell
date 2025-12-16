@extends('layouts.doctor')

@section('title', 'Discussions entre Médecins - Docteur')
@section('page-title', 'Discussions entre Médecins')
@section('page-subtitle', 'Communication avec les médecins de la clinique')
@section('user-role', 'Médecin')

@section('content')
<div class="container-fluid py-4">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Statistiques des discussions -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-primary">
                            <i class="fas fa-user-md text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $totalDiscussions }}</h4>
                            <p class="text-muted mb-0">Discussions actives</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-warning">
                            <i class="fas fa-exclamation-circle text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $unreadDiscussions }}</h4>
                            <p class="text-muted mb-0">Non lues</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-info">
                            <i class="fas fa-calendar-check text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $todayDiscussions }}</h4>
                            <p class="text-muted mb-0">Aujourd'hui</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-success">
                            <i class="fas fa-users text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $allDoctors->count() }}</h4>
                            <p class="text-muted mb-0">Médecins disponibles</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-user-md me-2"></i>Discussions entre médecins
                        </h5>
                        <div class="d-flex gap-2">
                            <a href="{{ route('doctor.messages') }}" class="btn btn-outline-primary">
                                <i class="fas fa-envelope me-2"></i>Messages patients
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des médecins disponibles -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-users me-2"></i>Médecins de la clinique
                    </h5>
                </div>
                <div class="card-body">
                    @if($allDoctors->count() > 0)
                        <div class="row">
                            @foreach($allDoctors as $otherDoctor)
                                @php
                                    // Vérifier si une discussion existe avec ce médecin
                                    $existingDiscussion = collect($result)->firstWhere('doctor_id', $otherDoctor->id);
                                @endphp
                                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                                    <div class="card h-100 doctor-card" style="cursor: pointer; transition: all 0.3s ease;" 
                                         onclick="window.location.href='{{ route('doctor.discussions.chat', $otherDoctor->id) }}'">
                                        <div class="card-body text-center">
                                            <div class="position-relative d-inline-block mb-3">
                                                @if($otherDoctor->photo)
                                                    <img src="{{ asset('storage/' . $otherDoctor->photo) }}" 
                                                         alt="Photo" 
                                                         class="rounded-circle" 
                                                         width="100" 
                                                         height="100"
                                                         style="object-fit: cover;">
                                                @else
                                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto" 
                                                         style="width: 100px; height: 100px;">
                                                        <i class="fas fa-user-md fa-3x"></i>
                                                    </div>
                                                @endif
                                                @if($existingDiscussion && $existingDiscussion['unread_count'] > 0)
                                                    <span class="badge bg-danger position-absolute top-0 start-100 translate-middle rounded-pill">
                                                        {{ $existingDiscussion['unread_count'] }}
                                                    </span>
                                                @endif
                                            </div>
                                            <h6 class="mb-1 fw-bold">
                                                Dr. {{ $otherDoctor->first_name }} {{ $otherDoctor->last_name }}
                                            </h6>
                                            <p class="text-muted small mb-2">
                                                <i class="fas fa-stethoscope me-1"></i>
                                                {{ $otherDoctor->service->name ?? 'Médecin' }}
                                            </p>
                                            @if($existingDiscussion)
                                                <div class="mb-2">
                                                    <small class="text-muted d-block">
                                                        <i class="fas fa-clock me-1"></i>
                                                        Dernier message: {{ $existingDiscussion['last_message_date'] }} à {{ $existingDiscussion['last_message_time'] }}
                                                    </small>
                                                    <small class="text-muted d-block mt-1">
                                                        {{ Str::limit($existingDiscussion['last_message'], 50) }}
                                                    </small>
                                                </div>
                                                <a href="{{ route('doctor.discussions.chat', $otherDoctor->id) }}" 
                                                   class="btn btn-sm btn-primary w-100"
                                                   onclick="event.stopPropagation();">
                                                    <i class="fas fa-comments me-1"></i>
                                                    {{ $existingDiscussion['unread_count'] > 0 ? 'Continuer (' . $existingDiscussion['unread_count'] . ')' : 'Continuer' }}
                                                </a>
                                            @else
                                                <a href="{{ route('doctor.discussions.chat', $otherDoctor->id) }}" 
                                                   class="btn btn-sm btn-outline-primary w-100"
                                                   onclick="event.stopPropagation();">
                                                    <i class="fas fa-comments me-1"></i>Démarrer une discussion
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-user-md fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">Aucun autre médecin disponible</h5>
                            <p class="text-muted">Il n'y a pas d'autres médecins dans la clinique pour le moment.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
}

.doctor-card {
    border: 2px solid transparent;
    transition: all 0.3s ease;
}

.doctor-card:hover {
    border-color: #0d6efd;
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 2rem 0 rgba(13, 110, 253, 0.2);
}

.card {
    border: none;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
}
</style>
@endpush

