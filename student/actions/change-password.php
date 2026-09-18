<?php
require_once "../../config/session.php";
require_once "../../config/database.php";
require_once "../../config/student-auth.php";

$id = $_SESSION['id'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $current_pass = $_POST['current_password'] ?? '';
    $new_pass     = $_POST['new_password'] ?? '';
    $confirm_pass = $_POST['confirm_password'] ?? '';

    if (empty($current_pass) || empty($new_pass) || empty($confirm_pass)) {
        $_SESSION['flash_message'] = 'All password fields are required.';
        $_SESSION['flash_badge']   = 'danger';
        header("Location: ../profile.php");
        exit();
    }

    if ($new_pass !== $confirm_pass) {
        $_SESSION['flash_message'] = 'New passwords do not match.';
        $_SESSION['flash_badge']   = 'danger';
        header("Location: ../profile.php");
        exit();
    }

    $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if (!$user || !password_verify($current_pass, $user['password'])) {
        $_SESSION['flash_message'] = 'Incorrect current password.';
        $_SESSION['flash_badge']   = 'danger';
        header("Location: ../profile.php");
        exit();
    }

    $new_hash = password_hash($new_pass, PASSWORD_BCRYPT);
    $update_stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
    $update_stmt->bind_param("si", $new_hash, $id);

    if ($update_stmt->execute()) {
        $_SESSION['flash_message'] = 'Password updated successfully!';
        $_SESSION['flash_badge']   = 'success';
    } else {
        $_SESSION['flash_message'] = 'Failed to update password.';
        $_SESSION['flash_badge']   = 'danger';
    }

    header("Location: ../profile.php");
    exit();
}