<?php 

include "../config/database.php";
include "../includes/functions.php";
require_once "../config/student-auth.php";

$id = $_SESSION['id'] ?? 0;
$fullname = $_SESSION['fullname'] ?? " ";
$message = "";
$badge = "";

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file_picture'])) {

    $result = ProfileUpload($conn, $id, $_FILES['file_picture']);

    $_SESSION['flash_message'] = $result['message'];
    $_SESSION['flash_badge'] = $result['success'] ? 'success' : 'danger';

    header("Location: profile.php");
    exit();
}

$sql = "SELECT profile_image FROM user_profiles WHERE profile_id = ?";
$stmt_upload = $conn->prepare($sql);
$stmt_upload->bind_param("i", $id);
$stmt_upload->execute();

$upload_result = $stmt_upload->get_result();
$fetch_profile = $upload_result->fetch_assoc();

$profileImage = $fetch_profile['profile_image'] ?? null;


$avatarSrc = $profileImage  
    ? "/TVAM_SCHOLARSHIP/assets/uploads/profile-pictures/" . htmlspecialchars($profileImage)
    : "/TVAM_SCHOLARSHIP/assets/images/TVAMLOGO.png";

$message = $_SESSION['flash_message'] ?? "";
$badge = $_SESSION['flash_badge'] ?? "";
unset($_SESSION['flash_message'], $_SESSION['flash_badge']);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile</title>
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/TVAM_SCHOLARSHIP/assets/css/style.css">
    <link rel="stylesheet" href="/TVAM_SCHOLARSHIP/assets/css/student.css">
    <link rel="icon" href="/TVAM_SCHOLARSHIP/assets/images/tvamlogo_web.png">
</head>

<body class="student-layout">
    <?php include "../includes/sidebar-student.php";?>

    <div class="profile-container container-fluid d-flex flex-column p-4">
        <section class="profile-header-card">
            <div class="row mb-0 g-2">
                <div class="col-4 col-lg-4">
                    <div class="profile-card card gap-3 h-100">
                        <?php if($message) : ?>
                            <div class="alert alert-<?php echo htmlspecialchars($badge); ?>">
                                <span>
                                    <?php echo htmlspecialchars($message); ?>
                                </span>
                            </div>
                        <?php endif; ?>

                        <form action="" method="POST" enctype="multipart/form-data" id="profile_form">
                            <div class="avatar-wrapper">
                                <label for="filePicture" class="avatar-label form-label" >
                                    <img 
                                        src="<?php echo $avatarSrc; ?>" 
                                        alt="Profile Picture"
                                        class="avatar-img img-fluid"
                                        id="avatarPreview"
                                    >
                                        
                                    <div class="avatar-overlay">
                                        <i class="bi bi-camera-fill"></i>
                                    </div>
                                </label>
                                <input type="file" name="file_picture" id="filePicture" accept="image/png, image/jpeg, image/webp" hidden>
                            </div>
                        </form>

                        <div class="profile-name">
                            <h4 class="fw-bold"><?php echo htmlspecialchars($fullname); ?></h4>
                            <span>Personalized your profile wall</span>
                        </div>
                    </div>
                </div>

                <div class="col-8 col-lg-8">
                    <div class="profile-college card p-4 h-100">
                        <h4 class="text-uppercase fw-bold">College of Industrial Education</h4>
                        <span class="college-section">COMPRO</span>
                        <span>Batch 2024 - 2028</span>
                    </div>
                </div>
            </div>
        </section>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/TVAM_SCHOLARSHIP/assets/js/student.js"></script>
</body>
</html>
