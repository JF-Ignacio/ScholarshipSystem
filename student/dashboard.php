<?php
include "../config/student-auth.php";
include "../config/database.php";

$id = (int) ($_GET['id'] ?? 0);

$role = $_SESSION['role'] ?? "";
$fullname = $_SESSION['fullname'] ?? "";
$status = $_SESSION['status'] ?? "NO APPLICATION DATA";

$scholar = "SELECT course, year_level, scholarship_type FROM scholars";
$scholar_result = $conn->query($scholar);
$get_scholar = $scholar_result->fetch_assoc();



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/TVAM_SCHOLARSHIP/assets/css/style.css">
    <link rel="stylesheet" href="/TVAM_SCHOLARSHIP/assets/css/student.css">
    <link rel="icon" href="/TVAM_SCHOLARSHIP/assets/images/tvamlogo_web.png">
</head>
<body class="student-layout">
    <?php include "../includes/sidebar-student.php"; ?>

<main class="student-dash container-fluid min-vh-100 d-flex flex-column p-4 flex-grow-1">

    <section class="container-fluid student-dashboard-hero">
        <div class="card border-0 shadow-sm px-5 py-4 text-white">
            <div>
                <span class="dashboard-eyebrow text-uppercase">STUDENT DASHBOARD</span>
                <h2 class="dashboard-greetings fw-bold">Hello, <?php echo htmlspecialchars($fullname); ?></h2>
                <p class=" small fw-bold">See your overall ranking in the scholarship system. </p>
            </div>
            <div class="student-dashboard-cta">
                <span class="role text-uppercase text-white"><?php echo htmlspecialchars($role); ?></span>
                <span class="student-cta">
                    <a href="" class="text-decoration-none text-dark btn btn-light">Apply Scholar</a>
                </span>
            </div>
        </div>  
    </section>

    <section class="student-grid px-4 mt-3">
        <div class="student-card-metric" id="scholarship-status">
            <div class="metric-header">
                <h4>SCHOLARSHIP</h4>
            </div>
            <span class="text-uppercase"><?php echo htmlspecialchars($get_scholar['scholarship_type']); ?></span>
            <span class="scholar-rate">SCHOLAR RATE: 45%</span>
        </div>

        <div class="student-card-metric" id="student-status">
            <div class="metric-header">
                <h4>STATUS</h4>
            </div>
            <span class="text-uppercase"> <?php echo htmlspecialchars($role); ?></span>
        </div>

        <div class="student-card-metric" id="student-status">
            <div class="metric-header">
                <h4>YEAR LEVEL</h4>
            </div>
            <span class="text-uppercase"></span>
        </div>

        <div class="student-card-metric" id="student-status">
            <div class="metric-header">
                <h4>COURSE / PROGRAM</h4>
            </div>
            <span class="text-uppercase"></span>
        </div>




    </section>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
