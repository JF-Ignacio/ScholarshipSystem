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

$feedBack = [
    'scholar' => ['message' => '', 'badge' => ''],
    'application' => ['message' => '', 'badge' => ''],
    'file' => ['message' => '', 'badge' => '']
];

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    if(isset($_POST['scholar'])) {
        $status = $_POST['scholarship_status'] ?? '';

        if(!in_array($status, $scholarshipStatus, true)) {
            $feedBack['scholar'] = [
                'message' => 'Invalid Status Selected',
                'badge' => 'danger'
            ];
        }
        else {
            $update = updateEventSettings($conn, 'scholarship_status', $status, 'Student can now Submit Scholarship');
            $feedBack['scholar'] = [
                'message' => $update ? 'Event added successfully' : 'Failed to add event',
                'badge' => $update ? 'success' : 'danger'
            ];
        }
    } 

    if(isset($_POST['app'])) {
        $application_status = trim($_POST['application_deadline']) ?? '';

        if(empty($application_status)) {
            $feedBack['application'] = [
                'message' => 'Data is empty.',
                'badge' => 'danger'
            ];
        }
        else {
            $update_app = updateEventSettings($conn, 'application_deadline', $application_status, 'Student can now submit Application');
            $feedBack['application'] = [
                'message' => $update_app ? 'Event Added Successfully' : 'Failed to updated',
                'badge' => $update_app ? 'success' : 'danger'
            ];
        }
    }

    if(isset($_POST['file'])) {
        $file_status = trim($_POST['file_deadline']) ?? '';

        if(empty($file_status)) {
            $feedBack['file'] = [
                'message' => 'Invalid Status Selected',
                'badge' => 'danger'
            ];
        }

        else {
            $file_update = updateEventSettings($conn, 'file_deadline', $file_status, 'Student now can process file submission');
            $feedBack['file'] = [
                'message' => $file_update ? 'Event Successfully added' : 'Failed to add.',
                'badge' => $file_update ? 'success' : 'danger'
            ];
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

        <main class="container-fluid flex-grow-1 p-3 p-md-4 px-3 px-md-5">
            <div class="event-head text-uppercase p-3 p-md-4">
                <h4 class="fw-bold">Configuration Settings</h4>
                <div class="support-text d-flex flex-column flex-sm-row flex-wrap gap-2 gap-sm-4 mt-3 text-uppercase small text-muted">
                    <p class="mb-0">Manage scholarship event</p>
                    <p class="mb-0">Application Deadline</p>
                    <p class="mb-0">Document Submission</p>
                </div>
            </div>

            <div class="row g-3 g-md-4 p-2 p-md-3">

                <!-- SCHOLARSHIP EVENT CARD -->
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-transparent text-center p-3">
                            <h4>SCHOLARSHIP EVENT</h4>
                        </div>
                        <div class="card-body d-flex flex-column p-3 p-md-4 align-items-stretch">
                            <form action="" method="POST" class="d-flex flex-column gap-3">
                                <div>
                                    <label for="school_name" class="form-label">KEY:</label>
                                    <input type="text" name="school_name" id="school_name" class="form-control" value="<?php echo htmlspecialchars($schoolName); ?>" readonly>
                                </div>
                                <div>
                                    <label for="scholarship_status" class="form-label">SETTINGS:</label>
                                    <select name="scholarship_status" id="scholarship_status" class="form-select">
                                        <option value="open" <?php echo ($schoolName === 'open') ? 'selected' : ''?>>OPEN</option>
                                        <option value="closed" <?php echo ($schoolName === 'closed') ? 'selected' : ''?>>CLOSE</option>
                                        <option value="upcoming" <?php echo ($schoolName === 'upcoming') ? 'selected' : ''?>>UPCOMING</option>
                                    </select>
                                </div>
                                <button type="submit" name="scholar" class="btn btn-primary w-100">EDIT EVENT</button>
                            </form>
                        </div>
                        <div class="card-footer">
                            <?php if(!empty($feedBack['scholar']['message'])) : ?>
                            <span class="badge bg-<?php echo $feedBack['scholar']['badge']; ?>">
                                <?php echo htmlspecialchars($feedBack['scholar']['message']); ?>
                            </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- APPLICATION DEADLINE CARD -->
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-transparent text-center p-3">
                            <h4>APPLICATION DEADLINE</h4>
                        </div>
                        <div class="card-body d-flex flex-column p-3 p-md-4">
                            <form action="" method="POST" class="d-flex flex-column gap-3">
                                <div>
                                    <label for="deadline_key" class="form-label">KEY:</label>
                                    <input type="text" id="deadline_key" class="form-control" value="<?php echo htmlspecialchars($application); ?>" readonly>
                                </div>
                                <div>
                                    <label for="application_deadline" class="form-label">SETTINGS:</label>
                                    <input type="date" name="application_deadline" id="application_deadline" class="form-control">
                                </div>
                                <button type="submit" name="app" class="btn btn-primary w-100">ADD EVENT</button>
                            </form>
                        </div>
                        <div class="card-footer">
                            <?php if(!empty($feedBack['application']['message'])) : ?>
                            <span class="badge bg-<?php echo $feedBack['application']['badge']; ?>">
                                <?php echo htmlspecialchars($feedBack['application']['message']); ?>
                            </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- FILE CONTROL SETTINGS CARD -->
                <div class="col-12 col-md-12 col-lg-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-transparent text-center p-3">
                            <h4>FILE CONTROL SETTINGS</h4>
                        </div>
                        <div class="card-body d-flex flex-column p-3 p-md-4">
                            <form action="" method="POST" class="d-flex flex-column gap-3">
                                <div>
                                    <label for="file" class="form-label">KEY:</label>
                                    <input type="text" name="fileD" id="file" class="form-control" value="<?php echo htmlspecialchars($file); ?>" readonly>
                                </div>
                                <div>
                                    <label for="file_deadline" class="form-label">SETTINGS:</label>
                                    <input type="date" name="file_deadline" id="file_deadline" class="form-control" value="<?php echo htmlspecialchars($file); ?>">
                                </div>
                                <button type="submit" name="file" class="btn btn-primary w-100">ADD EVENT</button>
                            </form>
                        </div>
                        <div class="card-footer">
                            <?php if(!empty($feedBack['file']['message'])) : ?>
                            <span class="badge bg-<?php echo $feedBack['file']['badge']; ?>">
                                <?php echo htmlspecialchars($feedBack['file']['message']); ?>
                            </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>