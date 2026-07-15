<?php
$currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

function isSidebarActive($paths)
{
    global $currentPath;

    foreach ((array) $paths as $path) {
        if ($currentPath === $path || strpos($currentPath, rtrim($path, '/') . '/') === 0) {
            return ' active';
        }
    }

    return '';
}
?>

<aside class="offcanvas-md offcanvas-start p-4 bg-dark text-light min-vh-100" tabindex="-1" id="sidebar" aria-labelledby="sidebarLabel">
    <div class="offcanvas-header admin-sidebar-header d-flex gap-3">
        <img src="/TVAM_SCHOLARSHIP/assets/images/TVAMLOGO.png" alt="TVAM LOGO" class="img-fluid rounded-circle bg-transparent" style="width: 50px;">
        <h1 class="offcanvas-title fs-5 fw-bold text-uppercase" id="sidebarLabel">ADMIN DASHBOARD</h1>
        <button type="button" class="btn-close btn-close-white d-md-none" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>

    <div class="offcanvas-body admin-sidebar-body d-flex flex-column">
        <h4 class="text-muted text-bg-light fs-6 p-2 rounded-5 text-center">MANAGE SCHOLARS</h4>
        <ul class="nav flex-column gap-4 w-100 mt-3">
            <li class="nav-item ">
                <a href="/TVAM_SCHOLARSHIP/admin/dashboard.php" class="nav-link<?php echo isSidebarActive('/TVAM_SCHOLARSHIP/admin/dashboard.php'); ?>"> 📈 Dashboard</a>
            </li>
            <li class="nav-item">
                <a href="/TVAM_SCHOLARSHIP/admin/scholars/index.php" class="nav-link<?php echo isSidebarActive('/TVAM_SCHOLARSHIP/admin/scholars/index.php'); ?>"> 💡 Manage Scholars</a>
            </li>
            <li class="nav-item">
                <a href="/TVAM_SCHOLARSHIP/admin/applications/index.php" class="nav-link<?php echo isSidebarActive('/TVAM_SCHOLARSHIP/admin/applications'); ?>"> 🗂️ Applicant Reports</a>
            </li>
            <li class="nav-item">
                <a href="/TVAM_SCHOLARSHIP/admin/reports/index-user.php" class="nav-link<?php echo isSidebarActive('/TVAM_SCHOLARSHIP/admin/reports/index-user.php'); ?>"> 👤 Users Reports</a>
            </li>
        </ul>

        <h4 class="text-muted text-bg-light fs-6 p-2 rounded-5 mt-3 text-center">APPLICATION PROFILING</h4>
        <ul class="nav flex-column w-100">
            <li class="nav-item"> 
                <a href="/TVAM_SCHOLARSHIP/admin/reports/logs.php" class="nav-link<?php echo isSidebarActive('/TVAM_SCHOLARSHIP/admin/reports/logs.php'); ?> mt-3">📥 Activity Logs</a>
            </li>
            <li class="nav-item">
                <a href="/TVAM_SCHOLARSHIP/admin/documents/index.php" class="mt-3 nav-link<?php echo isSidebarActive('/TVAM_SCHOLARSHIP/admin/documents/index.php'); ?>"> 🗃️ Manage Files</a>
            </li>
            <li class="nav-item">
                <a href="/TVAM_SCHOLARSHIP/auth/logout.php" class="nav-link mt-3"> ➜] Logout </a>
            </li>
        </ul>
    </div>
</aside>

<header class="navbar navbar-dark bg-dark d-md-none p-3 shadow-sm">
    <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar" aria-controls="sidebar" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="d-flex flex-row align-items-center justify-content-center">
          <span class="navbar-brand mb-0 h1 fs-6 fw-bold text-uppercase">TVAM Admin</span>
          <span><img src="/TVAM_SCHOLARSHIP/assets/images/TVAMLOGO.png" alt="" style="width: 40px;" class="img-fluid img-circle rounded-5"></span>
    </div>

</header>
