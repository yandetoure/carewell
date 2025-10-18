@extends('layouts.doctor')

@section('title', 'Nouvelle Absence - Docteur')
@section('page-title', 'Nouvelle Absence')
@section('page-subtitle', 'Planifier une absence ou un congé')
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

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-calendar-times me-2"></i>Informations de l'absence
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('doctor.calendar.store-absence') }}" method="POST">
                        @csrf
                        
                        <!-- Titre et description -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-info-circle me-2"></i>Informations générales
                                </h6>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="title" class="form-label">Titre <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                           id="title" name="title" 
                                           value="{{ old('title') }}" 
                                           placeholder="Ex: Congé annuel, Formation médicale..." required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="type" class="form-label">Type d'absence <span class="text-danger">*</span></label>
                                    <select class="form-select @error('type') is-invalid @enderror" 
                                            id="type" name="type" required>
                                        <option value="">Sélectionner un type...</option>
                                        <option value="congé" {{ old('type') == 'congé' ? 'selected' : '' }}>Congé</option>
                                        <option value="formation" {{ old('type') == 'formation' ? 'selected' : '' }}>Formation</option>
                                        <option value="maladie" {{ old('type') == 'maladie' ? 'selected' : '' }}>Maladie</option>
                                        <option value="personnel" {{ old('type') == 'personnel' ? 'selected' : '' }}>Personnel</option>
                                        <option value="autre" {{ old('type') == 'autre' ? 'selected' : '' }}>Autre</option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-12">
                                <div class="form-group mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="3" 
                                              placeholder="Description détaillée de l'absence...">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Dates -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-calendar-alt me-2"></i>Période d'absence
                                </h6>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="start_date" class="form-label">Date de début <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('start_date') is-invalid @enderror" 
                                           id="start_date" name="start_date" 
                                           value="{{ old('start_date') }}" 
                                           min="{{ date('Y-m-d') }}" required>
                                    @error('start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="end_date" class="form-label">Date de fin <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('end_date') is-invalid @enderror" 
                                           id="end_date" name="end_date" 
                                           value="{{ old('end_date') }}" 
                                           min="{{ date('Y-m-d') }}" required>
                                    @error('end_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Type d'absence -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-clock me-2"></i>Type d'absence
                                </h6>
                            </div>
                            
                            <div class="col-12">
                                <div class="form-group mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_full_day" name="is_full_day" value="1" 
                                               {{ old('is_full_day', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_full_day">
                                            Absence toute la journée
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6" id="start_time_group" style="display: none;">
                                <div class="form-group mb-3">
                                    <label for="start_time" class="form-label">Heure de début</label>
                                    <input type="time" class="form-control @error('start_time') is-invalid @enderror" 
                                           id="start_time" name="start_time" 
                                           value="{{ old('start_time') }}">
                                    @error('start_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6" id="end_time_group" style="display: none;">
                                <div class="form-group mb-3">
                                    <label for="end_time" class="form-label">Heure de fin</label>
                                    <input type="time" class="form-control @error('end_time') is-invalid @enderror" 
                                           id="end_time" name="end_time" 
                                           value="{{ old('end_time') }}">
                                    @error('end_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('doctor.calendar') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left me-2"></i>Retour
                                    </a>
                                    <button type="submit" class="btn btn-warning">
                                        <i class="fas fa-save me-2"></i>Créer l'absence
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar avec conseils -->
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-lightbulb me-2"></i>Conseils
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h6 class="alert-heading">
                            <i class="fas fa-info-circle me-2"></i>Bonnes pratiques
                        </h6>
                        <ul class="mb-0">
                            <li>Planifiez vos absences à l'avance</li>
                            <li>Informez vos patients des indisponibilités</li>
                            <li>Les RDV pris pendant vos absences seront en attente</li>
                            <li>Vous pouvez modifier ou annuler une absence</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>Impact sur les rendez-vous
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning">
                        <h6 class="alert-heading">
                            <i class="fas fa-calendar-times me-2"></i>Attention
                        </h6>
                        <p class="mb-0">
                            Les rendez-vous pris pendant vos périodes d'absence seront automatiquement 
                            mis en statut "En attente de confirmation". Vous devrez les confirmer 
                            ou les reporter à votre retour.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const isFullDayCheckbox = document.getElementById('is_full_day');
    const startTimeGroup = document.getElementById('start_time_group');
    const endTimeGroup = document.getElementById('end_time_group');
    const startTimeInput = document.getElementById('start_time');
    const endTimeInput = document.getElementById('end_time');

    // Gérer l'affichage des champs d'heure
    function toggleTimeFields() {
        if (isFullDayCheckbox.checked) {
            startTimeGroup.style.display = 'none';
            endTimeGroup.style.display = 'none';
            startTimeInput.removeAttribute('required');
            endTimeInput.removeAttribute('required');
        } else {
            startTimeGroup.style.display = 'block';
            endTimeGroup.style.display = 'block';
            startTimeInput.setAttribute('required', 'required');
            endTimeInput.setAttribute('required', 'required');
        }
    }

    isFullDayCheckbox.addEventListener('change', toggleTimeFields);
    toggleTimeFields(); // Initialiser l'état

    // Validation des dates
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');

    startDateInput.addEventListener('change', function() {
        if (endDateInput.value && this.value > endDateInput.value) {
            alert('La date de début doit être antérieure à la date de fin.');
            this.value = '';
        }
    });

    endDateInput.addEventListener('change', function() {
        if (startDateInput.value && this.value < startDateInput.value) {
            alert('La date de fin doit être postérieure à la date de début.');
            this.value = '';
        }
    });

    // Validation des heures
    startTimeInput.addEventListener('change', function() {
        if (endTimeInput.value && this.value >= endTimeInput.value) {
            alert('L\'heure de début doit être antérieure à l\'heure de fin.');
            this.value = '';
        }
    });

    endTimeInput.addEventListener('change', function() {
        if (startTimeInput.value && this.value <= startTimeInput.value) {
            alert('L\'heure de fin doit être postérieure à l\'heure de début.');
            this.value = '';
        }
    });
});
</script>
@endpush
