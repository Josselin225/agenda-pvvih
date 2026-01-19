<?php 
    // 1. Connexion et calculs (Logique)
    require_once 'config/db.php'; 

    try {
        $countPatients = $pdo->query("SELECT COUNT(*) FROM patients")->fetchColumn();
        $countRdv = $pdo->query("SELECT COUNT(*) FROM rendez_vous")->fetchColumn();
        $countRelances = $pdo->query("SELECT COUNT(*) FROM relances")->fetchColumn();
    } catch (Exception $e) {
        $countPatients = 0;
        $countRdv = 0;
        $countRelances = 0;
    }
?>

<?php 
    // 4. Affichage de la fin de page (Scripts + Fermeture balises)
    include 'includes/footer.php'; 
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
            <div class="user-info text-muted">Admin | 17 Jan 2026</div>
        </div>

        <div class="container-fluid px-4 mt-4">
            <div class="row g-4">
                <div class="col-md-4">
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
                                <h2 class="fw-bold mb-0">0</h2> </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#sidebarCollapse').on('click', function () {
                $('.sidebar').toggleClass('collapsed');
                // Optionnel: ajouter du CSS pour .sidebar.collapsed { width: 80px; }
            });
        });
    </script>
</body>
</html>