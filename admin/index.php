<?php
require_once __DIR__ . '/auth.php';
checkAdmin();
require_once __DIR__ . '/../includes/db.php';

$db = new Database();

// Detailed Stats
$db->query("SELECT SUM(views) as total_views FROM articles");
$total_views = $db->single()['total_views'] ?? 0;

$db->query("SELECT COUNT(*) as count FROM newsletter_subscribers");
$total_subs = $db->single()['count'];

$db->query("SELECT COUNT(*) as count FROM articles");
$total_news = $db->single()['count'];

$db->query("SELECT COUNT(*) as count FROM users");
$total_users = $db->single()['count'];

$db->query("SELECT COUNT(*) as count FROM comments");
$total_comments = $db->single()['count'];

$db->query("SELECT COUNT(*) as count FROM comments WHERE status = 'pending'");
$pending_comments = $db->single()['count'];

// Latest Activity (Articles)
$db->query("SELECT title_en, created_at FROM articles ORDER BY created_at DESC LIMIT 5");
$latest_articles = $db->resultSet();

$page_title = "Dashboard";
$active_page = "dashboard";
require_once __DIR__ . '/includes/header.php';
?>
<div class="row g-4 mb-5">
    <div class="col-md-3">
        <div class="card bg-primary text-white p-3 shadow-sm border-0">
            <div class="d-flex justify-content-between">
                <div><h5>News Views</h5><h3><?php echo number_format($total_views); ?></h3></div>
                <i class="fas fa-eye fa-2x opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white p-3 shadow-sm border-0">
            <div class="d-flex justify-content-between">
                <div><h5>Subscribers</h5><h3><?php echo $total_subs; ?></h3></div>
                <i class="fas fa-envelope fa-2x opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white p-3 shadow-sm border-0">
            <div class="d-flex justify-content-between">
                <div><h5>Users</h5><h3><?php echo $total_users; ?></h3></div>
                <i class="fas fa-users fa-2x opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-dark p-3 shadow-sm border-0">
            <div class="d-flex justify-content-between">
                <div><h5>Comments</h5><h3><?php echo $total_comments; ?></h3></div>
                <i class="fas fa-comments fa-2x opacity-50"></i>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card shadow-sm border-0 p-4">
            <h5 class="mb-4">Latest Published News</h5>
            <div class="list-group list-group-flush">
                <?php foreach($latest_articles as $la): ?>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <?php echo e($la['title_en']); ?>
                        <small class="text-muted"><?php echo date('M d', strtotime($la['created_at'])); ?></small>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-0 p-4">
            <h5 class="mb-4">Quick Actions</h5>
            <div class="d-grid gap-2">
                <a href="news.php?add=1" class="btn btn-primary"><i class="fas fa-plus me-2"></i> Add News</a>
                <a href="matches.php" class="btn btn-outline-dark"><i class="fas fa-trophy me-2"></i> Update Scores</a>
                <a href="comments.php" class="btn btn-outline-danger"><i class="fas fa-check-circle me-2"></i> Moderate Comments (<?php echo $pending_comments; ?>)</a>
            </div>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
