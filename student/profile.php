<?php 

include "../config/database.php";
include "../includes/functions.php";
require_once "../config/student-auth.php";

$id = $_SESSION['id'] ?? 0;
$fullname = $_SESSION['fullname'] ?? " ";
$message = "";
$badge = "";
$hasDescription = false;

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file_picture'])) {

    $result = ProfileUpload($conn, $id, $_FILES['file_picture']);

    $_SESSION['flash_message'] = $result['message'];
    $_SESSION['flash_badge'] = $result['success'] ? 'success' : 'danger';

    header("Location: profile.php");
    exit();
}



if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['description'])) {
    $text = trim($_POST['description'] ?? '');

    if(empty($text)) {
        $_SESSION['flash_message'] = 'Avoid Empty Fields.';
        $_SESSION['flash_badge'] = 'danger';

        header("Location: profile.php");
        exit();
    }
    
    $textResult = CreateDescription($conn, $id, $text);
    $_SESSION['flash_message'] = $textResult['message'];
    $_SESSION['flash_badge'] = $textResult['success'] ? 'success' : 'danger';
}

// DELETE DESCRIPTION HANDLER
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_description'])) {
    $sql_delete = "UPDATE user_profiles SET description = NULL WHERE profile_id = ?";
    $stmt_del = $conn->prepare($sql_delete);

    if(!$stmt_del) {
        $_SESSION['flash_message'] = 'Diary entry removed successfully.';
        $_SESSION['flash_badge'] = 'warning';
    }

    $stmt_del->bind_param("i", $id);
    
    if ($stmt_del->execute()) {
        $_SESSION['flash_message'] = 'Diary entry removed successfully.';
        $_SESSION['flash_badge'] = 'warning';
    } else {
        $_SESSION['flash_message'] = 'Failed to remove entry.';
        $_SESSION['flash_badge'] = 'danger';
    }

    header("Location: profile.php");
    exit();
}

$sql = "SELECT profile_image, description FROM user_profiles WHERE profile_id = ?";
$stmt_upload = $conn->prepare($sql);
$stmt_upload->bind_param("i", $id);
$stmt_upload->execute();

$upload_result = $stmt_upload->get_result();
$fetch_profile = $upload_result->fetch_assoc();

$profileImage = $fetch_profile['profile_image'] ?? null;
$textSelect = $fetch_profile['description'] ?? null;


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
            <?php if($message) : ?>
                <div class="alert alert-<?php echo htmlspecialchars($badge); ?>">
                    <span>
                        <?php echo htmlspecialchars($message); ?>
                    </span>
                </div>
            <?php endif; ?>

            <div class="row mb-0 g-2">
                <div class="col-4 col-lg-4">
                    <div class="profile-card card gap-3 h-100">

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

        <section class="description-section w-100 mt-3 p-2">
            <div class="description-text card p-4 px-5">
                <h5 class="fw-bold text-uppercase fs-3">SCHOLARSHIP DIARY JOURNEY</h5>
                <div class="text-message p-4 shadow-sm">
                    <span><?php echo htmlspecialchars($textSelect); ?></span>
                </div>
                <div class="btn-text">
                    <?php if(!empty($textSelect)) :?>
                        <form action="" method="POST" onsubmit="return confirm('Do you want to delete this description?');">
                            <button type="submit" name="delete_description" class="btn btn-outline-warning rounded-3 border">CLEAR</button>
                        </form>
                    <?php endif; ?>
                    <button type="button" id="add_description" class="btn btn-success rounded-3" data-bs-toggle="modal" data-bs-target="#bioModal">ADD</button>
                </div>
            </div>

            <div class="modal fade" id="bioModal" tabindex="-1" aria-labelledby="bioModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content description-card border-0 rounded-4 shadow-lg overflow-hidden">

                        <form action="" method="POST" class="p-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="fw-bold text-uppercase mb-0" id="bioModalLabel">Short Auto-Bio</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <div class="main-text d-flex flex-column gap-3">
                                <div>
                                    <label for="studentDescription" class="form-label text-muted small fw-semibold">DESCRIBE YOURSELF</label>
                                    <textarea 
                                        name="description" 
                                        id="studentDescription"
                                        class="form-control"
                                        rows="5"
                                        maxlength="1000"
                                        placeholder="Type message here..."></textarea>
                                    <div class="counter d-flex justify-content-end mt-1">
                                        <small class="text-muted">
                                            <span class="charcount" id="characters">0</span>/1000
                                        </small>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end gap-2">
                                    <button type="buttom" class="btn btn-outline-warning rounded-3 text-dark" id="clearText">Clear</button>
                                    <button type="button" class="btn btn-outline-secondary rounded-3" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" name="description_submit" class="btn btn-success rounded-3 px-4 fw-semibold">SUBMIT</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const textarea = document.getElementById('studentDescription');
            const charCount = document.getElementById('characters');
            const clearBtn = document.getElementById('clearText');
            const maxChar = 1000;

            const updateCount = () => {
                const currentLength = textarea.value.length;
                charCount.textContent = currentLength;

                if(currentLength >= maxChar) {
                    charCount.classList.add('text-danger', 'fw-bold');
                }
                else {
                    charCount.classList.remove('text-danger', 'fw-bold');
                }
            };

            textarea.addEventListener('input', updateCount);

            if(clearBtn) {
                clearBtn.addEventListener('click', () => {
                    textarea.value = "";
                    updateCount();
                    textarea.focus();
                });
            }

            updateCount();
        });
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/TVAM_SCHOLARSHIP/assets/js/student.js"></script>
</body>
</html>
