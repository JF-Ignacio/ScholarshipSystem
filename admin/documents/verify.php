<?php 

require_once "../../config/database.php";
require_once "../../config/admin-auth.php";
require_once "../../includes/functions.php";


$id = $_SESSION['id'] ?? 0;
$documentID = isset($_GET['id']) ? intval($_GET['id']) : 0;
$message = "";
$badge = "";

$doc = null;

if($documentID > 0) {
    $select_sql = "SELECT d.id, d.document_type, d.file_name, d.status, d.file_path,
                    s.fullname, s.student_id, s.scholarship_type
                    FROM documents d
                    INNER JOIN scholars s ON d.user_id = CAST(REPLACE(s.student_id, 'TVAM-', '') AS UNSIGNED)
                    WHERE d.id = ? 
                    LIMIT 1";
    
    if($stmt = $conn->prepare($select_sql)) {
        $stmt->bind_param("i", $documentID);
        $stmt->execute();

        $result = $stmt->get_result();
        $doc = $result->fetch_assoc();

        $studentID = $doc['student_id'];
        $fullname = $doc['fullname'];
        $file = $doc['file_name'];
        $filePath = $doc['file_path'];


        $stmt->close();
    }
}

if(!$doc) die ("DATABASE FAILED. TRY CONTACTING ADMIN.");

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['verify'])) {
    $conn->begin_transaction();

    try {
        $update_sql = "UPDATE documents SET status='Approved' WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);

        if(!$update_stmt) {
            throw new Exception ("Database failed. Contact admin.");
        }

        $update_stmt->bind_param("i", $documentID);

        if(!$update_stmt->execute()) {
            throw new Exception ("Database execution failed. Contact admin.");
        }

        $conn->commit();
        $update_stmt->close();

        // ACTIVITY LOGS 
        $adminName = $_SESSION['fullname'] ?? "admin";
        $logs = "FILES APPROVED - {$fullname} ({$studentID}) |- ${adminName}";
        activityLogs($conn, $id, $logs);

        header("Location: index.php");
        exit();
    }

    catch(exception $e) {
        $conn->rollback();
        $message = "Error ". $e->getMessage();
    }
}




?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Approved File</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="/TVAM_SCHOLARSHIP/assets/css/admin.css">
    <link rel="icon" href="../../assets/images/tvamlogo_web.png">
</head>
<body>
    <div class="min-vh-100 d-flex align-items-center justify-content-center overflow-hidden">
        <div class="card border-0 rounded-1 shadow-lg p-3 px-4 py-4 w-25 w-md-50">
            <div class="card-header bg-transparent border-0 d-flex flex-md-row align-items-center justify-content-center p-0">
                <img src="../../assets/images/TVAMLOGO.png" alt="" class="img-fluid" style="width: 120px;">
                <h3 class="text-uppercase fs-5 fw-bold">APPROVED FILE</h3>
            </div>

            <div class="card-body d-flex flex-md-column">
                <form action="" method="POST" class="form-group d-flex flex-md-column gap-3">
                    
                    <div>
                        <label for="student_id" class="form-label">Student ID:</label>
                        <input type="text" name="student_id" id="" value="<?php echo htmlspecialchars($studentID); ?>" class="form-control" readonly>
                    </div>

                    <div>
                        <label for="name" class="form-label">Fullname:</label>
                        <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($fullname); ?>" readonly>
                    </div>

                    <div>
                        <label for="file">File Submitted:</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-file-earmark-pdf-fill">
                            </i></span>
                            <input type="text" name="file" class="form-control fst-italic" value="<?php echo htmlspecialchars($file);?>" readonly>
                                <a href="<?php echo htmlspecialchars($filePath); ?>" target="_blank" class="btn btn-outline-dark fw-bold">
                                    <li class="bi bi-eye-fill">VIEW</li>
                                </a>
                        </div>
                    </div>

                    <div class="mt-3">
                        <button type="submit" name="verify" class="btn btn-success text-uppercase w-100 fw-bold">VERIFY</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>