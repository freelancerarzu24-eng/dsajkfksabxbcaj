<?php
require_once __DIR__ . '/auth.php';
checkAdmin();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
validateCSRF();

$db = new Database();
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_broadcaster'])) {
    $id = (int)$_POST['id'];
    $name = trim($_POST['name']);
    $website = trim($_POST['website']);
    $logo = $_POST['existing_logo'];

    if (!empty($_FILES['logo']['name'])) {
        $upload = uploadImage($_FILES['logo']);
        if ($upload['success']) $logo = $upload['file_name'];
    }

    if ($id > 0) {
        $db->query("UPDATE broadcasters SET name = :name, website = :website, logo = :logo WHERE id = :id");
        $db->bind(':id', $id);
    } else {
        $db->query("INSERT INTO broadcasters (name, website, logo) VALUES (:name, :website, :logo)");
    }
    $db->bind(':name', $name);
    $db->bind(':website', $website);
    $db->bind(':logo', $logo);
    $db->execute();
    $message = '<div class="alert alert-success">Broadcaster saved.</div>';
}

$broadcasters = $db->query("SELECT * FROM broadcasters");
$broadcasters = $db->resultSet();

$page_title = "Broadcasters";
$active_page = "broadcasters";
require_once __DIR__ . '/includes/header.php';
?>
<div class="row">
    <div class="col-md-4">
        <div class="card p-3">
            <form method="POST" enctype="multipart/form-data"> <?php csrf_field(); ?>
                <input type="hidden" name="id" id="b_id" value="0">
                <input type="hidden" name="existing_logo" id="b_existing_logo" value="">
                <div class="mb-3"><label>Name</label><input type="text" name="name" id="b_name" class="form-control" required></div>
                <div class="mb-3"><label>Website</label><input type="text" name="website" id="b_web" class="form-control"></div>
                <div class="mb-3"><label>Logo</label><input type="file" name="logo" class="form-control"></div>
                <button type="submit" name="save_broadcaster" class="btn btn-primary">Save</button>
            </form>
        </div>
    </div>
    <div class="col-md-8">
        <table class="table bg-white">
            <thead><tr><th>Logo</th><th>Name</th><th>Website</th><th>Actions</th></tr></thead>
            <tbody>
                <?php foreach($broadcasters as $b): ?>
                <tr>
                    <td><img src="../uploads/<?php echo e($b['logo']); ?> ?>" width="40"></td>
                    <td><?php echo e($b['name']); ?> ?></td>
                    <td><?php echo e($b['website']); ?> ?></td>
                    <td><button class="btn btn-sm btn-info" onclick='editB(<?php echo json_encode($b); ?>)'>Edit</button></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<script>
function editB(b) {
    document.getElementById('b_id').value = b.id;
    document.getElementById('b_name').value = b.name;
    document.getElementById('b_web').value = b.website;
    document.getElementById('b_existing_logo').value = b.logo;
}
</script>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
