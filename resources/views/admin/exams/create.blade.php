@extends('layouts.admin')

@section('title', 'Créer un Examen - Admin')
@section('page-title', 'Créer un nouvel examen')
@section('page-subtitle', 'Ajouter un nouvel examen médical')
@section('user-role', 'Administrateur')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-plus me-2"></i>
                        Nouvel examen médical
                    </h5>
                    <a href="{{ route('admin.exams') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Retour
                    </a>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Erreurs détectées :</strong>
                            <ul class="mb-0 mt-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('admin.exams.store') }}" method="POST" id="examForm">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-4">
                                    <label for="name" class="form-label">
                                        <i class="fas fa-vials me-1"></i>
                                        Nom de l'examen *
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name') }}" 
                                           placeholder="Ex: Prise de sang, IRM cérébrale, ECG..."
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-4">
                                    <label for="price" class="form-label">
                                        <i class="fas fa-money-bill me-1"></i>
                                        Prix (FCFA) *
                                    </label>
                                    <input type="number" 
                                           class="form-control @error('price') is-invalid @enderror" 
                                           id="price" 
                                           name="price" 
                                           value="{{ old('price') }}" 
                                           step="0.01" 
                                           min="0"
                                           placeholder="0"
                                           required>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="service_id" class="form-label">
                                <i class="fas fa-hospital me-1"></i>
                                Service médical *
                            </label>
                            <select class="form-select @error('service_id') is-invalid @enderror" 
                                    id="service_id" 
                                    name="service_id" 
                                    required>
                                <option value="">Sélectionner un service</option>
                                @foreach($services as $service)
                                    <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                        {{ $service->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text">Sélectionnez le service médical associé à cet examen</div>
                            @error('service_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label">
                                <i class="fas fa-align-left me-1"></i>
                                Description de l'examen *
                            </label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="5" 
                                      placeholder="Décrivez en détail ce que comprend cet examen, son objectif, comment il se déroule, etc."
                                      required>{{ old('description') }}</textarea>
                            <div class="form-text">Minimum 5 caractères</div>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Info Card -->
                        <div class="card bg-light mb-4">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Informations utiles
                                </h6>
                            </div>
                            <div class="card-body">
                                <ul class="mb-0">
                                    <li>Le nom de l'examen doit être unique dans le système</li>
                                    <li>La description doit être claire et détaillée (minimum 5 caractères)</li>
                                    <li>Le prix peut être modifié ultérieurement si nécessaire</li>
                                    <li>L'association à un service permet une meilleure organisation</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('admin.exams') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Annuler
                            </a>
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="fas fa-save me-2"></i>Créer l'examen
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Form validation
document.getElementById('examForm').addEventListener('submit', function(e) {
    const submitBtn = document.getElementById('submitBtn');
    const name = document.getElementById('name').value.trim();
    const price = document.getElementById('price').value;
    const description = document.getElementById('description').value.trim();
    const service_id = document.getElementById('service_id').value;
    
    if (!name || !price || !description || !service_id) {
        e.preventDefault();
        alert('Veuillez remplir tous les champs obligatoires.');
        return;
    }
    
    if (parseFloat(price) < 0) {
        e.preventDefault();
        alert('Le prix ne peut pas être négatif.');
        return;
    }
    
    if (description.length < 5) {
        e.preventDefault();
        alert('La description doit contenir au moins 5 caractères.');
        return;
    }
    
    // Show loading state
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Création en cours...';
    submitBtn.disabled = true;
});

// Character counter for description
document.getElementById('description').addEventListener('input', function() {
    const length = this.value.length;
    const minLength = 5;
    
    let counter = document.getElementById('charCounter');
    if (!counter) {
        counter = document.createElement('div');
        counter.id = 'charCounter';
        counter.className = 'form-text text-end';
        this.parentNode.appendChild(counter);
    }
    
    if (length < minLength) {
        counter.textContent = `${minLength - length} caractères manquants`;
        counter.className = 'form-text text-end text-danger';
    } else {
        counter.textContent = `${length} caractères`;
        counter.className = 'form-text text-end text-muted';
    }
});
</script>

<style>
.card {
    border: 1px solid #e3e6f0;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
}

.form-label {
    font-weight: 600;
    color: #5a5c69;
}

.bg-light {
    background-color: #f8f9fc !important;
}

.is-invalid {
    border-color: #dc3545;
}

.invalid-feedback {
    display: block;
}

.btn {
    border-radius: 0.5rem;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}
</style>
@endsection

