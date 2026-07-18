<?php

require_once "../../config/session.php";
require_once "../../config/database.php";

$is_admin = isset($_SESSION['admin_id']) || (isset($_SESSION['role']) && $_SESSION['role'] === 'admin');
$current_user_id = $_SESSION['user_id'] ?? $_SESSION['id'] ?? 0;

if (!$is_admin && $current_user_id <= 0) {
    http_response_code(401);
    die("401 UNAUTHORIZED: PLEASE LOG IN.");
}

$documentToken = $_GET['token'] ?? '';

if(empty($documentToken) || !ctype_xdigit($documentToken)) {
    http_response_code(400);
    die("400 BAD REQUEST: INVALID DOWNLOAD TOKEN");
}
$sql = "SELECT d.id, d.user_id, d.file_name, d.document_type, d.file_path
        FROM documents d
        WHERE d.download_token = ?
        LIMIT 1";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    http_response_code(500);
    die("Database Error.");
}

$stmt->bind_param("s", $documentToken);
$stmt->execute();
$result = $stmt->get_result();
$doc = $result->fetch_assoc();
$stmt->close();

if (!$doc) {
    http_response_code(404);
    die("404 NOT FOUND: Document record does not exist.");
}

if (!$is_admin && (int)$current_user_id !== (int)$doc['user_id']) {
    http_response_code(403);
    die("403 ACCESS DENIED: You do not have permission to access this file.");
}

$real_file_path = $_SERVER['DOCUMENT_ROOT'] . $doc['file_path'];

if (!file_exists($real_file_path)) {
    http_response_code(404);
    die("404 NOT FOUND: Physical file is missing from server path: " . htmlspecialchars($doc['file_path']));
}

$mime_type = mime_content_type($real_file_path) ?: 'application/octet-stream';

if (ob_get_level()) {
    ob_end_clean();
}

header("Content-Type: " . $mime_type);
header("Content-Length: " . filesize($real_file_path));

$disposition = (isset($_GET['action']) && $_GET['action'] === 'download') ? 'attachment' : 'inline';
header("Content-Disposition: {$disposition}; filename=\"" . basename($doc['file_name']) . "\"");

header("Cache-Control: private, max-age=0, must-revalidate");
header("Pragma: public");

readfile($real_file_path);
exit();
