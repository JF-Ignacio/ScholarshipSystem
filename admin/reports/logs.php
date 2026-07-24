<?php 

include "../../config/database.php";
include "../../config/admin-auth.php";

$id = $_GET['id'] ?? 0;

$sort = isset($_GET['sort']) ? trim($_GET['sort']) : 'newest';
$nextSort = "";
$signSort = "";

$page_setup = isset($_GET['page']) ? intval($_GET['page']) : 1;

$page_shown_logs = 5; 

if ($page_setup < 1) $page_setup = 1;

$count_query = "SELECT COUNT(*) AS total_logs FROM activity_logs";
$count_stmt = $conn->prepare($count_query);

if(!$count_stmt) die ("database failed. Contact admin");
if(!$count_stmt->execute()) die ("DATABASE FAILED. CONTACT ADMIN.");

$total_activity = $count_stmt->get_result()->fetch_assoc()['total_logs'];
$total_page = max(1, (int)ceil($total_activity/ $page_shown_logs));

if ($page_setup > $total_page) {
    $page_setup = $total_page;
}

$offset = ($page_setup - 1) * $page_shown_logs;

if($sort === 'newest') {
    $signSort .= " ORDER BY created_at DESC";
    $nextSort = "oldest";
}
else {
    $signSort = " ORDER BY created_at ASC";
    $nextSort = "newest";
}

$select_query = "SELECT * FROM activity_logs"
                . $signSort . 
                " LIMIT ? OFFSET ?";
$select_stmt = $conn->prepare($select_query);

if(!$select_stmt) die ("DATABASE FAILED. TRY CONTACTING ADMIN");

$select_stmt->bind_param("ii", $page_shown_logs, $offset);

if(!$select_stmt->execute()) die ("DATABASE FAILED. CONTACT ADMIN");

$results = $select_stmt->get_result();

function paginationLink($pageNumber) {
    $query = $_GET;
    $query['page'] = $pageNumber;
    return 'logs.php?' . http_build_query($query);
}
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

            <div class="mt-4">
                <?php if($results->num_rows > 0) :?>
                <nav aria-label="Page pagination justify-content-center">
                    <ul class="pagination justify-content-center">

                        <li class="page-item <?php echo($page_setup <= 1) ? 'disabled' : ''; ?>">
                            <a href="<?php echo paginationLink($page_setup - 1)?>" class="page-link">
                                PREVIOUS
                            </a>
                        </li>

                        <?php for ($i = 1; $i <= $total_page; $i++) :?>
                            <li class="page-item <?php echo ($i === $page_setup) ? 'active' : ''?>">
                                <a href="<?php echo paginationLink($i)?>" class="page-link">
                                    <?php echo $i; ?>
                                </a>
                            </li>
                        <?php endfor; ?>

                        <li class="page-item <?php echo($page_setup >= $total_page) ? 'disabled' : '' ?>">
                            <a href="<?php echo paginationLink($page_setup + 1)?>" class="page-link">
                                NEXT
                            </a>
                        </li>
                    </ul>
                </nav>

                <?php endif; ?>
            </div>
        </main>
    </div>
</body>
</html>