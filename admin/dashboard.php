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

$activePercent = $getApplicants > 0 ? min(100, round(($getActive / $getApplicants) * 100)) : 0;
$inactivePercent = $getApplicants > 0 ? min(100, round(($getInactive / $getApplicants) * 100)) : 0;
$scholarCoverage = $getApplicants > 0 ? min(100, round(($getScholars / $getApplicants) * 100)) : 0;
$studentPercent = $totalUsers > 0 ? min(100, round(($getStudent / $totalUsers) * 100)) : 0;
$adminPercent = $totalUsers > 0 ? min(100, round(($getAdmin / $totalUsers) * 100)) : 0;

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

        <main class="admin-dashboard flex-grow-1">
            <section class="dashboard-hero">
                <div>
                    <span class="dashboard-eyebrow">Admin Overview</span>
                    <h1>Hello, <?php echo htmlspecialchars($getname); ?></h1>
                    <p>Track scholarship activity, user access, and application status from one clean workspace.</p>
                </div>

                <div class="dashboard-hero-actions">
                    <span class="role-chip"><?php echo htmlspecialchars($getRole); ?></span>
                    <a href="../admin/scholars/index.php" class="btn btn-light dashboard-action">Manage Scholars</a>
                </div>
            </section>

            <section class="dashboard-grid">
                <div class="metric-card">
                    <div class="metric-card-top">
                        <span class="metric-icon icon-users">U</span>
                        <span class="metric-label">Total Users</span>
                    </div>
                    <strong><?php echo htmlspecialchars($totalUsers); ?></strong>
                    <span class="metric-note"><?php echo htmlspecialchars($getStudent); ?> students and <?php echo htmlspecialchars($getAdmin); ?> admins</span>
                </div>

                <div class="metric-card">
                    <div class="metric-card-top">
                        <span class="metric-icon icon-scholars">S</span>
                        <span class="metric-label">Scholars</span>
                    </div>
                    <strong><?php echo htmlspecialchars($getScholars); ?></strong>
                    <span class="metric-note"><?php echo htmlspecialchars($scholarCoverage); ?>% of total applications</span>
                </div>

                <div class="metric-card">
                    <div class="metric-card-top">
                        <span class="metric-icon icon-applications">A</span>
                        <span class="metric-label">Applications</span>
                    </div>
                    <strong><?php echo htmlspecialchars($getApplicants); ?></strong>
                    <span class="metric-note">Submitted scholarship records</span>
                </div>

                <div class="metric-card">
                    <div class="metric-card-top">
                        <span class="metric-icon icon-active">P</span>
                        <span class="metric-label">Active Rate</span>
                    </div>
                    <strong><?php echo htmlspecialchars($activePercent); ?>%</strong>
                    <span class="metric-note"><?php echo htmlspecialchars($getActive); ?> active applications</span>
                </div>
            </section>

            <section class="row g-4 mt-1">
                <div class="col-12 col-xl-8">
                    <div class="dashboard-panel h-100">
                        <div class="panel-header">
                            <div>
                                <span class="dashboard-eyebrow">Application Status</span>
                                <h2>Scholarship Pipeline</h2>
                            </div>
                            <a href="../admin/applications/index.php" class="panel-link">View reports</a>
                        </div>

                        <div class="status-summary">
                            <div class="status-item">
                                <div>
                                    <span>Active Applications</span>
                                    <strong><?php echo htmlspecialchars($getActive); ?></strong>
                                </div>
                                <span class="status-percent text-success"><?php echo htmlspecialchars($activePercent); ?>%</span>
                            </div>

                            <div class="progress dashboard-progress">
                                <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo htmlspecialchars($activePercent); ?>%;" aria-valuenow="<?php echo htmlspecialchars($activePercent); ?>" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>

                            <div class="status-item mt-4">
                                <div>
                                    <span>Inactive Applications</span>
                                    <strong><?php echo htmlspecialchars($getInactive); ?></strong>
                                </div>
                                <span class="status-percent text-danger"><?php echo htmlspecialchars($inactivePercent); ?>%</span>
                            </div>

                            <div class="progress dashboard-progress">
                                <div class="progress-bar bg-danger" role="progressbar" style="width: <?php echo htmlspecialchars($inactivePercent); ?>%;" aria-valuenow="<?php echo htmlspecialchars($inactivePercent); ?>" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>

                        <div class="pipeline-footer">
                            <div>
                                <span class="mini-label">Acceptance Coverage</span>
                                <strong><?php echo htmlspecialchars($scholarCoverage); ?>%</strong>
                            </div>
                            <div>
                                <span class="mini-label">Performance Target</span>
                                <strong>92%</strong>
                            </div>
                            <div>
                                <span class="mini-label">Scholar Rating</span>
                                <strong>88%</strong>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-xl-4">
                    <div class="dashboard-panel h-100">
                        <div class="panel-header">
                            <div>
                                <span class="dashboard-eyebrow">User Roles</span>
                                <h2>Account Mix</h2>
                            </div>
                        </div>

                        <div class="role-meter">
                            <div class="role-meter-row">
                                <span>Students</span>
                                <strong><?php echo htmlspecialchars($getStudent); ?></strong>
                            </div>
                            <div class="progress dashboard-progress">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: <?php echo htmlspecialchars($studentPercent); ?>%;" aria-valuenow="<?php echo htmlspecialchars($studentPercent); ?>" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>

                        <div class="role-meter">
                            <div class="role-meter-row">
                                <span>Admins</span>
                                <strong><?php echo htmlspecialchars($getAdmin); ?></strong>
                            </div>
                            <div class="progress dashboard-progress">
                                <div class="progress-bar bg-warning" role="progressbar" style="width: <?php echo htmlspecialchars($adminPercent); ?>%;" aria-valuenow="<?php echo htmlspecialchars($adminPercent); ?>" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>

                        <div class="account-card">
                            <span class="mini-label">System Access</span>
                            <strong><?php echo htmlspecialchars($totalUsers); ?> registered accounts</strong>
                        </div>
                    </div>
                </div>
            </section>

            <section class="row g-4 mt-1">
                <div class="col-12 col-lg-8">
                    <div class="dashboard-panel">
                        <div class="panel-header">
                            <div>
                                <span class="dashboard-eyebrow">Quick Actions</span>
                                <h2>Management Shortcuts</h2>
                            </div>
                        </div>

                        <div class="quick-actions">
                            <a href="../admin/scholars/index.php" class="quick-action">
                                <span>Scholars</span>
                                <strong>Manage scholarship records</strong>
                            </a>
                            <a href="../admin/applications/index.php" class="quick-action">
                                <span>Applications</span>
                                <strong>Review applicant reports</strong>
                            </a>
                            <a href="../admin/documents/index.php" class="quick-action">
                                <span>Documents</span>
                                <strong>Verify uploaded files</strong>
                            </a>
                            <a href="../admin/reports/logs.php" class="quick-action">
                                <span>Logs</span>
                                <strong>Check activity history</strong>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-4">
                    <div class="dashboard-panel spotlight-panel">
                        <span class="dashboard-eyebrow">TVAM Scholarship</span>
                        <h2>Admin Workspace</h2>
                        <p>Use this dashboard as the starting point for daily scholarship monitoring and student support tasks.</p>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
