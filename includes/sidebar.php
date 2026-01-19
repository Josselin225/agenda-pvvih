<?php
// On récupère le nom du fichier actuel
$current_page = basename($_SERVER['PHP_SELF']);

// On vérifie si on est dans le dossier 'pages' ou à la racine
// Si on est à la racine, le chemin vers 'pages' doit inclure le nom du dossier
// Si on est déjà dans 'pages', le chemin vers l'index doit remonter d'un cran (../)
$is_in_pages = (basename(dirname($_SERVER['PHP_SELF'])) == 'pages');

$path_to_root = $is_in_pages ? "../" : "";
$path_to_pages = $is_in_pages ? "" : "pages/";
?>

<div class="sidebar d-flex flex-column h-100">
    <div class="sidebar-header d-flex align-items-center justify-content-center py-4">
        <div class="logo-circle shadow">
            <i class="bi bi-heart-pulse-fill text-success fs-3"></i>
        </div>
        <span class="ms-3 fw-bold text-white fs-5 logo-text">PVVIH Admin</span>
    </div>
    
    <hr class="mx-3 border-light opacity-25">

    <ul class="nav flex-column px-2">
        <li class="nav-item">
            <a href="<?php echo $path_to_root; ?>index.php" 
            class="nav-link <?php echo ($current_page == 'index.php') ? 'active bg-white bg-opacity-25 shadow-sm' : ''; ?>">
                <i class="bi bi-grid me-2"></i> Dashboard
            </a>
        </li>

        <li class="nav-item">
            <a href="<?php echo $path_to_pages; ?>patients.php" 
            class="nav-link <?php echo ($current_page == 'patients.php') ? 'active bg-white bg-opacity-25 shadow-sm' : ''; ?>">
                <i class="bi bi-people me-2"></i> Patients
            </a>
        </li>

        <li class="nav-item">
            <a href="<?php echo $path_to_pages; ?>rendez_vous.php" 
            class="nav-link <?php echo ($current_page == 'rendez_vous.php') ? 'active bg-white bg-opacity-25 shadow-sm' : ''; ?>">
                <i class="bi bi-calendar-check-fill me-2"></i> Rendez-vous
            </a>
        </li>
    </ul>

    <div class="mt-auto p-3 text-center">
        <button id="sidebarCollapse" class="btn btn-sm btn-light rounded-pill px-3 shadow-sm">
            <i class="bi bi-arrow-left-right me-2"></i> Rétracter
        </button>
    </div>
</div>