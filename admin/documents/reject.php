<?php 

require_once "../../config/database.php";
require_once "../../config/admin-auth.php";
require_once "../../includes/functions.php";

$documentID = isset($_GET['id']) ? intval($_GET['id']) : 0;
$adminID = $_SESSION['id'] ?? 0;
$message = "";
$document = null;

if ($documentID > 0) {

    $reject_sql = "SELECT d.id, d.document_type, d.file_name, d.status, d.file_path, d.user_id,
                s.student_id, s.fullname
                FROM documents d
                INNER JOIN scholars s ON d.user_id = CAST(REPLACE(s.student_id, 'TVAM-', '') AS UNSIGNED)
                WHERE d.id = ?
                LIMIT 1";

    
    if ($stmt = $conn->prepare($reject_sql)) {
        $stmt->bind_param("i", $documentID);
        $stmt->execute();

        $result = $stmt->get_result();  
        $document = $result->fetch_assoc();

        $fullname = $document['fullname'] ?? "";
        $studentID = $document['student_id'] ?? "";
        $filePath = $document['file_path'] ?? "";
        $fileName = $document['file_name'] ?? "";

        $stmt->close();
    }
}


if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reject'])) {
    $conn->begin_transaction();

    try {
        $update_sql = "UPDATE documents SET status='Rejected' WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);

        if (!$update_stmt) {
            throw new Exception ("DATABASE FAILED. CONTACT ADMIN.");
        }

        $update_stmt->bind_param("i", $documentID);

        if(!$update_stmt->execute()) {
            throw new Exception ("DATABASE FAILED TO EXECUTE. CONTACT ADMIN");
        }

        $conn->commit();
        $update_stmt->close();

        // ACTIVITY LOGS 

        $adminName = $_SESSION['fullname'] ?? "admin";
        $logs = "FILES REJECTED - {$fullname} ({$studentID}) |- ${adminName}";
        activityLogs($conn, $adminID, $logs);

        // NOTIFICATION
        $userID = $document['user_id'];
        $notif_title = "FILE APPROVAL - REJECTED";
        $notif_message = "Hello, {$fullname} - {$studentID} | I'm sorry to tell you that your file is denied as it does not meet the requirements.";
        notificationAlert($conn, $userID, $notif_title, $notif_message);

        header("Location: index.php");
        exit();
    }

    catch(exception $e) {
        $conn->rollback();
        $message = "ERROR ". $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reject File</title>
</head>
<body>
    <div class="min-vh-100">
        <div>
            <form action="" method="POST">
                <div>
                    <label for="studentID">TVAM ID:</label>
                    <input type="text" name="studentID" value="<?php echo htmlspecialchars($studentID);  ?>" readonly>
                </div>

                <div>
                    <label for="fullname">FULLNAME</label>
                    <input type="text" name="fullname" value="<?php echo htmlspecialchars($fullname); ?>" readonly>
                </div>
                
                <div>
                    <label for="file">FILE NAME</label>
                    <div>
                        <input type="text" name="file" value="<?php echo htmlspecialchars($fileName); ?>" readonly>
                        <a href="<?php echo htmlspecialchars($filePath); ?>" target="_blank">
                            <i class="bi bi-eye-fill ">
                            </i>VIEW
                        </a>
                    </div>
                </div>

                <div>
                    <button type="submit" name="reject">REJECT</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>