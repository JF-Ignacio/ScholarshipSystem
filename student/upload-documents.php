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
                    
                    $token = bin2hex(random_bytes(16));
                    
                    $insert_sql = "INSERT INTO documents (user_id, document_type, file_name, download_token, file_path, status)
                                    VALUES (?, ?, ?, ?, ?, 'Pending')";
                    $insert_stmt = $conn->prepare($insert_sql);

                    if (!$insert_stmt) {
                        $message = "Database error. Contact Admin.";
                        $conn->rollback();
                    } else {
                        $insert_stmt->bind_param("issss", $id, $document_type, $file['name'], $token, $db_save_path);

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

            }
            catch (Exception $e) {
                $conn->rollback();
                $message = "Error in handling file".$e->getMessage();
            }
        }
    }
}

// Always fetch documents - moved outside POST block
$show_docu_sql = "SELECT id, download_token, user_id, document_type, file_name, status, file_path, created_at 
                FROM documents 
                WHERE user_id = ?
                ORDER BY created_at DESC";
$stmt_show = $conn->prepare($show_docu_sql);
$stmt_show->bind_param("i", $id);
$stmt_show->execute();
$result = $stmt_show->get_result();
$total_docs = $result->num_rows;

// Map each document type to a ledger tab color + short code, purely for display
$type_meta = [
    'Certificate of Enrollment' => ['code' => 'COE', 'class' => 'tab-coe'],
    'Grade Transcript'          => ['code' => 'GT',  'class' => 'tab-gt'],
    'Disbursement Record'       => ['code' => 'DOR', 'class' => 'tab-dor'],
];

?>

<?php include "../includes/header.php"; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Source+Serif+4:opsz,wght@8..60,500;8..60,600;8..60,700&family=Inter:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/TVAM_SCHOLARSHIP/assets/css/style.css">
    <title>Uploads</title>
    <style>
    </style>
</head>
<body class="student-upload"> 
    <div class="upload-shell">

        <div class="shell-heading">
            <span class="eyebrow">Student Portal &middot; Document Intake</span>
            <h1>Upload Documents</h1>
            <p>Submit your enrollment and disbursement records here. Every file is logged with a timestamp and reviewed by the scholarship office.</p>
        </div>

        <div class="portal-grid">

            <!-- LEFT: intake form -->
            <section class="intake-card">
                <span class="kicker">Intake Form</span>
                <h2>New Submission</h2>
                <p class="sub">All documents are used only for academic purposes.</p>

                <?php if(!empty($message)) : ?>
                    <div class="alert-banner <?php echo $badge; ?>"><?php echo htmlspecialchars($message); ?></div>
                <?php endif; ?>

                <form action="" method="post" enctype="multipart/form-data">
                    <div class="field-group">
                        <label for="documentType" class="field-label">Document Type</label>
                        <select name="documentType" id="documentType" class="intake-select">
                            <option value="Certificate of Enrollment">Certificate of Enrollment (COE)</option>
                            <option value="Grade Transcript">Grade Transcript (GT)</option>
                            <option value="Disbursement Record">Disbursement of Record (DOR)</option>
                        </select>
                    </div>

                    <div class="field-group">
                        <label for="fileUpload" class="field-label">File</label>
                        <input type="file" name="fileUpload" id="fileUpload" class="intake-file" accept=".pdf,.png,.jpg,.jpeg" required>
                        <p class="hint-text">PDF, PNG, JPG or JPEG &middot; 5MB max</p>
                    </div>

                    <button type="submit" name="submit_file" class="submit-btn">Submit Document</button>
                </form>

                <div class="privacy-note">
                    Files are stored securely and only accessible to you and the scholarship office. Each record is issued its own private access token.
                </div>
            </section>

            <!-- RIGHT: document ledger -->
            <section class="ledger-panel">
                <div class="ledger-header">
                    <h2>Your Records</h2>
                    <span class="ledger-count"><strong><?php echo (int) $total_docs; ?></strong> document<?php echo $total_docs === 1 ? '' : 's'; ?> on file</span>
                </div>

                <div class="ledger-table-wrap">
                    <table class="ledger-table">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>File</th>
                                <th>Status</th>
                                <th>Submitted</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if($result && $total_docs > 0) : ?>
                                <?php while($fetch = $result->fetch_assoc()) :
                                    $meta = $type_meta[$fetch['document_type']] ?? ['code' => '—', 'class' => 'tab-coe'];
                                    $status = $fetch['status'] ?? 'Pending';
                                    $stamp_class = ($status === 'Approved') ? 'stamp-success' : (($status === 'Rejected') ? 'stamp-danger' : 'stamp-pending');
                                ?>
                                <tr>
                                    <td>
                                        <div class="doc-type-cell">
                                            <span class="doc-tab <?php echo $meta['class']; ?>"></span>
                                            <div>
                                                <span class="doc-type-label"><?php echo htmlspecialchars($fetch['document_type']); ?></span>
                                                <span class="doc-type-code"><?php echo htmlspecialchars($meta['code']); ?></span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <a class="file-link" href="/TVAM_SCHOLARSHIP/shared/download.php?token=<?php echo htmlspecialchars($fetch['download_token']); ?>" target="_blank" rel="noopener noreferrer">
                                            <?php echo htmlspecialchars($fetch['file_name']); ?>
                                        </a>
                                    </td>
                                    <td><span class="stamp <?php echo $stamp_class; ?>"><?php echo htmlspecialchars($status); ?></span></td>
                                    <td class="created-cell"><?php echo htmlspecialchars($fetch['created_at']); ?></td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4">
                                        <div class="ledger-empty">
                                            <span class="icon-mark">&mdash;</span>
                                            <p>No documents on file yet. Submit your first record using the form on the left.</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>

        </div>
    </div>
</body>
</html>