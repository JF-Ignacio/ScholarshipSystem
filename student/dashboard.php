<?php
include "../config/student-auth.php";
include "../config/database.php";

$id = (int) ($_GET['id'] ?? 0);

$role = $_SESSION['role'] ?? "";
$fullname = $_SESSION['fullname'] ?? "";
$status = $_SESSION['status'] ?? "NO APPLICATION DATA";


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

    <div class="row gap-3 p-4">
        <div class="col border shadow-sm p-3">
            <h2>Scholarship Status </h2>
            <span class="text-muted fs-2"><?php echo htmlspecialchars($status); ?></span>
        </div>

        <div class="col border shadow-sm p-3">
            <h2> Total Semester Completed: </h2>
            <span class="text-muted fs-1">4</span>
        </div>

        <div class="col border shadow-sm p-3">
            <h2> Total Semester Completed: </h2>
            <span class="text-muted fs-1">4</span>
        </div>

        <div class="col-sm-12 border p-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-3">
                <div>
                    <h2 class="mb-1">Student Overall Performance</h2>
                    <p class="text-muted mb-0">Based on student database.</p>
                </div>
                <span class="badge bg-success fs-6 px-3 py-2">Excellent</span>
            </div>

            <div class="d-flex align-items-end gap-3 mb-2">
                <span class="display-5 fw-bold text-success">98.26%</span>
                <span class="text-muted mb-2">overall score</span>
            </div>

            <div class="progress" role="progressbar" aria-label="Student overall performance" aria-valuenow="98.26" aria-valuemin="0" aria-valuemax="100" style="height: 22px;">
                <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" style="width: 98.26%;">
                    98.26%
                </div>
            </div>

            <div class="d-flex justify-content-between text-muted small mt-2">
                <span>0%</span>
                <span>25%</span>
                <span>50%</span>
                <span>75%</span>
                <span>100%</span>
            </div>
        </div>
    </div>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
