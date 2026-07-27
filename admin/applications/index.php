<?php

require_once "../../config/database.php";
require_once "../../config/admin-auth.php";
    
$search = isset($_GET['applicant']) ? trim($_GET['applicant']) : '';
$statusFilter = isset($_GET['sort']) ? trim($_GET['sort']) : '';
$page_setup = isset($_GET['page']) ? intval($_GET['page']) : 1;

if ($page_setup < 1) $page_setup = 1;
$page_shown_scholars = 5;

$conditions = [];
$params = [];
$types = "";

if ($search !== '') {
    $conditions[] = ("u.fullname LIKE ? OR a.application_ID LIKE ?");
    $search_param = "%" . $search . "%";

    $params[] = $search_param;
    $params[] = $search_param;

    $types .= "ss";
}

if($statusFilter !== '') {
    $conditions[] = "a.status = ?";
    $params[] = $statusFilter;
    $types .= "s";  
}

$whereClause = !empty($conditions) ? " WHERE " .implode(" AND ", $conditions) : '';

// QUERY FOR SELECTING THE LIST APPLICANTS
$count_sql = "SELECT COUNT(*) AS total_applications,
                SUM(CASE WHEN LOWER(a.status) = 'active' THEN 1 ELSE 0 END) AS total_active,
                SUM(CASE WHEN LOWER(a.status) = 'inactive' THEN 1 ELSE 0 END) AS total_inactive,
                SUM(CASE WHEN LOWER(a.status) = 'pending' THEN 1 ELSE 0 END) AS total_pending
                FROM applications a
                INNER JOIN users u ON a.user_id = u.id"
                . $whereClause;
$count_stmt = $conn->prepare($count_sql);

if (!$count_stmt) die ("FAILED TO CONNECT WITH THE DATABASE. CONTACT ADMIN");

if(!empty($params)) {
    $count_stmt->bind_param($types, ...$params);
}

$count_stmt->execute();
$counts = $count_stmt->get_result()->fetch_assoc();

$approve = (int)($counts['total_active'] ?? 0);
$rejeceted = (int)($counts['total_inactive'] ?? 0);
$pending = (int)($counts['total_pending'] ?? 0);
$allApplicants = (int)($counts['total_applications'] ?? 0);

$total_page = max(1, (int)ceil($allApplicants / $page_shown_scholars));



if($page_setup > $total_page) {
    $page_setup = $total_page;
}

$offset = ($page_setup - 1) * $page_shown_scholars;

$select_query = "SELECT a.application_ID,
                a.scholarship_type,
                a.status,
                u.fullname 
                FROM applications a
                INNER JOIN users u ON a.user_id = u.id"
                . $whereClause .
                " ORDER BY a.application_ID DESC
                LIMIT ?
                OFFSET ?";

$data_params = $params;
$data_types = $types . "ii";
$data_params[] = $page_shown_scholars;
$data_params[] = $offset;

$stmt = $conn->prepare($select_query);

if(!$stmt) die ("DATABASE FAILED. CONTACT ADMIN");

$stmt->bind_param($data_types, ...$data_params);
$stmt->execute();
$selectResult = $stmt->get_result();

