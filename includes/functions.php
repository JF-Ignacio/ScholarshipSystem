<?php 

include "../config/database.php";
/**
 * * @param mysqli $conn 
 * 
 */


function activityLogs ($conn, $userID, $action) {
    $sql = "INSERT INTO activity_logs (user_id, actions) VALUES (?, ?)";

    if($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("is", $userID, $action);
        $result = $stmt->execute();
        $stmt->close();

        return $result;
    }

    return false;
}

function notificationAlert($conn, $userID, $title, $message) {
    $notif_sql = "INSERT INTO notifications (user_id, title, message) VALUES (?, ?, ?)";

    if($stmt_notif = $conn->prepare($notif_sql)) {
        $stmt_notif->bind_param("iss", $userID, $title, $message);
        $notifResult = $stmt_notif->execute();
        $stmt_notif->close();

        return $notifResult;
    }

    return false;
}

function getEventSettings($conn, $key, $default = "") {
    $key_sql = "SELECT settings_value FROM settings
                WHERE settings_key = ?
                LIMIT 1";
    $key_stmt = $conn->prepare($key_sql);

    if(!$key_stmt) return $default;

    $key_stmt->bind_param("s", $key);

    if(!$key_stmt->execute()) {
        $key_stmt->close();
        return $default;
    }

    $eventResult = $key_stmt->get_result();
    $rowEvent = $eventResult->fetch_assoc();

    $key_stmt->close();

    return $rowEvent['settings_value'] ?? $default;
}

function updateEventSettings($conn, $key, $value, $description = '') {
    $update_sql = "INSERT INTO settings (settings_key, settings_value, description)
                    VALUES (?, ?, ?)
                    ON DUPLICATE KEY UPDATE
                    settings_value = VALUES(settings_value),
                    description = VALUES(description),
                    updated_at = CURRENT_TIMESTAMP";
    $update_stmt = $conn->prepare($update_sql);

    if(!$update_stmt) return false;

    $update_stmt->bind_param("sss", $key, $value, $description);

    if(!$update_stmt->execute()) {
        $update_stmt->close();
        return false;
    }

    $update_stmt->close();
    return true;
}

function ProfileUpload($conn, int $userID, array $file): array {
    if($file['error'] != UPLOAD_ERR_OK) {
        return ['success' => false, 'message' => 'Failed to upload.'];
    }

    $max_file_size = 5 * 1024 * 1024;
    if($file['size'] > $max_file_size) {
        return ['success' => false, 'message' => 'File size is too large. 5MB or less.'];
    }

    $f_info = finfo_open(FILEINFO_MIME_TYPE);
    $mime_type = finfo_file($f_info, $file['tmp_name']);
    finfo_close($f_info);

    $allowed_mime_type = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
    ];

    if(!array_key_exists($mime_type, $allowed_mime_type)) {
        return ['success' => false, 'messasge' => 'File type is not allowed. jpg, png, and webp.'];
    }

    $extension = $allowed_mime_type[$mime_type];
    $token_name = bin2hex(random_bytes(16)) . '.' . $extension;

    $upload_dir = __DIR__ . '/../assets/uploads/profile-pictures/';
    $destination = $upload_dir . $token_name;

    if(!move_uploaded_file($file['tmp_name'], $destination)) {
        return ['success' => false, 'message' => 'Failed to upload. Try Again.'];
    }

    $sql = "INSERT INTO user_profiles (profile_id, profile_image) VALUES (?, ?)
            ON DUPLICATE KEY UPDATE profile_image = ?";
    $stmt = $conn->prepare($sql);

    if(!$stmt) {
        @unlink($destination);
        return ['success' => false, 'message' => 'Upload Failed. Try again or Contact admin.'];
    }

    $stmt->bind_param("iss", $userID, $token_name, $token_name);
    $stmt_success = $stmt->execute();
    $stmt->close();

    if(!$stmt_success) {
        @unlink($destination);
        return ['success' => false, 'message' => 'DB error: ' . $stmt->error];
    }

    activityLogs($conn, $userID, 'Profiile Picture updated');
    return ['success' => true, 'message' => 'Upload Succeded.'];
}

function CreateDescription($conn, int $userID, $description) {
    $sql = "INSERT INTO user_profiles (profile_id, description) VALUES (?, ?)
            ON DUPLICATE KEY UPDATE description = ?";
    $stmt_description = $conn->prepare($sql);

    if(!$stmt_description) {
        return ['success' => false, 'message' => 'Update failed. Try again'];
    }

    $stmt_description->bind_param("iss", $userID, $description, $description);
    $stmt_success = $stmt_description->execute();
    $stmt_description->close();

    if(!$stmt_success) {
        return ['success' => false, 'message' => 'Upload failed. Try again.'];
    }

    activityLogs($conn, $userID, 'Profile Description added.');
    return ['success' => true, 'message' => 'Description Added'];
}
?>