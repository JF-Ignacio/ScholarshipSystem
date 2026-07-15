<?php
include "../config/student-auth.php";
include "../config/database.php";

$id = (int) ($_GET['id'] ?? 0);

$role = $_SESSION['role'] ?? "";
$fullname = $_SESSION['fullname'] ?? "";
$status = $_SESSION['status'] ?? "NO APPLICATION DATA";


?>

<?php include "../includes/header.php"; ?>

<head>
    <title>Home</title>
</head>
<main class="container-fluid min-vh-100 d-flex flex-column p-4 flex-grow-1 ">
    <div class="container-fluid border p-4 mt-3 rounded-4 shadow-sm"">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h2 class="fs-1">Hello, <?php echo htmlspecialchars($fullname); ?></h2>
                <span class="badge bg-primary fs-6 text-uppercase p-2"><?php echo htmlspecialchars($role); ?></span>
            </div>
            
            <div class="col-md-6 text-md-end mt-3 mt-md-0">
                <a href="../student/disbursement.php" class="btn btn-primary fw-bold text-decoration-none">SEE STIPEND</a>
            </div>
        </div>
    </div>

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
