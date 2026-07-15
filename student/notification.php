<?php 
require_once "../config/database.php";
require_once "../config/student-auth.php";

$id = $_SESSION['id'] ?? 0;
$name = $_SESSION['fullname'] ?? " ";
$color = "";

// Fetch unread notifications
$sql = "SELECT id, title, message, created_at 
        FROM notifications 
        WHERE user_id = ? AND is_read = 0
        ORDER BY created_at DESC";

$stmt_notif = $conn->prepare($sql);
$stmt_notif->bind_param("i", $id);
$stmt_notif->execute();
$unreadData = $stmt_notif->get_result();
?>

<?php include "../includes/header.php"; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    <!-- Assuming your header.php includes Bootstrap 5, otherwise include it here -->
</head>
<body class="bg-light">
    
    <div class="container container-fluid py-5 w-100">
        <div class="mb-4 ">
            <h2 class="fw-bold text-dark fs-1"><?php echo htmlspecialchars($name); ?>'s Notifications</h2>
            <p class="text-muted small fs-5">Make sure to always check your notifications for any academic updates.</p>
        </div>

        <main class="d-flex flex-column gap-3">
            <?php if ($unreadData->num_rows > 0) { ?>
                <?php while($notif = $unreadData->fetch_assoc()) { 
                    $lowerTitle = $notif['title'];

                    if(str_contains($lowerTitle, 'approved') || str_contains($lowerTitle, 'updated')) $color = "success";
                    elseif(str_contains($lowerTitle, 'rejected')) $color = "danger";
                    else {
                        $color = "primary";
                    }
                ?>
                    <!-- Notification Item -->
                    <div class="card border-0 shadow-lg p-3 rounded-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="p-3">
                                <h6 class="fw-bold mb-1 fs-4 text-<?php echo $color; ?>">
                                    <?php echo htmlspecialchars($notif['title']); ?>
                                </h6>
                                <p class="text-dark mb-2 small"><?php echo htmlspecialchars($notif['message']); ?></p>
                                <span class="text-muted" style="font-size: 0.75rem;">
                                    <?php echo date('M d, Y | g:i A', strtotime($notif['created_at'])); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <!-- Empty State -->
                <div class="card border-0 shadow-sm p-5 text-center bg-white rounded-3">
                    <div class="text-muted">
                        <i class="bi bi-check-circle fs-1 mb-2"></i>
                        <p class="mb-0 fw-medium">All caught up!</p>
                        <small>You have no new notifications.</small>
                    </div>
                </div>
            <?php } ?>
        </main>
    </div>

</body>
</html>
<?php $stmt_notif->close(); ?>