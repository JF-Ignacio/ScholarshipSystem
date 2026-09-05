<?php
include "../config/student-auth.php";
include "../config/database.php";

$id = (int) ($_SESSION['id'] ?? 0);
$bg = "";
$color = "";

// CONVERT RAW ID - STUDENT_ID FROM SCHOLARS
$studentID = "TVAM-" .sprintf("%06d", $id);

// CONVERT STRING STUDENT_ID - USER_ID IN APPLICATION, DOCUMENTS, ETC.,
$userID = (int) str_replace("TVAM-", "", $studentID);

$scholar_sql = "SELECT s.course, s.year_level, s.scholarship_type, s.status 
                AS scholar_status,
                s.student_id,
                u.role, u.fullname
                FROM users u
                LEFT JOIN scholars s ON s.student_id = ?
                WHERE u.id = ? 
                LIMIT 1";

$stmt_scholar = $conn->prepare($scholar_sql);
$stmt_scholar->bind_param("si", $studentID, $id);
$stmt_scholar->execute();
$scholar_result = $stmt_scholar->get_result()->fetch_assoc();

$fullname = $scholar_result['fullname'] ?? ($_SESSION['fullname'] ?? 'Student A');
$role = $scholar_result['role'] ?? ($_SESSION['role'] ?? 'Student');
$course = $scholar_result['course'] ?? "N/A";
$year = $scholar_result['year_level'] ?? "N/A";
$scholarship_type = $scholar_result['scholarship_type'] ?? "N/A";
$scholarStatus = $scholar_result['scholar_status'] ?? 'Pending';

$document_sql = "SELECT document_type, file_name, status FROM documents WHERE user_id = ?";
$stmt_document = $conn->prepare($document_sql);
$stmt_document->bind_param("i", $userID);
$stmt_document->execute();
$document_result = $stmt_document->get_result();

$total_documents = $document_result->num_rows;

$document_pipeline = min(100, round(($total_documents / 3) * 100));

$type_meta = [
    'Certificate of Enrollment' => ['code' => 'COE', 'class' => 'tab-coe'],
    'Grade Transcript'          => ['code' => 'GT',  'class' => 'tab-gt'],
    'Disbursement Record'       => ['code' => 'DOR', 'class' => 'tab-dor'],
];

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
                    <h2 class="dashboard-greetings fw-bold">Hello, <?php echo htmlspecialchars($fullname); ?>!</h2>
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
                <div class="metric-header mb-2">
                    <h4 class="dashboard-eyebrow">SCHOLARSHIP</h4>
                </div>
                <span class="text-uppercase fw-bold fs-5"><?php echo htmlspecialchars($scholarship_type); ?></span>
                <span class="scholar-rate">SCHOLAR RATE: 45%</span>
            </div>

            <div class="student-card-metric" id="student-status">
                <div class="metric-header mb-2">
                    <h4 class="dashboard-eyebrow">STATUS</h4>
                </div>
                <?php
                if($scholarStatus !== 'active') { $bg = "danger"; }
                else { $bg = "success"; }
                ?>
                <span class="text-uppercase bg-<?php echo $bg; ?> rounded-2 p-3 text-light"> <?php echo htmlspecialchars($scholarStatus); ?></span>
            </div>

            <div class="student-card-metric" id="student-status">
                <div class="metric-header mb-2">
                    <h4 class="dashboard-eyebrow">YEAR LEVEL</h4>
                </div>
                <span class="text-uppercase"><?php echo htmlspecialchars($year); ?></span>
            </div>

            <div class="student-card-metric" id="student-status">
                <div class="metric-header mb-2">
                    <h4 class="dashboard-eyebrow">COURSE / PROGRAM</h4>
                </div>
                <span class="text-uppercase"><?php echo htmlspecialchars($course); ?></span>
            </div>
        </section>

        <section class="row px-1 mt-4">
            <div class="col-12 col-xl-8">
                <div class="document-panel h-100">
                    <div class="document-report-header">
                        <div>
                            <h5 class="dashboard-eyebrow">DOCUMENTATION</h5>
                            <span class="text-uppercase fw-bold">Documents submitted</span>
                        </div>
                        <a href="/TVAM_SCHOLARSHIP/student/upload-documents.php" class="bg-transparent text-decoration-none text-muted p-1">Upload</a>
                    </div>

                    <div class="document-report-files mt-0">
                        <div class="document-main">
                            <?php if($document_result && $total_documents > 0) : ?>
                                <?php while($doc = $document_result->fetch_assoc()) : 
                                    $doc_status = $doc['status'] ?? "Pending";
                                    
                                    $badge_status = match($doc_status) {
                                        'Approved' => 'success',
                                        'Rejected' => 'danger',
                                        default => 'info text-dark'
                                    };
                                ?>
                            <div class="document">
                                <h5 class="document-header"> <?php echo htmlspecialchars($doc['document_type']); ?></h5>
                                <span class="stamp stamp-successdocument-status">APPROVED</span>
                            </div>
                            <?php endwhile; ?>
                        <?php endif; ?>
                        </div>
                    </div>
                    <span>Uploaded document report needed for Scholarship Application</span>
                </div>
            </div>

            <div class="col-12 col-xl-4">
                <div class="document-panel h-100">
                    <div class="document-report-header">
                        <div>
                            <h5 class="dashboard-eyebrow">APPLICATION INFORMATION</h5>
                           <span class="text-uppercase fw-bold">APPLICATION PIPELINE</span>
                        </div>
                    </div>
                    
                    <div class="application-data d-flex flex-row justify-content-between mt-3">
                        <h5 class="text-muted">Documents</h5>
                        <span class="progress-data mb-2"><strong>2</strong></span>
                    </div>
                    <div class="progress application-progress">
                        <div class="progress-bar bg-dark" role="progressbar" style="width: <?php echo $document_pipeline; ?>;" aria-valuenow="2%" aria-valuemin="0%" aria-valuemax="3%"></div>
                    </div>

                    <div class="event-status mt-3">
                        <h5 class="text-muted text-uppercase small ">EVENT STATUS</h5>
                    </div>
                </div>
            </div>
        </section>

        <section class="student-easy-access">

        </section>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
