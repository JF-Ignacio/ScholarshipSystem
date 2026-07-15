<?php

require_once __DIR__ . "/session.php";
require_once __DIR__ . "/database.php";

if (!isset($_SESSION['id'])) {
    header("Location: /TVAM_SCHOLARSHIP/auth/login.php");
    exit();
}

if ($_SESSION['role'] !== 'student') {
    die("Access Denied.");
}
