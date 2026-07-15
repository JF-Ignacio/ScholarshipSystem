<?php 

include "../config/student-auth.php";
include "../config/database.php";

$id = $_GET['id'] ?? 0;

$message = "";
$alert = "";

// GIVEN DATA
$fullname = $_SESSION['fullname'] ?? '';
$userID = $_SESSION['id'] ?? '';
$userEmail = $_SESSION['email'] ?? '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    // data na wala pa and need i-input ng user.
    $course = trim($_POST['course'] ?? '');
    $yearLevel = trim($_POST['year_level'] ?? '');
    $scholarship = trim($_POST['scholarship_type'] ?? '');
    
    if(!empty($course) && !empty($yearLevel)) {
        $checkStmt = mysqli_prepare($conn, "SELECT * FROM applications WHERE user_id = ?");

        if($checkStmt) {
            mysqli_stmt_bind_param($checkStmt, "i", $userID);
            mysqli_stmt_execute($checkStmt);

            $result = mysqli_stmt_get_result($checkStmt);

            if (mysqli_num_rows($result) > 0) {
                $message = "You already applied for this scholarship";
                $alert = "alert-warning";
            }

            else {
                $stmt = mysqli_prepare($conn, "INSERT INTO applications (user_id, scholarship_type, course, year_level)
                VALUES (?, ?, ?, ?)");

                mysqli_stmt_bind_param($stmt, "isss", $userID, $scholarship, $course, $yearLevel);

                if(mysqli_stmt_execute($stmt)) {
                    $message="Application submitted!";
                    $alert = "alert-success";
                }
                else {
                    $message = "Application failed. Try again!";
                    $alert = "alert-secondary";
                }
                
            }
        }
    }
}

?>

<?php include "../includes/header.php"; ?>
<head>
    <title>Apply Scholarship</title>
</head>
<main class="container-fluid apply-page d-flex min-vh-100 px-3 px-md-4 py-4">
    <div class="container d-flex flex-column justify-content-center">
        <div class="row align-items-center g-4">
            <div class="col-12 col-lg-8 text-center text-lg-start">
                <h1 class="apply-title mb-3">BE A TVAM SCHOLAR FOR A BETTER AND SECURED FUTURE</h1>
                <h2 class="apply-subtitle mb-3">VALIDATE YOUR FUTURE.</h2>
                <p class="fs-3">Technical Vocational Teachers Academy Manila</p>
                <p class="fs-4 text-muted"> &#128204 La Soberidad St., Sampaloc, Manila</p>
                <a href="#scholar-info" class="btn-apply btn rounded-4 fw-bold fs-4">Scholarship Information</a>
            </div>

            <div class="col-12 col-lg-4 p-3 d-flex flex-column border rounded-3 shadow-sm">
                <form action="apply.php" method="POST" class="p-2 d-flex flex-column">
                    <?php if (!empty($message)) : ?>
                        <div class="alert <?php echo $alert?>" role="alert">
                            <?php echo $message; ?>
                        </div>
                    <?php endif; ?>

                    <div class="d-flex flex-row align-items-center">
                        <img src="../assets/images/TVAMLOGO.png" alt="" class="apply-img img-fluid rounded">
                        <h2 class="fs-4">TVAM SCHOLARSHIP FORM</h2>
                    </div>

                    <div class="mt-3">
                        <label for="user_id" class="form-label">User ID: </label>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($userID)?>"readonly>
                    </div>

                    <div class="mt-3">
                        <label for="fullname" class="form-label">Fullname: </label>
                        <input type="text" id="fullname" name="fullname" placeholder="ex. Pedro C. Gil" class="form-control" value="<?php echo htmlspecialchars($fullname);?>" readonly> 
                    </div>

                    <div class="mt-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($userEmail);?>" readonly>
                    </div>

                    <div class="row mt-3 align-items-center">
                        <div class="col">
                            <label for="course" class="form-label">Course</label>
                            <select name="course" id="course" class="form-select">
                                <option value="">--SELECT MAJOR--</option>
                                <option value="Compro">BTVTED - COMPRO</option>
                                <option value="ICT">BTVTED - ICT</option>
                                <option value="FSM">BTVTED - FSM</option>
                                <option value="FGT">BTVTED - FGT</option>
                                <option value="IA">BTVTED - IA</option>
                                <option value="BCW">BTVTED - BCW</option>
                                <option value="HE">BTVTED - HE</option>
                                <option value="EE">BTVTED - ELECTRONIC</option>
                                <option value="ELE">BTVTED - ELECTRICAL</option>
                            </select>
                        </div>

                        <div class="col">
                            <label for="year_level" class="form-label">Year level: </label>
                            <select name="year_level" id="year_level" class="form-select">
                                <option value="none">--CHOOSE YEAR--</option>
                                <option value="1st year">First year</option>
                                <option value="2nd year">Second year</option>
                                <option value="3rd year">Third year</option>
                                <option value="4th year">Fourth year</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-3">
                        <label for="scholarship_type" class="form-label">SCHOLARSHIP: </label>
                        <select name="scholarship_type" id="scholarship" class="form-select">
                            <option value="">--SELECT SCHOLARSHIP--</option>
                            <option value="TVAM_IskolarNgBayan">Iskolar Ng Bayan</option>
                            <option value="DOST-TVAM_Iskolar">DOST - TVAM</option>
                            <option value="DICT-TVAM_Iskolar">DICT - TVAM</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-success mt-3" name="apply">Apply as Scholar!</button>
                </form>
            </div>
        </div>
    </div>
