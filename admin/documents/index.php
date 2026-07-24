<?php 

require_once "../../config/database.php";
require_once "../../config/admin-auth.php";

$id = $_SESSION['id'] ?? 0;

$searchUser = isset($_GET['findUser']) ? trim($_GET['findUser']) : '';
$page_setup = isset($_GET['page']) ? intval($_GET['page']) : 1;

if($page_setup < 1) $page_setup = 1;

$page_shown_files = 5;

$conditions = [];
$params = [];
$types = "";

if($searchUser !== '') {
    $conditions[] = ("s.fullname LIKE ? OR s.student_id LIKE ? OR d.document_type LIKE ?");
    $search_params = "%" . $searchUser . "%";

    $params[] = $search_params;
    $params[] = $search_params;
    $params[] = $search_params;

    $types .= "sss";
}

$where_clasue = !empty($conditions) ?" WHERE " .implode(" AND ", $conditions) : '';

$count_query = "SELECT COUNT(*) AS total_documents 
                FROM documents" . $where_clasue;
$count_stmt = $conn->prepare($count_query);

if(!$count_stmt) die ("DATABASE FAILED. CONTACT ADMIN");

if(!empty($params)) {
    $count_stmt->bind_param($types, ...$params);
}

$count_stmt->execute();
$total_documents = $count_stmt->get_result()->fetch_assoc()['total_documents'];
$total_page = max(1, (int)ceil($total_documents / $page_shown_files));

if($page_setup > $total_page) {
    $page_setup = $total_page;
}

$offset = ($page_setup - 1) * $page_shown_files;

$data_params = $params;
$data_types = $types . "ii";
$data_params[] = $page_shown_files;
$data_params[] = $offset;

$docu_sql = "SELECT d.document_type, d.file_name, d.status, d.id, d.user_id, d.download_token,
            s.fullname,
            s.course,
            s.status AS scholar_status
            FROM documents d
            INNER JOIN scholars s ON d.user_id = CAST(REPLACE(s.student_id, 'TVAM-', '') AS UNSIGNED)"
            . $where_clasue .
            "LIMIT ?
            OFFSET ?"; 

$stmt = $conn->prepare($docu_sql);

if(!$stmt) die ("DATABASE FAILED. CONTACT ADMIN");

$stmt->bind_param($data_types, ...$data_params);
$stmt->execute();
$results = $stmt->get_result();
$total_files = $results->num_rows;

