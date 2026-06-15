<?php
require_once __DIR__ . '/../templates/header.php';

$slug = $_GET['slug'] ?? '';
$db->query("SELECT a.*, c.name_en as cat_name, c.slug as cat_slug, ad.full_name as author
            FROM articles a
            JOIN categories c ON a.category_id = c.id
            JOIN admins ad ON a.admin_id = ad.id
            WHERE a.slug = :slug AND a.status = 'published'");
$db->bind(':slug', $slug);
$article = $db->single();

if (!$article) {
    echo "<div class='container mt-5'><div class='alert alert-danger'>Article not found.</div></div>";
    require_once __DIR__ . '/../templates/footer.php';
    exit;
}

// Increment View Counter
$db->query("UPDATE articles SET views = views + 1 WHERE id = :id");
$db->bind(':id', $article['id']);
$db->execute();

// Fetch Related News
$db->query("SELECT * FROM articles WHERE category_id = :cat_id AND id != :id AND status = 'published' LIMIT 4");
$db->bind(':cat_id', $article['category_id']);
$db->bind(':id', $article['id']);
$related_news = $db->resultSet();

$page_title = $article['title_' . $current_lang];
?>

<div class="container mt-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo SITE_URL; ?>/index.php"><?php echo $texts['home']; ?></a></li>
            <li class="breadcrumb-item"><a href="<?php echo SITE_URL; ?>/category/index.php?slug=<?php echo e($article['cat_slug']); ?>"><?php echo e($article['cat_name']); ?></a></li>
            <li class="breadcrumb-item active"><?php echo e($article['title_' . $current_lang]); ?></li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-8">
            <h1 class="fw-bold mb-3"><?php echo e($article['title_' . $current_lang]); ?></h1>
            <div class="d-flex align-items-center mb-4 text-muted small">
                <span class="me-3"><i class="fas fa-user me-1"></i> <?php echo e($article['author']); ?></span>
                <span class="me-3"><i class="fas fa-calendar-alt me-1"></i> <?php echo date('M d, Y', strtotime($article['created_at'])); ?></span>
                <span><i class="fas fa-eye me-1"></i> <?php echo e($article['views']); ?> Views</span>
            </div>

            <img src="<?php echo SITE_URL; ?>/uploads/<?php echo e($article['featured_image']); ?>" class="img-fluid rounded mb-4 w-100" style="max-height: 500px; object-fit: cover;">

            <div class="article-content fs-5" style="line-height: 1.8;">
                <?php echo e($article['content_' . $current_lang]); ?>
            </div>

            <!-- Social Share -->
            <div class="mt-5 p-3 border-top border-bottom d-flex align-items-center">
                <span class="fw-bold me-3">Share:</span>
                <a href="#" class="btn btn-primary btn-sm me-2"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="btn btn-info btn-sm me-2 text-white"><i class="fab fa-twitter"></i></a>
                <a href="#" class="btn btn-danger btn-sm me-2"><i class="fab fa-pinterest"></i></a>
                <a href="#" class="btn btn-success btn-sm"><i class="fab fa-whatsapp"></i></a>
            </div>

            <!-- Comments Section -->
            <div class="mt-5">
                <h4><?php echo $texts['comments']; ?></h4>
                <div class="card p-3 mb-4">
                    <form action="../includes/post_comment.php" method="POST"> <?php csrf_field(); ?>
                        <input type="hidden" name="article_id" value="<?php echo e($article['id']); ?>">
                        <div class="mb-3">
                            <textarea name="comment" class="form-control" rows="3" placeholder="Write a comment..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-warning"><?php echo $texts['post_comment']; ?></button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Related News Sidebar -->
            <h4 class="mb-4"><?php echo $texts['related_news']; ?></h4>
            <?php foreach($related_news as $rn): ?>
            <div class="d-flex mb-3">
                <img src="<?php echo SITE_URL; ?>/uploads/<?php echo e($rn['featured_image']); ?>" width="100" height="70" class="rounded me-3" style="object-fit: cover;">
                <div>
                    <h6 class="mb-1"><a href="<?php echo SITE_URL; ?>/news/index.php?slug=<?php echo e($rn['slug']); ?>" class="text-dark text-decoration-none"><?php echo e($rn['title_' . $current_lang]); ?></a></h6>
                    <small class="text-muted"><?php echo date('M d, Y', strtotime($rn['created_at'])); ?></small>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>
