<?php
require_once '../config/db.php';
include '../includes/header.php';
?>

<div class="container-fluid px-4 mt-4">
    <div class="bg-white rounded-5 shadow-sm p-3 mb-4 border-bottom border-4 border-primary">
        <div class="row align-items-center">
            <div class="col-md-3">
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
                    <div><small class="text-muted d-block">Catégorie</small><span id="resCat" class="badge bg-info text-white rounded-pill">---</span></div>
                </div>
                <div id="waitMessage" class="text-muted italic text-center small">Veuillez scanner ou saisir un code patient pour commencer...</div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-5">
            <div class="bg-white rounded-5 shadow-sm p-4 h-100" style="min-height: 500px;">
                <h6 class="fw-bold mb-4 text-primary border-bottom pb-2">
                    <i class="bi bi-clock-history me-2"></i>HISTORIQUE DES RENDEZ-VOUS
                </h6>
                <div id="historiqueListe" class="pe-2" style="max-height: 450px; overflow-y: auto;">
                    <div class="text-center py-5 text-muted small">En attente de recherche...</div>
                </div>
            </div>
        </div>

        <div class="col-md-7">
                <div id="formContainer" class="bg-light rounded-5 shadow p-4 h-100 border-top border-4 border-success">
                    
                    <h6 class="fw-bold mb-4 text-success border-bottom border-2 pb-2">
                        <i class="bi bi-pencil-square me-2"></i>SAISIE DE LA CONSULTATION
                    </h6>
                    
                    <form action="../php/save_rdv.php" method="POST">
                        <input type="hidden" name="patient_id" id="formPatientId">
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-dark">Date de consultation</label>
                                <input type="date" name="date_rdv" class="form-control bg-white border-1 py-2 shadow-sm" value="<?= date('Y-m-d') ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-dark">Assiduité</label>
                                <select name="assiduite" class="form-select bg-white border-1 py-2 shadow-sm">
                                    <option value="RDV RESPECTER">🟢 RDV RESPECTER</option>
                                    <option value="RDV ANTICIPE">🟡 RDV ANTICIPE</option>
                                    <option value="RDV RATTRAPER">🔴 RDV RATTRAPER</option>
                                </select>
                            </div>

                            <div class="col-12 mt-3">
                                <label class="form-label small fw-bold text-dark mb-2">Motifs du RDV Actuel</label>
                                <div class="bg-white p-3 rounded-4 shadow-sm border">
                                    <div class="row g-2">
                                        <?php 
                                        $motifs = ["BILAN INITIAL", "BILAN DE SUIVI", "CHARGE VIRALE", "RENOUVELLEMENT ORDONNANCE", "VISITE DE SUIVI", "PCR", "SDEE-VIH", "CO/ETP"];
                                        foreach($motifs as $m): ?>
                                            <div class="col-md-6">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="motif_rdv[]" value="<?= $m ?>" id="curr_<?= str_replace(' ', '_', $m) ?>">
                                                    <label class="form-check-label small" for="curr_<?= str_replace(' ', '_', $m) ?>"><?= $m ?></label>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-7 mt-3">
                                <label class="form-label small fw-bold text-dark">Molécule / Régime ARV</label>
                                <select name="molecule" class="form-select bg-white border-1 shadow-sm py-2">
                                    <option value="">-- Sélectionner le régime --</option>
                                    <option value="TDF/3TC/DTG">TDF/3TC/DTG</option>
                                    <option value="ABC/3TC/DTG">ABC/3TC/DTG</option>
                                    <option value="AZT/3TC/NVP">AZT/3TC/NVP</option>
                                    <option value="AZT/3TC/DTG">AZT/3TC/DTG</option>
                                    <option value="TDF/3TC/EFV">TDF/3TC/EFV</option>
                                    <option value="ABC/3TC+LPV/r">ABC/3TC + LPV/r</option>
                                    <option value="ABC/3TC + DTG 50mg">ABC/3TC + DTG 50mg</option>
                                    <option value="ABC/3TC + DTG 10mg">ABC/3TC + DTG 10mg</option>
                                    <option value="TDF/3TC">TDF/3TC</option>
                                    <option value="TDF/3TC + ATV/r">TDF/3TC + ATV/r</option>
                                    <option value="TDF/3TC + DTG 50mg">TDF/3TC + DTG 50mg</option>
                                    <option value="TDF/3TC + DTG 10mg">TDF/3TC + DTG 10mg</option>
                                </select>
                            </div>
                            <div class="col-md-5 mt-3">
                                <label class="form-label small fw-bold text-dark">Durée (en jours)</label>
                                <input type="number" id="inputDuree" name="duree_traitement" class="form-control bg-white border-1 shadow-sm py-2" placeholder="Ex: 90" min="1">
                            </div>

                            <div class="col-12 mt-4 p-4 rounded-4 bg-white shadow-sm border border-success-subtle">
                                <h6 class="text-success fw-bold small mb-3"><i class="bi bi-calendar-check me-2"></i>PLANIFICATION DU PROCHAIN RDV</h6>
                                <div class="row g-3">
                                    <div class="col-md-12 mb-2">
                                        <label class="form-label small fw-bold text-muted">Date prévue (Calcul automatique)</label>
                                        <input type="date" id="inputDateProchain" name="date_prochain_rdv" class="form-control bg-light border-0 py-2" required readonly>
                                        <small class="text-muted" style="font-size: 0.75rem;">Calculée selon la date de consultation + durée</small>
                                    </div>
                                    
                                    <div class="col-12">
                                        <label class="form-label small fw-bold text-muted mb-2">Motifs du prochain RDV</label>
                                        <div class="row g-2">
                                            <?php foreach($motifs as $m): ?>
                                                <div class="col-md-6">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="motif_prochain_rdv[]" value="<?= $m ?>" id="next_<?= str_replace(' ', '_', $m) ?>">
                                                        <label class="form-check-label small" for="next_<?= str_replace(' ', '_', $m) ?>"><?= $m ?></label>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-success w-100 rounded-pill py-3 fw-bold shadow">
                                    <i class="bi bi-check2-circle me-2"></i>VALIDER ET ENREGISTRER
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('btnSearch').addEventListener('click', function() {
    let code = document.getElementById('patientCodeSearch').value.trim();
    if(code == "") return;

    fetch(`/agenda_pvvih/php/get_patient_by_code.php?code=${encodeURIComponent(code)}`)
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            // Mise à jour des infos (Sexe ajouté)
            document.getElementById('resNom').innerText = data.patient.nom + ' ' + data.patient.prenoms;
            document.getElementById('resAge').innerText = data.patient.age;
            document.getElementById('resSexe').innerText = data.patient.sexe; // Nouveau
            document.getElementById('resDernierRdv').innerText = data.last_rdv ? data.last_rdv : "Aucun";
            document.getElementById('resCat').innerText = data.patient.categorie;
            document.getElementById('formPatientId').value = data.patient.id;
            
            document.getElementById('waitMessage').style.setProperty('display', 'none', 'important');
            document.getElementById('patientInfoDisplay').style.setProperty('display', 'flex', 'important');

            // Débloquer et éclaircir le formulaire
            document.getElementById('formContainer').style.opacity = '1';
            document.getElementById('formContainer').style.pointerEvents = 'auto';

            // Historique
            let histHtml = '';
            if(data.historique.length > 0) {
                data.historique.forEach(h => {
                    let badge = h.assiduite == 'BONNE' ? 'success' : (h.assiduite == 'MOYENNE' ? 'warning' : 'danger');
                    // Dans la boucle forEach de votre script JS
                    histHtml += `
                        <div class="card border-0 bg-light rounded-4 mb-3 shadow-sm">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="badge bg-white text-dark border shadow-sm">${new Date(h.date_rdv).toLocaleDateString('fr-FR')}</span>
                                    <span class="badge bg-${badge}">${h.assiduite}</span>
                                </div>
                                <div class="small text-dark"><strong>Motif:</strong> ${h.motif_rdv}</div>
                                
                                ${h.molecule ? `<div class="small text-primary mt-1"><strong>Traitement:</strong> ${h.molecule} (${h.duree_traitement})</div>` : ''}
                                
                                <div class="small fw-bold text-success border-top pt-2 mt-2">
                                    Prochain RDV : ${new Date(h.date_prochain_rdv).toLocaleDateString('fr-FR')}
                                </div>
                            </div>
                        </div>`;
                });
            } else {
                histHtml = '<div class="text-center py-5 text-muted">Aucun historique trouvé.</div>';
            }
            document.getElementById('historiqueListe').innerHTML = histHtml;

        } else {
            alert("Code patient introuvable.");
        }
    })
    .catch(err => alert("Erreur de connexion : " + err));
});
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
    const inputDateRdv = document.querySelector('input[name="date_rdv"]');
    const inputDuree = document.getElementById('inputDuree');
    const inputDateProchain = document.getElementById('inputDateProchain');

    function calculerProchainRDV() {
        const dateDepart = new Date(inputDateRdv.value);
        const jours = parseInt(inputDuree.value);

        if (!isNaN(dateDepart.getTime()) && !isNaN(jours)) {
            // Calcul : Date de départ + nombre de jours
            const dateFin = new Date(dateDepart);
            dateFin.setDate(dateFin.getDate() + jours);

            // Formatage YYYY-MM-DD pour l'input date
            const yyyy = dateFin.getFullYear();
            const mm = String(dateFin.getMonth() + 1).padStart(2, '0');
            const dd = String(dateFin.getDate()).padStart(2, '0');
            
            inputDateProchain.value = `${yyyy}-${mm}-${dd}`;
        }
    }

    // Ecouter les changements sur les deux champs
    inputDuree.addEventListener('input', calculerProchainRDV);
    inputDateRdv.addEventListener('change', calculerProchainRDV);
});
</script>