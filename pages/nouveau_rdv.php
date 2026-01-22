<?php
require_once '../config/db.php';
include '../includes/header.php';
?>

<div class="d-flex">
    <main class="flex-grow-1" style="margin-left: 280px;"> <div class="container-fluid px-4 mt-4">
            <div class="bg-white rounded-5 shadow-sm p-3 mb-4 border-bottom border-4 border-primary sticky-top" style="top: 10px; z-index: 999;">
                <div class="row align-items-center">
                    <div class="col-md-3 border-end">
                        <div class="input-group shadow-sm rounded-pill overflow-hidden">
                            <span class="input-group-text bg-primary text-white border-0"><i class="bi bi-upc-scan"></i></span>
                            <input type="text" id="patientCodeSearch" class="form-control border-0 bg-light" placeholder="Code Patient...">
                            <button class="btn btn-primary" type="button" id="btnSearch"><i class="bi bi-search"></i></button>
                        </div>
                    </div>
                    
                    <div class="col-md-9">
                        <div id="patientInfoDisplay" class="d-flex align-items-center justify-content-around text-uppercase" style="display: none !important;">
                            <div><small class="text-muted d-block">Patient</small><strong id="resNom" class="text-dark fs-5">---</strong></div>
                            <div class="vr"></div>
                            <div>
                                <small class="text-muted d-block">Sexe & Âge</small>
                                <strong class="text-dark fs-5"><span id="resSexe">--</span> / <span id="resAge">--</span> <small>ans</small></strong>
                            </div>
                            <div class="vr"></div>
                            <div><small class="text-muted d-block">Dernier RDV</small><strong id="resDernierRdv" class="text-warning fs-5">Aucun</strong></div>
                            <div class="vr"></div>
                            <button type="button" class="btn btn-outline-primary rounded-pill btn-sm fw-bold" data-bs-toggle="modal" data-bs-target="#modalHistory">
                                <i class="bi bi-clock-history me-1"></i> HISTORIQUE
                            </button>
                        </div>
                        <div id="waitMessage" class="text-muted italic text-center small">Veuillez scanner ou saisir un code patient pour commencer...</div>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-11"> <div id="formContainer" class="bg-white rounded-5 shadow p-5 border-top border-4 border-success">
                        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                            <h5 class="fw-bold mb-0 text-success">
                                <i class="bi bi-pencil-square me-2"></i>SAISIE DE LA CONSULTATION
                            </h5>
                            <span class="badge bg-light text-success border border-success-subtle px-3 py-2 rounded-pill" id="displayCodePatient">AUCUN PATIENT SÉLECTIONNÉ</span>
                        </div>
                        
                        <form action="../php/save_rdv.php" method="POST" id="mainRdvForm">
                            <input type="hidden" name="patient_id" id="formPatientId">
                            
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-secondary">Date de consultation</label>
                                    <input type="date" name="date_rdv" id="date_rdv" class="form-control form-control-lg bg-light border-0 rounded-4 shadow-sm" value="<?= date('Y-m-d') ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-secondary">Assiduité</label>
                                    <select name="assiduite" class="form-select form-select-lg bg-light border-0 rounded-4 shadow-sm">
                                        <option value="RDV RESPECTER">🟢 RDV RESPECTER</option>
                                        <option value="RDV ANTICIPE">🟡 RDV ANTICIPE</option>
                                        <option value="RDV RATTRAPER">🔴 RDV RATTRAPER</option>
                                    </select>
                                </div>

                                <div class="col-12">
                                    <label class="form-label small fw-bold text-secondary mb-3">Motifs de la visite actuelle</label>
                                    <div class="bg-light p-4 rounded-5 border border-dashed">
                                        <div class="row g-3">
                                            <?php 
                                            $motifs = ["BILAN INITIAL", "BILAN DE SUIVI", "CHARGE VIRALE", "RENOUVELLEMENT ORDONNANCE", "VISITE DE SUIVI", "PCR", "SDEE-VIH", "CO/ETP"];
                                            foreach($motifs as $m): ?>
                                                <div class="col-md-3">
                                                    <div class="form-check custom-card-check">
                                                        <input class="form-check-input" type="checkbox" name="motif_rdv[]" value="<?= $m ?>" id="curr_<?= str_replace(' ', '_', $m) ?>">
                                                        <label class="form-check-label fw-medium" for="curr_<?= str_replace(' ', '_', $m) ?>"><?= $m ?></label>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-8">
                                    <label class="form-label small fw-bold text-secondary">Molécule / Régime ARV</label>
                                    <select name="molecule" class="form-select form-select-lg bg-light border-0 rounded-4 shadow-sm" required>
                                        <option value="">-- Sélectionner le régime --</option>
                                        
                                        <optgroup label="Régimes de 1ère Ligne (Adultes)">
                                            <option value="TDF/3TC/DTG">TDF/3TC/DTG (TLD)</option>
                                            <option value="TDF/3TC/EFV">TDF/3TC/EFV (TLE)</option>
                                            <option value="ABC/3TC/DTG">ABC/3TC/DTG</option>
                                            <option value="ABC/3TC/EFV">ABC/3TC/EFV</option>
                                            <option value="TDF/3TC + DTG 50mg">TDF/3TC + DTG 50mg</option>
                                        </optgroup>

                                        <optgroup label="Régimes de 2ème Ligne / Autres">
                                            <option value="AZT/3TC/NVP">AZT/3TC/NVP</option>
                                            <option value="AZT/3TC + LPV/r">AZT/3TC + LPV/r</option>
                                            <option value="TDF/3TC + LPV/r">TDF/3TC + LPV/r</option>
                                            <option value="ABC/3TC + LPV/r">ABC/3TC + LPV/r</option>
                                            <option value="TDF/3TC + ATV/r">TDF/3TC + ATV/r</option>
                                        </optgroup>

                                        <optgroup label="Régimes Pédiatriques">
                                            <option value="ABC/3TC/LPV/r">ABC/3TC/LPV/r (Pédiatrique)</option>
                                            <option value="AZT/3TC/ABC">AZT/3TC/ABC</option>
                                            <option value="ABC/3TC + DTG 10mg">ABC/3TC + DTG 10mg</option>
                                        </optgroup>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold text-secondary">Durée (jours)</label>
                                    <input type="number" id="inputDuree" name="duree_traitement" class="form-control form-control-lg bg-light border-0 rounded-4 shadow-sm" placeholder="Ex: 90" min="1">
                                </div>

                                <div class="col-12 mt-5">
                                    <div class="card border-0 bg-success bg-opacity-10 rounded-5 p-4">
                                        <h6 class="text-success fw-bold mb-4"><i class="bi bi-calendar-check me-2"></i>PROCHAIN RENDEZ-VOUS</h6>
                                        <div class="row align-items-end g-4">
                                            <div class="col-md-4">
                                                <label class="form-label small fw-bold">Date de retour prévue</label>
                                                <input type="date" id="inputDateProchain" name="date_prochain_rdv" class="form-control form-control-lg border-0 bg-white shadow-sm rounded-4" required readonly>
                                            </div>
                                            <div class="col-md-8">
                                                <label class="form-label small fw-bold mb-2">Motifs du prochain RDV</label>
                                                <div class="d-flex flex-wrap gap-3">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="motif_prochain_rdv[]" value="VISITE DE SUIVI" id="next_suivi" checked>
                                                        <label class="form-check-label" for="next_suivi">VISITE DE SUIVI</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="motif_prochain_rdv[]" value="CHARGE VIRALE" id="next_cv">
                                                        <label class="form-check-label" for="next_cv">CHARGE VIRALE</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="motif_prochain_rdv[]" value="RENOUVELLEMENT ORDONNANCE" id="next_ord" checked>
                                                        <label class="form-check-label" for="next_ord">RENOUVELLEMENT</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 mt-4 text-center">
                                    <button type="button" id="btnSubmit" class="btn btn-success btn-lg rounded-pill px-5 py-3 fw-bold shadow-lg" disabled>
                                        <i class="bi bi-cloud-arrow-up-fill me-2"></i> ENREGISTRER LA CONSULTATION
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<style>
    .custom-card-check { padding: 10px; border-radius: 10px; transition: background 0.2s; cursor: pointer;}
    .custom-card-check:hover { background: #e9ecef; }
    .border-dashed { border: 2px dashed #dee2e6 !important; }
    
    /* Responsive : on retire la marge sur mobile si la sidebar se cache */
    @media (max-width: 992px) {
        main { margin-left: 0 !important; }
    }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    
    function chargerPatient(codePatient) {
        if(!codePatient) return;

        $.ajax({
            url: '../php/get_patient_by_code.php',
            type: 'GET',
            data: { code: codePatient },
            dataType: 'json',
            success: function(response) {
                if(response.success) {
                    // Remplissage des textes
                    $('#resNom').text(response.nom + ' ' + response.prenoms);
                    $('#resSexe').text(response.sexe);
                    $('#resAge').text(response.age);
                    $('#resDernierRdv').text(response.dernier_rdv);
                    $('#displayCodePatient').text(response.code);
                    
                    // Remplissage du champ caché pour le formulaire
                    $('#formPatientId').val(response.id);
                    
                    // AFFICHAGE : On retire le 'display: none'
                    $('#waitMessage').hide();
                    $('#patientInfoDisplay').attr('style', 'display: flex !important');
                    
                    // Activer le bouton d'enregistrement
                    $('#btnSubmit').prop('disabled', false);
                } else {
                    alert("Patient introuvable avec le code : " + codePatient);
                }
            },
            error: function() {
                alert("Erreur de connexion au serveur");
            }
        });
    }

    // Gestion du clic manuel sur la loupe
    $('#btnSearch').click(function() {
        chargerPatient($('#patientCodeSearch').val());
    });

    // Auto-chargement si présence du code dans l'URL
    const urlParams = new URLSearchParams(window.location.search);
    const codeUrl = urlParams.get('code');
    if(codeUrl) {
        $('#patientCodeSearch').val(codeUrl);
        chargerPatient(codeUrl);
    }
});
</script>