<?php
include __DIR__ . "/session.php";

if (!isset($_SESSION['id'])) {
    header("Location: /TVAM_SCHOLARSHIP/auth/login.php");
    exit();
}
