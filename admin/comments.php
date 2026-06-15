<?php
require_once __DIR__ . '/auth.php';
checkAdmin();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

$db = new Database();
$message = '';

if (isset($_GET['approve'])) {
    $db->query("UPDATE comments SET status = 'approved' WHERE id = :id");
    $db->bind(':id', (int)$_GET['approve']);
    $db->execute();
}

if (isset($_GET['delete'])) {
    $db->query("DELETE FROM comments WHERE id = :id");
    $db->bind(':id', (int)$_GET['delete']);
    $db->execute();
}

$comments = $db->query("SELECT c.*, a.title_en as article_title FROM comments c JOIN articles a ON c.article_id = a.id ORDER BY c.created_at DESC");
$comments = $db->resultSet();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Comments - Admin</title>
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
            <li><a href="comments.php" class="nav-link active"><i class="fas fa-comments me-2"></i> Comments</a></li>
            <li><a href="newsletter.php" class="nav-link"><i class="fas fa-envelope me-2"></i> Newsletter</a></li>
        </ul>
    </div>

    <div class="main-content">
        <h3>Manage Comments</h3>
        <table class="table table-bordered bg-white">
            <thead class="table-dark">
                <tr>
                    <th>Article</th>
                    <th>User</th>
                    <th>Comment</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($comments as $c): ?>
                <tr>
                    <td><?php echo e($c['article_title']); ?></td>
                    <td><?php echo e($c['name']); ?></td>
                    <td><?php echo e($c['comment']); ?></td>
                    <td><span class="badge bg-<?php echo $c['status'] == 'approved' ? 'success' : 'warning'; ?>"><?php echo ucfirst($c['status']); ?></span></td>
                    <td>
                        <?php if($c['status'] == 'pending'): ?>
                            <a href="?approve=<?php echo e($c['id']); ?>" class="btn btn-sm btn-success">Approve</a>
                        <?php endif; ?>
                        <a href="?delete=<?php echo e($c['id']); ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