function paginationLink($pageNumber) {
    $query = $_GET;
    $query['page'] = $pageNumber;

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

        <main class="container-fluid d-flex flex-column p-4 px-md-5 py-5">
            <div class="card border-0 rounded-1 shadow-sm p-4 mb-4">
                <div class="row align-items-center mx-0 g-3">
                    <div class="col-12 col-md-6">
                        <h3 class="text-uppercase">FILE MANAGEMENT</h3>
                        <p class="text-muted fst-italic">All files from the student are manage in this page.</p>
                    </div>

                    <div class="col-12 col-md-6 text-md-end">
                        <h3 class="badge bg-warning">TOTAL FILES: <?php echo htmlspecialchars($total_files); ?> </h3>
                    </div>
                </div>
            </div>

            <div class="card border-0 rounded-1 shadow-sm mb-3 p-3 px-4 py-4">
                <form action="" method="GET" class="row align-items-end g-3 form-group">
                    <div>
                        <label for="findUser" class="form-label">Find User</label>
                        <div class="d-flex flex-row gap-3">
                            <input type="text" name="findUser" class="form-control w-25">
                            <button type="submit" value="findButton" name="findButton" class="btn btn-secondary fw-bold px-4 py-1">Find</button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="table-responsive bg-white border rounded shadow-sm p-3">
                <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-2 mb-3">
                    <div>
                        <h5 class="fw-bold mb-1">Document Directory</h5>
                        <p class="text-muted mb-0 small">Submitted scholar documents sorted by upload date.</p>
                    </div>
                </div>

                <div class="admin-table-scroll">
                    <table class="table table-hover align-middle mb-0 admin-responsive-table">
                        <thead class="text-center table-dark">
                            <tr class="align-items-center text-center">
                                <th class="p-3 p-sm-4">USER_ID</th>
                                <th class="p-3 p-sm-4">FULLNAME</th>
                                <th class="p-3 p-sm-4">DOCUMENT TYPE</th>
                                <th class="p-3 p-sm-4">FILE NAME</th>
                                <th class="p-3 p-sm-4">COURSE</th>
                                <th class="p-3 p-sm-4">SCHOLAR STATUS</th>
                                <th class="p-3 p-sm-4">FILE STATUS</th>
                                <th class="p-3 p-sm-4">ACTION</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php if ($total_files > 0): ?>
                                <?php while ($doc = $results->fetch_assoc()) :
                                $status = $doc['status'];
                                $scholar_status = $doc['scholar_status'];
                                $badge = ($status === 'Approved') ? 'success' : (($status === 'Rejected') ? 'danger' : 'warning');
                                ?>

                                <tr>
                                    <td data-label="USER_ID" class="text-center"><?php echo htmlspecialchars($doc['user_id']); ?></td>
                                    <td data-label="FULLNAME" class="text-center"><?php echo htmlspecialchars($doc['fullname']); ?></td>
                                    <td data-label="DOCUMENT TYPE" class="text-center"><?php echo htmlspecialchars($doc['document_type']); ?></td>
                                    <td data-label="FILE NAME" class="text-center">
                                        <a href="/TVAM_SCHOLARSHIP/shared/download.php?token=<?php echo htmlspecialchars($doc['download_token']); ?>" target="_blank" class="btn btn-sm btn-link">
                                            <i class="bi bi-file-earmark-text me-1"></i>
                                            <?php echo htmlspecialchars($doc['file_name']); ?>
                                        </a>
                                    </td>
                                    <td data-label="COURSE" class="text-center"><?php echo htmlspecialchars($doc['course']); ?></td>
                                    <td data-label="SCHOLAR STATUS" class="text-center"><?php echo htmlspecialchars($scholar_status); ?></td>
                                    <td data-label="FILE STATUS" class="text-center"><span class="badge bg-<?php echo $badge; ?>"><?php echo htmlspecialchars($status); ?></span></td>
                                    <td data-label="ACTION" class="action-cell">
                                        <div class="document-actions">
                                            <a href="verify.php?id=<?php echo $doc['id'];?>" class="btn btn-success btn-sm fw-bold document-action-btn">APPROVED</a>
                                            <a href="reject.php?id=<?php echo $doc['id']; ?>" class="btn btn-danger btn-sm fw-bold document-action-btn">REJECT</a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-4">
                <?php if($results->num_rows > 0) : ?>
                <nav aria-label="page pagination">
                    <ul class="pagination justify-content-center">
                        <li class="page-item <?php echo ($page_setup <= 1) ? 'disabled' : '';?>">
                            <a href="<?php echo paginationLink($page_setup - 1); ?>" class="page-link">
                                PREVIOUS
                            </a>
                        </li>

                        <?php for($i = 1; $i <= $total_page; $i++) :?>
                            <li class="page-item <?php echo ($i === $page_setup) ? 'active' : '' ?>">
                                <a href="<?php echo paginationLink($i); ?>" class="page-link">
                                    <?php echo $i; ?>
                                </a>
                            </li>
                        <?php endfor; ?>

                        <li class="page-item <?php echo ($page_setup >= $total_page) ? 'disabled' : '' ?>">
                            <a href="<?php echo paginationLink($page_setup + 1); ?>" class="page-link">
                                NEXT
                            </a>
                        </li>
                    </ul>

                    <p class="text-muted text-center small">
                        Showing Page <?php echo $page_setup; ?>
                        of 
                        <?php echo $total_page; ?>
                        (<?php echo $total_documents ?> TOTAL DOCUMENTS)
                    </p>
                </nav>
                <?php endif; ?>
            </div>
        </main>
    </div>





<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 
