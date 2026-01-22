<?php 
    require_once 'config/db.php'; 

    try {
        $countPatients = $pdo->query("SELECT COUNT(*) FROM patients")->fetchColumn();
        // Correction : Count des RDV du jour uniquement pour le dashboard
        $aujourdhui = date('Y-m-d');
        $countRdv = $pdo->query("SELECT COUNT(*) FROM rendez_vous WHERE date_prochain_rdv = '$aujourdhui'")->fetchColumn();
        $countRelances = $pdo->query("SELECT COUNT(*) FROM relances")->fetchColumn();
    } catch (Exception $e) {
        $countPatients = 0;
        $countRdv = 0;
        $countRelances = 0;
    }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda PVVIH - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

    <?php include 'includes/sidebar.php'; ?>

    <div id="content">
        <div class="topbar">
            <h4 id="page-title" class="fw-bold m-0">Tableau de Bord</h4>
            <div class="user-info text-muted">Admin | <?php echo date('d M Y'); ?></div>
        </div>

        <div class="container-fluid px-4 mt-4">
            <div class="row g-4">
                    <div class="col-md-4">
                        <a href="<?= BASE_URL ?>pages/patients.php" class="text-decoration-none text-dark">
                        <div class="card border-0 shadow-sm p-4 rounded-5 bg-white h-100">
                            <div class="d-flex align-items-center">
                                <div class="icon-box bg-success bg-opacity-10 text-success p-3 rounded-4 me-3">
                                    <i class="bi bi-people-fill fs-2"></i>
                                </div>
                                <div>
                                    <h6 class="text-muted mb-0">Total Patients</h6>
                                    <h2 class="fw-bold mb-0"><?php echo $countPatients; ?></h2>
                                </div>
                            </div>
                        </div>
                        </a>
                    </div>
                

                <div class="col-md-4">
                    <div class="card border-0 shadow-sm p-4 rounded-5 bg-white h-100">
                        <div class="d-flex align-items-center">
                            <div class="icon-box bg-primary bg-opacity-10 text-primary p-3 rounded-4 me-3">
                                <i class="bi bi-calendar-event fs-2"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-0">RDV Aujourd'hui</h6>
                                <h2 class="fw-bold mb-0"><?php echo $countRdv; ?></h2>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card border-0 shadow-sm p-4 rounded-5 bg-white h-100">
                        <div class="d-flex align-items-center">
                            <div class="icon-box bg-danger bg-opacity-10 text-danger p-3 rounded-4 me-3">
                                <i class="bi bi-telephone-outbound fs-2"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-0">Relances urgentes</h6>
                                <h2 class="fw-bold mb-0">0</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        $(document).ready(function () {
            // Fonction de bascule de la sidebar
            $('#sidebarCollapse').on('click', function () {
                $('.sidebar').toggleClass('collapsed');
            });

            // Optionnel : maintenir l'état au rafraîchissement
            if(localStorage.getItem('sidebarState') === 'collapsed') {
                $('.sidebar').addClass('collapsed');
            }

            $('.sidebar').on('transitionend', function() {
                const isCollapsed = $(this).hasClass('collapsed');
                localStorage.setItem('sidebarState', isCollapsed ? 'collapsed' : 'expanded');
            });
        });
    </script>
</body>
</html>