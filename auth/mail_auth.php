<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../PHPMailer/src/Exception.php';
require_once __DIR__ . '/../PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../PHPMailer/src/SMTP.php';


function sendResetEmail($recipientEmail, $recipientName, $resetLink)
{
    $mail = new PHPMailer(true);

    try {

        // SMTP configuration
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'YOUR_GMAIL@gmail.com';
        $mail->Password   = 'YOUR_APP_PASSWORD';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Sender
        $mail->setFrom(
            'YOUR_GMAIL@gmail.com',
            'TVAM Scholarship System'
        );

        // Recipient
        $mail->addAddress($recipientEmail, $recipientName);

        // Email content
        $mail->isHTML(true);
        $mail->Subject = 'TVAM Scholarship - Password Reset';

        $mail->Body = "
            <h2>Password Reset</h2>

            <p>Hello {$recipientName},</p>

            <p>
                We received a request to reset your
                TVAM Scholarship System password.
            </p>

            <p>
                Click the button below to reset your password:
            </p>

            <p>
                <a href='{$resetLink}'>
                    Reset Password
                </a>
            </p>

            <p>
                This link will expire in 30 minutes.
            </p>

            <p>
                If you did not request this, you can safely ignore this email.
            </p>
        ";

        $mail->AltBody =
            "Reset your password using this link: {$resetLink}";

        return $mail->send();

    } catch (Exception $e) {

        error_log(
            "Password reset email failed: " . $mail->ErrorInfo
        );

        return false;
    }
}