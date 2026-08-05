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

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    if(isset($_POST['scholar'])) {

        $status = $_POST['scholarship_status'] ?? '';

        if(!in_array($status, $scholarshipStatus, true)) {
            $message = "Event added successfully.";
            $badge = "danger";
        }
        else {
            $update = updateEventSettings($conn, 'scholarship_status', $status, 'Student can now Submit Scholarship');
            $message = $update ? 'Event Successfully added' : 'Event failed to add';
            $badge = $update ? 'success' : 'danger';
        }
    } 

    if(isset($_POST['app'])) {
        $application_status = trim($_POST['application_deadline']) ?? '';

        if(empty($application_status)) {
            $message = "Date is empty. Apply deadline now.";
            $badge = "warning";
        }
        else {
            $update_app = updateEventSettings($conn, 'application_deadline', $application_status, 'Student can now submit Application');
            $message = $update_app ? 'Application date is set.' : 'No application date was set';
            $badge = $update_app ? 'succes' : 'danger';
        }
    }

    if(isset($_POST['file'])) {
        $file_status = trim($_POST['file_deadline']) ?? '';

        if(empty($file_status)) {
            $message = "File deadline is empty.";
            $badge = "warning";
        }

        else {
            $file_update = updateEventSettings($conn, 'file_deadline', $file_status, 'Student now can process file submission');
            $message = $file_update ? 'File Deadline is set.' : 'File deadline settings is in error';
            $badge = $file_update ? 'success' : 'danger';
        }
    }

}

$settings = [];
$key = [];

$sql = "SELECT settings_key, settings_value FROM settings";
$result = $conn->query($sql);

if($result) {
    while($row = $result->fetch_assoc()) {
        $settings[$row['settings_key']] = $row['settings_value'];
    }
}


// GET SETINGS VALUE
$schoolName = $settings['scholarship_status'] ?? '';
$application = $settings['application_deadline'] ?? '';
$file = $settings['file_deadline'] ?? '';



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

    <!--FOR FONT STYLE-->
</head>
<body class="admin-page">
    <div class="min-vh-100 d-flex flex-column flex-md-row">
        <?php include "../../includes/sidebar.php"; ?>

        <main class="container-fluid flex-grow-1 p-4 px-5">
            <div class="event-head text-uppercase p-4">
                <h4 class="fw-bold">Configurationg Settings</h4>
                <div class="support-text d-flex flex-row gap-4 mt-4 text-uppercase small text-muted">
                    <p>Manage scholarship event</p>
                    <p>Application Deadline</p>
                    <p>Document Submission</p>
                </div>
            </div>
            <div class="row p-3">
                <div class="col-4 col-lg-4">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h4>SCHOLARSHIP EVENT</h4>
                        </div>
                        <div class="card-body">
                            <form action="" method="POST">
                                <div>
                                    <label for="school_name">KEY: <label>
                                    <input type="text" name="school_name" id="" value="<?php echo htmlspecialchars($schoolName); ?>" readonly>
                                </div>
                                <div>
                                    <label for="scholarship_status">SETTINGS: </label>
                                    <select name="scholarship_status" id="">
                                        <option value="open">OPEN</option>
                                        <option value="closed">CLOSE</option>
                                    </select>
                                </div>
                                <button type="submit" name="scholar">EDIT EVENT</button>
                            </form>
                        </div>

                        <div class="card-footer">
                            <span class="badge bg-<?php echo $badge?>">
                                <?php echo htmlspecialchars($message); ?>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-4 col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h4>APPLICATION DEADLINE</h4>
                        </div>

                        <div class="card-body">
                            <form action="" method="POST" class="form-group">
                                <div>
                                    <label for="deadline" class="form-label">KEY: </label>
                                    <input type="text" name="" id="" class="form-control" value="" readonly>
                                </div>
                                <div>
                                    <label for="application_deadline">SETTINGS: </label>
                                    <input type="date" name="application_deadline" id="application_deadline" value="<?php echo $application; ?>">
                                </div>
                                <div>
                                    <button type="submit" name="app">ADD EVENT</button>
                                </div>
                            </form>
                        </div>

                        <div class="card-footer">

                        </div>
                    </div>
                </div>

                <div class="col-4 col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h4>FILE CONTROL SETTINGS</h4>
                        </div>

                        <div class="card-body">
                            <form action="" method="POST">
                                <div>
                                    <label for="fileD">KEY: </label>
                                    <input type="text" name="fileD" id="file" value="file_deadline">
                                </div>
                                <div>
                                    <label for="file_deadline">SETTINGS: </label>
                                    <input type="text" name="file_deadline" value="<?php echo $file; ?>">
                                </div>

                                <div>
                                    <button type="submit" name="file">ADD EVENT</button>
                                </div>
                            </form>
                        </div>

                        <div class="card-footer">

                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>