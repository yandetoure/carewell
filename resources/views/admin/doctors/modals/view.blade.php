<!-- Modal Voir Médecin -->
<div class="modal fade" id="viewDoctorModal" tabindex="-1" aria-labelledby="viewDoctorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewDoctorModalLabel">
                    <i class="fas fa-user-md me-2"></i>Détails du Médecin
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4 text-center">
                        <div class="avatar-lg mx-auto mb-3">
                            <img id="doctorPhoto" src="" alt="Photo" class="rounded-circle" width="120" height="120" style="object-fit: cover;">
                        </div>
                        <h5 id="doctorName" class="mb-1"></h5>
                        <p id="doctorSpecialty" class="text-muted mb-2"></p>
                        <span id="doctorStatus" class="badge"></span>
                    </div>
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-6">
                                <h6 class="text-primary">Informations Personnelles</h6>
                                <p><strong>Email:</strong> <span id="doctorEmail"></span></p>
                                <p><strong>Téléphone:</strong> <span id="doctorPhone"></span></p>
                                <p><strong>Adresse:</strong> <span id="doctorAddress"></span></p>
                                <p><strong>Date de naissance:</strong> <span id="doctorBirthdate"></span></p>
                            </div>
                            <div class="col-6">
                                <h6 class="text-primary">Informations Professionnelles</h6>
                                <p><strong>Spécialité:</strong> <span id="doctorSpecialtyDetail"></span></p>
                                <p><strong>Numéro de licence:</strong> <span id="doctorLicense"></span></p>
                                <p><strong>Années d'expérience:</strong> <span id="doctorExperience"></span></p>
                                <p><strong>Date d'embauche:</strong> <span id="doctorHireDate"></span></p>
                            </div>
                        </div>
                        
                        <div class="mt-3">
                            <h6 class="text-primary">Services Proposés</h6>
                            <div id="doctorServices" class="d-flex flex-wrap gap-1">
                                <!-- Services seront chargés dynamiquement -->
                            </div>
                        </div>
                        
                        <div class="mt-3">
                            <h6 class="text-primary">Statistiques</h6>
                            <div class="row">
                                <div class="col-4 text-center">
                                    <div class="border rounded p-2">
                                        <h6 class="text-success mb-0" id="totalAppointments">0</h6>
                                        <small class="text-muted">Rendez-vous</small>
                                    </div>
                                </div>
                                <div class="col-4 text-center">
                                    <div class="border rounded p-2">
                                        <h6 class="text-info mb-0" id="totalPatients">0</h6>
                                        <small class="text-muted">Patients</small>
                                    </div>
                                </div>
                                <div class="col-4 text-center">
                                    <div class="border rounded p-2">
                                        <h6 class="text-warning mb-0" id="avgRating">0</h6>
                                        <small class="text-muted">Note moyenne</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-warning" onclick="editDoctorFromModal()">
                    <i class="fas fa-edit me-1"></i>Modifier
                </button>
                <button type="button" class="btn btn-info" onclick="manageServicesFromModal()">
                    <i class="fas fa-stethoscope me-1"></i>Gérer Services
                </button>
            </div>
        </div>
    </div>
</div>

