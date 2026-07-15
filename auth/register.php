<?php 

require_once "../config/session.php";
require_once "../config/database.php";

$message = "";
$alert = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $fullname = trim($_POST['fullname']) ?? "";
    $email = trim($_POST['email']) ?? "";
    $password = trim($_POST['password']) ?? "";
    $checkPass = trim($_POST['checkpass']) ?? "";

    if(!empty($fullname) &&
    !empty($email) &&
    !empty($password) &&
    !empty($checkPass)) {

        $checkStmt = mysqli_prepare($conn, "SELECT * FROM users WHERE email = ?");

        mysqli_stmt_bind_param($checkStmt, "s", $email);
        mysqli_stmt_execute($checkStmt);

        $resultData = mysqli_stmt_get_result($checkStmt);

        if(mysqli_num_rows($resultData) > 0) {
            $message = "DATA ALREADY EXIST!";
            $alert = "alert-danger";
        }

        else {
            $passwordHashed = password_hash($password, PASSWORD_DEFAULT);

            // NO ROLE VAR - I USED ENUM DEFAULT STUDENT
            $stmt = mysqli_prepare($conn, "INSERT INTO users (fullname, email, password)
            VALUES (?, ?, ?)");

            mysqli_stmt_bind_param($stmt, "sss", $fullname, $email, $passwordHashed);

            if(mysqli_stmt_execute($stmt)) {
                header("Location: ../auth/login.php");
                exit();
            }
            else {
                $message = "Registration failed";
                $alert = "alert-danger";
            }

        }


    }

    elseif ($password !== $checkPass) {
        $message = "Password do not match";
        $alert = "alert-warning";
    }

    else {
        $message = "Fields are empty.";
        $alert = "alert-danger";
    }
}

if(isset($_POST['exit'])) {
    header("Location: ../index.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TVAM Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/TVAM_SCHOLARSHIP/assets/js/bg.js"></script>
    <link rel="stylesheet" href="/TVAM_SCHOLARSHIP/assets/css/admin.css">
    <link rel="stylesheet" href="/TVAM_SCHOLARSHIP/assets/css/style.css">
    <link rel="icon" href="../assets/images/tvamlogo_web.png">
</head>

<main class="registration-class container-fluid min-vh-100 p-3 p-sm-4 d-flex justify-content-center align-items-center">
    <div class="reg-div registration-card border p-4 p-sm-5 rounded-3 shadow-lg">
        
        <form action="register.php" method="POST" class="w-100">
            <div class=" d-flex align-items-center justify-content-center gap-2 mb-4">
                <img src="../assets/images/TVAMLOGO.png" alt="" class="img-fluid rounded-circle bg-transparent" style="height: 100px;">
                <h2 class="text-uppercase lead">REGISTRATION</h2>
            </div>
            <div>
                <label for="fullname" class="form-label text-uppercase">Fullname:</label>
                <input type="text" class="form-control" name="fullname" placeholder="ex. Pedro C. Gil">
            </div>

            <div class="mt-3">
                <label for="email" class="form-label text-uppercase">Email:</label>
                <input type="text" class="form-control" name="email" placeholder="ex. pedro.tvam@gmail.com">
            </div>

            <div class="mt-3">
                <label for="password" class="form-label text-uppercase">Password:</label>
                <input type="password" class="form-control" name="password" placeholder="Password must containt symbols and numbers.">
            </div>

            <div class="mt-3">
                <label for="checkpass" class="form-label text-uppercase">Check your Password:</label>
                <input type="text" class="form-control" name="checkpass" placeholder="Confirm your password">
            </div>

            <div class="mt-4 mb-3">
                <button type="submit" name="register" class="btn btn-success">REGISTER</button>
                <button type="submit" name="exit" class="btn btn-secondary">EXIT</button>
            </div>
            <div class="alert <?php echo $alert?>" role="alert">
                <span> <?php echo $message; ?></span>
            </div>
        </form>

    </div>
</main>