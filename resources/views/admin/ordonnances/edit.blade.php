@extends('layouts.admin')

@section('title', 'Modifier Prescription - Admin')
@section('page-title', 'Modifier la Prescription')
@section('page-subtitle', 'Modifier les informations de l\'ordonnance médicale')
@section('user-role', 'Administrateur')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-prescription-bottle-alt me-2"></i>
                        Modifier la prescription {{ $ordonnance->numero_ordonnance }}
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

                    <form action="{{ route('admin.prescriptions.update', $ordonnance) }}" method="POST">
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
                                    <label for="numero_ordonnance" class="form-label">Numéro d'ordonnance *</label>
                                    <input type="text" class="form-control" id="numero_ordonnance" name="numero_ordonnance" 
                                           value="{{ old('numero_ordonnance', $ordonnance->numero_ordonnance) }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="date_prescription" class="form-label">Date de prescription *</label>
                                    <input type="datetime-local" class="form-control" id="date_prescription" name="date_prescription" 
                                           value="{{ old('date_prescription', $ordonnance->date_prescription->format('Y-m-d\TH:i')) }}" required>
                                </div>
                            </div>
                        </div>

                        <!-- Patient et médecin -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-users me-2"></i>Patient et médecin
                                </h6>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="patient_id" class="form-label">Patient *</label>
                                    <select class="form-select" id="patient_id" name="patient_id" required>
                                        <option value="">Sélectionner un patient</option>
                                        @foreach(\App\Models\User::role('Patient')->get() as $patient)
                                            <option value="{{ $patient->id }}" {{ old('patient_id', $ordonnance->patient_id) == $patient->id ? 'selected' : '' }}>
                                                {{ $patient->name }} ({{ $patient->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="medecin_id" class="form-label">Médecin *</label>
                                    <select class="form-select" id="medecin_id" name="medecin_id" required>
                                        <option value="">Sélectionner un médecin</option>
                                        @foreach(\App\Models\User::role('Doctor')->get() as $doctor)
                                            <option value="{{ $doctor->id }}" {{ old('medecin_id', $ordonnance->medecin_id) == $doctor->id ? 'selected' : '' }}>
                                                Dr. {{ $doctor->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Statut et instructions -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-cog me-2"></i>Statut et instructions
                                </h6>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="statut" class="form-label">Statut *</label>
                                    <select class="form-select" id="statut" name="statut" required>
                                        <option value="active" {{ old('statut', $ordonnance->statut) === 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="expiree" {{ old('statut', $ordonnance->statut) === 'expiree' ? 'selected' : '' }}>Expirée</option>
                                        <option value="annulee" {{ old('statut', $ordonnance->statut) === 'annulee' ? 'selected' : '' }}>Annulée</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="date_expiration" class="form-label">Date d'expiration</label>
                                    <input type="date" class="form-control" id="date_expiration" name="date_expiration" 
                                           value="{{ old('date_expiration', $ordonnance->date_expiration ? $ordonnance->date_expiration->format('Y-m-d') : '') }}">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="instructions" class="form-label">Instructions spéciales</label>
                                    <textarea class="form-control" id="instructions" name="instructions" rows="3" 
                                              placeholder="Instructions particulières pour le patient...">{{ old('instructions', $ordonnance->instructions) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Médicaments prescrits -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-pills me-2"></i>Médicaments prescrits
                                </h6>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="medicaments" class="form-label">Médicaments *</label>
                                    <select class="form-select" id="medicaments" name="medicaments[]" multiple required>
                                        @foreach(\App\Models\Medicament::all() as $medicament)
                                            <option value="{{ $medicament->id }}" 
                                                    {{ in_array($medicament->id, old('medicaments', $ordonnance->medicaments->pluck('id')->toArray())) ? 'selected' : '' }}>
                                                {{ $medicament->nom }} - {{ $medicament->categorie }} ({{ number_format($medicament->prix_unitaire, 0, ',', ' ') }} FCFA)
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="form-text text-muted">Maintenez Ctrl (Cmd sur Mac) pour sélectionner plusieurs médicaments</small>
                                </div>
                            </div>
                        </div>

                        <!-- Détails des médicaments -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-list me-2"></i>Détails des médicaments
                                </h6>
                            </div>
                            <div id="medicament-details">
                                @foreach($ordonnance->medicaments as $medicament)
                                <div class="card mb-3 medicament-detail" data-medicament-id="{{ $medicament->id }}">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label class="form-label">Médicament</label>
                                                <input type="text" class="form-control" value="{{ $medicament->nom }}" readonly>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Quantité</label>
                                                <input type="number" class="form-control" name="medicament_quantite[{{ $medicament->id }}]" 
                                                       value="{{ old('medicament_quantite.' . $medicament->id, $medicament->pivot->quantite ?? 1) }}" min="1">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Posologie</label>
                                                <input type="text" class="form-control" name="medicament_posologie[{{ $medicament->id }}]" 
                                                       value="{{ old('medicament_posologie.' . $medicament->id, $medicament->pivot->posologie ?? '') }}" 
                                                       placeholder="Ex: 1 comprimé matin et soir">
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Durée</label>
                                                <input type="text" class="form-control" name="medicament_duree[{{ $medicament->id }}]" 
                                                       value="{{ old('medicament_duree.' . $medicament->id, $medicament->pivot->duree ?? '') }}" 
                                                       placeholder="Ex: 7 jours">
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Actions</label>
                                                <button type="button" class="btn btn-outline-danger btn-sm w-100" onclick="removeMedicament(this)">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Informations supplémentaires -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-plus-circle me-2"></i>Informations supplémentaires
                                </h6>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="diagnostic" class="form-label">Diagnostic</label>
                                    <input type="text" class="form-control" id="diagnostic" name="diagnostic" 
                                           value="{{ old('diagnostic', $ordonnance->diagnostic ?? '') }}" 
                                           placeholder="Diagnostic principal">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="allergies" class="form-label">Allergies connues</label>
                                    <input type="text" class="form-control" id="allergies" name="allergies" 
                                           value="{{ old('allergies', $ordonnance->allergies ?? '') }}" 
                                           placeholder="Allergies du patient">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="observations" class="form-label">Observations</label>
                                    <textarea class="form-control" id="observations" name="observations" rows="3" 
                                              placeholder="Observations particulières...">{{ old('observations', $ordonnance->observations ?? '') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Boutons d'action -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('admin.prescriptions.show', $ordonnance) }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left me-2"></i>Retour
                                    </a>
                                    <div>
                                        <button type="button" class="btn btn-outline-danger me-2" onclick="deletePrescription({{ $ordonnance->id }})">
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
function deletePrescription(prescriptionId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette prescription ? Cette action est irréversible.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/prescriptions/${prescriptionId}`;
        
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

function removeMedicament(button) {
    if (confirm('Supprimer ce médicament de la prescription ?')) {
        button.closest('.medicament-detail').remove();
    }
}

// Gestion des médicaments
document.addEventListener('DOMContentLoaded', function() {
    const medicamentsSelect = document.getElementById('medicaments');
    const medicamentDetails = document.getElementById('medicament-details');
    
    // Charger les détails des médicaments sélectionnés
    function loadMedicamentDetails() {
        const selectedMedicaments = Array.from(medicamentsSelect.selectedOptions);
        
        // Supprimer les détails existants
        medicamentDetails.innerHTML = '';
        
        // Ajouter les détails pour chaque médicament sélectionné
        selectedMedicaments.forEach(option => {
            const medicamentId = option.value;
            const medicamentName = option.text.split(' - ')[0];
            
            const detailDiv = document.createElement('div');
            detailDiv.className = 'card mb-3 medicament-detail';
            detailDiv.setAttribute('data-medicament-id', medicamentId);
            detailDiv.innerHTML = `
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <label class="form-label">Médicament</label>
                            <input type="text" class="form-control" value="${medicamentName}" readonly>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Quantité</label>
                            <input type="number" class="form-control" name="medicament_quantite[${medicamentId}]" value="1" min="1">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Posologie</label>
                            <input type="text" class="form-control" name="medicament_posologie[${medicamentId}]" placeholder="Ex: 1 comprimé matin et soir">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Durée</label>
                            <input type="text" class="form-control" name="medicament_duree[${medicamentId}]" placeholder="Ex: 7 jours">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Actions</label>
                            <button type="button" class="btn btn-outline-danger btn-sm w-100" onclick="removeMedicament(this)">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;
            
            medicamentDetails.appendChild(detailDiv);
        });
    }
    
    // Écouter les changements de sélection
    medicamentsSelect.addEventListener('change', loadMedicamentDetails);
    
    // Validation du formulaire
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const selectedMedicaments = medicamentsSelect.selectedOptions.length;
        if (selectedMedicaments === 0) {
            e.preventDefault();
            alert('Veuillez sélectionner au moins un médicament.');
            medicamentsSelect.focus();
        }
    });
    
    // Validation de la date d'expiration
    const dateExpiration = document.getElementById('date_expiration');
    const datePrescription = document.getElementById('date_prescription');
    
    dateExpiration.addEventListener('change', function() {
        if (this.value && datePrescription.value) {
            const prescriptionDate = new Date(datePrescription.value);
            const expirationDate = new Date(this.value);
            
            if (expirationDate <= prescriptionDate) {
                alert('La date d\'expiration doit être postérieure à la date de prescription.');
                this.value = '';
            }
        }
    });
});
</script>
@endsection
