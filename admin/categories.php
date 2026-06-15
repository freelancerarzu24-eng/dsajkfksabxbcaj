<?php
require_once __DIR__ . '/auth.php';
checkAdmin();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
validateCSRF();

$db = new Database();
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name_en = trim($_POST['name_en']);
    $name_bn = trim($_POST['name_bn']);
    $slug = slugify($name_en);
    $description = trim($_POST['description']);
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

    if ($id > 0) {
        $db->query("UPDATE categories SET name_en = :name_en, name_bn = :name_bn, slug = :slug, description = :description WHERE id = :id");
        $db->bind(':id', $id);
    } else {
        $db->query("INSERT INTO categories (name_en, name_bn, slug, description) VALUES (:name_en, :name_bn, :slug, :description)");
    }
    $db->bind(':name_en', $name_en);
    $db->bind(':name_bn', $name_bn);
    $db->bind(':slug', $slug);
    $db->bind(':description', $description);
    $db->execute();
    $message = '<div class="alert alert-success">Category saved.</div>';
}

if (isset($_GET['delete'])) {
    $db->query("DELETE FROM categories WHERE id = :id");
    $db->bind(':id', (int)$_GET['delete']);
    $db->execute();
}

$categories = $db->query("SELECT * FROM categories ORDER BY id DESC");
$categories = $db->resultSet();

$page_title = "Manage Categories";
$active_page = "categories";
require_once __DIR__ . '/includes/header.php';
?>
<?php echo e($message); ?>
<div class="row">
    <div class="col-md-4">
        <div class="card p-3">
            <form method="POST"> <?php csrf_field(); ?>
                <input type="hidden" name="id" id="cat_id" value="0">
                <div class="mb-3"><label>Name (EN)</label><input type="text" name="name_en" id="cat_name_en" class="form-control" required></div>
                <div class="mb-3"><label>Name (BN)</label><input type="text" name="name_bn" id="cat_name_bn" class="form-control" required></div>
                <div class="mb-3"><label>Description</label><textarea name="description" id="cat_desc" class="form-control"></textarea></div>
                <button type="submit" class="btn btn-primary">Save</button>
            </form>
        </div>
    </div>
    <div class="col-md-8">
        <table class="table bg-white">
            <thead><tr><th>ID</th><th>Name (EN)</th><th>Name (BN)</th><th>Actions</th></tr></thead>
            <tbody>
                <?php foreach($categories as $cat): ?>
                <tr><td><?php echo e($cat['id']); ?></td><td><?php echo e($cat['name_en']); ?></td><td><?php echo e($cat['name_bn']); ?></td><td><button class="btn btn-sm btn-info" onclick='editCat(<?php echo json_encode($cat); ?>)'>Edit</button></td></tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<script>
function editCat(cat) {
    document.getElementById('cat_id').value = cat.id;
    document.getElementById('cat_name_en').value = cat.name_en;
    document.getElementById('cat_name_bn').value = cat.name_bn;
    document.getElementById('cat_desc').value = cat.description;
}
</script>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
