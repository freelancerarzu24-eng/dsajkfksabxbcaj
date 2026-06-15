<?php
require_once __DIR__ . '/../templates/header.php';

$slug = $_GET['slug'] ?? '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 9;
$offset = ($page - 1) * $limit;

$db->query("SELECT * FROM categories WHERE slug = :slug");
$db->bind(':slug', $slug);
$category = $db->single();

if (!$category) {
    echo "<div class='container mt-5'><div class='alert alert-danger'>Category not found.</div></div>";
    require_once __DIR__ . '/../templates/footer.php';
    exit;
}

// Get Total Articles for Pagination
$db->query("SELECT COUNT(*) as count FROM articles WHERE category_id = :cat_id AND status = 'published'");
$db->bind(':cat_id', $category['id']);
$total_articles = $db->single()['count'];
$total_pages = ceil($total_articles / $limit);

$db->query("SELECT * FROM articles WHERE category_id = :cat_id AND status = 'published' ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
$db->bind(':cat_id', $category['id']);
$db->bind(':limit', $limit);
$db->bind(':offset', $offset);
$articles = $db->resultSet();

$page_title = $category['name_' . $current_lang];
?>

<div class="container mt-5">
    <h2 class="fw-bold mb-4 border-start border-warning border-4 ps-3"><?php echo e($category['name_' . $current_lang]); ?></h2>

    <div class="row g-4">
        <?php if (empty($articles)): ?>
            <p class="text-center py-5">No news available in this category.</p>
        <?php else: ?>
            <?php foreach($articles as $art): ?>
            <div class="col-md-4">
                <div class="card h-100 news-card shadow-sm">
                    <img src="../uploads/<?php echo $art['featured_image']; ?>" class="card-img-top news-card-img" alt="..." loading="lazy">
                    <div class="card-body">
                        <h5 class="card-title">
                            <a href="../news/index.php?slug=<?php echo $art['slug']; ?>" class="text-dark text-decoration-none"><?php echo e($art['title_' . $current_lang]); ?></a>
                        </h5>
                        <p class="small text-muted mb-0"><?php echo date('M d, Y', strtotime($art['created_at'])); ?></p>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Pagination -->
    <?php if ($total_pages > 1): ?>
    <nav class="mt-5">
        <ul class="pagination justify-content-center">
            <?php for($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                    <a class="page-link" href="?slug=<?php echo $slug; ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>
