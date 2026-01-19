<div class="modal fade" id="modalPatient" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
      
      <div class="modal-header border-0 p-4 text-white" style="background: linear-gradient(135deg, #1a402e 0%, #4d1212 100%);">
        <h5 class="modal-title fw-bold">
            <i class="bi bi-person-plus-fill me-2"></i>Nouveau Dossier Patient
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body p-4 bg-light">
        <form id="formAddPatient" action="../php/save_patient.php" method="POST">
          
          <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-white border-0 pt-3">
                <h6 class="text-success fw-bold"><i class="bi bi-info-circle me-2"></i>Informations du Patient</h6>
            </div>
            <div class="card-body pt-0">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-muted">Code Patient</label>
                        <input type="text" 
                            name="code" 
                            id="patientCode"
                            class="form-control border-0 shadow-sm py-2" 
                            style="background-color: #f8f9fa;" 
                            placeholder="09817/01/26/00001" 
                            required
                            maxlength="17"
                            pattern="^[a-zA-Z0-9]{5}/[a-zA-Z0-9]{2}/[0-9]{2}/[0-9]{5}$"
                            title="Format strict (17 car.) : 5 car. / 2 car. / 2 chiffres / 5 chiffres">
                        <div class="form-text" style="font-size: 0.7rem;">Ex: 09817/01/26/00001</div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-muted">Nom</label>
                        <input type="text" name="nom" class="form-control border-0 shadow-sm py-2" style="background-color: #f8f9fa;" required>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label small fw-bold text-muted">Prénoms</label>
                        <input type="text" name="prenoms" class="form-control border-0 shadow-sm py-2" style="background-color: #f8f9fa;" required>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-muted">Sexe</label>
                        <select name="sexe" class="form-select border-0 shadow-sm py-2" style="background-color: #f8f9fa;" required>
                            <option value="" selected disabled>Choisir...</option>
                            <option value="M">Masculin</option>
                            <option value="F">Féminin</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-muted">Date de Naissance</label>
                        <input type="date" name="date_naissance" class="form-control border-0 shadow-sm py-2" style="background-color: #f8f9fa;">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-bold text-muted">Âge</label>
                        <input type="number" name="age" class="form-control border-0 shadow-sm py-2" style="background-color: #f8f9fa;">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-muted">Quartier / Résidence</label>
                        <input type="text" name="quartier" class="form-control border-0 shadow-sm py-2" style="background-color: #f8f9fa;">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-muted">Téléphone 1</label>
                        <input type="text" name="telephone1" class="form-control border-0 shadow-sm py-2" style="background-color: #f8f9fa;" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-muted">Téléphone 2 (Urgence)</label>
                        <input type="text" name="telephone2" class="form-control border-0 shadow-sm py-2" style="background-color: #f8f9fa;">
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-muted">Catégorie</label>
                        <select name="categorie" class="form-select border-0 shadow-sm py-2" style="background-color: #f8f9fa;" required>
                            <option value="" selected disabled>CHOISIR UNE CATÉGORIE...</option>
                            <option value="FEMME ENCEINTE">FEMME ENCEINTE</option>
                            <option value="FEMME ALLAITANTE">FEMME ALLAITANTE</option>
                            <option value="POPULATION CLÉ">POPULATION CLÉ</option>
                            <option value="ENFANT EXPOSÉ">ENFANT EXPOSÉ</option>
                            <option value="POPULATION GÉNÉRALE">POPULATION GÉNÉRALE</option>
                        </select>
                    </div>
                </div>
            </div>
          </div>

          <div class="card border-0 shadow-sm rounded-4" style="background-color: #fdf2f2;">
            <div class="card-header border-0 pt-3" style="background: transparent;">
                <h6 class="text-danger fw-bold"><i class="bi bi-shield-check me-2"></i>Informations de l'Aval (Accompagnant)</h6>
            </div>
            <div class="card-body pt-0">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-muted">Nom complet de l'aval</label>
                        <input type="text" name="nom_aval" class="form-control border-0 shadow-sm py-2 bg-white">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-muted">Lien avec le patient</label>
                        <select name="lien_parente" class="form-select border-0 shadow-sm py-2 bg-white">
                            <option value="" selected disabled>Choisir le lien...</option>
                            <option value="PARENT">PARENT (PÈRE/MÈRE)</option>
                            <option value="ENFANT">ENFANT</option>
                            <option value="FRÈRE/SŒUR">FRÈRE/SŒUR</option>
                            <option value="CONJOINT">CONJOINT(E)</option>
                            <option value="ONCLE/TANTE">ONCLE/TANTE</option>
                            <option value="COUSIN(E)">COUSIN(E)</option>
                            <option value="AMI(E)">AMI(E)</option>
                            <option value="CONNAISSANCE">CONNAISSANCE</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-muted">Téléphone de l'aval</label>
                        <input type="text" name="tel_aval" class="form-control border-0 shadow-sm py-2 bg-white">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-muted">Confidentialité</label>
                        <select name="confidentialite" class="form-select border-0 shadow-sm py-2 bg-white">
                            <option value="Oui">OUI</option>
                            <option value="Non">NON</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

          <div class="d-flex justify-content-between mt-4">
            <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Annuler</button>
            <button type="submit" class="btn text-white rounded-pill px-5 shadow" style="background: linear-gradient(135deg, #1a402e 0%, #4d1212 100%); border:none;">
                Enregistrer le Patient
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
    document.querySelectorAll('#formAddPatient input, #formAddPatient select').forEach(element => {
        element.addEventListener('input', function() {
            this.value = this.value.toUpperCase();
        });
    });
</script>