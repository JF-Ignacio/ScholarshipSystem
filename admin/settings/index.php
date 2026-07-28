<?php 
require_once "../../config/database.php";
require_once "../../config/admin-auth.php";
require_once "../../includes/functions.php";

$message = "";
$badge = "";

$event_ID = isset($_GET['id']) ? intval($_GET['id']) : 0;

$scholarshipStatus = [
    'open',
    'closed',
    'upcoming'
];

if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['scholarship_status'])) {
    $status = trim($_POST['scholarship_status'] ?? '');

    if(!in_array($status, $scholarshipStatus, true)) {
        $message = "Invalid Scholarship token";
        $badge = "danger";
    }
    else {
        $update = updateEventSettings(
            $conn,
            'scholarship_status',
            $status,
            'Student can now submit their application before deadline'
        );

        if ($update) {
            $message = "Scholarship status updated";
            $badge = "success";
        }
        else {
            $message = "Failed to update settings";
            $badge = "warning";
        }
    }
}

$settings = [];

$sql = "SELECT settings_key, settings_value FROM settings";
$result = $conn->query($sql);

if($result) {
    while($row = $result->fetch_assoc()) {
        $settings[$row['settings_key']] = $row['settings_value'];
    }
}


$schoolName = $settings['school_name'] ?? '';


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Settings</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="../../assets/images/TVAMLOGO.png">
    <link rel="stylesheet" href="../../assets/css/admin.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body class="admin-page">
    <div class="min-vh-100 d-flex flex-column flex-md-row">
        <?php include "../../includes/sidebar.php"; ?>

        <main class="container-fluid flex-grow-1 p-4">
            <div class="card">
                <div class="card-header">
                    <h3></h3>
                </div>
                <div class="card-body">
                    <form action="" method="POST">
                        <div>
                            <label for="school_name">SCHOOL NAME: </label>
                            <input type="text" name="school_name" id="" value="<?php echo htmlspecialchars($schoolName); ?>"readonly>
                        </div>
                        <div>
                            <label for="scholarship_status">SCHOLARSHIP STATUS: </label>
                            <select name="scholarship_status" id="">
                                <option value="open">OPEN</option>
                                <option value="closed">CLOSE</option>
                            </select>
                        </div>

                        <div>
                            <label for="deadline">DEADLINE: </label>
                            <input type="datetime-local" name="deadline" id="">
                        </div>

                        <button type="submit">ADD EVENT</button>
                        <button type="submit">EDIT EVENT</button>
                    </form>
                </div>

                <div class="card-footer">
                    <span class="badge bg-<?php echo $badge?>">
                        <?php echo htmlspecialchars($message); ?>
                    </span>
                </div>
            </div>
        </main>
    </div>
</body>
</html>