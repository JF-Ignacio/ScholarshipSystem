<?php 

include "../config/session.php";
include "../config/database.php";

$id = isset($_GET['id']) ?? 0;

$message = "";
$badge = "";



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
<body>
    <div class="min-vh-100">
        <div class="container-fluid">
            <div class="d-flex align-items-center justify-content-center">
                <div class="card">
                    <div class="card-header">
                        <h4>RESET PASSWORD</h4>
                    </div>
                    <div class="card-body">
                        <form action="" method="GET">
                            <div>
                                <label for="email">EMAIL: </label>
                                <input type="text" name="email" id="email">
                            </div>
                        </form>
                    </div>
                    <div class="card-footer">
                        <button type="submit" name="check_token" id="btn" onclick="getToken()">RESET PASSWORD</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>