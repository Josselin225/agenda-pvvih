<?php
require_once '../config/db.php'; 

// --- 1. LOGIQUE DE PAGINATION ---
$elements_par_page = 11; 
$page_actuelle = isset($_GET['p']) ? (int)$_GET['p'] : 1;
if ($page_actuelle < 1) $page_actuelle = 1;
$debut = ($page_actuelle - 1) * $elements_par_page;

// Calcul du nombre de pages
$total_query = $pdo->query("SELECT COUNT(*) FROM patients"); 
$total_patients = $total_query->fetchColumn();
$total_pages = ceil($total_patients / $elements_par_page);

// Requête compatible avec le mode ONLY_FULL_GROUP_BY
$sql = "SELECT p.*, MAX(a.nom_complet) as nom_aval 
        FROM patients p 
        LEFT JOIN avals a ON p.id = a.patient_id 
        GROUP BY p.id 
        ORDER BY p.id DESC 
        LIMIT $debut, $elements_par_page";

$stmt = $pdo->query($sql);

include '../includes/header.php'; 

// Fonction pour les badges (si elle n'est pas dans un autre fichier)
if (!function_exists('getBadgeClass')) {
    function getBadgeClass($categorie) {
        switch ($categorie) {
            case 'FEMME ENCEINTE': return 'bg-danger text-white';
            case 'FEMME ALLAITANTE': return 'bg-warning text-dark';
            case 'ENFANT EXPOSÉ': return 'bg-info text-white';
            case 'POPULATION CLÉ': return 'bg-primary text-white';
            default: return 'bg-secondary text-white';
        }
    }
}
?>

<?php if (isset($_GET['msg']) && $_GET['msg'] == 'success'): ?>
    <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm border-0 mb-4" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>
        <strong>Succès !</strong> Le dossier du patient a été mis à jour avec succès.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="container-fluid px-4 mt-4">
    <div class="bg-white rounded-5 shadow-sm p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="fw-bold mb-0"><i class="bi bi-people me-2"></i>Gestion des Patients</h5>
            
            <div class="input-group w-50 mx-3">
                <span class="input-group-text bg-light border-0"><i class="bi bi-search"></i></span>
                <input type="text" id="searchPatient" class="form-control bg-light border-0" placeholder="Rechercher par nom ou code...">
            </div>

            <button type="button" class="btn btn-success rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#modalPatient">
                <i class="bi bi-plus-lg me-2"></i> Nouveau Patient
            </button>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle" id="tablePatients">
                <thead class="table-light">
                    <tr>
                        <th>Code</th>
                        <th>Nom & Prénoms</th>
                        <th>Téléphone</th>
                        <th>Catégorie</th>
                        <th>Aval</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    // On utilise ici le $stmt défini tout en haut de la page
                    while ($row = $stmt->fetch()) : ?>
                        <tr>
                            <td><span class="badge bg-light text-dark border fw-normal">#<?= $row['code'] ?></span></td>
                            <td class="fw-semibold text-uppercase"><?= $row['nom'] ?> <?= $row['prenoms'] ?></td>
                            <td><i class="bi bi-telephone text-muted me-1"></i><?= $row['telephone1'] ?></td>
                            <td>
                                <span class="badge rounded-pill <?= getBadgeClass($row['categorie']) ?>" style="font-size: 0.7rem;">
                                    <?= $row['categorie'] ?>
                                </span>
                            </td>
                            <td><?= $row['nom_aval'] ? '<span class="small">'.$row['nom_aval'].'</span>' : '<small class="text-muted italic">Aucun</small>' ?></td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="detail_patient.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-light rounded-circle shadow-sm mx-1"><i class="bi bi-eye-fill text-primary"></i></a>
                                    <a href="edit_patient.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-light rounded-circle shadow-sm mx-1"><i class="bi bi-pencil-square text-warning"></i></a>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <div class="d-flex justify-content-between align-items-center mt-3 px-2">
                <div class="text-muted small">
                    Affichage de <b><?= ($total_patients > 0) ? ($debut + 1) : 0 ?></b> à <b><?= min($debut + $elements_par_page, $total_patients) ?></b> sur <b><?= $total_patients ?></b> patients
                </div>
                
                <?php if ($total_pages > 1): ?>
                <nav>
                    <ul class="pagination pagination-sm mb-0">
                        <li class="page-item <?= ($page_actuelle <= 1) ? 'disabled' : '' ?>">
                            <a class="page-link shadow-sm border-0 rounded-3 me-1" href="?p=<?= $page_actuelle - 1 ?>"><i class="bi bi-chevron-left"></i></a>
                        </li>
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="page-item <?= ($page_actuelle == $i) ? 'active' : '' ?>">
                                <a class="page-link shadow-sm border-0 rounded-3 mx-1 <?= ($page_actuelle == $i) ? 'bg-success text-white' : 'text-dark' ?>" href="?p=<?= $i ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                        <li class="page-item <?= ($page_actuelle >= $total_pages) ? 'disabled' : '' ?>">
                            <a class="page-link shadow-sm border-0 rounded-3 ms-1" href="?p=<?= $page_actuelle + 1 ?>"><i class="bi bi-chevron-right"></i></a>
                        </li>
                    </ul>
                </nav>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include 'modal_patient.php'; ?>
<?php include '../includes/footer.php'; ?>

<script>
    document.getElementById('searchPatient').addEventListener('keyup', function() {
        let filter = this.value.toUpperCase();
        let rows = document.querySelector("#tablePatients tbody").querySelectorAll("tr");
        rows.forEach(row => {
            let code = row.cells[0].textContent.toUpperCase();
            let nom = row.cells[1].textContent.toUpperCase();
            row.style.display = (code.indexOf(filter) > -1 || nom.indexOf(filter) > -1) ? "" : "none";
        });
    });
</script>