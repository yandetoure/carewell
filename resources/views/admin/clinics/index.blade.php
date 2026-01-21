@extends('layouts.admin')

@section('title', 'Gestion des Cliniques - Admin')
@section('page-title', 'Gestion des Cliniques')
@section('page-subtitle', 'Gérer toutes les cliniques de la plateforme')
@section('user-role', 'Super Administrateur')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-building me-2"></i>
                        Liste des Cliniques
                    </h5>
                    <a href="{{ route('admin.clinics.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Nouvelle Clinique
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Statistiques rapides -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center">
                                    <h4 class="mb-1">{{ $clinics->total() }}</h4>
                                    <small>Total cliniques</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h4 class="mb-1">{{ $clinics->where('is_active', true)->count() }}</h4>
                                    <small>Cliniques actives</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <h4 class="mb-1">{{ $clinics->sum('users_count') }}</h4>
                                    <small>Total utilisateurs</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body text-center">
                                    <h4 class="mb-1">{{ $clinics->sum('appointments_count') }}</h4>
                                    <small>Total rendez-vous</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Logo</th>
                                    <th>Nom</th>
                                    <th>Contact</th>
                                    <th>Adresse</th>
                                    <th>Statistiques</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($clinics as $clinic)
                                <tr>
                                    <td>
                                        @if($clinic->logo)
                                            <img src="{{ asset('storage/' . $clinic->logo) }}" 
                                                 alt="{{ $clinic->name }}" 
                                                 class="rounded" 
                                                 style="width: 50px; height: 50px; object-fit: cover;">
                                        @else
                                            <div class="bg-secondary rounded d-flex align-items-center justify-content-center" 
                                                 style="width: 50px; height: 50px;">
                                                <i class="fas fa-building text-white"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $clinic->name }}</div>
                                        <small class="text-muted">{{ $clinic->email }}</small>
                                    </td>
                                    <td>
                                        <div><i class="fas fa-phone me-1"></i>{{ $clinic->phone_number }}</div>
                                        <small class="text-muted"><i class="fas fa-envelope me-1"></i>{{ $clinic->email }}</small>
                                    </td>
                                    <td>
                                        <div>{{ $clinic->address }}</div>
                                        <small class="text-muted">{{ $clinic->city }}, {{ $clinic->country }}</small>
                                    </td>
                                    <td>
                                        <div class="small">
                                            <div><i class="fas fa-users me-1"></i>{{ $clinic->users_count ?? 0 }} utilisateurs</div>
                                            <div><i class="fas fa-calendar me-1"></i>{{ $clinic->appointments_count ?? 0 }} RDV</div>
                                            <div><i class="fas fa-stethoscope me-1"></i>{{ $clinic->services_count ?? 0 }} services</div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($clinic->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('admin.clinics.show', $clinic) }}" 
                                               class="btn btn-outline-primary" 
                                               title="Voir les détails">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.clinics.edit', $clinic) }}" 
                                               class="btn btn-outline-warning" 
                                               title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button class="btn btn-outline-danger" 
                                                    onclick="deleteClinic({{ $clinic->id }})" 
                                                    title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-5">
                                        <i class="fas fa-building fa-3x mb-3"></i>
                                        <h5>Aucune clinique trouvée</h5>
                                        <p>Commencez par créer votre première clinique.</p>
                                        <a href="{{ route('admin.clinics.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus me-2"></i>Créer une clinique
                                        </a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($clinics->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $clinics->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function deleteClinic(clinicId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette clinique ? Cette action est irréversible et supprimera toutes les données associées.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/clinics/${clinicId}`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        
        form.appendChild(csrfToken);
        form.appendChild(methodField);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection

