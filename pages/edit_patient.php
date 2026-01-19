<?php
require_once '../config/db.php';
include '../includes/header.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Récupération de toutes les colonnes de la table patients et de l'aval associé
$sql = "SELECT p.*, a.nom_complet as aval_nom, a.lien_parente, a.telephone as aval_tel, a.confidentialite 
        FROM patients p 
        LEFT JOIN avals a ON p.id = a.patient_id 
        WHERE p.id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $id]);
$patient = $stmt->fetch();

if (!$patient) {
    echo "<script>window.location.href='patients.php';</script>";
    exit;
}
?>

<div class="container-fluid px-4 mt-4">
    <div class="d-flex align-items-center mb-4">
        <a href="patients.php" class="btn btn-white shadow-sm rounded-circle me-3 text-dark"><i class="bi bi-arrow-left"></i></a>
        <h4 class="fw-bold mb-0 text-uppercase text-dark">Modifier le dossier : <?= $patient['nom'] ?> <?= $patient['prenoms'] ?></h4>
    </div>

    <form action="../php/update_patient.php" method="POST">
        <input type="hidden" name="id" value="<?= $patient['id'] ?>">
        
        <div class="row g-4">
            <div class="col-md-8">
                <div class="bg-white rounded-5 shadow-sm p-4 h-100">
                    <h6 class="fw-bold text-success border-bottom pb-2 mb-4"><i class="bi bi-person-lines-fill me-2"></i>IDENTITÉ COMPLÈTE DU PATIENT</h6>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Code Patient (Non modifiable)</label>
                            <input type="text" class="form-control bg-light border-0 fw-bold shadow-sm" value="<?= $patient['code'] ?>" readonly>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Catégorie</label>
                            <select name="categorie" class="form-select bg-light border-0" required>
                                <?php 
                                $cats = ['POPULATION GÉNÉRALE', 'FEMME ENCEINTE', 'FEMME ALLAITANTE', 'ENFANT EXPOSÉ', 'POPULATION CLÉ'];
                                foreach($cats as $cat) {
                                    $selected = ($patient['categorie'] == $cat) ? 'selected' : '';
                                    echo "<option value='$cat' $selected>$cat</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Nom</label>
                            <input type="text" name="nom" class="form-control bg-light border-0 text-uppercase" value="<?= $patient['nom'] ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Prénoms</label>
                            <input type="text" name="prenoms" class="form-control bg-light border-0 text-uppercase" value="<?= $patient['prenoms'] ?>" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Sexe</label>
                            <select name="sexe" class="form-select bg-light border-0" required>
                                <option value="M" <?= ($patient['sexe'] == 'M') ? 'selected' : '' ?>>Masculin</option>
                                <option value="F" <?= ($patient['sexe'] == 'F') ? 'selected' : '' ?>>Féminin</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Date de Naissance</label>
                            <input type="date" name="date_naissance" class="form-control bg-light border-0" value="<?= $patient['date_naissance'] ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Âge actuel</label>
                            <input type="number" name="age" class="form-control bg-light border-0" value="<?= $patient['age'] ?>" required>
                        </div>

                        <div class="col-md-12 mt-4">
                            <h6 class="fw-bold text-success border-bottom pb-2 mb-3"><i class="bi bi-geo-alt me-2"></i>CONTACTS ET ADRESSE</h6>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Quartier / Résidence</label>
                            <input type="text" name="quartier" class="form-control bg-light border-0 text-uppercase" value="<?= $patient['quartier'] ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Téléphone 1 (Principal)</label>
                            <input type="text" name="telephone1" class="form-control bg-light border-0" value="<?= $patient['telephone1'] ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Téléphone 2 (Urgence)</label>
                            <input type="text" name="telephone2" class="form-control bg-light border-0" value="<?= $patient['telephone2'] ?>">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="bg-dark rounded-5 shadow-sm p-4 text-white h-100 d-flex flex-column">
                    <h6 class="fw-bold text-success border-bottom border-secondary pb-2 mb-4">PERSONNE DE SOUTIENT (AVAL)</h6>
                    
                    <div class="mb-3">
                        <label class="form-label small opacity-75">Nom Complet de l'Aval</label>
                        <input type="text" name="aval_nom" class="form-control bg-white bg-opacity-10 border-0 text-white" value="<?= $patient['aval_nom'] ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small opacity-75">Lien de parenté</label>
                        <select name="lien_parente" class="form-select bg-white bg-opacity-10 border-0 text-white">
                            <?php 
                            $liens = ['CONJOINT(E)', 'PARENT', 'FRÈRE/SŒUR', 'ENFANT', 'AMI(E)', 'AUTRE'];
                            foreach($liens as $lien) {
                                $sel = ($patient['lien_parente'] == $lien) ? 'selected' : '';
                                echo "<option value='$lien' class='text-dark' $sel>$lien</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small opacity-75">Téléphone Aval</label>
                        <input type="text" name="aval_tel" class="form-control bg-white bg-opacity-10 border-0 text-white" value="<?= $patient['aval_tel'] ?>">
                    </div>
                    <div class="mb-4">
                        <label class="form-label small opacity-75 d-block">Confidentialité</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="confidentialite" value="OUI" <?= ($patient['confidentialite'] == 'OUI') ? 'checked' : '' ?>>
                            <label class="form-check-label small">OUI</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="confidentialite" value="NON" <?= ($patient['confidentialite'] == 'NON') ? 'checked' : '' ?>>
                            <label class="form-check-label small">NON</label>
                        </div>
                    </div>

                    <div class="mt-auto">
                        <button type="submit" class="btn btn-success w-100 rounded-pill py-3 fw-bold shadow">
                            <i class="bi bi-save2 me-2"></i> METTRE À JOUR LE DOSSIER
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.querySelector('input[name="date_naissance"]').addEventListener('change', function() {
    const dateNaissance = new Date(this.value);
    const aujourdhui = new Date();
    
    if (!isNaN(dateNaissance.getTime())) {
        let age = aujourdhui.getFullYear() - dateNaissance.getFullYear();
        const mois = aujourdhui.getMonth() - dateNaissance.getMonth();
        
        // Ajustement si l'anniversaire n'est pas encore passé cette année
        if (mois < 0 || (mois === 0 && aujourdhui.getDate() < dateNaissance.getDate())) {
            age--;
        }
        
        // Mise à jour du champ âge (on s'assure que l'âge n'est pas négatif)
        document.querySelector('input[name="age"]').value = age >= 0 ? age : 0;
    }
});
</script>