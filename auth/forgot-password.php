<?php 

include "../config/session.php";
include "../config/database.php";
include "mail_auth.php";

$message = "";
$badge = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email'] ?? '');

    if (empty($email)) {
        $message = "Please enter your valid email.";
        $badge = "bg-danger";
    } 
    else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "INVALID EMAIL INPUT";
        $badge = "bg-warning";
    } 
    else {
        // 1. Fetch user ID, fullname, and email
        $sql_email = 'SELECT id, fullname, email FROM users WHERE email = ?';
        $stmt_email = $conn->prepare($sql_email);

        if (!$stmt_email) die("DATABASE FAILED. CONTACT ADMIN");

        $stmt_email->bind_param("s", $email);
        $stmt_email->execute();
        $result = $stmt_email->get_result();

        if ($result->num_rows > 0) {
            $validateEmail = $result->fetch_assoc();
            $user_id = $validateEmail['id']; // GET THE ID FROM USERS (FOREIGN KEY)
            $name = $validateEmail['fullname'] ?? '';

            // 2. Generate token & expiration timestamp
            $token_details = bin2hex(random_bytes(32));
            $expire_at = date('Y-m-d H:i:s', strtotime('+15 minutes'));

            // 3. Clear existing tokens for this email
            $sanitize_sql = "DELETE FROM password_reset WHERE email = ?";
            $stmt_sanitize = $conn->prepare($sanitize_sql);
            $stmt_sanitize->bind_param("s", $email);
            $stmt_sanitize->execute();

            // 4. Insert user_id along with email, token, and expiration
            $insert_sql = 'INSERT INTO password_reset (user_id, email, token, expires_at) VALUES (?, ?, ?, ?)';
            $insert_stmt = $conn->prepare($insert_sql);
            $insert_stmt->bind_param("isss", $user_id, $email, $token_details, $expire_at);
            
            if ($insert_stmt->execute()) {
                $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
                $domain = $_SERVER['HTTP_HOST'];
                
                // Fixed URL link & variable references
                $resetLink = "{$protocol}://{$domain}/TVAM_SCHOLARSHIP/auth/reset-password.php?token={$token_details}&email=" . urlencode($email);

                $emailMessage = "
                <div>
                    <div style='margin: 20px 0;'>
                        <a href='{$resetLink}' style='background:#198754; color:#fff; padding:10px 20px; text-decoration:none; border-radius:5px;'>Reset Password</a>
                    </div>
                </div>";


                if (sendResetEmail($email, $name, $emailMessage)) {
                    $message = "Password reset link has been sent to your email.";
                    $badge = "bg-success";
                } else {
                    $message = "Failed to send the reset link. Please check SMTP settings.";
                    $badge = "bg-warning";
                }
            } else {
                $message = "DATABASE ERROR. Failed to process request.";
                $badge = "bg-danger";
            }
        } else {
            $message = "If that email exists in our system, a reset link will be sent.";
            $badge = "bg-warning";
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
    <script src="/TVAM_SCHOLARSHIP/assets/js/bg.js"></script>
    <link rel="stylesheet" href="/TVAM_SCHOLARSHIP/assets/css/admin.css">
    <link rel="stylesheet" href="/TVAM_SCHOLARSHIP/assets/css/style.css">
    <link rel="icon" href="../assets/images/tvamlogo_web.png">
</head>

<body class="bg-light">
    <div class="container-fluid d-flex align-items-center justify-content-center min-vh-100 px-3">
        <div class="card shadow-lg border-0 rounded-3" style="width: 100%; max-width: 420px;">

            <div class="card-header bg-dark text-white text-center py-4 rounded-top-3 d-flex flex-column">
                <h4 class="fw-bold mb-1">RESET PASSWORD</h4>
                <p class="mb-0 small text-white-50">Enter your email to receive a reset link</p>
            </div>

            <form action="" method="POST">
                <div class="card-body p-4">
                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold">EMAIL</label>
                        <input type="email" name="email" id="email" class="form-control form-control-lg" placeholder="you@example.com" required>
                        <?php if (!empty($message)) : ?>
                            <div class="alert p-0 mb-0 py-2 px-2 mt-2" role="alert">
                                <span class="fst-italic" style="color: red;"> <?php echo htmlspecialchars($message); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="card-footer bg-white border-0 p-4 pt-0">
                    <button type="submit" name="check_token" id="btn" class="btn btn-dark btn-lg w-100 fw-semibold">
                        RESET PASSWORD
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>