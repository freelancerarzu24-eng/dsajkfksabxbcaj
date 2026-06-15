<?php
require_once __DIR__ . '/auth.php';
checkAdmin();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
validateCSRF();

$db = new Database();
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_team'])) {
    $id = (int)$_POST['id'];
    $name_en = trim($_POST['name_en']);
    $name_bn = trim($_POST['name_bn']);
    $description = trim($_POST['description']);
    $logo = $_POST['existing_logo'];
    if (!empty($_FILES['logo']['name'])) {
        $upload = uploadImage($_FILES['logo']);
        if ($upload['success']) $logo = $upload['file_name'];
    }
    if ($id > 0) {
        $db->query("UPDATE teams SET name_en = :name_en, name_bn = :name_bn, description = :description, logo = :logo WHERE id = :id");
        $db->bind(':id', $id);
    } else {
        $db->query("INSERT INTO teams (name_en, name_bn, description, logo) VALUES (:name_en, :name_bn, :description, :logo)");
    }
    $db->bind(':name_en', $name_en);
    $db->bind(':name_bn', $name_bn);
    $db->bind(':description', $description);
    $db->bind(':logo', $logo);
    $db->execute();
}
$teams = $db->query("SELECT * FROM teams ORDER BY id DESC");
$teams = $db->resultSet();
$page_title = "Manage Teams";
$active_page = "teams";
require_once __DIR__ . '/includes/header.php';
?>
<div class="row">
    <div class="col-md-4">
        <div class="card p-3">
            <form method="POST" enctype="multipart/form-data"> <?php csrf_field(); ?>
                <input type="hidden" name="id" id="team_id" value="0">
                <input type="hidden" name="existing_logo" id="existing_logo" value="">
                <div class="mb-3"><label>Name (EN)</label><input type="text" name="name_en" id="team_name_en" class="form-control" required></div>
                <div class="mb-3"><label>Name (BN)</label><input type="text" name="name_bn" id="team_name_bn" class="form-control" required></div>
                <div class="mb-3"><label>Logo</label><input type="file" name="logo" class="form-control"></div>
                <button type="submit" name="save_team" class="btn btn-primary">Save</button>
            </form>
        </div>
    </div>
    <div class="col-md-8">
        <table class="table bg-white"><thead><tr><th>Logo</th><th>Name (EN)</th><th>Actions</th></tr></thead>
        <tbody><?php foreach($teams as $t): ?>
            <tr><td><img src="../uploads/<?php echo e($t['logo']); ?>" width="40"></td><td><?php echo e($t['name_en']); ?></td><td><button class="btn btn-sm btn-info" onclick='editTeam(<?php echo json_encode($t); ?>)'>Edit</button></td></tr>
        <?php endforeach; ?></tbody></table>
    </div>
</div>
<script>
function editTeam(t) {
    document.getElementById('team_id').value = t.id;
    document.getElementById('team_name_en').value = t.name_en;
    document.getElementById('team_name_bn').value = t.name_bn;
    document.getElementById('existing_logo').value = t.logo;
}
</script>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
