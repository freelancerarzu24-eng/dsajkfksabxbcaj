<?php
require_once __DIR__ . '/auth.php';
checkAdmin();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
validateCSRF();

$db = new Database();
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_article'])) {
    $id = (int)$_POST['id'];
    $category_id = (int)$_POST['category_id'];
    $title_en = trim($_POST['title_en']);
    $title_bn = trim($_POST['title_bn']);
    $slug = slugify($title_en);
    $content_en = $_POST['content_en'];
    $content_bn = $_POST['content_bn'];
    $status = $_POST['status'];
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    $admin_id = $_SESSION['admin_id'];

    $featured_image = $_POST['existing_image'];
    if (!empty($_FILES['featured_image']['name'])) {
        $upload = uploadImage($_FILES['featured_image']);
        if ($upload['success']) $featured_image = $upload['file_name'];
    }

    if ($id > 0) {
        $db->query("UPDATE articles SET category_id = :category_id, title_en = :title_en, title_bn = :title_bn, slug = :slug, content_en = :content_en, content_bn = :content_bn, status = :status, is_featured = :is_featured, featured_image = :featured_image WHERE id = :id");
        $db->bind(':id', $id);
    } else {
        $db->query("INSERT INTO articles (admin_id, category_id, title_en, title_bn, slug, content_en, content_bn, status, is_featured, featured_image) VALUES (:admin_id, :category_id, :title_en, :title_bn, :slug, :content_en, :content_bn, :status, :is_featured, :featured_image)");
        $db->bind(':admin_id', $admin_id);
    }
    $db->bind(':category_id', $category_id);
    $db->bind(':title_en', $title_en);
    $db->bind(':title_bn', $title_bn);
    $db->bind(':slug', $slug);
    $db->bind(':content_en', $content_en);
    $db->bind(':content_bn', $content_bn);
    $db->bind(':status', $status);
    $db->bind(':is_featured', $is_featured);
    $db->bind(':featured_image', $featured_image);
    $db->execute();
    $message = '<div class="alert alert-success">Article saved.</div>';
}

if (isset($_GET['delete'])) {
    $db->query("DELETE FROM articles WHERE id = :id");
    $db->bind(':id', (int)$_GET['delete']);
    $db->execute();
}

$articles = $db->query("SELECT a.*, c.name_en as cat_name FROM articles a JOIN categories c ON a.category_id = c.id ORDER BY a.created_at DESC");
$articles = $db->resultSet();
$categories = $db->query("SELECT id, name_en FROM categories");
$categories = $db->resultSet();

$page_title = "Manage News";
$active_page = "news";
require_once __DIR__ . '/includes/header.php';
?>
<?php echo e($message); ?> ?>
<?php if (isset($_GET['add']) || isset($_GET['edit'])):
    $edit_article = null;
    if (isset($_GET['edit'])) {
        $db->query("SELECT * FROM articles WHERE id = :id");
        $db->bind(':id', (int)$_GET['edit']);
        $edit_article = $db->single();
    }
?>
    <div class="card p-4">
        <form method="POST" enctype="multipart/form-data"> <?php csrf_field(); ?>
            <input type="hidden" name="id" value="<?php echo $edit_article['id'] ?? 0; ?>">
            <input type="hidden" name="existing_image" value="<?php echo $edit_article['featured_image'] ?? ''; ?>">
            <div class="row">
                <div class="col-md-6 mb-3"><label>Category</label><select name="category_id" class="form-select"><?php foreach($categories as $cat) echo "<option value='{$cat['id']}'>{$cat['name_en']}</option>"; ?></select></div>
                <div class="col-md-6 mb-3"><label>Status</label><select name="status" class="form-select"><option value="draft">Draft</option><option value="published">Published</option></select></div>
            </div>
            <div class="mb-3"><label>Title (EN)</label><input type="text" name="title_en" class="form-control" value="<?php echo e($edit_article['title_en'] ?? ''); ?>"></div>
            <div class="mb-3"><label>Title (BN)</label><input type="text" name="title_bn" class="form-control" value="<?php echo e($edit_article['title_bn'] ?? ''); ?>"></div>
            <div class="mb-3"><label>Content (EN)</label><textarea name="content_en" id="editor_en"><?php echo $edit_article['content_en'] ?? ''; ?></textarea></div>
            <div class="mb-3"><label>Content (BN)</label><textarea name="content_bn" id="editor_bn"><?php echo $edit_article['content_bn'] ?? ''; ?></textarea></div>
            <div class="mb-3"><label>Featured Image</label><input type="file" name="featured_image" class="form-control"></div>
            <button type="submit" name="save_article" class="btn btn-primary">Save</button>
        </form>
    </div>
    <script>CKEDITOR.replace('editor_en'); CKEDITOR.replace('editor_bn');</script>
<?php else: ?>
    <a href="?add=1" class="btn btn-primary mb-3">Add News</a>
    <table class="table bg-white">
        <thead><tr><th>Image</th><th>Title</th><th>Category</th><th>Actions</th></tr></thead>
        <tbody>
            <?php foreach($articles as $art): ?>
            <tr>
                <td><img src="../uploads/<?php echo e($art['featured_image']); ?> ?>" width="50"></td>
                <td><?php echo e($art['title_en']); ?></td>
                <td><?php echo e($art['cat_name']); ?> ?></td>
                <td><a href="?edit=<?php echo e($art['id']); ?> ?>" class="btn btn-sm btn-info">Edit</a></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
