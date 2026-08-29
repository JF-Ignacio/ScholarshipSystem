<?php 
include '../config/database.php';
include '../config/session.php';

$message = "";
$badge = "";
$passwordResetSuccess = false;

$email = trim($_GET['email'] ?? '');
$token = trim($_GET['token'] ?? '');
$isValidTime = false;

if(empty($email) || empty($token) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $message = "Email doesn't recognized. Try Again or tr contacing admin.";
    $badge = "warning";
}

else {
    // CHECK IF THE DATA EMAIL ID EXIST 
    $sql_check = "SELECT id, expires_at FROM password_reset WHERE email = ? AND token = ?";
    $check_stmt = $conn->prepare($sql_check);

    if($check_stmt) {
        $check_stmt->bind_param("ss", $email, $token);
        $check_stmt->execute();
        $result = $check_stmt->get_result();

        if($result->num_rows === 1) {
            $fetchData = $result->fetch_assoc();
            $expiry = strtotime($fetchData['expires_at'] ?? 0);

            if(time() <= $expiry) {
                $isValidTime = true;
            }
            else {
                $message = "The password reset link is expired. Request another.";
                $badge = "primary";
            }
        }

        else {
            $message = "Try requesting another reset link.";
            $badge = "information";
        }
    }

    else {
        $message = "Database failed. Try contacing admin";
        $badge = "danger";
    }
}

if($_SERVER['REQUEST_METHOD'] === 'POST' && $isValidTime) {
    $password = $_POST['new'] ?? '';
    $confirmation_pass = $_POST['confirm'] ?? '';

    if(empty($password) || empty($confirmation_pass)) {
        $message = "Empty fields";
        $badge = "danger";
    }
    else {
        if($password !== $confirmation_pass) {
            $message = "Password not match. Check again.";
            $badge = "danger";
        }
        else if (strlen($password) < 10) {
           $message = "Characters should contain at least 10 characters"; 
           $badge = "warning";
        }
        else {
            $hashed = password_hash($password, PASSWORD_BCRYPT);
            $redirect = "login.php";
            $conn->begin_transaction(); 

            try {
                $sql_reset = "UPDATE users SET password = ? WHERE email = ?"; 
                $reset_stmt = $conn->prepare($sql_reset);
                $reset_stmt->bind_param("ss", $hashed, $email);
                $reset_stmt->execute();


                $delete_sql = "DELETE FROM password_reset WHERE email = ?";
                $delete_stmt = $conn->prepare($delete_sql);
                $delete_stmt->bind_param("s", $email);
                $delete_stmt->execute();
                
                $conn->commit();

                $message = "Successful reset. Try logging in again.";
                $badge = "success";
                $passwordResetSuccess = true;
            }
            catch(Exception $e) {
                $conn->rollback();
                $message = "Failed to reset password due to database conflict. Try to contact admin";
                $badge = "danger";
            }
        }
    }
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TVAM | Forgot Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/TVAM_SCHOLARSHIP/assets/css/style.css">
    <link rel="icon" href="../assets/images/tvamlogo_web.png">
</head>
<body class="reset-body">
    <div class="reset-hero min-vh-100">
        <div class="container-fluid">
            <div class="card shadow-sm">

                <div class="card-header border-0 d-flex align-items-center flex-column justify-content-center bg-transparent py-3 px-1 text-center">
                    <h4 class="fw-bold text-uppercase">RESET PASSWORD</h4>
                    <p class="text-muted small text-uppercase">Make sure you remember your password this time.</p>
                </div>
                
                <div class="card-body">
                    <?php if($isValidTime && !$passwordResetSuccess): ?>
                    <form action="" method="POST" class="form-group border-0 ">
                        <div class="input">
                            <label for="new_password" class="form-label fw-bold">New Password: </label>
                            <input type="password" name="new" id="newpass" class="form-control">
                        </div>

                        <div class="input">
                            <label for="new_password" class="form-label fw-bold">Confirm Password: </label>
                            <input type="password" name="confirm" id="confirmpass"  class="form-control">
                            <button type="button"  id="new_password_hide" onclick="hide('newpass', 'confirmpass', 'new_password_hide')" class="mt-2">Show password ꗃ</button>
                        </div>
                        <button type="submit" name="ValidateToken" id="btn-token" class="mt-4">CONFIRM</button>
                    </form>

                    <?php else: ?>
                        <div>
                            <a href="forgot-password.php">Request another reset link.</a>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="card-footer bg-transparent border-0">
                    <?php if(!empty($message)): ?>
                    <div class="alert bg-<?php echo $badge; ?>" role="alert">
                        <small class="text-uppercase text-white fw-bold"><?php echo htmlspecialchars($message); ?></small>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="reset-redirect">
            <div id="resetSuccessPopup" class="reset-success-popup" data-show="<?php echo $passwordResetSuccess ? 'true' : 'false'; ?>">
                <div class="reset-success-box">
                    <h5>Password reset successful</h5>
                    <p>Your password has been changed. You can now log in again using your new password.</p>
                    <a href="login.php" class="reset-login-link">Go to Login</a>
                </div>
            </div>
        </div>
    </div>
        <script src="/TVAM_SCHOLARSHIP/assets/js/validation.js"></script>
        <script src="/TVAM_SCHOLARSHIP/assets/js/bg.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