function paginationLink($page_number) {
    $query = $_GET;
    $query['page'] = $page_number;
    
    return 'index.php?' . http_build_query($query);
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applications - TVAM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/TVAM_SCHOLARSHIP/assets/css/style.css">
    <link rel="stylesheet" href="/TVAM_SCHOLARSHIP/assets/css/admin.css">
    <link rel="icon" href="../../assets/images/tvamlogo_web.png">
</head>
<body class="admin-page">
    <div class="d-flex flex-column flex-md-row min-vh-100">
        <?php include "../../includes/sidebar.php"; ?>

        <main class="container-fluid flex-grow-1 p-4">
            <div class="d-flex flex-column justify-content-between flex-lg-row flex-md-column align-items-lg-center gap-2 p-4 px-4 mb-4 border shadow-sm bg-white rounded">
                <div>
                    <h2 class="fs-2 text-uppercase fw-bold"> Applicant Reports</h2>
                    <p class="badge bg-warning"> 
                        TOTAL APPLICANTS<?php echo $allApplicants; ?>
                    </p>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <span class="badge bg-success p-2">
                        TOTAL APPROVED: <?php echo $approve; ?>
                    </span>
                    <span class="badge bg-danger p-2">
                        TOTAL REJECTED: <?php echo $rejeceted; ?>
                    </span>

                    <span class="badge bg-primary p-2">
                        TOTAL PENDING: <?php echo $pending; ?>
                    </span>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-1 p-3 px-4 py-4 mb-4">
                <form action="" method="GET">
                    <div class="row align-items-center d-flex flex-row justify-content-between">
                        <label for="applicant" class="form-label text-muted text-uppercase">FIND APPLICANT</label>
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="d-flex flex-column flex-md-row gap-2">
                                <input 
                                    type="text" 
                                    name="applicant" 
                                    class="form-control"
                                    placeholder="e.g Juan Pedro">
                                    <button type="submit" name="user-btn" class="btn btn-secondary text-uppercase px-3 w-25">Find</button>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 col-lg-4 text-md-end mt-3">
                            <input 
                                type="hidden" 
                                name="sort"
                                value=""
                            >

                            <a href="index.php?sort=active" class="btn btn-success">ACTIVE</a>
                            <a href="index.php?sort=inactive" class="btn btn-danger">INACTIVE</a>
                            <a href="index.php?sort=pending" class="btn btn-warning">PENDING</a>
                        </div>
                    </div>
                </form>
            </div>

            <div class="table-responsive bg-white border rounded shadow-sm p-3">
                <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-2 mb-3">
                    <div>
                        <h5 class="fw-bold mb-1">Application Directory</h5>
                        <p class="text-muted mb-0 small">Scholarship applications sorted by application date.</p>
                    </div>
                </div>

                <div class="admin-table-scroll">
                    <table class="table table-hover align-middle mb-0 admin-responsive-table">
                    <thead class="text-center table-dark text-light">
                        <tr class="align-items-center text-center">
                            <th class="p-3 p-sm-4">APPLICANT ID</th>
                            <th class="p-3 p-sm-4">NAME</th>
                            <th class="p-3 p-sm-4">SCHOLARSHIP</th>
                            <th class="p-3 p-sm-4">STATUS</th>
                            <th class="p-3 p-sm-4">ACTION</th>
                        </tr>   
                    </thead>

                    <tbody>
                        <?php 
                        if ($selectResult->num_rows > 0) {
                            while ($fetch = $selectResult->fetch_assoc()) :
                                $badgeClass = "bg-secondary";
                                if(strtolower($fetch['status']) == 'active') $badgeClass = "bg-success";
                                if(strtolower($fetch['status']) == 'pending') $badgeClass = "bg-warning text-dark";
                                if(strtolower($fetch['status']) == 'inactive') $badgeClass = "bg-danger";
                        ?>
                        <tr>
                            <td data-label="APPLICANT ID" class="fw-bold text-center"><?php echo htmlspecialchars($fetch['application_ID']); ?></td>
                            <td data-label="NAME" class="text-center"> <?php echo htmlspecialchars($fetch['fullname']);?></td>
                            <td data-label="SCHOLARSHIP" class="text-center"> <?php echo htmlspecialchars(str_replace('_', '', $fetch['scholarship_type'])); ?></td>
                            <td data-label="STATUS" class="text-center"> 
                                <span class="badge <?php echo $badgeClass;?> text-uppercase px-3 py-2">
                                    <?php echo htmlspecialchars($fetch['status']);?>
                                </span>
                            </td>
                            <td data-label="ACTION" class="d-flex flex-column gap-2">
                                <a href="approved.php?id=<?php echo $fetch['application_ID'];?>" class="index-btn btn btn-primary fw-bold ">Approved</a>
                                <a href="rejected.php?id=<?php echo $fetch['application_ID'];?>" class="index-btn btn btn-danger fw-bold">Rejected</a>
                            </td>
                        </tr>
                        <?php  endwhile;
                        }
                        else { ?>

                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">No Scholars in the database.</td>
                        </tr>
                        <?php } ?>

                    </tbody>
                </table>
                </div>
            </div>

            <div>
                <?php if($total_page > 1) : ?>

                <nav aria-label="Page pagination" class=" mt-3">
                    <ul class="pagination justify-content-center p-3 px-4 py-4">
                        <li class="page-item <?php echo($page_setup <= 1) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="<?php echo paginationLink($page_setup - 1); ?>">PREVIOUS</a>
                        </li>

                        <?php for($i = 1; $i <= $total_page; $i++) : ?>
                            <li class="page-item <?php echo ($i === $page_setup) ? 'active' : ''; ?>">
                                <a href="<?php echo paginationLink($i); ?>" class="page-link">
                                    <?php echo $i; ?>
                                </a>
                            </li>
                        <?php endfor; ?>

                        <li class="page-item <?php echo ($page_setup >= $total_page) ? 'disabled' : ''?>">
                            <a href="<?php echo paginationLink($page_setup + 1); ?>" class="page-link">
                                NEXT
                            </a>
                        </li>
                    </ul>

                    <p class="text-muted text-center small">
                        Showing Page <?php echo $page_setup; ?>
                        of <?php echo $total_page; ?>
                        (<?php echo $allApplicants;?> total Applicants)
                    </p>
                </nav>
                <?php endif; ?>
            </div>
        </main>
    </div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
