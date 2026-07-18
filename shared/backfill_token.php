<?php 
require_once "../../config/database.php";

$script = $conn->query("SELECT COUNT(*) FROM documents WHERE download_token is NULL");

if($script->num_rows > 0) {
    echo "SUCCESS. NO NULL LEFT";
}

else {
    die("THERE ARE NULLS");
}

?>