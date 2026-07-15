<?php
require_once "../../config/database.php";
require_once "../../config/admin-auth.php";

$search = isset($_GET['user']) ? trim($_GET['user']) : "";
$roleSearch = isset($_GET['role']) ? trim($_GET['role']) : "";


$sql_display = "SELECT id, fullname, email, role, created_at 
                FROM users";

$conditions = [];
$params = [];
$types = "";

if($search !== '') {
    $conditions[] = "(fullname LIKE ? OR email LIKE ? OR role LIKE ?)";
    $search_param = "%" . $search . "%";

    $params[] = $search_param;
    $params[] = $search_param;
    $params[] = $search_param;
    $types .= "sss";
}


if($roleSearch !== '') {
    $conditions[] = "role=?";
    $params[] = $roleSearch;
    $types .= "s";

}

if(!empty($conditions)) {
    $sql_display .= " WHERE " .implode(" AND ", $conditions);
}

$sql_display .= " ORDER BY created_at DESC";

$stmt = $conn->prepare($sql_display);

if (!$stmt) die ("DATABASE FAILED. Try contacting admin.");

if(!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

$users = [];
$adminCount = 0;
$studentCount = 0;

while ($row = $result->fetch_assoc()) {
    $role = strtolower($row['role']);

    if ($role === 'admin') {
        $adminCount++;
    }

    if ($role === 'student') {
        $studentCount++;
    }

    $users[] = $row;
}

$totalUsers = count($users);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TVAM Users List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="/TVAM_SCHOLARSHIP/assets/css/style.css">
    <link rel="stylesheet" href="/TVAM_SCHOLARSHIP/assets/css/admin.css">
    <link rel="icon" href="../../assets/images/tvamlogo_web.png">
</head>

<body class="admin-page admin-report-page">
    <div class="d-flex flex-column flex-md-row min-vh-100">
        <?php include "../../includes/sidebar.php"; ?>

        <main class="container-fluid flex-grow-1 p-3 p-md-4">
            <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3 p-4 mb-4 border shadow-sm bg-white rounded">
                <div>
                    <p class="text-uppercase text-muted small fw-bold mb-1">Reports</p>
                    <h2 class="text-uppercase fw-bold mb-0">TVAM Users</h2>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <span class="badge text-bg-primary report-count-badge">Total: <?php echo $totalUsers; ?></span>
                    <span class="badge text-bg-warning report-count-badge">Admins: <?php echo $adminCount; ?></span>
                    <span class="badge text-bg-info report-count-badge">Students: <?php echo $studentCount; ?></span>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-2 mt-1 p-3 px-4 py-4">
                <form action="" method="GET" class="form-group">
                    <div class="row mt-3 align-items-center g-2">
                        <div class="col-12 col-md-6 col-lg-8">
                            <label for="user" class="form-label text-uppercase">FIND USER</label>
                            <div class="d-flex flex-column flex-md-row gap-2">
                                <input type="text" name="user" class="form-control" placeholder="e.g Juan Pedro" value="<?php echo htmlspecialchars($search); ?>">
                                <button type="submit" value="findUser" name="find" class="btn btn-secondary w-25 w-md-auto">FIND</button>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 col-lg-4 text-start text-md-end">
                            <input 
                                type="hidden" 
                                name="role" 
                                value="<?php echo htmlspecialchars($roleSearch); ?>">
                                
                            <div class="d-flex flex-wrap gap-2 justify-content-start justify-content-md-end user-search-actions">
                                <a href="index-user.php" class="btn btn-primary">ALL</a>
                                <a href="index-user.php?role=student" class="btn btn-info text-uppercase">STUDENT</a>
                                <a href="index-user.php?role=admin" class="btn btn-warning text-uppercase">ADMIN</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="bg-white border rounded shadow-sm p-3 mt-3">
                <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-2 mb-3">
                    <div>
                        <h5 class="fw-bold mb-1">Account Directory</h5>
                        <p class="text-muted mb-0 small">Registered accounts sorted by creation date.</p>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 admin-responsive-table">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Account Created</th>
                        </tr>
                        </thead>
                    
                        <tbody>
                            <?php if ($totalUsers > 0) {
                                foreach ($users as $row) {
                                    $role = strtolower($row['role']);
                                    $badgeClass = "text-bg-secondary";

                                    if ($role === 'admin') {
                                        $badgeClass = "text-bg-warning";
                                    }

                                    if ($role === 'student') {
                                        $badgeClass = "text-bg-info";
                                    }

                                    $createdAt = !empty($row['created_at']) ? date("M d, Y h:i A", strtotime($row['created_at'])) : "N/A";
                            ?>
                            <tr>
                                <td data-label="ID" class="fw-bold"><?php echo htmlspecialchars($row['id']); ?></td>
                                <td data-label="Full Name"><?php echo htmlspecialchars($row['fullname']); ?></td>
                                <td data-label="Email">
                                    <a class="text-decoration-none" href="mailto:<?php echo htmlspecialchars($row['email']); ?>">
                                        <?php echo htmlspecialchars($row['email']); ?>
                                    </a>
                                </td>
                                <td data-label="Role">
                                    <span class="badge <?php echo $badgeClass; ?> text-uppercase px-3 py-2">
                                        <?php echo htmlspecialchars($row['role']); ?>
                                    </span>
                                </td>
                                <td data-label="Account Created"><?php echo htmlspecialchars($createdAt); ?></td>
                            </tr>
                            <?php }
                            } else { ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-5">No users found in the database.</td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>

    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
