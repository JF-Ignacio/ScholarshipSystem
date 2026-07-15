
<?php
/*


require_once "../../config/admin-auth.php";

$message = "";

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    if (isset($_POST['add'])) {
        $studentID = trim($_POST['student_id'] ?? "");
        $fullName = trim($_POST['fullname'] ?? "");
        $course = trim($_POST['course'] ?? "");
        $yearLevel = trim($_POST['year_level'] ?? "");
        $status = $_POST['status'] ?? "Pending";
        $scholarType = trim($_POST['scholarship_type'] ?? "");
        $email = trim($_POST['email'] ?? "");

        if (!empty($studentID) && !empty($fullName)) {
            $checkStmt = mysqli_prepare($conn, "SELECT student_id FROM scholars WHERE student_id = ?");
            mysqli_stmt_bind_param($checkStmt, "s", $studentID);
            mysqli_stmt_execute($checkStmt);
            mysqli_stmt_store_result($checkStmt);

            if (mysqli_stmt_num_rows($checkStmt) > 0) {
                $message = "DATA ALREADY EXIST. Make your data unique.";
                mysqli_stmt_close($checkStmt);
            }
            
            else {
                mysqli_stmt_close($checkStmt);

                $stmt = mysqli_prepare($conn, "INSERT INTO scholars (student_id, fullname, course, year_level, status, scholarship_type, email) 
                VALUES (?, ?, ?, ?, ?, ?, ?)");

                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, "sssssss", $studentID, $fullName, $course, $yearLevel, $status, $scholarType, $email);

                    if (mysqli_stmt_execute($stmt)) {
                        mysqli_stmt_close($stmt);
                        header("Location: index.php");
                        exit();
                    }

                    else {
                        $message = "Failed to add scholar. Try Again.";
                    }
                }

                else {
                    $message = "Failed to add scholar. Try again.";
                }
            }
        }
        else {
            $message = "All data are requird to fill in.";
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

<main class="main-hero container-fluid p-5 mb-3 w-100" style="min-height: 100vh;">
    <div class="d-flex align-items-center justify-content-center mt-3 pt-4">
        <form action="add.php" method="POST"class="d-flex flex-column gap-3 border w-100 p-4 shadow-lg" style="max-width: 400px;">
            <div class="d-flex flex-column flex-sm-row align-items-center justify-content-center text-align-center gap-2">
                <img src="../../assets/images/tvamlogo_web.png" alt="TVAM LOGO" class="img-fluid"style="width: 100px;">
                <h2>ADD ISKOLAR</h2>
            </div>

            <div class="mb-2">
                <label for="student_id" class="form-label">STUDENT ID: </label>
                <input type="text" name="student_id" placeholder="XXXX" class="form-control">
            </div>

            <div class="mb-2">
                <label for="fullname" class="form-label">FULL NAME: </label>
                <input type="text" name="fullname" class="form-control" placeholder="eg. Pedro Gil">
            </div>

            <div class="row mb-2">
                <div class="col">
                    <label for="course">COURSE: </label>
                    <input type="text" name="course" placeholder="Course" class="form-control">
                </div>

                <div class="col">
                    <label for="year_level">Year level: </label>
                    <select name="year_level" id="year_level" class="form-select">
                        <option value="none">--CHOOSE YEAR--</option>
                        <option value="1st year">First year</option>
                        <option value="2nd year">Second year</option>
                        <option value="3rd year">Third year</option>
                        <option value="4th year">Fourth year</option>
                    </select>
                </div>
            </div>

            <div class="mb-2">
                <label for="status">STATUS: </label>
                <select name="status" id="status" class="form-select">
                    <option value="Active">Active</option>
                    <option value="Inactive">Inactive</option>
                    <option value="Pending">Pending</option>
                </select>
            </div>

            <div class="mb-2">
                <label for="scholarship_type">SCHOLARSHIP: </label>
                <select name="scholarship_type" id="scholarship" class="form-select">
                    <option value="TVAM_IskolarNgBayan">Iskolar Ng Bayan</option>
                    <option value="DOST-TVAM_Iskolar">DOST - TVAM</option>
                    <option value="DICT-TVAM_Iskolar">DICT - TVAM</option>
                </select>
            </div>

            <div class="mb-2">
                <label for="email" class="form-label">Email: </label>
                <input type="text" name="email" placeholder="pedro.tvam@gmail.com" class="form-control">
            </div>

            <div class="mb-2">
                <button type="submit" name="add" class="btn btn-success">ADD SCHOLAR</button>
                <a href="index.php" class="btn btn-secondary">EXIT</a>
            </div>

            <?php if(!empty($message)) { ?>
            <div class="alert alert-danger w-100 mb-1 text-center shadow-sm" role="alert">
                <strong>WARNING: </strong>
                <?php echo $message; ?>
            </div>

            <?php } ?>
        </form>
    </div>
</main>

*/