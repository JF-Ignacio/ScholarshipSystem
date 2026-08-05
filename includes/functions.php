<?php 

include "../../config/database.php";
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

?>