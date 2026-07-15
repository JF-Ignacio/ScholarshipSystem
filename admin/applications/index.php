<?php

require_once "../../config/database.php";
require_once "../../config/admin-auth.php";



$search = isset($_GET['applicant']) ? trim($_GET['applicant']) : '';
$statusFilter = isset($_GET['sort']) ? trim($_GET['sort']) : '';

$query_stmt = "SELECT a.application_ID, 
                u.fullname,
                a.scholarship_type,
                a.status
                FROM applications a
                INNER JOIN users u ON a.user_id = u.id";

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

if(!empty($conditions)) {
    $query_stmt .= " WHERE " . implode(" AND ", $conditions);
}

$query_stmt .= " ORDER BY a.created_at DESC";

$stmt = $conn->prepare($query_stmt);

if(!$stmt) die ("DATABASE FAILED. CONTACT ADMIN.");

if(!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();

$query_result = $stmt->get_result();


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
            <div class="d-flex align-items-center justify-content-between gap-3 p-4 mb-4 border shadow-sm bg-white rounded">
                <h2 class="text-uppercase mb-0">Applications</h2>
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
                <table class="table table-hover align-middle mb-0 table-responsive">
                    <thead>
                        <tr>
                            <th>APPLICANT ID</th>
                            <th>NAME</th>
                            <th>SCHOLARSHIP</th>
                            <th>STATUS</th>
                            <th>ACTION</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php 
                        if ($query_result->num_rows > 0) {
                            while($row = $query_result->fetch_assoc()) {
                                $badgeClass = "bg-secondary";
                                if(strtolower($row['status']) == 'active') $badgeClass = "bg-success";
                                if(strtolower($row['status']) == 'pending') $badgeClass = "bg-warning text-dark";
                                if(strtolower($row['status']) == 'inactive') $badgeClass = "bg-danger";
                        ?>
                        <tr>
                            <td class="fw-bold"><?php echo htmlspecialchars($row['application_ID']); ?></td>
                            <td> <?php echo htmlspecialchars($row['fullname']);?></td>
                            <td> <?php echo htmlspecialchars(str_replace('_', '', $row['scholarship_type'])); ?></td>
                            <td> 
                                <span class="badge <?php echo $badgeClass;?> text-uppercase px-3 py-2">
                                    <?php echo htmlspecialchars($row['status']);?>
                                </span>
                            </td>
                            <td class="d-flex flex-column gap-2">
                                <a href="approved.php?id=<?php echo $row['application_ID'];?>" class="index-btn btn btn-primary fw-bold ">Approved</a>
                                <a href="rejected.php?id=<?php echo $row['application_ID'];?>" class="index-btn btn btn-danger fw-bold">Rejected</a>
                            </td>
                        </tr>
                        <?php } 
                        }
                        else { ?>

                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">No Scholars in the database.</td>
                        </tr>
                        <?php } ?>

                    </tbody>
                </table>
            </div>
        </main>
    </div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
