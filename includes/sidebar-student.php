<?php 

$currentPage = basename($_SERVER['PHP_SELF']);

// Added 'icon' key using Bootstrap Icons (bi-*)
$studentSidebarItems = [
    "dashboard.php"        => ["label" => "Dashboard",     "href" => "/TVAM_SCHOLARSHIP/student/dashboard.php",        "icon" => "bi-speedometer2"],
    "profile.php"          => ["label" => "Profile",       "href" => "/TVAM_SCHOLARSHIP/student/profile.php",          "icon" => "bi-person-circle"],
    "apply.php"            => ["label" => "Apply",         "href" => "/TVAM_SCHOLARSHIP/student/apply.php",            "icon" => "bi-file-earmark-text"],
    "status.php"           => ["label" => "Status",        "href" => "/TVAM_SCHOLARSHIP/student/status.php",           "icon" => "bi-clock-history"],
    "notification.php"     => ["label" => "Notifications", "href" => "/TVAM_SCHOLARSHIP/student/notification.php",     "icon" => "bi-bell"],
    "upload-documents.php" => ["label" => "Uploads",       "href" => "/TVAM_SCHOLARSHIP/student/upload-documents.php", "icon" => "bi-upload"],
    "disbursement.php"     => ["label" => "Disbursement",  "href" => "/TVAM_SCHOLARSHIP/student/disbursement.php",     "icon" => "bi-wallet2"],
    "settings.php"         => ["label" => "Settings",      "href" => "/TVAM_SCHOLARSHIP/student/settings.php",         "icon" => "bi-gear"],
];

?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">


<aside class="student-sidebar" aria-label="Student sidebar">
    <a href="/TVAM_SCHOLARSHIP/student/dashboard.php"
       class="student-sidebar-brand"
       data-bs-toggle="tooltip"
       data-bs-placement="right"
       data-bs-title="TVAM Student Portal">
        <img src="/TVAM_SCHOLARSHIP/assets/images/tvamlogo_web.png" alt="TVAM" class="student-sidebar-logo img-fluid">
        <span class="visually-hidden">TVAM Student Portal</span>
    </a>

    <ul class="student-sidebar-nav">
        <?php foreach ($studentSidebarItems as $page => $item) :
            $isActive = $currentPage === $page;
        ?>
            <li class="student-sidebar-item">
                <a href="<?php echo htmlspecialchars($item['href']); ?>"
                   class="student-sidebar-link<?php echo $isActive ? ' active' : ''; ?>"
                   aria-label="<?php echo htmlspecialchars($item['label']); ?>"
                   <?php echo $isActive ? 'aria-current="page"' : ''; ?>
                   data-bs-toggle="tooltip"
                   data-bs-placement="right"
                   data-bs-title="<?php echo htmlspecialchars($item['label']); ?>">
                    <!-- Updated to render dynamic icon classes -->
                    <i class="bi <?php echo $item['icon']; ?> student-sidebar-icon" aria-hidden="true"></i>
                    <span class="visually-hidden"><?php echo htmlspecialchars($item['label']); ?></span>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>

    <a href="/TVAM_SCHOLARSHIP/auth/logout.php"
       class="student-sidebar-link student-sidebar-logout"
       aria-label="Logout"
       data-bs-toggle="tooltip"
       data-bs-placement="right"
       data-bs-title="Logout">
        <i class="bi bi-box-arrow-right student-sidebar-icon" aria-hidden="true"></i>
        <span class="visually-hidden">Logout</span>
    </a>
</aside>

<script>
document.addEventListener('DOMContentLoaded', () => {
    if (window.bootstrap && window.bootstrap.Tooltip) {
        document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach((el) => {
            new bootstrap.Tooltip(el, { container: 'body' });
        });
    }
});
</script>