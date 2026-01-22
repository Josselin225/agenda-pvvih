<?php
    require_once '../config/db.php';

/* =========================
   1. Récupération des filtres
   ========================= */
   $aujourdhui = date('Y-m-d');

    $search     = $_GET['search'] ?? '';
    $date_debut = $_GET['date_debut'] ?? '';
    $date_fin   = $_GET['date_fin'] ?? '';
    $filtre_utilisateur = ($search !== '' || $date_debut !== '' || $date_fin !== '');

    if ($date_debut !== '' && $date_fin !== '' && $date_debut > $date_fin) {
    [$date_debut, $date_fin] = [$date_fin, $date_debut];
    }

    /* =========================
    2. Pagination
    ========================= */
    $elements_par_page = 10;
    $page_actuelle = isset($_GET['p']) ? (int)$_GET['p'] : 1;
    if ($page_actuelle < 1) $page_actuelle = 1;
    $debut = ($page_actuelle - 1) * $elements_par_page;

    /* =========================
    3. Construction du WHERE
    ========================= */
    $where  = " WHERE 1=1 ";
    $params = [];

    /* =========================
    FILTRE PAR DÉFAUT : AUJOURD'HUI
    ========================= */
    if (!$filtre_utilisateur) {
        $where .= " AND r.date_prochain_rdv = :aujourdhui";
        $params['aujourdhui'] = $aujourdhui;
    }

    /* =========================
    FILTRES UTILISATEUR
    ========================= */

    // Recherche (code, nom, prénoms)
    if ($search !== '') {
        $where .= " AND (p.code LIKE :search
                        OR p.nom LIKE :search
                        OR p.prenoms LIKE :search)";
        $params['search'] = "%$search%";
    }

    // Date début
    // Filtre par intervalle de dates
    if ($date_debut !== '' && $date_fin !== '') {

        $where .= " AND r.date_prochain_rdv BETWEEN :date_debut AND :date_fin";
        $params['date_debut'] = $date_debut;
        $params['date_fin']   = $date_fin;

    } elseif ($date_debut !== '') {

        $where .= " AND r.date_prochain_rdv >= :date_debut";
        $params['date_debut'] = $date_debut;

    } elseif ($date_fin !== '') {

        $where .= " AND r.date_prochain_rdv <= :date_fin";
        $params['date_fin'] = $date_fin;
    }


    /* =========================
    4. COUNT pour pagination
    ========================= */
    $count_sql = "
        SELECT COUNT(*)
        FROM rendez_vous r
        JOIN patients p ON r.patient_id = p.id
        $where
    ";

    $count_stmt = $pdo->prepare($count_sql);
    $count_stmt->execute($params);
    $total_rdv = $count_stmt->fetchColumn();
    $total_pages = ceil($total_rdv / $elements_par_page);

    /* =========================
    5. Requête principale
    ========================= */
    $sql = "
        SELECT r.*, p.nom, p.prenoms, p.code
        FROM rendez_vous r
        JOIN patients p ON r.patient_id = p.id
        $where
        ORDER BY r.date_prochain_rdv DESC
        LIMIT :debut, :limite
    ";

    $stmt = $pdo->prepare($sql);

    // Bind des filtres
    foreach ($params as $key => $value) {
        $stmt->bindValue(":$key", $value);
    }

    // Bind pagination (IMPORTANT)
    $stmt->bindValue(':debut', $debut, PDO::PARAM_INT);
    $stmt->bindValue(':limite', $elements_par_page, PDO::PARAM_INT);

    $stmt->execute();

    // Résultat final
    $rendezVous = $stmt->fetchAll(PDO::FETCH_ASSOC);


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
            <div class="mb-4">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0">Suivi des Rendez-vous</h5>

                    <a href="<?= BASE_URL ?>pages/nouveau_rdv.php"
                    class="btn btn-success rounded-pill px-4 fw-bold shadow-sm">
                        <i class="bi bi-plus-lg me-2"></i> Nouveau RDV
                    </a>
                </div>

                <form method="GET" id="filterForm">

                    <div class="row g-3 align-items-center">

                        <!-- Recherche patient -->
                        <div class="col-md-6">
                            <div class="input-group shadow-sm rounded-pill overflow-hidden border">
                                <span class="input-group-text bg-white border-0 ps-3">
                                    <i class="bi bi-search text-muted"></i>
                                </span>
                                <input type="text"
                                    id="search"
                                    name="search"
                                    class="form-control border-0 py-2"
                                    placeholder="Code, nom ou prénom"
                                    value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                            </div>
                        </div>

                        <!-- Date début -->
                        <div class="col-md-3">
                            <input type="date"
                                id="date_debut"
                                name="date_debut"
                                class="form-control shadow-sm rounded-pill py-2"
                                value="<?= $_GET['date_debut'] ?? '' ?>">
                        </div>

                        <!-- Date fin -->
                        <div class="col-md-3">
                            <input type="date"
                                id="date_fin"
                                name="date_fin"
                                class="form-control shadow-sm rounded-pill py-2"
                                value="<?= $_GET['date_fin'] ?? '' ?>">
                        </div>

                    </div>

                </form>


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
                            <?php foreach ($rendezVous as $row): ?>
                                <tr>
                                    <td>
                                        <div class="fw-bold text-uppercase text-dark">
                                            <?= htmlspecialchars($row['nom'].' '.$row['prenoms']) ?>
                                        </div>
                                        <small class="text-muted"><?= htmlspecialchars($row['code']) ?></small>
                                    </td>

                                    <td class="text-muted">
                                        <?= $row['date_derniere_visite']
                                            ? date('d/m/Y', strtotime($row['date_derniere_visite']))
                                            : '-' ?>
                                    </td>

                                    <td>
                                        <span class="badge bg-light text-dark border px-3 py-2 fw-normal rounded-3">
                                            <?= date('d/m/Y', strtotime($row['date_rdv'])) ?>
                                        </span>
                                    </td>

                                    <td class="small text-muted">
                                        <?= nl2br(htmlspecialchars($row['motif_rdv'])) ?>
                                    </td>

                                    <td>
                                        <span class="badge rounded-pill <?= getAssiduiteBadge($row['assiduite']) ?> px-3">
                                            <?= strtoupper($row['assiduite']) ?>
                                        </span>
                                    </td>

                                    <td>
                                        <div class="fw-bold text-success">
                                            <?= date('d/m/Y', strtotime($row['date_prochain_rdv'])) ?>
                                        </div>
                                    </td>

                                    <td class="text-center">
                                        <a href="detail_patient.php?id=<?= $row['patient_id'] ?>"
                                        class="btn btn-sm btn-outline-primary rounded-circle">
                                            <i class="bi bi-eye-fill"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    Aucun rendez-vous trouvé.
                                </td>
                            </tr>
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
    document.addEventListener('DOMContentLoaded', function () {

        const form = document.getElementById('filterForm');
        const search = document.getElementById('search');
        const dateDebut = document.getElementById('date_debut');
        const dateFin = document.getElementById('date_fin');

        if (!form || !search || !dateDebut || !dateFin) {
            console.error('Un ou plusieurs champs sont introuvables');
            return;
        }

        let timer = null;

        // Recherche texte
        search.addEventListener('keyup', function () {
            clearTimeout(timer);
            timer = setTimeout(() => {
                form.submit();
            }, 400);
        });

        // Dates
        dateDebut.addEventListener('change', () => form.submit());
        dateFin.addEventListener('change', () => form.submit());

    });
</script>

</body>
</html>