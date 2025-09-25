<!-- Modal Modifier Médecin -->
<div class="modal fade" id="editDoctorModal" tabindex="-1" aria-labelledby="editDoctorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editDoctorModalLabel">
                    <i class="fas fa-edit me-2"></i>Modifier le Médecin
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editDoctorForm">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editFirstName" class="form-label">Prénom *</label>
                                <input type="text" class="form-control" id="editFirstName" name="first_name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editLastName" class="form-label">Nom *</label>
                                <input type="text" class="form-control" id="editLastName" name="last_name" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editEmail" class="form-label">Email *</label>
                                <input type="email" class="form-control" id="editEmail" name="email" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editPhone" class="form-label">Téléphone</label>
                                <input type="tel" class="form-control" id="editPhone" name="phone">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="editAddress" class="form-label">Adresse</label>
                        <textarea class="form-control" id="editAddress" name="address" rows="2"></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editBirthdate" class="form-label">Date de naissance</label>
                                <input type="date" class="form-control" id="editBirthdate" name="birthdate">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editGender" class="form-label">Sexe</label>
                                <select class="form-select" id="editGender" name="gender">
                                    <option value="">Sélectionner</option>
                                    <option value="male">Homme</option>
                                    <option value="female">Femme</option>
                                    <option value="other">Autre</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    <h6 class="text-primary">Informations Professionnelles</h6>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editSpecialty" class="form-label">Spécialité *</label>
                                <select class="form-select" id="editSpecialty" name="specialty" required>
                                    <option value="">Sélectionner une spécialité</option>
                                    <option value="general">Médecine Générale</option>
                                    <option value="cardiology">Cardiologie</option>
                                    <option value="dermatology">Dermatologie</option>
                                    <option value="neurology">Neurologie</option>
                                    <option value="pediatrics">Pédiatrie</option>
                                    <option value="surgery">Chirurgie</option>
                                    <option value="orthopedics">Orthopédie</option>
                                    <option value="ophthalmology">Ophtalmologie</option>
                                    <option value="psychiatry">Psychiatrie</option>
                                    <option value="gynecology">Gynécologie</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editLicenseNumber" class="form-label">Numéro de licence</label>
                                <input type="text" class="form-control" id="editLicenseNumber" name="license_number">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editExperience" class="form-label">Années d'expérience</label>
                                <input type="number" class="form-control" id="editExperience" name="experience" min="0" max="50">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editHireDate" class="form-label">Date d'embauche</label>
                                <input type="date" class="form-control" id="editHireDate" name="hire_date">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="editBio" class="form-label">Biographie</label>
                        <textarea class="form-control" id="editBio" name="bio" rows="3" placeholder="Description du médecin, formations, expériences..."></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="editPhoto" class="form-label">Photo</label>
                        <input type="file" class="form-control" id="editPhoto" name="photo" accept="image/*">
                        <div class="form-text">Formats acceptés: JPG, PNG, GIF. Taille max: 2MB</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Enregistrer les modifications
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

