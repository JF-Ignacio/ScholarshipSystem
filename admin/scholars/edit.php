<?php 

require_once "../../config/admin-auth.php";
require_once "../../includes/functions.php";

$id = $_GET['id'] ?? 0;
$message = "";
$alertClass = "";

$stmt = mysqli_prepare($conn, "SELECT * FROM scholars where id = ?");

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $scholar = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if (!$scholar) {
        die ("No scholar found!");
    }
}

if(isset($_POST['update'])) {
    $studentID = trim($_POST['student_id']);
    $fullname = trim($_POST['fullname']);
    $course = trim($_POST['course']);
    $yearLevel = trim($_POST['year_level']);
    $status = trim($_POST['status']);
    $scholarshipType = trim($_POST['scholarship_type']);
    $email = trim($_POST['email']);

    if (
        empty($studentID) ||
        empty($fullname) ||
        empty($course) ||
        empty($yearLevel) || 
        empty($status) ||
        empty($scholarshipType) ||
        empty($email)
    ) {
        $alertClass = "alert-warning";
        $message = "ALL FIELDS ARE REQUIRED TO ANSWER. NO MISSING FIELDS!";
    }
    else {
        $extraction_id = str_replace("TVAM-", "", $scholar['student_id']);
        $user_id = (int)$extraction_id;

        mysqli_begin_transaction($conn);

        try {
            // UPDATE SCHOLARS
            $update_scholar = "UPDATE scholars SET 
            student_id = ?, fullname = ?, course = ?, year_level = ?, scholarship_type = ?,
            status = ?, email = ? 
            WHERE id = ?";

            $updates_stmt_scholar = $conn->prepare($update_scholar);
            if(!$updates_stmt_scholar) throw new Exception("Failed to load scholar database. Check system.");

            $updates_stmt_scholar->bind_param("sssssssi",
            $studentID, $fullname, $course, $yearLevel, $scholarshipType, $status, $email, $id);
            if(!$updates_stmt_scholar->execute()) throw new Exception("Failed to execute update. Check admin.");
            $updates_stmt_scholar->close();

            // UPDATE APPLICATIONS
            $update_applications = "UPDATE applications 
            SET scholarship_type = ?, course = ?, year_level = ?, status = ?
            WHERE user_id = ?";

            $application_stmt = $conn->prepare($update_applications);
            if(!$application_stmt) throw new Exception ("Failed to load application updates. Check Admin.");
            
            $application_stmt->bind_param("ssssi", $scholarshipType, $course, $yearLevel, $status, $user_id);
            if(!$application_stmt->execute()) throw new Exception("Fauled to updated applications. Check admin.");
            $application_stmt->close();

            // UPDATE USERS 
            $sql_user = "UPDATE users SET fullname = ?, email = ? WHERE id = ?";
            $user_stmt = $conn->prepare($sql_user);
            if(!$user_stmt) throw new Exception("Failed to load users. Check admin.");

            $user_stmt->bind_param("ssi", $fullname, $email, $user_id);
            if(!$user_stmt->execute()) throw new Exception("Failed to update in users. Check admin.");
            $user_stmt->close();

            // AUDIT-TRAIL SYSTEM
            $admin_id = $_SESSION['id'] ?? 1;
            $admin_name = $_SESSION['fullname'] ?? "Admin";
            $logActivity = "UPDATED by {$admin_name} | {$fullname} - ({$studentID})";
            activityLogs($conn, $admin_id, $logActivity);

            mysqli_commit($conn);

            $alertClass = "alert-success";
            $message = "UPDATED SUCCESSFULLY!";

            // UPDATE ON LOCAL STATE - FOR REFRESH
            $scholar = [
                'student_id' => $studentID, 
                'fullname' => $fullname,
                'scholarship_type' => $scholarshipType,
                'course' => $course,
                'year_level' => $yearLevel,
                'status' => $status,
                'email' => $email
            ];



        }

        catch(Exception $e) {
            mysqli_rollback($conn);
            $alertClass = "alert-danger";
            $message = "Sync Error" . $e->getMessage();
        }
    }


}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - TVAM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/TVAM_SCHOLARSHIP/assets/css/style.css">
    <link rel="stylesheet" href="/TVAM_SCHOLARSHIP/assets/css/admin.css">
    <link rel="icon" href="../../assets/images/tvamlogo_web.png">
