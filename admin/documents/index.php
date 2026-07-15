<?php 

require_once "../../config/database.php";
require_once "../../config/admin-auth.php";

$id = $_SESSION['id'] ?? 0;

$searchUser = isset($_GET['findUser']) ? trim($_GET['findUser']) : '';


$docu_sql = "SELECT d.document_type, d.file_name, d.status, d.id, d.user_id,
            s.fullname,
            s.course,
            s.status AS scholar_status
            FROM documents d
            INNER JOIN scholars s ON d.user_id = CAST(REPLACE(s.student_id, 'TVAM-', '') AS UNSIGNED)";

if ($searchUser !== '') {
    $docu_sql .= " WHERE s.fullname LIKE ? OR s.student_id LIKE ? OR d.document_type LIKE ?";
}

$docu_sql .= " ORDER BY d.created_at DESC";

$stmt = $conn->prepare($docu_sql);

if (!$stmt) die ("FAILED TO LOAD ENGINE. CONTACT ADMIN");

if ($searchUser !== '') {
    $search_param = "%" . $searchUser . "%";
    $stmt->bind_param("sss", $search_param, $search_param, $search_param);
}

$stmt->execute();
$results = $stmt->get_result();
$total_files = $results->num_rows;



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

            <div class="card border-0 rounded-1 shadow-sm">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 text-center">
                        <thead>
                            <tr>
                                <th>USER_ID</th>
                                <th>FULLNAME</th>
                                <th>DOCUMENT TYPE</th>
                                <th>FILE NAME</th>
                                <th>COURSE</th>
                                <th>SCHOLAR STATUS</th>
                                <th>FILE STATUS</th>
                                <th>ACTION</th>
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
                                    <td data-label="USER_ID"><?php echo htmlspecialchars($doc['user_id']); ?></td>
                                    <td data-label="FULLNAME"><?php echo htmlspecialchars($doc['fullname']); ?></td>
                                    <td data-label="DOCUMENT TYPE"><?php echo htmlspecialchars($doc['document_type']); ?></td>
                                    <td data-label="FILE NAME"><?php echo htmlspecialchars($doc['file_name']); ?></td>
                                    <td data-label="COURSE"><?php echo htmlspecialchars($doc['course']); ?></td>
                                    <td data-label="SCHOLAR STATUS"><?php echo htmlspecialchars($scholar_status); ?></td>
                                    <td data-label="FILE STATUS"><span class="badge bg-<?php echo $badge; ?>"><?php echo htmlspecialchars($status); ?></span></td>
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
        </main>
    </div>





<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 
