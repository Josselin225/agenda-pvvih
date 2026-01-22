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

// Requête optimisée avec jointure pour l'aval
$sql = "SELECT p.*, MAX(a.nom_complet) as nom_aval 
        FROM patients p 
        LEFT JOIN avals a ON p.id = a.patient_id 
        GROUP BY p.id 
        ORDER BY p.id DESC 
        LIMIT $debut, $elements_par_page";

$stmt = $pdo->query($sql);

// Fonction pour les classes de badges
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

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda PVVIH - Gestion des Patients</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body class="bg-light">

    <?php include '../includes/sidebar.php'; ?>

    <div id="content">
        <div class="container-fluid px-4 mt-4">
            
            <?php if (isset($_GET['msg']) && $_GET['msg'] == 'success'): ?>
                <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm border-0 mb-4" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    <strong>Succès !</strong> L'opération a été effectuée avec succès.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="bg-white rounded-5 shadow-sm p-4 border-0">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
                    <h5 class="fw-bold mb-0 text-dark">
                        <i class="bi bi-people-fill text-success me-2"></i>Gestion des Patients
                    </h5>
                    
                    <div class="input-group w-100 w-md-50 shadow-sm rounded-pill overflow-hidden border">
                        <span class="input-group-text bg-white border-0 ps-3"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" id="searchPatient" class="form-control border-0 py-2" placeholder="Rechercher un nom ou un code...">
                    </div>

                    <button type="button" class="btn btn-success rounded-pill px-4 shadow-sm fw-bold" data-bs-toggle="modal" data-bs-target="#modalPatient">
                        <i class="bi bi-plus-lg me-2"></i>Nouveau Patient
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="tablePatients">
                        <thead class="table-light border-bottom">
                            <tr>
                                <th class="py-3">Code</th>
                                <th class="py-3">Nom & Prénoms</th>
                                <th class="py-3">Téléphone</th>
                                <th class="py-3">Catégorie</th>
                                <th class="py-3">Aval</th>
                                <th class="py-3 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if($total_patients > 0): ?>
                                <?php while ($row = $stmt->fetch()) : ?>
                                    <tr>
                                        <td><span class="badge bg-light text-dark border fw-normal py-2 px-3">#<?= htmlspecialchars($row['code']) ?></span></td>
                                        <td class="fw-bold text-dark text-uppercase"><?= htmlspecialchars($row['nom']) ?> <?= htmlspecialchars($row['prenoms']) ?></td>
                                        <td><i class="bi bi-telephone text-muted me-2"></i><?= htmlspecialchars($row['telephone1']) ?></td>
                                        <td>
                                            <span class="badge rounded-pill <?= getBadgeClass($row['categorie']) ?> px-3 py-2" style="font-size: 0.75rem;">
                                                <?= $row['categorie'] ?>
                                            </span>
                                        </td>
                                        <td><?= $row['nom_aval'] ? '<span class="text-muted small">'.$row['nom_aval'].'</span>' : '<small class="text-muted fst-italic">Aucun</small>' ?></td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-2">
                                                <a href="detail_patient.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-primary rounded-circle" title="Voir détails">
                                                    <i class="bi bi-eye-fill"></i>
                                                </a>
                                                <a href="edit_patient.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-warning rounded-circle" title="Modifier">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted fst-italic">Aucun patient trouvé dans la base de données.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4 border-top pt-3 gap-3">
                    <div class="text-muted small">
                        Affichage de <b><?= ($total_patients > 0) ? ($debut + 1) : 0 ?></b> à <b><?= min($debut + $elements_par_page, $total_patients) ?></b> sur <b><?= $total_patients ?></b> patients
                    </div>
                    
                    <?php if ($total_pages > 1): ?>
                    <nav>
                        <ul class="pagination pagination-sm mb-0 shadow-sm">
                            <li class="page-item <?= ($page_actuelle <= 1) ? 'disabled' : '' ?>">
                                <a class="page-link border-0 rounded-start-4 px-3" href="?p=<?= $page_actuelle - 1 ?>"><i class="bi bi-chevron-left"></i></a>
                            </li>
                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <li class="page-item <?= ($page_actuelle == $i) ? 'active' : '' ?>">
                                    <a class="page-link border-0 mx-1 <?= ($page_actuelle == $i) ? 'bg-success text-white' : 'text-dark' ?>" href="?p=<?= $i ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>
                            <li class="page-item <?= ($page_actuelle >= $total_pages) ? 'disabled' : '' ?>">
                                <a class="page-link border-0 rounded-end-4 px-3" href="?p=<?= $page_actuelle + 1 ?>"><i class="bi bi-chevron-right"></i></a>
                            </li>
                        </ul>
                    </nav>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <?php include 'modal_patient.php'; ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function () {
            // 1. Recherche dynamique dans la table
            $('#searchPatient').on('keyup', function() {
                const value = $(this).val().toLowerCase();
                $("#tablePatients tbody tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });

            // 2. Gestion de la Sidebar (Rétracter)
            const sidebar = $('.sidebar');
            
            // Appliquer l'état mémorisé
            if (localStorage.getItem('sidebar-collapsed') === 'true') {
                sidebar.addClass('collapsed');
            }

            $('#sidebarCollapse').on('click', function () {
                sidebar.toggleClass('collapsed');
                // Mémoriser l'état
                localStorage.setItem('sidebar-collapsed', sidebar.hasClass('collapsed'));
            });
        });
    </script>
</body>
</html>