<?php 

include "../config/database.php";
require_once "../config/student-auth.php";

$application_ID = (int) ($_SESSION['id'] ?? 0);

$cardColor = "";
$textColor = "text-white";
$badge = "";
$message = "";
$border = "";
$symbol = "";
$applyText = "STATUS CARD";
$symbol = "bg-dark";

// Initialize template output string values safely to prevent layout system crashes
$fullname = "";
$studentID = "N/A";
$email = "";
$scholarship = "No Application Filed";
$date = "N/A";

$sql_status = "SELECT a.*,
            u.fullname,
            u.email,
            s.student_id AS official_student_id
            FROM applications a 
            INNER JOIN users u ON a.user_id = u.id
            LEFT JOIN scholars s ON a.user_id = s.id
            WHERE a.user_id = ? 
            LIMIT 1";

$sql_stmt = $conn->prepare($sql_status);
$sql_stmt->bind_param("i", $application_ID);
$sql_stmt->execute();

$result = $sql_stmt->get_result()->fetch_assoc();

if ($result) {
    $fullname = $result['fullname'] ?? "";
    $email = $result['email'] ?? "";
    $scholarship = $result['scholarship_type'] ?? "";
    $date = $result['created_at'] ?? "";
    
    // If our fixed join works, this will now hold your formatted "TVAM-000008" string string value!
    $studentID = !empty($result['official_student_id']) ? $result['official_student_id'] : ("TVAM-" . str_pad($application_ID, 5, "0", STR_PAD_LEFT));
    $message = "APPLICATION INFORMATION CARD";
}
else {
    $message = "APPLY SCHOLARSHIP FIRST TO CREATE STATUS DATA CARD";
}

$status = strtolower($result['status'] ?? '');

if($status === 'active') {
    $cardColor = "bg-success";
    $badge = "bg-primary";
    $border = "border-light";

} elseif($status === 'pending') {
    $cardColor = "bg-warning";
    $textColor = "text-dark";
    $badge = "bg-dark";
    $border = "border-light";

} elseif($status === 'inactive') {
    $cardColor = "bg-danger";
    $badge = "bg-danger";
    $border = "border-white";
}
else {
    $cardColor = "bg-light";
    $textColor = "text-dark";
    $border = "border-dark";
    $applyText = "APPLY FIRST";
}

$sql_stmt->close();

?>

<?php include "../includes/header.php"; ?>

<head>
    <title>Status Card</title>
</head>

<main class="container-fluid p-3 px-5 py-5 min-vh-100">
    <div class="d-flex flex-column">
        <div class="card border-0 p-3 shadow-lg <?php echo htmlspecialchars($cardColor); ?> <?php echo htmlspecialchars($textColor); ?>">
            <div class="card-body w-100">
                <span class="fs-4 mt-3 mb-4 badge <?php echo $symbol; ?>"><strong><?php echo htmlspecialchars($applyText); ?></strong></span>
                <h3 class="text-uppercase fw-bold mb-3"><?php echo htmlspecialchars($message); ?></h3>
                <hr class="<?php echo $border; ?> rounded-5" style="height: 10px; background-color: white;">
                
                <div class="row g-3 fs-5">
                    <div class="col-md-6"><strong>Name:</strong> <?php echo htmlspecialchars($fullname); ?></div>
                    <div class="col-md-6"><strong>Student ID:</strong> <?php echo htmlspecialchars($studentID); ?></div>
                    <div class="col-md-6"><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></div>
                    <div class="col-md-6"><strong>Scholarship Type:</strong> <?php echo htmlspecialchars(str_replace('_', ' ', $scholarship)); ?></div>
                    <div class="col-md-6"><strong>Evaluated at:</strong> <?php echo htmlspecialchars($date); ?></div>
                    <div class="col-md-6 text-uppercase"><strong>Current Status:</strong> <strong><span class=" p-2 fs-4  rounded badge <?php echo $badge; ?>"> <?php echo htmlspecialchars($status); ?></span></strong></div>
                </div>
            </div>
        </div>

        <div class="mt-5">
                <h4 class="text-muted fs-4 fst-italic fw-bold">****NOTHING FOLLOWS****</h4>
        </div>

    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>