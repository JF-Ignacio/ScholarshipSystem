<?php

require_once "../config/session.php";
require_once "../config/database.php";


$errorAlert = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $email = $_POST['email'] ?? "";
    $password = $_POST['password'] ?? "";

    if (!empty($email) && !empty($password)) {

        // prepare data 
        $stmt = mysqli_prepare($conn, "SELECT id, fullname, email, password, role FROM users WHERE email = ?");

        // bind data
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);

        // get the result from db
        $resultDB = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($resultDB) > 0) {
            $fetchDB = mysqli_fetch_assoc($resultDB);

            // PASSWORD HASHING 
            if (password_verify($password, $fetchDB['password'])) {

                $_SESSION['id'] = $fetchDB['id'];
                $_SESSION['fullname'] = $fetchDB['fullname'];
                $_SESSION['email'] = $fetchDB['email'];
                $_SESSION['role'] = $fetchDB['role'];

                if ($fetchDB['role'] === 'admin') {
                    header("Location: ../admin/dashboard.php");
                } else {
                    header("Location: ../student/dashboard.php");
                }
                exit();
            }
            else {
                $errorAlert = "Incorrect Password";
            }
        }

        else {
            $errorAlert = "No account found!";
        }
    }

    else {
        $errorAlert = "Fields are required to answer";
    }
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

<body class="login-page">
    <div class="container-fluid min-vh-100 d-flex justify-content-center align-items-center px-3">
        <div class="card shadow p-4 mx-3 w-100" style="max-width: 400px;">
            <div class="card-header d-flex align-items-center justify-content-center bg-transparent border-0 text-center">
                <img src="/TVAM_SCHOLARSHIP/assets/images/tvamlogo_web.png" class="card-image-top img-fluid rounded-circl w-25"alt="TVAM Logo">
                <h2 class="card-title">Login</h2>
            </div>

            <div class="card-body d-flex flex-column align-items-center">
                <form action="login.php" method="POST" class="d-flex flex-column gap-3 w-100">
                    <div class="form-group d-flex flex-column gap-2">
                        <label for="email">Email</label>
                        <input type="text" class="form-control" id="email" name="email" placeholder="Enter email" required>
                    </div>
                    <div class="form-group d-flex flex-column gap-2">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Enter password">
                    </div>
                    
                    <a href="/TVAM_SCHOLARSHIP/auth/forgot-password.php" class="text-center">Forgot Password?</a>

                    <div class="form-group d-flex flex-column gap-3">
                        <button type="submit" class="btn btn-success" name="login">Login</button>
                        <div class="form-text">Don't have an account?<a href="register.php" class="text-decoration-none align-items-center fst-italic fw-bold"> Register First</a></div>
                        <a href="../index.php" class="text-center text-decoration-none text-uppercase text-light fst-italic btn btn-secondary">Back to Home page</a>
                    </div>
                </form>
            </div>

            <div class="card-footer d-flex flex-column align-items-center bg-transparent border-0">
                <?php 
                    if (!empty($errorAlert)) {
                ?>
                <div class="alert alert-danger w-100 mb-1 text-center shadow-sm" role="alert">
                    <strong>ALERT:</strong>
                    <?php echo $errorAlert; ?>
                </div>

                <?php } ?>
            </div>
        </div>
    </div>
</body>
</html>