</head>

<main class="container-fluid min-vh-100 d-flex align-items-center justify-content-center p-5 mt-5">
    <form action="edit.php?id=<?php echo $id; ?>" method="POST" class="d-flex flex-column gap-3 border w-100 p-4 shadow-lg" style="max-width: 500px;">
            <div class="d-flex flex-column flex-sm-row align-items-center justify-content-center text-align-center gap-2">
                <img src="../../assets/images/tvamlogo_web.png" alt="TVAM LOGO" class="img img-fluid"style="width: 100px;">
                <h2>UPDATE ISKOLAR</h2>
            </div>

            <?php if (!empty($message)) { ?>
                <div class="alert <?php echo $alertClass; ?>" role="alert">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php } ?>

            <div class="mb-2">
                <label for="student_id" class="form-label fw-bold">STUDENT ID: </label>
                <input type="text" name="student_id" placeholder="TVAM-XXXX" class="form-control" value="<?php echo htmlspecialchars($scholar['student_id']); ?>">
            </div>

            <div class="mb-2">
                <label for="fullname" class="form-label fw-bold">FULL NAME: </label>
                <input type="text" name="fullname" placeholder="TVAM-XXXX" class="form-control" value="<?php echo htmlspecialchars($scholar['fullname']); ?>">
            </div>

            <div class="row mb-2">
                <div class="col">
                    <label for="course" class="form-label fw-bold">COURSE: </label>
                    <input type="text" name="course" placeholder="Course" class="form-control" value="<?php echo htmlspecialchars($scholar['course']); ?>">
                </div>

                <div class="col">
                    <label for="year_level" class="form-label fw-bold">Year level: </label>
                    <select name="year_level" id="year_level" class="form-select">
                        <option value="<?php echo htmlspecialchars($scholar['year_level']);?>"> <?php echo htmlspecialchars($scholar['year_level']); ?></option>
                        <option value="1st year">First year</option>
                        <option value="2nd year">Second year</option>
                        <option value="3rd year">Third year</option>
                        <option value="4th year">Fourth year</option>
                    </select>
                </div>
            </div>

            <div class="mb-2">
                <label for="status" class="form-label fw-bold">STATUS: </label>
                <select name="status" id="status" class="form-select">
                    <option value="<?php echo htmlspecialchars($scholar['status']);?>"> <?php echo htmlspecialchars($scholar['status']); ?></option>
                    <option value="Active">Active</option>
                    <option value="Inactive">Inactive</option>
                    <option value="Pending">Pending</option>
                </select>
            </div>

            <div class="mb-2">
                <label for="scholarship_type" class="form-label fw-bold">SCHOLARSHIP: </label>
                <select name="scholarship_type" id="scholarship" class="form-select">
                    <option value="<?php echo htmlspecialchars($scholar['scholarship_type']);?>"> <?php echo htmlspecialchars($scholar['scholarship_type']); ?></option>
                    <option value="TVAM_IskolarNgBayan">Iskolar Ng Bayan</option>
                    <option value="DOST-TVAM_Iskolar">DOST - TVAM</option>
                    <option value="DICT-TVAM_Iskolar">DICT - TVAM</option>
                </select>
            </div>

            <div class="mb-2">
                <label for="email" class="form-label fw-bold">Email: </label>
                <input type="text" name="email" placeholder="pedro.tvam@gmail.com" class="form-control" value="<?php echo htmlspecialchars($scholar['email']); ?>">
            </div>

            <div class="mb-2">
                <button type="submit" name="update" class="btn btn-success">UPDATE DATA</button>
                <a href="index.php" class="btn btn-secondary">EXIT</a>
            </div>
    </form>
</main>
