<?php 

include "../config/session.php";

$_SESSION = [];

session_destroy();

header("Location: ../auth/login.php");
exit();

?>