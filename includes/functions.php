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

?>