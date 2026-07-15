<?php

require_once "../../config/database.php";
require_once "../../config/admin-auth.php";
require_once "../../includes/functions.php";

$message = "";
$badge = "";

$applicantID = (int) ($_GET['id'] ?? 0);

    $check_sql = "SELECT a.*, 
    u.fullname,
    u.email,
    s.status

    FROM applications a 
    INNER JOIN users u on a.user_id = u.id
    LEFT JOIN scholars s ON a.user_id = s.student_id
    WHERE a.application_ID = ?";

    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("i", $applicantID);
    $check_stmt->execute();

    $result = $check_stmt->get_result();
    $application = $result->fetch_assoc();

    $fullname = trim($application['fullname'] ?? "");
    $scholarship = trim($application['scholarship_type'] ?? "");
    $studentID = "TVAM-" .str_pad($application['user_id'], 6, "0", STR_PAD_LEFT);
    

    $check_stmt->close();

    if(!$application) {
        die("No database found for APPLICATION ID - " . htmlspecialchars($applicantID));
    }


if($_SERVER['REQUEST_METHOD'] == "POST") {

    if(isset($_POST['reject'])) {

        $targetID = (int)$application['user_id'];
        $conn->begin_transaction();

        try {

            $application_sql = "UPDATE applications SET status='Inactive' WHERE application_ID = ?";
            $stmt_app = $conn->prepare($application_sql);
            $stmt_app->bind_param("i", $applicantID);
            $stmt_app->execute();
            $stmt_app->close();

            $scholar_sql = "UPDATE scholars SET status='inactive' WHERE student_id = ?";
            $stmt_scholar = $conn->prepare($scholar_sql);
            $stmt_scholar->bind_param("s", $studentID);
            $stmt_scholar->execute();
            $stmt_scholar->close();

            $adminID = $_SESSION['id'] ?? 0;
            $adminName = $_SESSION['fullname'] ?? " ";
            $logs = "REJECTED by {$adminName} | {$fullname} - (Application ID: {$applicantID})";
            activityLogs($conn, $adminID, $logs);

            $notif_title = "Scholarship Application - REJECTED !";
            $notif_text = "Hello, {$fullname} - {$studentID}! your application for the {$scholarship} has been updated. Check your status card for more information. Best of luck!";
            notificationAlert($conn, $targetID, $notif_title, $notif_text);

            $conn->commit();
            
            header("Location: index.php");
            exit();

        }

        catch(Exception $e) {
            $conn->rollback();
            $message = "Database error. Access Denied";

        }
    }
}






?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - TVAM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/TVAM_SCHOLARSHIP/assets/css/admin.css">
    <link rel="icon" href="/TVAM_SCHOLARSHIP/assets/images/tvamlogo_web.png">
</head>

<body class="reject-body container-fluid min-vh-100 d-flex align-items-center justify-content-center">
    <div class="reject-class container-fluid p-4 px-5 py-5 border rounded-3 d-flex flex-column align-items-center shadow-lg w-25">
        <?php if(!empty($message))?>
            <div class="alert alert-<?php echo $badge; ?> text-center w-100 mb-3" role="alert">
                <?php echo htmlspecialchars($message);?>
            </div>
        <?php ?>

        <form action="" method="post" class="d-flex flex-column gap-3">
            <div class="d-flex flex-row align-items-center justify-content-center">
                <img src="/TVAM_SCHOLARSHIP/assets/images/TVAMLOGO.png" alt="" class="img-rounded" style="height: 100px;">
                <h2 class="text-uppercase fw-bold fs-4">REJECT APPLICATION</h2>
            </div>
            <div class="">
                <label for="application_id" class="form-label fw-bold">Application ID</label>
                <input type="text" name="application_id" class="form-control" value="<?php echo htmlspecialchars($applicantID)?>" readonly>
            </div>

            <div>
                <label for="fullname" class="form-label fw-bold">Fullname</label>
                <input type="text" class="form-control" value="<?php echo htmlspecialchars($fullname); ?>" readonly>
            </div> 
            <div>
                <label for="scholarship_type" class="form-label fw-bold">Scholarship</label>
                <input type="text" name="scholarship_type" class="form-control" value="<?php echo htmlspecialchars($scholarship);?>" readonly>
            </div>

            <div class="d-flex flex-column align-items-center">
                <button type="submit" name="reject" class="btn btn-danger w-100 fw-bold mt-3">Confirm Rejection</button>
                <a href="index.php" class="btn btn-secondary w-100 mt-3 fw-bold">EXIT</a>
            </div>
        </form>
    </div>


 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>