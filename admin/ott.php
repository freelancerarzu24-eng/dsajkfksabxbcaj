<?php
require_once __DIR__ . '/auth.php';
checkAdmin();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
validateCSRF();

$db = new Database();
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_ott'])) {
    $id = (int)$_POST['id'];
    $name = trim($_POST['name']);
    $url = trim($_POST['url']);
    $logo = $_POST['existing_logo'];
    if (!empty($_FILES['logo']['name'])) {
        $upload = uploadImage($_FILES['logo']);
        if ($upload['success']) $logo = $upload['file_name'];
    }
    if ($id > 0) {
        $db->query("UPDATE ott_platforms SET name = :name, url = :url, logo = :logo WHERE id = :id");
        $db->bind(':id', $id);
    } else {
        $db->query("INSERT INTO ott_platforms (name, url, logo) VALUES (:name, :url, :logo)");
    }
    $db->bind(':name', $name);
    $db->bind(':url', $url);
    $db->bind(':logo', $logo);
    $db->execute();
}
$platforms = $db->query("SELECT * FROM ott_platforms");
$platforms = $db->resultSet();
$page_title = "OTT Platforms";
$active_page = "streaming";
require_once __DIR__ . '/includes/header.php';
?>
<div class="row">
    <div class="col-md-4">
        <div class="card p-3">
            <form method="POST" enctype="multipart/form-data"> <?php csrf_field(); ?>
                <input type="hidden" name="id" id="o_id" value="0">
                <input type="hidden" name="existing_logo" id="o_existing_logo" value="">
                <div class="mb-3"><label>Name</label><input type="text" name="name" id="o_name" class="form-control" required></div>
                <div class="mb-3"><label>URL</label><input type="text" name="url" id="o_url" class="form-control"></div>
                <div class="mb-3"><label>Logo</label><input type="file" name="logo" class="form-control"></div>
                <button type="submit" name="save_ott" class="btn btn-primary">Save</button>
            </form>
        </div>
    </div>
    <div class="col-md-8">
        <table class="table bg-white">
            <thead><tr><th>Logo</th><th>Name</th><th>URL</th><th>Actions</th></tr></thead>
            <tbody>
                <?php foreach($platforms as $p): ?>
                <tr><td><img src="../uploads/<?php echo e($p['logo']); ?> ?>" width="40"></td><td><?php echo e($p['name']); ?> ?></td><td><?php echo e($p['url']); ?> ?></td><td><button class="btn btn-sm btn-info" onclick='editO(<?php echo json_encode($p); ?>)'>Edit</button></td></tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<script>
function editO(o) {
    document.getElementById('o_id').value = o.id;
    document.getElementById('o_name').value = o.name;
    document.getElementById('o_url').value = o.url;
    document.getElementById('o_existing_logo').value = o.logo;
}
</script>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
