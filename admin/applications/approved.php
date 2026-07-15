<?php

require_once "../../config/database.php";
require_once "../../config/admin-auth.php";
require_once "../../includes/functions.php";

$message = "";
$badge = "";

$applicantID = (int) ($_GET['id'] ?? 0);

    $check_stmt = mysqli_prepare($conn, 
    "SELECT a.*,
    u.fullname, 
    u.email,
    s.status

    FROM applications a 
    INNER JOIN users u ON a.user_id = u.id 
    LEFT JOIN scholars s ON a.user_id = s.student_id
    WHERE a.application_ID = ?");

    mysqli_stmt_bind_param($check_stmt, "i", $applicantID);
    mysqli_stmt_execute($check_stmt);
    $result = mysqli_stmt_get_result($check_stmt);

    $application = mysqli_fetch_assoc($result);


    if(!$application) {
        die("No connection Found!" . htmlspecialchars($applicantID));
    }

if($_SERVER['REQUEST_METHOD'] == "POST") {
    if(isset($_POST['approved'])) {

        mysqli_begin_transaction($conn);


        try {

            $sql_update_status = "UPDATE applications SET status = 'Active' WHERE application_ID = ?";

                if($update_stmt = $conn->prepare($sql_update_status)) {
                    $update_stmt->bind_param("i", $applicantID);

                    if(!$update_stmt->execute()) {
                        throw new Exception("Application Failed to Update. Contact Admin.");

                    }
                    
                    $update_stmt->close();

                    $scholarID = "TVAM-" . str_pad ($application['user_id'], 6, "0", STR_PAD_LEFT);
                    $fulname = $application['fullname'] ?? "";
                    $course = $application['course'] ?? "";
                    $year = $application['year_level'] ?? "";
                    $scholarship = $application['scholarship_type'] ?? "";
                    $status = "active";
                    $email = $application['email'] ?? "";

                    $sql_check = "SELECT student_id FROM scholars WHERE student_id = ?";

                    if($stmt_check = $conn->prepare($sql_check)) {
                        $stmt_check->bind_param("s", $scholarID);
                        $stmt_check->execute();

                        $checkScholar = $stmt_check->get_result();
                        $fetch = $checkScholar->fetch_assoc();

                        $scholarExists = $checkScholar->num_rows > 0;
                        $stmt_check->close();

                        if(!$scholarExists) {
                            $sql_insert = "INSERT INTO scholars
                            (student_id, fullname, scholarship_type, course, year_level, status, email)
                            VALUES (?, ?, ?, ?, ?, ?, ?)";

                            if($stmt_insert = $conn->prepare($sql_insert)) {
                                $stmt_insert->bind_param("sssssss",
                                $scholarID,
                                $fulname,
                                $scholarship,
                                $course,
                                $year,
                                $status,
                                $email);
                                
                                if(!$stmt_insert->execute()) {
                                    throw new Exception("Failed to add scholars");
                                }

                                $stmt_insert->close();
                            }
                            else {
                                throw new Exception("Failed to capture application.");
                            }
                        }

                    }

                    else {
                        throw new Exception("Failed to prepare application.");
                    }

                    $badge = "bg-success";
                    $message = "Successful Approval executed.";

                    // ACTIVITY LOGS
                    $adminID = $_SESSION['id'] ?? 0;
                    $adminName = $_SESSION['fullname'] ?? " ";
                    $logs = "APPROVED by {$adminName} | {$fulname} - (Application ID: {$applicantID})";
                    activityLogs($conn, $adminID, $logs);

                    // NOTIFICATIONS

                    $convertID = str_replace("TVAM-", "", $fetch['student_id']);
                    $targetID = (int)$convertID;

                    $notif_title = "Scholarship Application Updated - APPROVED!";
                    $notif_text = "Hello, {$fulname} - {$scholarID}! your application for the {$scholarship} has been updated. Check your status card for more information. Best of luck!";
                    notificationAlert($conn, $targetID, $notif_title, $notif_text);
                }

                else {
                    throw new Exception("Failed to capture updates. Try again later or try contacting admin.");
                }

            
            
            mysqli_commit($conn);
            header("Location: index.php");
            exit();
        }

        catch(Exception $e) {
            $conn->rollback();
            $message = $e->getMessage();
            $badge = "bg-danger";
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

<body class="approved-body container-fluid min-vh-100 d-flex align-items-center justify-content-center">
    <div class="approved-class container-fluid p-4 px-5 py-5 border rounded-3 d-flex flex-column align-items-center shadow-">
        <form action="" method="post" class="d-flex flex-column gap-3">
            <div class="d-flex flex-row align-items-center justify-content-center">
                <img src="/TVAM_SCHOLARSHIP/assets/images/TVAMLOGO.png" alt="" class="img-rounded" style="height: 100px;">
                <h2 class="text-uppercase fw-bold fs-4">APPROVE APPLICATION</h2>
            </div>
            <div class="">
                <label for="application_id" class="form-label fw-bold">Application ID</label>
                <input type="text" name="application_id" class="form-control" value="<?php echo htmlspecialchars($application['application_ID']); ?>" readonly>
            </div>

            <div>
                <label for="fullname" class="form-label fw-bold">Fullname</label>
                <input type="text" class="form-control" value="<?php echo htmlspecialchars($application['fullname']); ?>" readonly>
            </div> 
            <div>
                <label for="scholarship_type" class="form-label fw-bold">Scholarship</label>
                <input type="text" name="scholarship_type" class="form-control" value="<?php echo htmlspecialchars($application['scholarship_type']); ?>" readonly>
            </div>

            <div class="d-flex align-items-center">
                <button type="submit" name="approved" class="btn btn-success w-100 fw-bold mt-3">Approved</button>
            </div>
        </form>
    </div>


 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
