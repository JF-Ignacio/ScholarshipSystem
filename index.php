<?php

include "config/database.php";


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Technical-Vocational Academy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/TVAM_SCHOLARSHIP/assets/css/style.css">
    <link rel="icon" href="/TVAM_SCHOLARSHIP/assets/images/tvamlogo_web.png">
</head>
<body>
    <nav class="index-navbar navbar navbar-expand-lg navbar-dark sticky-top shadow-sm">
        <div class="index-nav-container container-fluid px-4">
            <a href="/TVAM_SCHOLARSHIP/index.php" class="navbar-brand d-flex align-items-center me-2">
                <img src="/TVAM_SCHOLARSHIP/assets/images/TVAMLOGO.png" alt="TVAM Logo" class="main-img rounded-pill me-2">
                <span class="brand-text">Technical-Vocational Academy of Manila</span>
            </a>

            <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbar"> 
                <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2 py-lg-0 py-3">
                    <li class="nav-item">
                        <a href="/TVAM_SCHOLARSHIP/index.php" class="nav-link active" aria-current="page">Home</a>
                    </li>
                    <li class="nav-item">
                        <a href="#AboutUs" class="nav-link">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a href="/TVAM_SCHOLARSHIP/index.php#offers" class="nav-link">Offers</a>
                    </li>
                    <li class="nav-item">
                        <a href="/TVAM_SCHOLARSHIP/index.php#scholarships" class="nav-link">Scholarships</a>
                    </li>
                    <li class="nav-item">
                        <a href="/TVAM_SCHOLARSHIP/index.php#FAQs" class="nav-link">FAQs</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <header class="index-head">
        <div class="info-box">
            <h3>TVAM Partners for Youth</h3>
            <div class="partner-logos" aria-label="TVAM partner agencies">
                <img src="/TVAM_SCHOLARSHIP/assets/images/tesdalogo.png" alt="TESDA logo" class="img-fluid rounded-circle">
                <img src="/TVAM_SCHOLARSHIP/assets/images/dswdlogo.png" alt="DSWD logo" class="img-fluid rounded-circle">
                <img src="/TVAM_SCHOLARSHIP/assets/images/dostlogo.png" alt="DOST logo" class="rounded-circle img-fluid">
            </div>
        </div>
    </header>

    <main class="index-hero container-fluid bg-light">
        <div class="row align-items-center g-4">
            <div class="col-12 col-lg-8 text-center text-lg-start">
                <h1 class="h1-hero text-uppercase">Empowering the youth by building</h1>
                <h2 class="h2-hero text-uppercase">Tech-Voc Skills for the Industry</h2>
                <p class="p-hero text-muted f-2">Validate their capabilities.</p>

                <div class="btn-hero d-flex flex-column flex-sm-row align-items-center justify-content-center justify-content-lg-start text-center">
                    <button type="submit" class="apply-btn btn" name="login" onclick="window.location.href='auth/login.php'">LOGIN</button>
                    <button type="submit" class="register-btn btn" name="register" onclick="window.location.href='auth/register.php'">REGISTER</button>
                </div>
            </div>

            <div class="main-img col-12 col-lg-4 text-center">
                <img src="/TVAM_SCHOLARSHIP/assets/images/tvamlogo_web.png" alt="TVAM logo" class="img-hero img-fluid">
            </div>
        </div>
    </main>

    <section class="about-us container-fluid p-3 px-4 py-4 d-flex flex-column" id="AboutUs">
        <div class="row p-4 mt-5">
            <div class="col-md-12 col-lg-6 mb-3">
                <div class="about-carousel carousel slide carousel-fade" data-bs-ride="carousel" id="carouselFade">
                    <div class="carousel-indicators">
                        <button type="button" data-bs-target="#carouselFade" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                        <button type="button" data-bs-target="#carouselFade" data-bs-slide-to="1" aria-label="Slide 2"></button>
                        <button type="button" data-bs-target="#carouselFade" data-bs-slide-to="2" aria-label="Slide 3"></button>
                    </div>

                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="/TVAM_SCHOLARSHIP/assets/images/schoolbg.jpg" alt="TVAM campus" class="d-block w-100">
                        </div>
                        <div class="carousel-item">
                            <img src="/TVAM_SCHOLARSHIP/assets/images/samplePicture.jpg" alt="TVAM students" class="d-block w-100">
                        </div>

                        <div class="carousel-item">
                            <img src="/TVAM_SCHOLARSHIP/assets/images/schoolbg.jpg" alt="TVAM training facility" class="d-block w-100">
                        </div>
                    </div>

                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselFade" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>

                    <button class="carousel-control-next" type="button" data-bs-target="#carouselFade" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>

            <div class="col-md-12 col-lg-6">
                <div class="card d-flex border bg-light p-3 py-3 text-left align-items-center justify-content-center">
                    <h2 class="text-uppercase fw-bold">About Technical-Vocational Academy of Manila</h2>
                </div>
                <hr class="border ">

                <div class="card-body">
                    <h2 class="fs-5 text-light justify-content-center">Technical-Vocational Academy of Manila (TVAM) is committed to providing quality technical and vocational education that equips learners with practical skills, industry knowledge, and professional values.
                        Through partnerships with TESDA, DOST, and DSWD, TVAM helps students gain opportunities for employment, entrepreneurship, and lifelong learning.
                    </h2>
                    <hr class="border">
                    <div class="v-m-container row g-3">
                            <div class="col-12 col-lg-6">
                                <div class="card vm-card h-100">
                                    <h2 class="fs-6 fw-bold text-uppercase">Vision</h2>
                                    <div class="card-body">
                                        <p>To become a leading institution in developing skilled, competent, and globally competitive professionals.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-lg-6">
                                <div class="card vm-card h-100">
                                    <h2 class="fs-6 fw-bold text-uppercase">Mission</h2>
                                    <div class="card-body">
                                        <p>To empower learners through accessible, industry-relevant technical and vocational education.</p>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="colleges bg-light py-5" id="offers">
        <div class="container">
            
            <div class="text-center mb-5">
                <h2 class="text-uppercase fw-bold tracking-wide text-dark">Available Scholarship Programs</h2>
                <p class="text-muted">Review the eligibility metrics and student allowance features for our current slots</p>
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

    <section class="scholarships container-fluid py-4 py-sm-5 px-sm-4" id="scholarships">
        <div class="container text-center">
            <div class="row g-3 justify-content-center">
                <div class="col-12 col-md-6 col-lg-6">
                    <div class="card d-flex h-100 shadow-sm d-flex align-items-center overflow-hidden">
                        <div class="card-header p-3 bg-transparent">
                            <h2>Information Communication Technology</h2>
                        </div>
                        <div class="img-container">
                            <img src="/TVAM_SCHOLARSHIP/assets/images/ictimage.jpg" alt="" class="img-fluid">

                            <div class="floating-btn">
                                <span><a href="" class="text-decoration-none btn btn-dark">View course</a></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-6">
                    <div class="card d-flex h-100 shadow-sm d-flex align-items-center overflow-hidden">
                        <div class="card-header p-3 bg-transparent">
                            <h2>Electrical Technology</h2>
                        </div>
                        <div class="img-container">
                            <img src="/TVAM_SCHOLARSHIP/assets/images/ELECTRICALimg.jpg" alt="" class="img-fluid">

                            <div class="floating-btn">
                                <span><a href="" class="text-decoration-none btn btn-dark">View course</a></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-3 justify-content-center mt-2">
                <div class="col-12 col-md-6 col-lg-6">
                    <div class="card d-flex h-100 shadow-sm d-flex align-items-center overflow-hidden">
                        <div class="card-header p-3 bg-transparent">
                            <h2>Electronics Technology</h2>
                        </div>
                        <div class="img-container">
                            <img src="/TVAM_SCHOLARSHIP/assets/images/ELECTRONICimg.png" alt="" class="img-fluid">

                            <div class="floating-btn">
                                <span><a href="" class="text-decoration-none btn btn-dark">View course</a></span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-12 col-md-6 col-lg-6">
                    <div class="card d-flex h-100 shadow-sm d-flex align-items-center overflow-hidden">
                        <div class="card-header p-3 bg-transparent">
                            <h2>Food and Beverage Industry</h2>
                        </div>
                        <div class="img-container">
                            <img src="/TVAM_SCHOLARSHIP/assets/images/foodanbevimg.jpg" alt="" class="img-fluid">

                            <div class="floating-btn">
                                <span><a href="" class="text-decoration-none btn btn-dark">View course</a></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-3 justify-content-center mt-2">
                <div class="col-12 col-md-6 col-lg-6">
                    <div class="card d-flex h-100 shadow-sm d-flex align-items-center overflow-hidden">
                        <div class="card-header p-3 bg-transparent">
                            <h2>Hotel & Restaurant Management</h2>
                        </div>
                        <div class="img-container">
                            <img src="/TVAM_SCHOLARSHIP/assets/images/HRMimage.jpg" alt="" class="img-fluid">

                            <div class="floating-btn">
                                <span><a href="" class="text-decoration-none btn btn-dark">View course</a></span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-12 col-md-6 col-lg-6">
                    <div class="card d-flex h-100 shadow-sm d-flex align-items-center overflow-hidden">
                        <div class="card-header p-3 bg-transparent">
                            <h2>Home Economics</h2>
                        </div>
                        <div class="img-container">
                            <img src="/TVAM_SCHOLARSHIP/assets/images/HOMEECOimg.jpg" alt="" class="img-fluid">

                            <div class="floating-btn">
                                <span><a href="" class="text-decoration-none btn btn-dark">View course</a></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="FAQs p-4 px-4 py-3 align-items-center d-flex" id="FAQs">
        <div class="accordion-container d-flex  flex-column p-4 align-items-center w-100 min-vh-100 justify-content-center">
            <div class="header align-items-center text-center g-3 mb-3 p-3">
                <h1 class="text-uppercase fw-bold fs-2">FAQs ABOUT Technical Vocational Academy of MANILA</h1>
                <span class="text-muted fs-4 fst-italic">TVAM is one of the respected and recognized high school academy that transitioned youth</span>
            </div>

            <div class="accordion accordion-flush w-75 align-items-center">
                <div class="accordion-item">
                    <h1 class="accordion-header">
                        <button class="accordion-button collpased fw-bold fs-3 text-uppercase" type="button" data-bs-toggle="collapse" 
                        data-bs-target="#flush-one" aria-expanded="false" aria-controls="flush-one"> 
                            What programs are offered at Technical-Vocational Academy of Manila (TVAM)?
                        </button>
                    </h1>

                    <div class="accordion-collapse collapse" id="flush-one" data-bs-parent="#FAQs">
                        <div class="accordion-body">
                           TVAM offers a variety of technical-vocational programs designed to prepare students for employment and industry certification. 
                           These include Computer Programming, Computer Systems Servicing, Electrical Installation and Maintenance, Automotive Servicing, Cookery, 
                           Housekeeping, and other skills-based training programs aligned with industry standards and workforce demands.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h1 class="accordion-header mt-4 bg-transparent">
                        <button class="accordion-button collpased fw-bold fs-3 text-uppercase" type="button" data-bs-toggle="collapse" 
                        data-bs-target="#flush-two" aria-expanded="false" aria-controls="flush-two"> 
                            What scholarship opportunities are available for students?
                        </button>
                    </h1>

                    <div class="accordion-collapse collapse" id="flush-two" data-bs-parent="#FAQs">
                        <div class="accordion-body">
                            VAM partners with government agencies and organizations such as TESDA, DOST, and DSWD to provide scholarship opportunities for qualified students. 
                            These scholarships may cover training costs, educational assistance, certification fees, 
                            and other forms of financial support to help learners complete their studies successfully.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h1 class="accordion-header mt-4">
                        <button class="accordion-button collpased fw-bold fs-3 text-uppercase" type="button" data-bs-toggle="collapse" 
                        data-bs-target="#flush-three" aria-expanded="false" aria-controls="flush-three"> 
                            How can I apply for admission and scholarships?
                        </button>
                    </h1>

                    <div class="accordion-collapse collapse" id="flush-three" data-bs-parent="#FAQs">
                        <div class="accordion-body">
                            Students may register through the TVAM Scholarship Management System by creating an account, completing the application form, and submitting the required documents.
                             Applications are evaluated based on academic performance, eligibility requirements, and scholarship criteria established by the sponsoring agency or program.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h1 class="accordion-header mt-4">
                        <button class="accordion-button collpased fw-bold fs-3 text-uppercase" type="button" data-bs-toggle="collapse" 
                        data-bs-target="#flush-four" aria-expanded="false" aria-controls="flush-four"> 
                            Why should I choose TVAM for my technical-vocational education?
                        </button>
                    </h1>

                    <div class="accordion-collapse collapse" id="flush-four" data-bs-parent="#FAQs">
                        <div class="accordion-body">
                            TVAM is committed to providing quality, industry-relevant training that equips students with practical skills,
                             professional values, and nationally recognized competencies. Through experienced trainers, modern learning approaches, 
                             and partnerships with government agencies, 
                            TVAM helps learners become job-ready and competitive in today's workforce.                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/TVAM_SCHOLARSHIP/assets/js/app.js"></script>
    <?php include "includes/footer.php"; ?>

