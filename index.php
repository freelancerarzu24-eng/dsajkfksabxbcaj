<?php
$page_title = 'Sports News, Live Scores & Streaming';
require_once __DIR__ . '/templates/header.php';

// Fetch Featured News
$db->query("SELECT * FROM articles WHERE status = 'published' AND is_featured = 1 ORDER BY created_at DESC LIMIT 5");
$featured_news = $db->resultSet();

// Fetch Latest News
$db->query("SELECT a.*, c.name_en as cat_name FROM articles a JOIN categories c ON a.category_id = c.id WHERE a.status = 'published' ORDER BY a.created_at DESC LIMIT 8");
$latest_news = $db->resultSet();

// Fetch Live Matches
$db->query("SELECT m.*, t1.name_en as home, t2.name_en as away, t1.logo as home_logo, t2.logo as away_logo
            FROM matches m
            JOIN teams t1 ON m.team_home_id = t1.id
            JOIN teams t2 ON m.team_away_id = t2.id
            WHERE m.status = 'live' LIMIT 4");
$live_matches = $db->resultSet();
?>

<!-- Breaking News Ticker -->
<div class="breaking-ticker shadow-sm">
    <div class="container d-flex align-items-center">
        <span class="badge bg-danger me-3"><?php echo $texts['breaking_news']; ?></span>
        <marquee onmouseover="this.stop();" onmouseout="this.start();">
            <?php foreach($latest_news as $ln): ?>
                <a href="<?php echo SITE_URL; ?>/news/index.php?slug=<?php echo e($ln['slug']); ?>" class="text-dark text-decoration-none me-5">
                    <i class="fas fa-circle text-danger small me-1"></i> <?php echo e($ln['title_' . $current_lang]); ?>
                </a>
            <?php endforeach; ?>
        </marquee>
    </div>
</div>

<div class="container mt-4">
    <div class="row">
        <!-- Hero Slider (Main Featured) -->
        <div class="col-lg-8">
            <?php if (!empty($featured_news)): ?>
            <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner rounded shadow">
                    <?php foreach($featured_news as $index => $fn): ?>
                    <div class="carousel-item <?php echo $index == 0 ? 'active' : ''; ?>">
                        <img src="<?php echo SITE_URL; ?>/uploads/<?php echo e($fn['featured_image']); ?>" class="d-block w-100" style="height: 450px; object-fit: cover;" alt="...">
                        <div class="carousel-caption d-none d-md-block" style="background: rgba(0,0,0,0.6); bottom: 0; left: 0; right: 0; padding: 20px;">
                            <h3><?php echo e($fn['title_' . $current_lang]); ?></h3>
                            <a href="<?php echo SITE_URL; ?>/news/index.php?slug=<?php echo e($fn['slug']); ?>" class="btn btn-warning"><?php echo $texts['read_more']; ?></a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>
            </div>
            <?php endif; ?>
        </div>

        <!-- Live Scores Sidebar -->
        <div class="col-lg-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-dark text-white d-flex justify-content-between">
                    <h5 class="mb-0"><?php echo $texts['live_scores']; ?></h5>
                    <span class="badge bg-danger badge-live">LIVE</span>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($live_matches)): ?>
                        <p class="p-3 text-center text-muted">No matches live right now.</p>
                    <?php else: ?>
                        <?php foreach($live_matches as $lm): ?>
                        <div class="p-3 border-bottom">
                            <div class="text-center small text-muted mb-2"><?php echo e($lm['tournament_name']); ?></div>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-center" style="width: 40%;">
                                    <img src="<?php echo SITE_URL; ?>/uploads/<?php echo e($lm['home_logo']); ?>" width="30" class="mb-1 d-block mx-auto">
                                    <div class="small fw-bold"><?php echo e($lm['home']); ?></div>
                                </div>
                                <div class="text-center">
                                    <h4 class="mb-0"><?php echo e($lm['score_home']); ?> : <?php echo e($lm['score_away']); ?></h4>
                                </div>
                                <div class="text-center" style="width: 40%;">
                                    <img src="<?php echo SITE_URL; ?>/uploads/<?php echo e($lm['away_logo']); ?>" width="30" class="mb-1 d-block mx-auto">
                                    <div class="small fw-bold"><?php echo e($lm['away']); ?></div>
                                </div>
                            </div>
                            <div class="text-center mt-2">
                                <a href="<?php echo SITE_URL; ?>/match-center/index.php?id=<?php echo e($lm['id']); ?>" class="btn btn-sm btn-outline-primary py-0" style="font-size: 10px;">Details</a>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <div class="p-3 text-center">
                        <a href="<?php echo SITE_URL; ?>/match-center/index.php" class="btn btn-sm btn-dark w-100"><?php echo $texts['upcoming_matches']; ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Latest News Section -->
    <h3 class="mt-5 mb-4 border-start border-warning border-4 ps-3"><?php echo $texts['latest_news']; ?></h3>
    <div class="row g-4">
        <?php foreach($latest_news as $ln): ?>
        <div class="col-md-3">
            <div class="card h-100 news-card shadow-sm">
                <img src="<?php echo SITE_URL; ?>/uploads/<?php echo e($ln['featured_image']); ?>" class="card-img-top news-card-img" alt="..." loading="lazy">
                <div class="card-body">
                    <span class="badge bg-primary mb-2"><?php echo e($ln['cat_name']); ?></span>
                    <h5 class="card-title h6">
                        <a href="<?php echo SITE_URL; ?>/news/index.php?slug=<?php echo e($ln['slug']); ?>" class="text-dark text-decoration-none"><?php echo e($ln['title_' . $current_lang]); ?></a>
                    </h5>
                    <p class="small text-muted mb-0"><?php echo date('M d, Y', strtotime($ln['created_at'])); ?></p>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?php require_once __DIR__ . '/templates/footer.php'; ?>
