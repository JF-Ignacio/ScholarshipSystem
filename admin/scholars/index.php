<?php

require_once "../../config/admin-auth.php";
require_once "../../config/database.php";

$id = $_SESSION['id'] ?? 0;

$page_shown = 5;

$page_setup =  isset($_GET['page']) ? intval($_GET['page']) : 1;

if($page_setup < 1) $page_setup = 1;

$search = isset($_GET['user']) ? trim($_GET['user']) : '';
$status = isset($_GET['status']) ? trim($_GET['status']) : '';

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

$where_clause = !empty($conditions) ? " WHERE ".implode(" AND ", $conditions) : "";

$count_scholar = "SELECT COUNT(*) AS total_scholars FROM scholars" . $where_clause;
$count_stmt = $conn->prepare($count_scholar);

if(!$count_stmt) die ("DATABASE FAILED. CONTACT ADMIN");

if(!empty($params)) {
    $count_stmt->bind_param($types, ...$params);
}

$count_stmt->execute();
$total_rows = $count_stmt->get_result()->fetch_assoc()['total_scholars'];
$total_page = max(1, (int)ceil($total_rows / $page_shown));

if($page_setup > $total_page) $page_setup = $total_page;

$offset = ($page_setup - 1) * $page_shown;

$scholar_sql = "SELECT * FROM scholars" . $where_clause . " ORDER BY created_at DESC LIMIT  ? OFFSET ?";

$data_params = $params;
$data_types = $types . "ii";
$data_params[] = $page_shown;
$data_params[] = $offset;

$stmt = $conn->prepare($scholar_sql);
if(!$stmt) die ("DATABASE FAILED. CONTACT ADMIN.");

$stmt->bind_param($data_types, ...$data_params);

if(!$stmt->execute()) die ("FAILED TO EXECUTE. CONTACT ADMIN");

$result = $stmt->get_result();

// HELPER
function paginationLink($page_num) {
    $query = $_GET;
    $query['page'] = $page_num;
    return 'index.php?' . http_build_query($query);
}
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

        <div class="card p-2 px-4 py-4 border-0 rounded-1">
            <div class="card-body p-1 d-flex flex-md-column flex-wrap flex-lg-column justify-content-center">
                <div class="row align-items-center">

                    <div class="col-12 col-md-6 col-lg-6">
                        <span class="fw-bold text-uppercase text-muted">Reports</span>
                        <h3 class="text-uppercase fw-bold">SCHOLAR</h3>
                    </div>

                    <div class="col-12 col-md-6 col-lg-12">
                        <p class="badge bg-warning"> TOTAL SCHOLARS: <?php echo $total_rows;?></p>
                    </div>
                </div>
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

        <div>
            <?php if ($total_page > 1) : ?>
            <nav aria-label="Page navigation" class="mt-3">
                <ul class="pagination justify-content-center p-3 px-4 py-4">
                    <li class="page-item <?php echo($page_setup <= 1) ? 'disabled' : ''; ?>">
                        <a  class="page-link" href="<?php echo paginationLink($page_setup - 1); ?>">PREVIOUS</a>
                    </li>
                    
                    <?php for($i = 1; $i <= $total_page; $i++): ?>
                        <li class="page-item" href="<?php echo($i === $page_setup) ? 'disabled' : 'active' ;?>">
                            <a class="page-link" href="<?php echo paginationLink($i); ?>">
                                <span class="p-3 text-dark"><?php echo $i; ?></span>
                            </a>
                        </li>
                    <?php endfor; ?>

                    <li class="page-item <?php echo ($page_setup >= $total_page) ? 'disabled' : '';?>">
                        <a href="<?php echo paginationLink($page_setup + 1);?>" class="page-link">NEXT</a>
                    </li>
                </ul>

                <p class="text-center text-muted small">
                    Showing Page <?php echo $page_setup; ?>
                    of 
                    <?php echo $total_page; ?>
                    (<?php echo $total_rows; ?> total Scholars)
                </p>
            </nav>

            <?php endif; ?>
        </div>
    </main>
</div>
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
