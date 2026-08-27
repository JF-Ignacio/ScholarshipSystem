<?php 
include '../config/database.php';
include '../config/session.php';

$message = "";
$badge = "";

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

                $message = "
                <div>
                    <a href='{$redirect}'>
                    Login Again
                    </a>
                </div>
                ";
                $badge = "success";
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="/TVAM_SCHOLARSHIP/assets/css/admin.css">
    <link rel="stylesheet" href="/TVAM_SCHOLARSHIP/assets/css/style.css">
    <link rel="icon" href="../assets/images/tvamlogo_web.png">
</head>
<body>
    <div class="min-vh-100">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h4>VALIDATION</h4>
                </div>
                <div class="card-body">
                    <?php if($isValidTime): ?>
                    <form action="" method="POST" class="form-control border-0">
                        <div>
                            <label for="new_password" class="form-label">New Password: </label>
                            <input type="password" name="new" id="newpass">
                            <button type="button" id="new_password_hide" onclick="hide('newpass', 'new_password_hide')">Show</button>
                        </div>

                        <div>
                            <label for="new_password">Confirm Password: </label>
                            <input type="password" name="confirm" id="confirmpass">
                            <button type="button"  id="confirm_password_hide" onclick="hide('confirmpass', 'confirm_password_hide')" class="text-uppercase">SHOW</button>
                        </div>
                        <button type="submit" name="ValidateToken" id="btn-token">VERIFY</button>
                    </form>

                    <?php else: ?>
                        <div>
                            <a href="forgot-password.php">Request another reset link.</a>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="card-footer bg-transparent">
                    <?php if(!empty($message)): ?>
                    <div class="alert bg-<?php echo $badge; ?>" role="alert">
                        <small class="text-uppercase text-white fw-bold"><?php echo htmlspecialchars($message); ?></small>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
        <script src="/TVAM_SCHOLARSHIP/assets/js/validation.js"></script>
        <script src="/TVAM_SCHOLARSHIP/assets/js/bg.js"></script>
</body>
</html>