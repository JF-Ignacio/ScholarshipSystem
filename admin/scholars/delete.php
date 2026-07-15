<?php

require_once "../../config/admin-auth.php";
require_once "../../includes/functions.php";

$id = $_GET['id'] ?? 0;
$scholars = null;
$badge  = "";

$stmt = mysqli_prepare($conn, "SELECT * FROM scholars WHERE id = ?");

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    $deleteResult = mysqli_stmt_get_result($stmt);
    $scholars = mysqli_fetch_assoc($deleteResult);

    if(!$scholars) {
        die ("No scholars found!");
    }
}

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['delete'])) {

    mysqli_begin_transaction($conn);

    try {

        $rawID = $scholars['student_id'];
        $conversion = str_replace("TVAM-", "", $rawID);
        $studentID = (int) $conversion;

        $inactive_query = "UPDATE scholars SET status = 'inactive' WHERE id = ?";
        $inactive_stmt = $conn->prepare($inactive_query);
        if (!$inactive_stmt) {
            throw new Exception ("Failed to delete data.");
        }

        $inactive_stmt->bind_param("i", $id);
        if(!$inactive_stmt->execute()) {
            throw new Exception("Failed to load in the database engine.");
        }

        $inactive_stmt->close();

        $update_query = "UPDATE applications SET status = 'Inactive' WHERE user_id = ?";
        $update_stmt = $conn->prepare($update_query);

        if(!$update_stmt) {
            throw new Exception ("Failed data to load");
        }

        $update_stmt->bind_param("i", $studentID);
        if(!$update_stmt->execute()) {
            throw new Exception ("Failed to load in engine");
        }

        $update_stmt->close();

        $admin_id = $_SESSION['id'];
        $adminName = $_SESSION['fullname'] ?? " ";
        $logActivity = "DELETED by {$adminName} | {$scholars['fullname']} - ({$scholars['student_id']})";
        activityLogs($conn, $admin_id, $logActivity);

        mysqli_commit($conn);

        header("Location: ../../admin/scholars/index.php");
        exit();
    }

    catch (Exception $e) {
        mysqli_rollback($conn);
        die("Critical system error: " .htmlspecialchars($e->getMessage()));
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
    <link rel="stylesheet" href="/TVAM_SCHOLARSHIP/assets/css/style.css">
    <link rel="stylesheet" href="/TVAM_SCHOLARSHIP/assets/css/admin.css">
    <link rel="icon" href="../../assets/images/tvamlogo_web.png">
</head>

<main class="container-fluid d-flex align-items-center justify-content-center min-vh-100 p-5 mt-4">
    <form action="delete.php?id=<?php echo $id; ?>" method="POST" class="card card-sm shadow-lg border border-black" style="min-height: 300px; width: 450px; ">

        <div class="card-header bg-transparent d-flex justify-content-center align-items-center text-center" style="min-height: 80px;">
            <img src="../../assets/images/tvamlogo_web.png" alt="TVAM LOGO" class="img-fluid"style="width: 100px;">
            <h2 class="fs-3">DELETE ISKOLAR</h2>
        </div>

        <div class="card-body d-flex flex-column">
            <div class="alert alert-danger d-flex align-items-center justify-content-center gap-2 text-center mb-4" role="alert">
                <strong>WARNING:</strong>
                <span>Do you really want to delete this data?</span>
            </div>
            <label for="student_id" class="form-label fw-bold">STUDENT ID:</label>
            <input type="text" name="student_id" value="<?php echo htmlspecialchars($scholars['student_id']);?>" class="form-control mb-4" readonly>

            <label for="fullname" class="form-label fw-bold">FULLNAME:</label>
            <input type="text" name="fullname" value="<?php echo htmlspecialchars($scholars['fullname']);?>" class="form-control" readonly>
        </div>
        <div class="card-footer p-3">
            <button type="submit" class="btn btn-danger" name="delete">DELETE</button>
            <a href="../../admin/scholars/index.php" class="btn btn-secondary">EXIT</a>
        </div>
    </form>
</main>
