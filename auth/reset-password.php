<?php 
include '../config/database.php';
include '../config/session.php';



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
            <div class="card">
                <div class="card-header">
                    <h4>VALIDATION</h4>
                </div>
                <div class="card-body">
                    <form action="" method="POST">
                        <div>
                            <label for="token">EMAIL TOKEN: </label>
                            <input type="text" id="token" name="token">
                        </div>
                    </form>
                </div>
                <div class="card-footer">
                    <button type="submit" name="ValidateToken" id="btn-token">VERIFY</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>