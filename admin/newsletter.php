<?php
require_once __DIR__ . '/auth.php';
checkAdmin();
require_once __DIR__ . '/../includes/db.php';

$db = new Database();

$subscribers = $db->query("SELECT * FROM newsletter_subscribers ORDER BY created_at DESC");
$subscribers = $db->resultSet();

if (isset($_GET['export'])) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="subscribers.csv"');
    $output = fopen('php://output', 'w');
    fputcsv($output, ['ID', 'Email', 'Subscribed At']);
    foreach ($subscribers as $row) {
        fputcsv($output, [$row['id'], $row['email'], $row['created_at']]);
    }
    fclose($output);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Newsletter - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --sidebar-width: 250px; }
        .sidebar { width: var(--sidebar-width); height: 100vh; position: fixed; background: #1a237e; color: white; }
        .main-content { margin-left: var(--sidebar-width); padding: 20px; }
        .nav-link { color: rgba(255,255,255,0.8); }
        .nav-link:hover, .nav-link.active { color: white; background: rgba(255,255,255,0.1); }
    </style>
</head>
<body>
    <div class="sidebar d-flex flex-column p-3">
        <h4>PlayPulse</h4>
        <hr>
        <ul class="nav nav-pills flex-column mb-auto">
            <li><a href="index.php" class="nav-link"><i class="fas fa-tachometer-alt me-2"></i> Dashboard</a></li>
            <li><a href="categories.php" class="nav-link"><i class="fas fa-list me-2"></i> Categories</a></li>
            <li><a href="news.php" class="nav-link"><i class="fas fa-newspaper me-2"></i> News</a></li>
            <li><a href="matches.php" class="nav-link"><i class="fas fa-trophy me-2"></i> Matches</a></li>
            <li><a href="teams.php" class="nav-link"><i class="fas fa-users me-2"></i> Teams</a></li>
            <li><a href="players.php" class="nav-link"><i class="fas fa-user-ninja me-2"></i> Players</a></li>
            <li><a href="streaming.php" class="nav-link"><i class="fas fa-play-circle me-2"></i> Streaming</a></li>
            <li><a href="ads.php" class="nav-link"><i class="fas fa-ad me-2"></i> Advertisements</a></li>
            <li><a href="comments.php" class="nav-link"><i class="fas fa-comments me-2"></i> Comments</a></li>
            <li><a href="newsletter.php" class="nav-link active"><i class="fas fa-envelope me-2"></i> Newsletter</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="d-flex justify-content-between mb-3">
            <h3>Newsletter Subscribers</h3>
            <a href="?export=1" class="btn btn-success">Export CSV</a>
        </div>
        <table class="table table-bordered bg-white">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Email</th>
                    <th>Subscribed At</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($subscribers as $s): ?>
                <tr>
                    <td><?php echo e($s['id']); ?> ?></td>
                    <td><?php echo e($s['email']); ?> ?></td>
                    <td><?php echo e($s['created_at']); ?> ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
