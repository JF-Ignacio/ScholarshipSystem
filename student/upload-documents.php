<?php 

require_once "../config/database.php";
require_once "../config/student-auth.php";

$id = $_SESSION['id'] ?? 0;
$message = "";
$badge = "danger";
$result = null; 

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_file'])) {

    $document_type = $_POST['documentType'] ?? '';

    $allowed_file_name = ['Certificate of Enrollment', 'Grade Transcript', 'Disbursement Record'];
    $allowed_file_extension = ['pdf', 'jpg', 'png', 'jpeg'];

    if(!in_array($document_type, $allowed_file_name)) {
        $message = "Invalid Document";
    }

    elseif(!isset($_FILES['fileUpload']) || $_FILES['fileUpload']['error'] !== UPLOAD_ERR_OK) {
        $message = "Invalid Upload. Try Again.";
    }

    else {
        $file = $_FILES['fileUpload'];
        $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        $f_info = finfo_open(FILEINFO_MIME_TYPE);
        $real_mime_type = finfo_file($f_info, $file['tmp_name']);
        finfo_close($f_info);

        $allowed_mime_type = [
            'application/pdf',
            'image/jpeg',
            'image/pdf',
            'image/png',
            'image/jpg'
        ];

        if($file['size'] > 5 * 1024 * 1024) {
            $message = "File size exceeds the limit. File must be 5mb or less.";
        } 
        elseif(!in_array($real_mime_type, $allowed_mime_type)) {
            $message = "Invalid File extension. Try Again.";
        }

        else {
            $upload_directory = "../assets/uploads/document/";
            
            if(!is_dir($upload_directory)) {
                mkdir($upload_directory, 0755, true);
            }

            $clean_type = strtolower(str_replace(' ', '_', $document_type));
            $new_file_name = "tvam_user_". $id . "_"  .$clean_type . "_" . time() . "." .$file_ext;
            $target_file_path = $upload_directory.$new_file_name;

            try { 
                
                $conn->begin_transaction();

                if(move_uploaded_file($file['tmp_name'], $target_file_path)) {

                    $db_save_path = "/TVAM_SCHOLARSHIP/assets/uploads/document/" .$new_file_name;

                    $insert_sql = "INSERT INTO documents (user_id, document_type, file_name, file_path, status)
                                    VALUES (?, ?, ?, ?, 'Pending')";
                    $insert_stmt = $conn->prepare($insert_sql);
                    $insert_stmt->bind_param("isss", $id, $document_type, $file['name'], $db_save_path);

                    if(!$insert_stmt->execute()) {
                        $message = "Database Upload failed.";
                        $conn->rollback();
                    }

                    else {
                        $message = "Document upload successful!";
                        $badge = "success";
                        $insert_stmt->close();
                        $conn->commit();
                    }

                }

            }
            catch (Exception $e) {
                $conn->rollback();
                $message = "Error in handling file".$e->getMessage();
            }
        }
    }
}

// Always fetch documents - moved outside POST block
$show_docu_sql = "SELECT user_id, document_type, file_name, file_path, created_at 
                FROM documents 
                WHERE user_id = ?";
$stmt_show = $conn->prepare($show_docu_sql);
$stmt_show->bind_param("i", $id);
$stmt_show->execute();
$result = $stmt_show->get_result();

?>

<?php include "../includes/header.php"; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/TVAM_SCHOLARSHIP/assets/css/style.css">
    <title>Uploads</title>
</head>
<body class="student-upload"> 
    <div class="container-fluid vh-100 d-flex flex-column flex-grow-1 gap-4 align-items-center p-3 py-5 px-4 mt-4 justiy-content-center">
        <div class="card shadow-lg border-1 overflow-hidden" style="width: 100%; max-width: 600px;">
            <div class="card-header bg-secondary py-3 text-center" style="background: linear-gradient(190deg, rgb(10, 10, 112), rgb(105, 183, 251), rgb(10, 10, 112)) !important;">
                <h3 class="fw-bold text-white fs-4 d-flex justify-content-center text-center">Upload Documents</h3>
                <span class="text-white" style="font-size: 0.9rem;">All documents are used only for academic purposes.</span>
            </div>

            <div class="card-body p-3 overflow-y-auto" style="max-height: calc(100vh - 200px);">
                <div class="mt-3">
                    <?php if(!empty($message)) :  ?>
                        <div class="alert alert-<?php echo $badge?>"> <?php echo htmlspecialchars($message); ?></div>
                    <?php endif; ?>
                </div>

                <form action="" method="post" enctype="multipart/form-data" class="p-3 px-5">
                    <div class="mb-3">
                        <label for="documentType" class="form-label">Document Availability</label>
                        <select name="documentType" id="documentType" class="form-select">
                            <option value="Certificate of Enrollment">Certificate of Enrollment (COE)</option>
                            <option value="Grade Transcript">Grade Transcript (GT)</option>
                            <option value="Disbursement Record">Disbursement of Record (DOR)</option>
                        </select>
                    </div>

                    <div class="mt-3">
                        <label for="fileUpload" class="form-label">Upload File</label>
                        <input type="file" name="fileUpload" id="fileUpload" class="form-control" accept=".pdf,.png.,.jpg,.jpeg" required>
                        <small class="form-text text-muted fst-italic">Attached only PDF, PNG, JPG, JPEG formats</small>
                    </div>

                    <div class="mt-4">
                        <button type="submit" name="submit_file" class="btn btn-success">Upload File</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="mt-4 table-responsive">
            <table class="table table-responsive border shadow-sm table-hover">

                <thead class="">
                    <tr class="bg-primary">
                        <th>UID</th>
                        <th>DOCUMENT TYPE</th>
                        <th>FILE NAME</th>
                        <th>FILE PATH</th>
                        <th>CREATED AT</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if($result && $result->num_rows > 0) : ?>
                        <?php while($fetch = $result->fetch_assoc()) : 
                        ?>
                    <tr>
                        <td> <?php echo htmlspecialchars($fetch['user_id']); ?></td>
                        <td> <?php echo htmlspecialchars ($fetch['document_type']); ?></td>
                        <td>
                            <a href="<?php echo htmlspecialchars($fetch['file_path']); ?>" target="_blank" rel="noopner noreferrer">
                                <?php echo htmlspecialchars($fetch['file_name']); ?>
                            </a>
                        </td>
                        <td> <?php echo htmlspecialchars($fetch['file_path']);?></td>
                        <td><?php echo htmlspecialchars($fetch['created_at']);?></td>
                    </tr>
                    <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td>
                                No documents upload this time.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>