@extends('layouts.admin')

@section('title', 'Modifier Lit - Admin')
@section('page-title', 'Modifier le Lit')
@section('page-subtitle', 'Modifier les informations du lit d\'hospitalisation')
@section('user-role', 'Administrateur')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-bed me-2"></i>
                        Modifier les informations du lit {{ $bed['number'] }}
                    </h5>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('admin.beds.update', $bedModel->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <!-- Informations générales -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-info-circle me-2"></i>Informations générales
                                </h6>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="bed_number" class="form-label">Numéro du lit *</label>
                                    <input type="text" class="form-control" id="bed_number" name="bed_number" 
                                           value="{{ old('bed_number', $bed['number']) }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="room_number" class="form-label">Numéro de chambre *</label>
                                    <input type="text" class="form-control" id="room_number" name="room_number" 
                                           value="{{ old('room_number', $bed['room']) }}" required>
                                </div>
                            </div>
                        </div>

                        <!-- Informations du service -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-stethoscope me-2"></i>Informations du service
                                </h6>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="service_id" class="form-label">Service</label>
                                    <select class="form-select" id="service_id" name="service_id">
                                        <option value="">Sélectionner un service</option>
                                        @foreach($services as $service)
                                            <option value="{{ $service->id }}" {{ old('service_id', $bed['service_id']) == $service->id ? 'selected' : '' }}>
                                                {{ $service->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="bed_type" class="form-label">Type de lit *</label>
                                    <select class="form-select" id="bed_type" name="bed_type" required>
                                        <option value="">Sélectionner un type</option>
                                        <option value="standard" {{ old('bed_type', $bed['bed_type']) === 'standard' ? 'selected' : '' }}>Standard</option>
                                        <option value="premium" {{ old('bed_type', $bed['bed_type']) === 'premium' ? 'selected' : '' }}>Premium</option>
                                        <option value="vip" {{ old('bed_type', $bed['bed_type']) === 'vip' ? 'selected' : '' }}>VIP</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Statut et disponibilité -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-toggle-on me-2"></i>Statut et disponibilité
                                </h6>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Statut *</label>
                                    <select class="form-select" id="status" name="status" required>
                                        <option value="libre" {{ old('status', $bed['status']) === 'libre' ? 'selected' : '' }}>Libre</option>
                                        <option value="occupe" {{ old('status', $bed['status']) === 'occupe' ? 'selected' : '' }}>Occupé</option>
                                        <option value="maintenance" {{ old('status', $bed['status']) === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                        <option value="admission_impossible" {{ old('status', $bed['status']) === 'admission_impossible' ? 'selected' : '' }}>Admission impossible</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-sticky-note me-2"></i>Notes
                                </h6>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="notes" class="form-label">Notes</label>
                                    <textarea class="form-control" id="notes" name="notes" rows="4" 
                                              placeholder="Notes particulières sur ce lit...">{{ old('notes', $bed['notes'] ?? '') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Boutons d'action -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('admin.beds.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left me-2"></i>Retour à la liste
                                    </a>
                                    <div>
                                        <button type="button" class="btn btn-outline-danger me-2" onclick="deleteBed({{ $bedModel->id }})">
                                            <i class="fas fa-trash me-2"></i>Supprimer
                                        </button>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save me-2"></i>Enregistrer les modifications
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function deleteBed(bedId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce lit ? Cette action est irréversible.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/beds/${bedId}`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        form.appendChild(csrfToken);
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        form.appendChild(methodField);
        
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection
