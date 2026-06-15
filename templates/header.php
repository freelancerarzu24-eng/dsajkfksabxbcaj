<?php
session_start();
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

$current_lang = getLang();
$texts = loadLang($current_lang);
$db = new Database();

// Get Categories for Menu
$db->query("SELECT * FROM categories");
$nav_categories = $db->resultSet();
?>
<!DOCTYPE html>
<html lang="<?php echo e($current_lang); ?> ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ' . SITE_NAME : SITE_NAME; ?></title>

    <!-- SEO Tags -->
    <meta name="description" content="<?php echo $meta_desc ?? 'Your ultimate destination for sports news, live scores, and streaming guides.'; ?>">
    <meta property="og:title" content="<?php echo $page_title ?? SITE_NAME; ?>">
    <meta property="og:description" content="<?php echo $meta_desc ?? 'Sports News and Live Scores'; ?>">
    <meta name="twitter:card" content="summary_large_image">

    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/style.css">
    <script>const SITE_URL = '<?php echo SITE_URL; ?>';</script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="<?php echo SITE_URL; ?>/index.php">
                <i class="fas fa-bolt text-warning"></i> PLAYPULSE
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="<?php echo SITE_URL; ?>/index.php"><?php echo $texts['home']; ?> ?> ?></a></li>
                    <li class="nav-item">
                        <div class="position-relative ms-lg-3">
                            <input type="text" id="liveSearch" class="form-control form-control-sm bg-secondary text-white border-0" placeholder="<?php echo $texts['search']; ?>..." autocomplete="off">
                            <div id="searchSuggestions" class="list-group position-absolute w-100 shadow-lg d-none" style="z-index: 1000; top: 100%;"></div>
                        </div>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="<?php echo SITE_URL; ?>/match-center/index.php"><?php echo $texts['live_scores']; ?> ?> ?></a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown"><?php echo $texts['sports']; ?> ?> ?></a>
                        <ul class="dropdown-menu">
                            <?php foreach($nav_categories as $n_cat): ?>
                                <li><a class="dropdown-item" href="<?php echo SITE_URL; ?>/category/index.php?slug=<?php echo e($n_cat['slug']); ?> ?>"><?php echo e($n_cat['name_' . $current_lang]); ?> ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="<?php echo SITE_URL; ?>/where-to-watch/index.php"><?php echo $texts['streaming']; ?> ?> ?></a></li>
                </ul>
                <div class="d-flex align-items-center">
                    <div class="dropdown me-3">
                        <button class="btn btn-outline-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <?php echo strtoupper($current_lang); ?>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="?lang=en">English</a></li>
                            <li><a class="dropdown-item" href="?lang=bn">Bangla</a></li>
                        </ul>
                    </div>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="<?php echo SITE_URL; ?>/user/profile.php" class="btn btn-warning btn-sm"><?php echo e($_SESSION['username']); ?> ?></a>
                    <?php else: ?>
                        <a href="<?php echo SITE_URL; ?>/user/login.php" class="btn btn-warning btn-sm me-2"><?php echo $texts['login']; ?> ?> ?></a>
                        <a href="<?php echo SITE_URL; ?>/user/register.php" class="btn btn-outline-warning btn-sm"><?php echo $texts['register']; ?> ?> ?></a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>
