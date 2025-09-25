<!-- Modal Gérer Services du Médecin -->
<div class="modal fade" id="manageServicesModal" tabindex="-1" aria-labelledby="manageServicesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="manageServicesModalLabel">
                    <i class="fas fa-stethoscope me-2"></i>Gérer les Services du Médecin
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <h6 id="doctorNameServices" class="text-primary"></h6>
                    <p class="text-muted mb-3">Sélectionnez les services que ce médecin peut proposer.</p>
                </div>
                
                <!-- Services disponibles -->
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-success">Services Disponibles</h6>
                        <div id="availableServices" class="border rounded p-3" style="max-height: 300px; overflow-y: auto;">
                            <!-- Services disponibles seront chargés dynamiquement -->
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-primary">Services Assignés</h6>
                        <div id="assignedServices" class="border rounded p-3" style="max-height: 300px; overflow-y: auto;">
                            <!-- Services assignés seront chargés dynamiquement -->
                        </div>
                    </div>
                </div>
                
                <!-- Actions -->
                <div class="text-center mt-3">
                    <button type="button" class="btn btn-success" onclick="assignSelectedServices()">
                        <i class="fas fa-arrow-right me-1"></i>Assigner Sélectionnés
                    </button>
                    <button type="button" class="btn btn-warning" onclick="removeSelectedServices()">
                        <i class="fas fa-arrow-left me-1"></i>Retirer Sélectionnés
                    </button>
                    <button type="button" class="btn btn-info" onclick="assignAllServices()">
                        <i class="fas fa-arrow-right me-1"></i>Assigner Tous
                    </button>
                </div>
                
                <!-- Tarifs et disponibilités -->
                <hr>
                <div class="mt-3">
                    <h6 class="text-primary">Configuration des Services</h6>
                    <div id="serviceConfiguration" class="border rounded p-3">
                        <p class="text-muted text-center">Sélectionnez un service pour configurer ses détails</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-primary" onclick="saveServicesConfiguration()">
                    <i class="fas fa-save me-1"></i>Enregistrer la Configuration
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Template pour les services disponibles -->
<template id="serviceItemTemplate">
    <div class="service-item border rounded p-2 mb-2 d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <input type="checkbox" class="form-check-input me-2 service-checkbox" value="">
            <div>
                <h6 class="mb-0 service-name"></h6>
                <small class="text-muted service-description"></small>
            </div>
        </div>
        <div>
            <span class="badge bg-secondary service-price"></span>
            <button type="button" class="btn btn-sm btn-outline-info ms-1" onclick="configureService()">
                <i class="fas fa-cog"></i>
            </button>
        </div>
    </div>
</template>

<!-- Template pour la configuration de service -->
<template id="serviceConfigTemplate">
    <div class="service-config">
        <h6 class="service-config-name text-primary"></h6>
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Prix (€)</label>
                    <input type="number" class="form-control service-price-input" min="0" step="0.01">
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Durée (minutes)</label>
                    <input type="number" class="form-control service-duration-input" min="15" step="15">
                </div>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Description personnalisée</label>
            <textarea class="form-control service-description-input" rows="2"></textarea>
        </div>
        <div class="mb-3">
            <div class="form-check">
                <input class="form-check-input service-available-input" type="checkbox" checked>
                <label class="form-check-label">Service disponible</label>
            </div>
        </div>
    </div>
</template>

