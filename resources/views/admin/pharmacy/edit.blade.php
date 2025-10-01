@extends('layouts.admin')

@section('title', 'Modifier Médicament - Admin')
@section('page-title', 'Modifier le Médicament')
@section('page-subtitle', 'Modifier les informations du médicament')
@section('user-role', 'Administrateur')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-pills me-2"></i>
                        Modifier les informations de {{ $medicament->nom }}
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

                    <form action="{{ route('admin.pharmacy.update', $medicament) }}" method="POST">
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
                                    <label for="nom" class="form-label">Nom du médicament *</label>
                                    <input type="text" class="form-control" id="nom" name="nom" 
                                           value="{{ old('nom', $medicament->nom) }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="categorie" class="form-label">Catégorie *</label>
                                    <select class="form-select" id="categorie" name="categorie" required>
                                        <option value="">Sélectionner une catégorie</option>
                                        <option value="antibiotique" {{ old('categorie', $medicament->categorie) === 'antibiotique' ? 'selected' : '' }}>Antibiotique</option>
                                        <option value="analgesique" {{ old('categorie', $medicament->categorie) === 'analgesique' ? 'selected' : '' }}>Analgésique</option>
                                        <option value="vitamine" {{ old('categorie', $medicament->categorie) === 'vitamine' ? 'selected' : '' }}>Vitamine</option>
                                        <option value="autre" {{ old('categorie', $medicament->categorie) === 'autre' ? 'selected' : '' }}>Autre</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $medicament->description) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Informations du stock -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-boxes me-2"></i>Informations du stock
                                </h6>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="quantite_stock" class="form-label">Quantité en stock *</label>
                                    <input type="number" class="form-control" id="quantite_stock" name="quantite_stock" 
                                           value="{{ old('quantite_stock', $medicament->quantite_stock) }}" required min="0">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="unite_mesure" class="form-label">Unité de mesure *</label>
                                    <select class="form-select" id="unite_mesure" name="unite_mesure" required>
                                        <option value="">Sélectionner une unité</option>
                                        <option value="comprimé" {{ old('unite_mesure', $medicament->unite_mesure) === 'comprimé' ? 'selected' : '' }}>Comprimé</option>
                                        <option value="gélule" {{ old('unite_mesure', $medicament->unite_mesure) === 'gélule' ? 'selected' : '' }}>Gélule</option>
                                        <option value="flacon" {{ old('unite_mesure', $medicament->unite_mesure) === 'flacon' ? 'selected' : '' }}>Flacon</option>
                                        <option value="tube" {{ old('unite_mesure', $medicament->unite_mesure) === 'tube' ? 'selected' : '' }}>Tube</option>
                                        <option value="ampoule" {{ old('unite_mesure', $medicament->unite_mesure) === 'ampoule' ? 'selected' : '' }}>Ampoule</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="prix_unitaire" class="form-label">Prix unitaire (FCFA) *</label>
                                    <input type="number" class="form-control" id="prix_unitaire" name="prix_unitaire" 
                                           value="{{ old('prix_unitaire', $medicament->prix_unitaire) }}" required min="0" step="0.01">
                                </div>
                            </div>
                        </div>

                        <!-- Informations d'expiration -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-calendar-alt me-2"></i>Informations d'expiration
                                </h6>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="date_expiration" class="form-label">Date d'expiration</label>
                                    <input type="date" class="form-control" id="date_expiration" name="date_expiration" 
                                           value="{{ old('date_expiration', $medicament->date_expiration ? $medicament->date_expiration->format('Y-m-d') : '') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="disponible" class="form-label">Statut de disponibilité *</label>
                                    <select class="form-select" id="disponible" name="disponible" required>
                                        <option value="1" {{ old('disponible', $medicament->disponible) ? 'selected' : '' }}>Disponible</option>
                                        <option value="0" {{ !old('disponible', $medicament->disponible) ? 'selected' : '' }}>Rupture de stock</option>
                                    </select>
                                </div>
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
                                    <label for="code_barre" class="form-label">Code-barres</label>
                                    <input type="text" class="form-control" id="code_barre" name="code_barre" 
                                           value="{{ old('code_barre', $medicament->code_barre ?? '') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="fournisseur" class="form-label">Fournisseur</label>
                                    <input type="text" class="form-control" id="fournisseur" name="fournisseur" 
                                           value="{{ old('fournisseur', $medicament->fournisseur ?? '') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="seuil_alerte" class="form-label">Seuil d'alerte</label>
                                    <input type="number" class="form-control" id="seuil_alerte" name="seuil_alerte" 
                                           value="{{ old('seuil_alerte', $medicament->seuil_alerte ?? 10) }}" min="0">
                                    <small class="form-text text-muted">Quantité minimale avant alerte de stock faible</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="temperature_stockage" class="form-label">Température de stockage</label>
                                    <input type="text" class="form-control" id="temperature_stockage" name="temperature_stockage" 
                                           value="{{ old('temperature_stockage', $medicament->temperature_stockage ?? '') }}" 
                                           placeholder="Ex: 2-8°C">
                                </div>
                            </div>
                        </div>

                        <!-- Instructions et précautions -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-exclamation-triangle me-2"></i>Instructions et précautions
                                </h6>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="instructions_utilisation" class="form-label">Instructions d'utilisation</label>
                                    <textarea class="form-control" id="instructions_utilisation" name="instructions_utilisation" rows="3">{{ old('instructions_utilisation', $medicament->instructions_utilisation ?? '') }}</textarea>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="precautions" class="form-label">Précautions</label>
                                    <textarea class="form-control" id="precautions" name="precautions" rows="3">{{ old('precautions', $medicament->precautions ?? '') }}</textarea>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="contre_indications" class="form-label">Contre-indications</label>
                                    <textarea class="form-control" id="contre_indications" name="contre_indications" rows="3">{{ old('contre_indications', $medicament->contre_indications ?? '') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Boutons d'action -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('admin.pharmacy.show', $medicament) }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left me-2"></i>Retour
                                    </a>
                                    <div>
                                        <button type="button" class="btn btn-outline-danger me-2" onclick="deleteMedicament({{ $medicament->id }})">
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
function deleteMedicament(medicamentId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce médicament ? Cette action est irréversible.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/pharmacy/${medicamentId}`;
        
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

// Validation du formulaire
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const quantiteStock = document.getElementById('quantite_stock');
    const disponible = document.getElementById('disponible');
    const prixUnitaire = document.getElementById('prix_unitaire');
    
    // Validation de la quantité de stock
    quantiteStock.addEventListener('input', function() {
        const quantite = parseInt(this.value);
        if (quantite <= 0) {
            disponible.value = '0';
        } else {
            disponible.value = '1';
        }
    });
    
    // Validation du prix unitaire
    prixUnitaire.addEventListener('input', function() {
        const prix = parseFloat(this.value);
        if (prix < 0) {
            this.value = 0;
        }
    });
    
    // Calcul automatique de la valeur du stock
    function calculateStockValue() {
        const quantite = parseInt(quantiteStock.value) || 0;
        const prix = parseFloat(prixUnitaire.value) || 0;
        const valeur = quantite * prix;
        
        // Afficher la valeur du stock quelque part si nécessaire
        console.log('Valeur du stock:', valeur.toLocaleString() + ' FCFA');
    }
    
    quantiteStock.addEventListener('input', calculateStockValue);
    prixUnitaire.addEventListener('input', calculateStockValue);
    
    // Validation de la date d'expiration
    const dateExpiration = document.getElementById('date_expiration');
    dateExpiration.addEventListener('change', function() {
        const selectedDate = new Date(this.value);
        const today = new Date();
        
        if (selectedDate <= today) {
            alert('La date d\'expiration doit être dans le futur.');
            this.value = '';
        }
    });
});
</script>
@endsection
