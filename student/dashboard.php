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

<div class="d-flex flex-column min-vh-100 flex-md-row">
    <main class="student-dash container-fluid flex-grow-1 p-4">
        <section class="container-fluid student-dashboard-hero p-1">
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

        <section class="student-grid px-1 mt-3">
            <div class="student-card-metric" id="scholarship-status">
                <div class="metric-header">
                    <h4 class="dashboard-eyebrow">SCHOLARSHIP</h4>
                </div>
                <span class="text-uppercase"><?php echo htmlspecialchars($get_scholar['scholarship_type']); ?></span>
                <span class="scholar-rate">SCHOLAR RATE: 45%</span>
            </div>

            <div class="student-card-metric" id="student-status">
                <div class="metric-header">
                    <h4 class="dashboard-eyebrow">STATUS</h4>
                </div>
                <span class="text-uppercase"> <?php echo htmlspecialchars($role); ?></span>
            </div>

            <div class="student-card-metric" id="student-status">
                <div class="metric-header">
                    <h4 class="dashboard-eyebrow">YEAR LEVEL</h4>
                </div>
                <span class="text-uppercase"></span>
            </div>

            <div class="student-card-metric" id="student-status">
                <div class="metric-header">
                    <h4 class="dashboard-eyebrow">COURSE / PROGRAM</h4>
                </div>
                <span class="text-uppercase"></span>
            </div>
        </section>

        <section class="row px-1 mt-4">
            <div class="col-12 col-xl-8">
                <div class="document-panel h-100">
                    <div class="document-report-header">
                        <div>
                            <h5 class="dashboard-eyebrow">DOCUMENTATION</h5>
                            <span class="text-uppercase fw-bold">Application Pipeline</span>
                        </div>
                        <a href="">Upload</a>
                    </div>

                    <div class="document-report-files mt-0">
                        <div class="document-main">
                            <div class="document">
                                <h5 class="document-header">Certificate of Enrollment</h5>
                                <span class="stamp stamp-successdocument-status">APPROVED</span>
                            </div>
                            <div class="document">
                                <h5 class="document-header">Certificate of Enrollment</h5>
                                <span class=" stamp stamp-successdocument-status">APPROVED</span>
                            </div>
                            <div class="document">
                                <h5 class="document-header">Certificate of Enrollment</h5>
                                <span class="stamp stamp-successdocument-status">APPROVED</span>
                            </div>
                        </div>
                    </div>
                    <span>Uploaded document report needed for Scholarship Application</span>
                </div>
            </div>

            <div class="col-12 col-xl-4 application-report">
                <div class="document-panel">

                </div>
            </div>
        </section>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
