<?php 

include "../config/database.php";
require_once "../config/student-auth.php";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile</title>
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/TVAM_SCHOLARSHIP/assets/css/style.css">
    <link rel="icon" href="/TVAM_SCHOLARSHIP/assets/images/tvamlogo_web.png">
</head>

<body>
    <?php include "../includes/header.php";?>

    <div class="container-fluid">
        <div class="row">
            <aside class="col-md-4 col-lg-4 bg-dark text-light min-vh-75 p-4 d-flex flex-column align-items-center">
                <div class="w-100 d-flex flex-column p-3 gap-3 px-4 ">
                    <div class="row d-flex">
                        <div class="col-md-4 col-lg-4 col-sm-2">
                            <img src="/TVAM_SCHOLARSHIP/assets/images/samplePicture.jpg" alt="" class="img-fluid rounded-circle">
                        </div>
                        <div class="col-md-8 col-lg-8 px-4 text-left d-flex flex-column">
                            <h1>Rai Senal</h1>
                            <span>COMRPO</span>
                            <a href="../auth/logout.php" class="text-decoration-none btn btn-danger btn-outline-secondary border-0 text-light mt-3 w-50 ">Logout</a>
                        </div>
                    </div>
                    <hr class="border w-100">
                </div>

                <div class="w-100 d-flex flex-column">
                    <ul class="nav flex-column gap-4 p-4 text-light">
                        <li class="nav-item">
                            <a href="" class="text-decoration-none fs-6 btn btn-outline-light border-0  w-100 text-start py-2 px-4 rounded-3"> 🪪 Manage Profile</a>
                        </li>
                        <li class="nav-item">
                            <a href="" class="text-decoration-none fs-6 btn btn-outline-light border-0  w-100 text-start py-2 px-4 rounded-3"> 👨‍🎓 Apply as Scholar</a>
                        </li>

                        <li class="nav-item">
                            <a href="" class="text-decoration-none fs-6 btn btn-outline-light border-0  w-100 text-start py-2 px-4 rounded-3"> 💸 See Stipend Balance</a>
                        </li>

                        <li class="nav-item">
                            <a href="" class="text-decoration-none fs-6 btn btn-outline-light border-0  w-100   text-start py-2 px-4 rounded-3">🔑 Change Password</a>
                        </li>
                    </ul>
                </div>
            </aside>

            <main class="col-md-8 col-lg-8 min-vh-100 p-5 px-4 py-5 bg-light">
                    <div class="container-fluid d-flex flex-column gap-3 px-4">
                        <div class="profile-main-card card w-50 p-3 rounde-2 shadow-lg">
                            <div class="card-body ">
                                <h2>Welcome, Rai!</h2>
                            </div>
                        </div>

                        <div class="row d-flex flex-column border mt-4 gap-3">
                            <div class="card col-md-12 col-lg-12 border">
                                <div class="card-body">
                                    <h1>About me</h1>
                                </div>
                            </div>

                            <div class=" card col-md-12 col-lg-12 border">
                                <div class="card-body">
                                    <h1>Why I choose this course?</h1>
                                    <p></p>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </main>
        </div>
    </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>