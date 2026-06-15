<?php
require_once __DIR__ . '/../auth.php';
checkAdmin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'Admin Dashboard'; ?> - PlayPulse</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.ckeditor.com/4.21.0/standard/ckeditor.js"></script>
    <style>
        :root { --sidebar-width: 250px; }
        body { background-color: #f4f7f6; }
        .sidebar { width: var(--sidebar-width); height: 100vh; position: fixed; background: #1a237e; color: white; overflow-y: auto; }
        .main-content { margin-left: var(--sidebar-width); padding: 20px; }
        .nav-link { color: rgba(255,255,255,0.8); }
        .nav-link:hover, .nav-link.active { color: white; background: rgba(255,255,255,0.1); }
    </style>
</head>
<body>
    <div class="sidebar d-flex flex-column p-3">
        <h4 class="text-center fw-bold">PLAYPULSE</h4>
        <hr>
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item"><a href="index.php" class="nav-link <?php echo $active_page == 'dashboard' ? 'active' : ''; ?>"><i class="fas fa-tachometer-alt me-2"></i> Dashboard</a></li>
            <li><a href="categories.php" class="nav-link <?php echo $active_page == 'categories' ? 'active' : ''; ?>"><i class="fas fa-list me-2"></i> Categories</a></li>
            <li><a href="news.php" class="nav-link <?php echo $active_page == 'news' ? 'active' : ''; ?>"><i class="fas fa-newspaper me-2"></i> News</a></li>
            <li><a href="matches.php" class="nav-link <?php echo $active_page == 'matches' ? 'active' : ''; ?>"><i class="fas fa-trophy me-2"></i> Matches</a></li>
            <li><a href="teams.php" class="nav-link <?php echo $active_page == 'teams' ? 'active' : ''; ?>"><i class="fas fa-users me-2"></i> Teams</a></li>
            <li><a href="players.php" class="nav-link <?php echo $active_page == 'players' ? 'active' : ''; ?>"><i class="fas fa-user-ninja me-2"></i> Players</a></li>
            <li><a href="streaming.php" class="nav-link <?php echo $active_page == 'streaming' ? 'active' : ''; ?>"><i class="fas fa-play-circle me-2"></i> Streaming</a></li>
            <li><a href="broadcasters.php" class="nav-link <?php echo $active_page == 'broadcasters' ? 'active' : ''; ?>"><i class="fas fa-tower-broadcast me-2"></i> Broadcasters</a></li>
            <li><a href="ads.php" class="nav-link <?php echo $active_page == 'ads' ? 'active' : ''; ?>"><i class="fas fa-ad me-2"></i> Advertisements</a></li>
            <li><a href="comments.php" class="nav-link <?php echo $active_page == 'comments' ? 'active' : ''; ?>"><i class="fas fa-comments me-2"></i> Comments</a></li>
            <li><a href="newsletter.php" class="nav-link <?php echo $active_page == 'newsletter' ? 'active' : ''; ?>"><i class="fas fa-envelope me-2"></i> Newsletter</a></li>
            <li><a href="notifications.php" class="nav-link <?php echo $active_page == 'notifications' ? 'active' : ''; ?>"><i class="fas fa-bell me-2"></i> Notifications</a></li>
        </ul>
        <hr>
        <div class="dropdown">
            <a href="?logout=1" class="nav-link text-danger"><i class="fas fa-sign-out-alt me-2"></i> Logout</a>
        </div>
    </div>
    <div class="main-content">
        <header class="d-flex justify-content-between align-items-center mb-4">
            <h2><?php echo $page_title ?? 'Dashboard'; ?></h2>
            <div>Welcome, <strong><?php echo e($_SESSION['admin_username']); ?> ?></strong></div>
        </header>
