<?php
require_once '../config/db.php';

// --- 1. LOGIQUE DE PAGINATION ---
$elements_par_page = 10;
$page_actuelle = isset($_GET['p']) ? (int)$_GET['p'] : 1;
if ($page_actuelle < 1) $page_actuelle = 1;
$debut = ($page_actuelle - 1) * $elements_par_page;

$total_query = $pdo->query("SELECT COUNT(*) FROM rendez_vous");
$total_rdv = $total_query->fetchColumn();
$total_pages = ceil($total_rdv / $elements_par_page);

$sql = "SELECT r.*, p.nom, p.prenoms, p.code 
        FROM rendez_vous r
        JOIN patients p ON r.patient_id = p.id
        ORDER BY r.date_rdv DESC
        LIMIT $debut, $elements_par_page";

$stmt = $pdo->query($sql);

function getAssiduiteBadge($status) {
    switch (trim(strtoupper($status))) {
        case 'RDV RESPECTE': return 'bg-secondary text-white';
        case 'RDV ANTICIPE': return 'bg-danger text-white';
        case 'RDV RATTRAPER': return 'bg-danger text-white';
        default: return 'bg-light text-dark border';
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suivi des Rendez-vous</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        /* Ajustement pour le layout dynamique */
        body {
            display: flex; /* Force l'alignement horizontal avec la sidebar */
            min-height: 100vh;
            background-color: #f8f9fa;
            margin: 0;
        }
        #content {
            flex-grow: 1; /* Occupe tout l'espace restant */
            width: 100%;
            transition: all 0.3s;
            overflow-x: hidden;
        }
        .table-responsive {
            overflow-x: auto;
        }
    </style>
</head>
<body>

<?php include '../includes/sidebar.php'; ?>

<div id="content">
    <div class="container-fluid px-4 mt-4">
        
        <div class="bg-white rounded-5 shadow-sm p-4 mb-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold mb-0">Suivi des Rendez-vous</h5>
                
                <div class="input-group w-50 shadow-sm rounded-pill overflow-hidden border">
                    <span class="input-group-text bg-white border-0 ps-3">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" id="searchRdv" class="form-control border-0 py-2" placeholder="Rechercher un patient...">
                </div>

                <a href="nouveau_rdv.php" class="btn btn-success rounded-pill px-4 fw-bold shadow-sm">
                    <i class="bi bi-plus-lg me-2"></i> Nouveau RDV
                </a>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="tableRdv">
                    <thead class="table-light border-bottom">
                        <tr>
                            <th class="py-3">Patient</th>
                            <th class="py-3">Dernière Visite</th>
                            <th class="py-3">RDV Actuel</th>
                            <th class="py-3">Motif</th>
                            <th class="py-3">Assiduité</th>
                            <th class="py-3">Prochain RDV</th>
                            <th class="py-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($total_rdv > 0): ?>
                            <?php while ($row = $stmt->fetch()) : ?>
                                <tr>
                                    <td>
                                        <div class="fw-bold text-uppercase text-dark"><?= htmlspecialchars($row['nom'] . ' ' . $row['prenoms']) ?></div>
                                        <small class="text-muted"><?= htmlspecialchars($row['code']) ?></small>
                                    </td>
                                    <td class="text-muted">
                                        <?= $row['date_derniere_visite'] ? date('d/m/Y', strtotime($row['date_derniere_visite'])) : '-' ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border px-3 py-2 fw-normal rounded-3">
                                            <?= date('d/m/Y', strtotime($row['date_rdv'])) ?>
                                        </span>
                                    </td>
                                    <td class="small text-muted" style="max-width: 250px;">
                                        <?= nl2br(htmlspecialchars($row['motif_rdv'])) ?>
                                    </td>
                                    <td>
                                        <span class="badge rounded-pill <?= getAssiduiteBadge($row['assiduite']) ?> px-3" style="font-size: 0.65rem; letter-spacing: 0.5px;">
                                            <?= strtoupper($row['assiduite']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-success"><?= date('d/m/Y', strtotime($row['date_prochain_rdv'])) ?></div>
                                        <div style="font-size: 0.7rem;" class="text-muted text-uppercase"><?= htmlspecialchars($row['motif_prochain_rdv'] ?? '') ?></div>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="detail_patient.php?id=<?= $row['patient_id'] ?>" class="btn btn-sm btn-outline-primary rounded-circle shadow-sm">
                                                <i class="bi bi-eye-fill"></i>
                                            </a>
                                            <a href="nouveau_rdv.php?code=<?= urlencode($row['code']) ?>" class="btn btn-sm btn-outline-success rounded-circle">
                                                <i class="bi bi-calendar-plus-fill"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="7" class="text-center py-4 text-muted">Aucun rendez-vous trouvé.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-4 border-top pt-3">
                <div class="text-muted small">
                    Affichage de <?= $total_rdv > 0 ? $debut + 1 : 0 ?> à <?= min($debut + $elements_par_page, $total_rdv) ?> sur <b><?= $total_rdv ?></b>
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function () {
        $('#searchRdv').on('keyup', function() {
            let value = $(this).val().toLowerCase();
            $("#tableRdv tbody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });

        $('#sidebarCollapse').on('click', function () {
            $('.sidebar').toggleClass('collapsed');
            // Optionnel : Forcer un recalcul de largeur si nécessaire
            localStorage.setItem('sidebar-collapsed', $('.sidebar').hasClass('collapsed'));
        });
    });
</script>

</body>
</html>