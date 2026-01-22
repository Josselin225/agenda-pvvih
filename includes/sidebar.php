<?php
$current_page = basename($_SERVER['PHP_SELF']);
$is_in_pages = (basename(dirname($_SERVER['PHP_SELF'])) == 'pages');

$path_to_root = $is_in_pages ? "../" : "";
$path_to_pages = $is_in_pages ? "" : "pages/";
?>

<div class="sidebar d-flex flex-column">
    <div class="sidebar-header d-flex align-items-center justify-content-center py-4">
        <div class="logo-circle shadow">
            <i class="bi bi-heart-pulse-fill text-success fs-3"></i>
        </div>
        <span class="ms-3 fw-bold text-white fs-5 logo-text link-text">PVVIH Admin</span>
    </div>
    
    <hr class="mx-3 border-light opacity-25">

    <ul class="nav flex-column px-2">
        <li class="nav-item">
            <a href="<?php echo $path_to_root; ?>index.php" 
               class="nav-link <?php echo ($current_page == 'index.php') ? 'active shadow-sm' : ''; ?>">
                <i class="bi bi-grid fs-5"></i> 
                <span class="link-text">Dashboard</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="<?php echo $path_to_pages; ?>patients.php" 
               class="nav-link <?php echo ($current_page == 'patients.php') ? 'active shadow-sm' : ''; ?>">
                <i class="bi bi-people fs-5"></i> 
                <span class="link-text">Patients</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="<?php echo $path_to_pages; ?>rendez_vous.php" 
               class="nav-link <?php echo ($current_page == 'rendez_vous.php') ? 'active shadow-sm' : ''; ?>">
                <i class="bi bi-calendar-check-fill fs-5"></i> 
                <span class="link-text">Rendez-vous</span>
            </a>
        </li>
    </ul>

    <div class="mt-auto p-3 text-center">
        <button id="sidebarCollapse" class="btn btn-sm btn-light rounded-pill px-3 shadow-sm w-100">
            <i class="bi bi-arrow-left-right"></i> 
            <span class="link-text ms-1">Rétracter</span>
        </button>
    </div>
</div>