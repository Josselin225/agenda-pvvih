<?php
require_once '../config/db.php';
include '../includes/header.php';

// Pagination pour les rendez-vous
$elements_par_page = 10;
$page_actuelle = isset($_GET['p']) ? (int)$_GET['p'] : 1;
$debut = ($page_actuelle - 1) * $elements_par_page;

// Récupération du total pour la pagination
$total_rdv = $pdo->query("SELECT COUNT(*) FROM rendez_vous")->fetchColumn();
$total_pages = ceil($total_rdv / $elements_par_page);

// Requête avec jointure pour avoir le nom du patient
$sql = "SELECT r.*, p.nom, p.prenoms, p.code 
        FROM rendez_vous r
        JOIN patients p ON r.patient_id = p.id
        ORDER BY r.date_rdv DESC
        LIMIT $debut, $elements_par_page";

$stmt = $pdo->query($sql);
?>

<div class="container-fluid px-4 mt-4">
    <div class="bg-white rounded-5 shadow-sm p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="fw-bold mb-0"><i class="bi bi-calendar-event me-2 text-success"></i>Suivi des Rendez-vous</h5>
            
            <div class="d-flex">
                <div class="input-group me-3">
                    <span class="input-group-text bg-light border-0"><i class="bi bi-search"></i></span>
                    <input type="text" id="searchRdv" class="form-control bg-light border-0" placeholder="Rechercher un patient...">
                </div>
                <a href="nouveau_rdv.php" class="btn btn-success rounded-pill px-4">
                    <i class="bi bi-plus-lg me-2"></i> Nouveau RDV
                </a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle" id="tableRdv">
                <thead class="table-light">
                    <tr>
                        <th>Patient</th>
                        <th>Dernière Visite</th>
                        <th>RDV Actuel</th>
                        <th>Motif</th>
                        <th>Assiduité</th>
                        <th>Prochain RDV</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $stmt->fetch()) : ?>
                        <tr>
                            <td>
                                <div class="fw-bold text-uppercase"><?= $row['nom'] ?> <?= $row['prenoms'] ?></div>
                                <small class="text-muted">#<?= $row['code'] ?></small>
                            </td>
                            <td class="small"><?= $row['date_derniere_visite'] ? date('d/m/Y', strtotime($row['date_derniere_visite'])) : '-' ?></td>
                            <td><span class="badge bg-light text-dark border"><?= date('d/m/Y', strtotime($row['date_rdv'])) ?></span></td>
                            <td class="small text-truncate" style="max-width: 150px;"><?= $row['motif_rdv'] ?></td>
                            <td>
                                <?php 
                                    $badge = ($row['assiduite'] == 'BONNE') ? 'bg-success' : (($row['assiduite'] == 'MOYENNE') ? 'bg-warning' : 'bg-danger');
                                ?>
                                <span class="badge rounded-pill <?= $badge ?> shadow-sm" style="font-size: 0.7rem;">
                                    <?= $row['assiduite'] ?>
                                </span>
                            </td>
                            <td class="fw-bold text-success">
                                <?= date('d/m/Y', strtotime($row['date_prochain_rdv'])) ?>
                                <div class="small fw-normal text-muted italic"><?= $row['motif_prochain_rdv'] ?></div>
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="detail_patient.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-light rounded-circle shadow-sm mx-1">
                                        <i class="bi bi-eye-fill text-primary"></i>
                                    </a>
                                    <a href="nouveau_rdv.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-light rounded-circle shadow-sm mx-1" title="Nouveau RDV">
                                        <i class="bi bi-calendar-plus-fill text-success"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-3">
            <div class="text-muted small">Total : <b><?= $total_rdv ?></b> rendez-vous enregistrés</div>
            <?php if ($total_pages > 1): ?>
                <nav>
                    <ul class="pagination pagination-sm mb-0">
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="page-item <?= ($page_actuelle == $i) ? 'active' : '' ?>">
                                <a class="page-link border-0 rounded-3 mx-1 <?= ($page_actuelle == $i) ? 'bg-success text-white' : 'text-dark' ?>" href="?p=<?= $i ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>