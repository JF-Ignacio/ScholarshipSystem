<?php
require_once "../config/database.php";
require_once __DIR__ . "/../../config/student-auth.php";

header("Content-Type: application/json");

function jsonResponse($success, $message, $data = [], $status = 200) {
    http_response_code($status);

    echo json_encode([
        "success" => $success,
        "message" => $message,
        "data" => $data
    ]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== "POST") {
    jsonResponse(false, "Invalid Request Method", [], 405);
}

$id = $_SESSION['id'] ?? 0;
$fullname = trim($_POST['fullname'] ?? '');

if ($id <= 0) {
    jsonResponse(false, "You must be logged in.", [], 401);
}

if ($fullname === '') {
    jsonResponse(false, "Full name is required.", [], 422);
}

$stmt_update = $conn->prepare("UPDATE users SET fullname = ? WHERE id = ?");

if (!$stmt_update) {
    jsonResponse(false, "Failed to update profile. Contact admin.", [], 500);
}

$stmt_update->bind_param("si", $fullname, $id);

if (!$stmt_update->execute()) {
    $stmt_update->close();
    jsonResponse(false, "Failed to update profile.", [], 500);
}

$stmt_update->close();

$stmt_update_scholars = $conn->prepare("UPDATE scholars SET fullname = ? WHERE user_id = ?");

if (!$stmt_update_scholars) {
    jsonResponse(false, "Profile name updated, but scholar record failed.", [], 500);
}

$stmt_update_scholars->bind_param("si", $fullname, $id);

if (!$stmt_update_scholars->execute()) {
    $stmt_update_scholars->close();
    jsonResponse(false, "Profile name updated, but scholar record failed.", [], 500);
}

$stmt_update_scholars->close();
$_SESSION['fullname'] = $fullname;

jsonResponse(true, "Profile updated successfully.", [
    "fullname" => $fullname
]);
