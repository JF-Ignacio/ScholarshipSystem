<?php
$currentPage = basename($_SERVER['PHP_SELF']);

$navItems = [
    "dashboard.php" => ["label" => "Home", "href" => "/TVAM_SCHOLARSHIP/student/dashboard.php"],
    "profile.php" => ["label" => "Profile", "href" => "/TVAM_SCHOLARSHIP/student/profile.php"],
    "apply.php" => ["label" => "Apply", "href" => "/TVAM_SCHOLARSHIP/student/apply.php"],
    "status.php" => ["label" => "Status", "href" => "/TVAM_SCHOLARSHIP/student/status.php"],
    "notification.php" => ["label" => "Notifications", "href" => "/TVAM_SCHOLARSHIP/student/notification.php"],
    "upload-documents.php" => ["label" => "Uploads", "href" => "/TVAM_SCHOLARSHIP/student/upload-documents.php"],
];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/TVAM_SCHOLARSHIP/assets/css/style.css">
    <link rel="icon" href="../assets/images/tvamlogo_web.png">
</head>

<body class="has-fixed-navbar">
    <nav class="navigation navbar navbar-expand-lg navbar-light fixed-top shadow-sm">
        <div class="container-fluid px-4">
            <a href="/TVAM_SCHOLARSHIP/student/dashboard.php" class="navbar-brand align-items-center d-flex">
                <img src="/TVAM_SCHOLARSHIP/assets/images/TVAMLOGO.png" alt="TVAM Logo" class="rounded-pill me-2" style="width: 50px">
                <span class="brand-text">TVAM SCHOLARSHIP MANAGEMENT</span>
            </a>

            <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbar">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2 py-3">
                    <?php foreach ($navItems as $page => $item) {
                        $isActive = $currentPage === $page;
                    ?>
                    <li class="nav-item">
                        <a href="<?php echo htmlspecialchars($item['href']); ?>"
                           class="nav-link<?php echo $isActive ? ' active' : ''; ?>"
                           <?php echo $isActive ? 'aria-current="page"' : ''; ?>>
                            <?php echo htmlspecialchars($item['label']); ?>
                        </a>
                    </li>
                    <?php } ?>
                    
                    <li class="nav-item">
                        <a href="/TVAM_SCHOLARSHIP/auth/logout.php" class="nav-link">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

