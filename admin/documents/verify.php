<?php 

require_once "../../config/database.php";
require_once "../../config/admin-auth.php";


$id = $_SESSION['id'] ?? 0;
$documentID = isset($_GET['id']) ? intval($_GET['id']) : 0;
$message = "";
$badge = "";

$doc = null;

if($documentID > 0) {
    $select_sql = "SELECT d.id, d.document_type, d.file_name, d.status,
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
        $stmt->close();
    }
}

if(!$doc) die ("DATABASE FAILED. TRY CONTACTING ADMIN.");

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['verify'])) {
    $conn->begin_transaction();

    try {
        $update_sql = "UPDATE documents SET status='Approved' WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);

    }

    catch(exception $e) {

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
        <div class="card border-0 rounded-1 shadow-lg p-3 px-4 py-4">
            <div class="card-header bg-transparent border-0 d-flex flex-md-row align-items-center justify-content-center p-0">
                <img src="../../assets/images/TVAMLOGO.png" alt="" class="img-fluid" style="width: 120px;">
                <h3 class="text-uppercase fs-5 fw-bold">APPROVED FILE</h3>
            </div>

            <div class="card-body">
                <form action="" method="POST" class="form-group d-flex flex-md-column gap-3">
                    <div>
                        <label for="name" class="form-label">Fullname:</label>
                        <input type="text" name="name" class="form-control" readonly>
                    </div>

                    <div>
                        <label for="file" class="form-label">File name</label>
                        <input type="file" name="file" id="file" class="form-control" value="">
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