<?php 

require_once "../../config/database.php";
require_once "../../config/admin-auth.php";
require_once "../../includes/functions.php";

$id = $_SESSION['id'] ?? 0;

$view_sql = "SELECT * FROM scholars WHERE id = ?";
$view_stmt = $conn->prepare($view_sql);
$view_stmt->bind_param("i", $id);
$view_stmt->execute();
$scholar = $view_stmt->get_result()->fetch_assoc();

if(!$scholar) { die("No scholar found."); }

$clean_id = str_replace("TVAM-", "", $scholar['student_id']);
$newID = (int)$clean_id;

    // ACITIVY LOGS QUERY
    $admin_id = $_SESSION['id'] ?? 0;
    $admin_name = $_SESSION['fullname'] ?? " ";
    $logs = "VIEW by ADMIN {$admin_name} | {$scholar['fullname']} - ({$scholar['student_id']})";
    activityLogs($conn, $admin_id, $logs);

    // NOTIFICATIONS QUERY

    $notif_sql = "SELECT title, message, created_at
                FROM notifications
                WHERE user_id = ?";
    
    $stmt_sql = $conn->prepare($notif_sql);
    $stmt_sql->bind_param("i", $newID);
    $stmt_sql->execute();
    $notifResult = $stmt_sql->get_result()->fetch_assoc();

    $notif_title = "Profile Visits!";
    $notif_text = "Hello, {$scholar['fullname']} ({$scholar['student_id']})! Someone visits your profile.";
    notificationAlert($conn, $newID, $notif_title, $notif_text);

    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - TVAM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/TVAM_SCHOLARSHIP/assets/css/style.css">
    <link rel="stylesheet" href="/TVAM_SCHOLARSHIP/assets/css/admin.css">
    <link rel="icon" href="../../assets/images/tvamlogo_web.png">
</head>

<main class="view-hero container-fluid d-flex justify-content-center align-items-center p-4 min-vh-100">
    <div class="main-card card">
        <div class="card-header bg-transparent d-flex align-items-center justify-content-center text-center">
            <img src="../../assets/images/TVAMLOGO.png" alt="TVAM LOGO" class="cardImg img-fluid rounded">
            <h2 class="fs-4 mt-3 mb-0">SCHOLAR INFORMATION</h2>
        </div>
        
        <div class="card-body d-flex flex-column">
            <div class="row">
                <div class="col">
                    <strong>NAME: </strong> <p> <?php echo $scholar['fullname'];?></p>
                </div>
                <div class="col">
                    <strong>TVAM ID:</strong> <p> <?php echo $scholar['student_id'];?></p>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <strong>COURSE: </strong> <p> <?php echo $scholar['course'];?></p>
                </div>
                <div class="col">
                    <strong>YEAR:</strong> <p> <?php echo $scholar['year_level'];?></p>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <strong>Scholarship: </strong> <p> <?php echo $scholar['scholarship_type'];?></p>
                </div>
                <div class="col">
                    <strong>Status: </strong> <p> <?php echo $scholar['status'];?></p>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <strong>Email: </strong> <p> <?php echo $scholar['email'];?></p>
                </div>
                <div class="col">
                    <strong>Documents: </strong> <p> Not Available</p>
                </div>
            </div>
        </div>

        <div class="card-footer d-flex gap-2">
            <a href="../../admin/scholars/index.php" class="btn btn-secondary">EXIT</a>
            <button type="submit" value="PRINT" class="btn btn-primary d-flex align-items-center gap-2" onclick="window.print()">
                <i class="bi bi-printer-fill">PRINT RECORD</i>
            </button>
        </div>
    </div>
</main>
