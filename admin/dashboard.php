<?php 

require_once "../config/database.php";
require_once "../config/admin-auth.php";

$greet = "";

$id = (int) ($_GET['id'] ?? 0);

$getname = $_SESSION['fullname'] ?? "Admin";
$getRole = $_SESSION['role'] ?? "TVAM ADMIN";

// USERS
$sql_count = "SELECT COUNT(*) AS total_users FROM users";
$stmt_users = $conn->prepare($sql_count);
$stmt_users->execute();
$users_result = $stmt_users->get_result()->fetch_assoc();

$totalUsers = $users_result['total_users'] ?? 0;
$stmt_users->close();

// SCHOLARS
$sql_scholars = "SELECT COUNT(*) AS total_scholars FROM scholars";
$stmt_scholars = $conn->prepare($sql_scholars);
$stmt_scholars->execute();
$scholar_results = $stmt_scholars->get_result()->fetch_assoc();

$getScholars = $scholar_results['total_scholars'] ?? 0;
$stmt_scholars->close();

// APPLICATIONS
$sql_appplicants = "SELECT COUNT(*) AS total_applicants FROM applications";
$stmt_applicants = $conn->prepare($sql_appplicants);
$stmt_applicants->execute();

$applicant_result = $stmt_applicants->get_result()->fetch_assoc();
$getApplicants = $applicant_result['total_applicants'] ?? 0;
$stmt_applicants->close();


// ACTIVE APPLICATIONS
$sql_active = "SELECT COUNT(*) AS total_active FROM applications WHERE status ='active'";
$stmt_active = $conn->prepare($sql_active);
$stmt_active->execute();

$activeResult = $stmt_active->get_result()->fetch_assoc();
$getActive = $activeResult['total_active'] ?? 0;
$stmt_active->close();

// INACTIVE APPLICATIONS
$sql_Inactive = "SELECT COUNT(*) AS total_Inactive FROM applications WHERE status = 'inactive'";
$stmt_Inactive = $conn->prepare($sql_Inactive);
$stmt_Inactive->execute();

$inactiveResult = $stmt_Inactive->get_result()->fetch_assoc();
$getInactive = $inactiveResult['total_Inactive'] ?? 0;
$stmt_Inactive->close();

// COUNT AUTHENTICATION - ADMIN
$sql_admin = "SELECT COUNT(*) AS total_admin FROM users WHERE role = 'admin'";
$stmt_admin = $conn->prepare($sql_admin);
$stmt_admin->execute();

$adminResult = $stmt_admin->get_result()->fetch_assoc();
$getAdmin = $adminResult['total_admin'] ?? 0;
$stmt_admin->close();

// COUNT AUTHENTICATION - STUDENT 
$sql_student = "SELECT COUNT(*) AS total_student FROM users WHERE role = 'student'";
$stmt_student = $conn->prepare($sql_student);
$stmt_student->execute();

$studentResult = $stmt_student->get_result()->fetch_assoc();
$getStudent = $studentResult['total_student'] ?? 0;
$stmt_student->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - TVAM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/TVAM_SCHOLARSHIP/assets/css/admin.css">
    <link rel="stylesheet" href="/TVAM_SCHOLARSHIP/assets/css/style.css">
    <link rel="icon" href="../assets/images/tvamlogo_web.png">
</head>
<body class="admin-page">
    <div class="d-flex flex-column flex-md-row min-vh-100">
        <?php include "../includes/sidebar.php"; ?>

        <main class="container-fluid p-4 d-flex flex-column flex-grow-1">
            <div class="container-fluid mt-3 border p-4 shadow-sm bg-white rounded">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h2>Hello, <?php echo htmlspecialchars($getname); ?>
                            <span class="badge bg-secondary fs-6 text-center"> <?php echo htmlspecialchars($getRole); ?></span>
                        </h2> 
                    </div>

                    <div class="col-md-6 text-md-end"> 
                        <a href="../admin/scholars/index.php" class="btn btn-primary">MANAGE SCHOLARS</a>
                    </div>
                </div>
            </div>

            <div class="container-fluid mt-3 d-flex flex-column flex-grow-1 mb-3">

                <!--FIRST ROW-->
                <div class="row g-3 flex-grow-2 p-3 px-3 py-3 d-flex text-center">
                    <div class="col-12 col-md-6 col-lg-4 ">
                        <div class="card h-100 border shadow-sm">
                            <div class="card-body p-3">
                                <h6 class="fs-5 text-muted text-uppercase font-weight-bold">Total TVAM USERS: </h6>
                                <h2 class="fs-1 display-6 my-2"><?php echo htmlspecialchars($totalUsers); ?></h2>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="card h-100 border shadow-sm">
                            <div class="card-body p-3 bg-info">
                                <h6 class="fs-5 text-muted text-uppercase font-weight-bold text-light">TOTAL SCHOLARS</h6>
                                <h2 class="fs-1 display-6 my-2"><?php echo htmlspecialchars($getScholars); ?></h2>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="card h-100 border shadow-sm">
                            <div class="card-body p-3 bg-warning text-dark">
                                <h6 class="fs-5 text-dark text-uppercase font-weight-bold">TOTAL SCHOLAR APPLICATIONS </h6>
                                <h2 class="fs-3 display-6 my-2"><?php echo htmlspecialchars($getApplicants); ?></h2>
                            </div>
                        </div>
                    </div>
                </div>

                <!--SECOND ROW-->
                <div class="row g-3 flex-grow-2 p-3 px-3 py-3 text-center">
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="card h-75 border shadow-sm">
                            <div class="card-body p-3 bg-success text-light">
                                <h6 class="fs-5 text-lighttext-uppercase font-weight-bold">TOTAL ACTIVE APPLICATIONS </h6>
                                <h2 class="fs-3 display-6 my-2"><?php echo htmlspecialchars($getActive); ?></h2>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="card h-75 border shadow-sm text-light">
                            <div class="card-body p-3 bg-danger">
                                <h6 class="fs-5 text-uppercase font-weight-bold">TOTAL INACTIVE APPLICANTS </h6>
                                <h2 class="fs-3 display-6 my-2"><?php echo htmlspecialchars($getInactive); ?></h2>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="card border shadow-sm">
                            <div class="card-body d-flex flex-column h-100">
                                <h6 class="fs-6">AVERAGE SCHOOL ACCEPTING PERFORMANCE </h6>
                                <span class="text-uppercase fs-6">Scholar's Performance: </span>
                                <div class="progress mb-2" style="height: 10px;">
                                    <div class="progress-bar  bg-primary" style="width: 88%;"> 88%</div>
                                </div>

                                <span class="text-uppercase fs-6">School's academic ratings: </span>
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar  bg-success" style="width: 92%;"> 92%</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!--THIRD ROW-->
                <div class="row g-3 flex-grow-3 p-3 px-5 mt-2 text-center">
                    <div class=" col-12 col-md-12 col-lg-6">
                        <div class="admin-card card border shadow-sm h-100">
                            <div class="card-body d-flex flex-column">
                                <h2 class="text-dark text-uppercase fw-bold fs-5">TOTAL ADMINS:</h2>
                                <h1> <?php echo htmlspecialchars($getAdmin); ?></h1>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-12 col-lg-6">
                       <div class="student-card card border shadow-sm ">
                            <div class="card-body d-flex flex-column">
                                <h2 class="text-dark text-uppercase fw-bold fs-5">TOTAL STUDENT:</h2>
                                <h1> <?php echo htmlspecialchars($getStudent); ?></h1>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-body w-100 d-flex flex-column shadow-sm">
                        <h2>TVAM </h2>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