</main>

    <section class="scholar-info text-white py-5" id="scholar-info">
        <div class="container text-white">
            
            <div class="text-center mb-5">
                <h2 class="text-uppercase fw-bold tracking-wide text-white">Available Scholarship Programs</h2>
                <p class="text-white">Review the eligibility metrics and student allowance features for our current slots</p>
            </div>

            <div class="row g-4 justify-content-center">
                
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm rounded-3">
                        <div class="card-header bg-white border-0 pt-4 text-center">
                            <img src="/TVAM_SCHOLARSHIP/assets/images/tesdalogo.png" alt="TESDA Logo" class="scholar-logo img-fluid rounded-circle shadow-inner p-sm-3">
                        </div>
                        <div class="card-body d-flex flex-column p-4">
                            <h4 class="card-title text-uppercase fw-bold text-center mb-4 fs-4 text-dark">TESDA Scholarship</h4>

                            <h6 class="text-success text-uppercase fw-bold small mb-2 tracking-wider">Benefits</h6>
                            <ul class="list-unstyled mb-4 text-secondary">
                                <li><i class="text-success me-2">•</i> <strong>Free Training Framework</strong></li>
                                <li><i class="text-success me-2">•</i> <strong>National Certification Support</strong></li>
                                <li><i class="text-success me-2">•</i> <strong>Assessment Expense Coverage</strong></li>
                            </ul>

                            <h6 class="text-danger text-uppercase fw-bold small mb-2 tracking-wider">Requirements</h6>
                            <ul class="list-unstyled mt-auto text-secondary mb-0">
                                <li><i class="text-danger me-2">•</i> Valid Student Identification</li>
                                <li><i class="text-danger me-2">•</i> Average Grade Point of <strong>1.75</strong></li>
                                <li><i class="text-danger me-2">•</i> Active Certificate of Enrollment</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm rounded-3">
                        <div class="card-header bg-white border-0 pt-4 text-center">
                            <img src="/TVAM_SCHOLARSHIP/assets/images/dostlogo.png" alt="DOST Logo" class="scholar-logo img-fluid rounded-circle">
                        </div>
                        <div class="card-body d-flex flex-column p-4">
                            <h4 class="card-title text-uppercase fw-bold text-center mb-4 fs-4 text-dark">DOST Scholarship</h4>

                            <h6 class="text-success text-uppercase fw-bold small mb-2 tracking-wider">Benefits</h6>
                            <ul class="list-unstyled mb-4 text-secondary">
                                <li><i class="text-success me-2">•</i> <strong>Monthly Stipend Allowance</strong></li>
                                <li><i class="text-success me-2">•</i> <strong>Learning Resource Support</strong></li>
                                <li><i class="text-success me-2">•</i> <strong>Group Insurance Policies</strong></li>
                            </ul>

                            <h6 class="text-danger text-uppercase fw-bold small mb-2 tracking-wider">Requirements</h6>
                            <ul class="list-unstyled mt-auto text-secondary mb-0">
                                <li><i class="text-danger me-2">•</i> STEM Strand Graduate Matrix</li>
                                <li><i class="text-danger me-2">•</i> Average Grade Point of <strong>1.75</strong></li>
                                <li><i class="text-danger me-2">•</i> Passing Evaluation Exam Score</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm rounded-3">
                        <div class="card-header bg-white border-0 pt-4 text-center">
                            <img src="/TVAM_SCHOLARSHIP/assets/images/dswdlogo.png" alt="DSWD Logo" class="scholar-logo img-fluid rounded-circle">
                        </div>
                        <div class="card-body d-flex flex-column p-4">
                            <h4 class="card-title text-uppercase fw-bold text-center mb-4 fs-4 text-dark">Tulong Iskolar</h4>

                            <h6 class="text-success text-uppercase fw-bold small mb-2 tracking-wider">Benefits</h6>
                            <ul class="list-unstyled mb-4 text-secondary">
                                <li><i class="text-success me-2">•</i> <strong>Tuition Fee Subsidies</strong></li>
                                <li><i class="text-success me-2">•</i> <strong>Book Purchase Voucher Logs</strong></li>
                                <li><i class="text-success me-2">•</i> <strong>Community Grant Access</strong></li>
                            </ul>

                            <h6 class="text-danger text-uppercase fw-bold small mb-2 tracking-wider">Requirements</h6>
                            <ul class="list-semibold mt-auto text-secondary mb-0">
                                <li><i class="text-danger me-2"></i>Certificate of Indigency Record</li>
                                <li><i class="text-danger me-2"></i>Average Grade Point of <strong>1.75</strong></li>
                                <li><i class="text-danger me-2"></i>Certificate of Enrollment File</li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
