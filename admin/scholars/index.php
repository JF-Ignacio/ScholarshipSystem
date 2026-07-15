<?php

require_once "../../config/admin-auth.php";
require_once "../../config/database.php";

$id = $_SESSION['id'] ?? 0;


$search = isset($_GET['user']) ? trim($_GET['user']) : '';
$status = isset($_GET['status']) ? trim($_GET['status']) : '';

$scholar_sql = "SELECT * FROM scholars";

$conditions = [];
$params = [];
$types = "";

if($search !== '') {
    $conditions[] = "(fullname LIKE ? OR course LIKE ? OR scholarship_type LIKE ?)";
    $search_param = "%" . $search . "%";

    $params[] = $search_param;
    $params[] = $search_param;
    $params[] = $search_param;

    $types .= "sss";
}

if($status !== '') {
    $conditions[] = "status=?";
    $params[] = $status;
    $types .= "s";
}

if(!empty($conditions)) {
    $scholar_sql .= " WHERE " . implode(" AND ", $conditions);
}

$scholar_sql .= " ORDER BY created_at DESC";

$stmt = $conn->prepare($scholar_sql);

if(!$stmt) die ("DATABASE FAILED. TRY CONTACTING ADMIN.");

if(!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - TVAM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/TVAM_SCHOLARSHIP/assets/css/style.css">
    <link rel="stylesheet" href="/TVAM_SCHOLARSHIP/assets/css/admin.css">
    <link rel="icon" href="../../assets/images/tvamlogo_web.png">
</head>

<body class="admin-page">
<div class="d-flex flex-column flex-md-row min-vh-100">
    <?php include "../../includes/sidebar.php"; ?>

    <main class="container-fluid w-100 mt-4 p-4" style="min-height: 100vh;">

        <div class="card border-0 rounded-1 shadow-sm p-3 px-4 py-4">
            <div class=" text-start align-items-center">
                <h3 class="text-uppercase fs-4">Scholar data</h3>
            </div>
        </div>

        <div class="card border-0 rounded-1 shadow-sm p-3 px-4 py-4 mb-3 mt-3">
            <form action="" method="GET" class="form-group">
                <div class="row g-3 align-items-end justify-content-between">
                    <div class="col-12 col-md-6 col-lg-5">
                        <label for="user" class="form-label text-uppercase text-muted">FIND USER</label>
                        <div class="d-flex flex-md-row gap-2">
                            <input type="text" name="user" class="form-control" placeholder="e.g Juan Pedro" value="<?php echo htmlspecialchars($search);?>">
                            <button type="submit" class="btn btn-secondary">FIND</button>
                        </div>
                    </div>

                    <div class="col-12 col-md-6 col-lg-5 d-flex flex-end justify-content-md-end">
                        <div class="d-flex flex-md-row gap-1" role="tablist">

                            <input 
                                type="hidden"
                                name="status"
                                value="<?php echo htmlspecialchars($status);?>"
                            >

                            <a href="index.php?status=active" class="btn btn-success">Active</a>
                            <a href="index.php?status=inactive" class="btn btn-danger">Inactive</a>
                            <a href="index.php?status=pending" class="btn btn-warning">Pending</a>
                            <a href="index.php" class="btn btn-primary">All</a>

                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="container-fluid mt-3 table-responsive p-2">
            <table class="table table-hover shadow-sm">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>STUDENT_UID</th>
                        <th>Fullname</th>
                        <th>Course</th>
                        <th>Year</th>
                        <th>Scholarship</th>
                        <th>Status</th>
                        <th>Action Buttons</th>
                    </tr>
                </thead>

                <tbody> 
                    <?php if($result->num_rows > 0) { ?>
                    <?php while($row = $result->fetch_assoc()) : ?>
                        <?php 
                            $sanitizeStatus = strtolower($row['status'] ?? '');
                            if($sanitizeStatus === 'inactive') $bg = "bg-danger";
                            elseif($sanitizeStatus === 'active') $bg = "bg-success";
                            else { $bg = "bg-warning"; }
                            
                        ?>
                    <tr>
                        <td> <?php echo $row['id'] ?></td>
                        <td> <?php echo htmlspecialchars($row['student_id']); ?></td>
                        <td> <?php echo htmlspecialchars($row['fullname']); ?></td>
                        <td> <?php echo htmlspecialchars($row['course']); ?></td>
                        <td> <?php echo htmlspecialchars($row['year_level']); ?></td>
                        <td> <?php echo htmlspecialchars($row['scholarship_type']); ?></td>
                        <td>
                            <span class="badge <?php echo $bg; ?> text-uppercase fw-bold px-2 py-2 small">
                                <?php echo htmlspecialchars($row['status']);?>
                            </span>
                        </td>
                        <td class=""> 
                            <a href="edit.php?id=<?php echo $row['id']; ?>" class="badge bg-primary text-decoration-none"> EDIT </a> 
                            <a href="delete.php?id=<?php echo $row['id']; ?>" class="badge bg-danger text-decoration-none"> DELETE </a>
                            <a href="view.php?id=<?php echo $row['id']; ?>" class="badge bg-success text-decoration-none">VIEW</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                    <?php } else { ?>
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">No scholars found.</td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </main>
</div>
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
