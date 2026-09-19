<?php 
require_once "../../config/database.php";
require_once "../../config/session.php";

$id = $_SESSION['id'] ?? 0;

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_name'])) {
    $fullname = trim($_POST['fullname'] ?? '');

    if(empty($fullname)) {
        $_SESSION['flash_message'] = "Do not leave empty fields.";
        $_SESSION['flash_badge'] = "danger";

        header("Location: ../profile.php");
        exit();
    }

    $stmt_update = $conn->prepare("UPDATE users SET fullname = ? WHERE id = ?");

    if(!$stmt_update) {
        $_SESSION['flash_message'] = "Failed to update. Try again or Contact admin.";
        $_SESSION['flash_badge'] = "danger";
        
        header("Location: ../profile.php");
        exit();
    }

    $stmt_update->bind_param("si", $fullname, $id);
    $stmt_update->execute();

    $stmt_update_scholars = $conn->prepare("UPDATE scholars SET fullname = ? WHERE user_id = ?");

    if(!$stmt_update_scholars) {
        $_SESSION['flash_message'] = "Failed to update. Try again or Contact admin.";
        $_SESSION['flash_badge'] = "danger";
        
        header("Location: ../profile.php");
        exit();
    }

    $stmt_update_scholars->bind_param("si", $fullname, $id);
    $stmt_update_scholars->execute();

    $_SESSION['flash_message'] = "Updated successfully.";
    $_SESSION['flash_badge'] = "success";

    $stmt_update->close();
    $stmt_update_scholars->close();

    header("Location: ../profile.php");
    exit();

}


?>