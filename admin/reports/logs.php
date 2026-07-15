<?php 
include "../../config/database.php";
include "../../config/admin-auth.php";

$id = $_GET['id'] ?? 0;

$sort = isset($_GET['sort']) ? trim($_GET['sort']) : 'newest';
$nextSort = "";
$signSort = "";

$logs_sql = "SELECT l.id, l.actions, l.created_at, u.fullname, u.email, a.status, a.scholarship_type
            FROM activity_logs l
            INNER JOIN users u ON l.user_id = u.id
            LEFT JOIN applications a ON l.user_id = a.user_id";

if($sort === 'oldest') {
    $logs_sql .= " ORDER BY l.created_at ASC";
    $nextSort = 'newest';
} else {
    $logs_sql .= " ORDER BY l.created_at DESC";
    $nextSort = 'oldest';
}

$results = $conn->query($logs_sql);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applications - TVAM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="/TVAM_SCHOLARSHIP/assets/css/style.css">
    <link rel="stylesheet" href="/TVAM_SCHOLARSHIP/assets/css/admin.css">
    <link rel="icon" href="/TVAM_SCHOLARSHIP/assets/images/tvamlogo_web.png">
</head>
<body class="admin-page admin-report-page">
    <div class="container-fluid min-vh-100 d-flex flex-column flex-md-row bg-light p-0">
        <?php include "../../includes/sidebar.php"; ?>

        <main class="d-flex flex-column flex-grow-1 w-100 p-4 px-5 py-5">
            <div class="row d-flex flex-row p-4 align-items-center justify-content-between">
                <div class="col-12 col-md-6 col-lg-4">
                    <h3 class="fw-bold text-dark fs-3 text-uppercase">Admin Activity Logs</h3>
                    <p class="text-muted">All activites are listed and recorder by the admin</p>
                </div>

                <div class="col-12 col-md-6 col-lg-4 text-end">
                    <form action="" method="GET">
                        <a href="logs.php?sort=<?php echo htmlspecialchars($nextSort); ?>" class="btn btn-secondary">
                            <?php if ($nextSort === 'newest') $signSort = "▼";
                                else $signSort = "▲";
                            ?>

                            SORT <?php echo $signSort; ?>
                        </a>
                    </form>
                </div>
            </div>

            <div class="card d-flex shadow-sm rounded-3 border-0 overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="text-center table-dark">
                            <tr class="fs-5 align-items-center text-center">
                                <th class="p-3 p-sm-4">ID</th>
                                <th class="p-3 p-sm-4">ACTIONS</th>
                                <th class="p-3 p-sm-3">CREATED AT</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if($results->num_rows > 0) {?>
                            <?php while($row = $results->fetch_assoc()) { ?>
                            <?php $actions = strtolower($row['actions']);
                                    $bg = "";
                    
                                    if (str_contains($actions, 'updated')) { $bg = "warning"; }
                                    elseif(str_contains($actions, "view")) { $bg = "secondary"; }
                                    elseif(str_contains($actions, "approved")) {$bg = "success"; }
                                    elseif(str_contains($actions, "rejected")) {$bg = "danger"; }
                                    else { $bg = "primary"; }
                            ?>
                            <tr>
                                <td class="text-mutedtext-center bg-<?php echo $bg; ?>"><?php echo htmlspecialchars($row['id']); ?></td>
                                <td class="text-center justify-content-center bg-<?php echo $bg; ?>"><?php echo htmlspecialchars($row['actions']); ?></td>
                                <td class=" text-center bg-<?php echo $bg; ?>"><?php echo htmlspecialchars($row['created_at']);?></td>
                            </tr>
                            <?php } ?>
                        <?php } else {?>
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4">No logs activity</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</body>
</html>