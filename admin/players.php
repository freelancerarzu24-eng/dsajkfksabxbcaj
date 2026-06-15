<?php
require_once __DIR__ . '/auth.php';
checkAdmin();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

$db = new Database();
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_player'])) {
    $id = (int)$_POST['id'];
    $team_id = (int)$_POST['team_id'];
    $name_en = trim($_POST['name_en']);
    $name_bn = trim($_POST['name_bn']);
    $bio_en = $_POST['bio_en'];
    $bio_bn = $_POST['bio_bn'];
    $photo = $_POST['existing_photo'];

    if (!empty($_FILES['photo']['name'])) {
        $upload = uploadImage($_FILES['photo']);
        if ($upload['success']) {
            $photo = $upload['file_name'];
        }
    }

    if ($id > 0) {
        $db->query("UPDATE players SET team_id = :team_id, name_en = :name_en, name_bn = :name_bn, bio_en = :bio_en, bio_bn = :bio_bn, photo = :photo WHERE id = :id");
        $db->bind(':id', $id);
    } else {
        $db->query("INSERT INTO players (team_id, name_en, name_bn, bio_en, bio_bn, photo) VALUES (:team_id, :name_en, :name_bn, :bio_en, :bio_bn, :photo)");
    }
    $db->bind(':team_id', $team_id);
    $db->bind(':name_en', $name_en);
    $db->bind(':name_bn', $name_bn);
    $db->bind(':bio_en', $bio_en);
    $db->bind(':bio_bn', $bio_bn);
    $db->bind(':photo', $photo);

    if ($db->execute()) {
        $message = '<div class="alert alert-success">Player saved successfully.</div>';
    }
}

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $db->query("DELETE FROM players WHERE id = :id");
    $db->bind(':id', $id);
    $db->execute();
    $message = '<div class="alert alert-success">Player deleted.</div>';
}

$players = $db->query("SELECT p.*, t.name_en as team_name FROM players p LEFT JOIN teams t ON p.team_id = t.id ORDER BY p.id DESC");
$players = $db->resultSet();

$teams = $db->query("SELECT id, name_en FROM teams");
$teams = $db->resultSet();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Players - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --sidebar-width: 250px; }
        .sidebar { width: var(--sidebar-width); height: 100vh; position: fixed; background: #1a237e; color: white; }
        .main-content { margin-left: var(--sidebar-width); padding: 20px; }
        .nav-link { color: rgba(255,255,255,0.8); }
        .nav-link:hover, .nav-link.active { color: white; background: rgba(255,255,255,0.1); }
    </style>
</head>
<body>
    <div class="sidebar d-flex flex-column p-3">
        <h4>PlayPulse</h4>
        <hr>
        <ul class="nav nav-pills flex-column mb-auto">
            <li><a href="index.php" class="nav-link"><i class="fas fa-tachometer-alt me-2"></i> Dashboard</a></li>
            <li><a href="categories.php" class="nav-link"><i class="fas fa-list me-2"></i> Categories</a></li>
            <li><a href="news.php" class="nav-link"><i class="fas fa-newspaper me-2"></i> News</a></li>
            <li><a href="matches.php" class="nav-link"><i class="fas fa-trophy me-2"></i> Matches</a></li>
            <li><a href="teams.php" class="nav-link"><i class="fas fa-users me-2"></i> Teams</a></li>
            <li><a href="players.php" class="nav-link active"><i class="fas fa-user-ninja me-2"></i> Players</a></li>
            <li><a href="streaming.php" class="nav-link"><i class="fas fa-play-circle me-2"></i> Streaming</a></li>
            <li><a href="ads.php" class="nav-link"><i class="fas fa-ad me-2"></i> Advertisements</a></li>
            <li><a href="comments.php" class="nav-link"><i class="fas fa-comments me-2"></i> Comments</a></li>
            <li><a href="newsletter.php" class="nav-link"><i class="fas fa-envelope me-2"></i> Newsletter</a></li>
        </ul>
    </div>

    <div class="main-content">
        <h3>Manage Players</h3>
        <?php echo e($message); ?> ?>

        <div class="row">
            <div class="col-md-4">
                <div class="card p-3">
                    <h5>Add/Edit Player</h5>
                    <form action="players.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" id="player_id" value="0">
                        <input type="hidden" name="existing_photo" id="existing_photo" value="">
                        <div class="mb-3">
                            <label>Team</label>
                            <select name="team_id" id="player_team_id" class="form-select">
                                <option value="0">No Team</option>
                                <?php foreach($teams as $t): ?>
                                    <option value="<?php echo e($t['id']); ?> ?>"><?php echo e($t['name_en']); ?> ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Name (English)</label>
                            <input type="text" name="name_en" id="player_name_en" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Name (Bangla)</label>
                            <input type="text" name="name_bn" id="player_name_bn" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Photo</label>
                            <input type="file" name="photo" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label>Bio (English)</label>
                            <textarea name="bio_en" id="player_bio_en" class="form-control"></textarea>
                        </div>
                        <div class="mb-3">
                            <label>Bio (Bangla)</label>
                            <textarea name="bio_bn" id="player_bio_bn" class="form-control"></textarea>
                        </div>
                        <button type="submit" name="save_player" class="btn btn-primary">Save Player</button>
                        <button type="button" onclick="resetForm()" class="btn btn-secondary">Reset</button>
                    </form>
                </div>
            </div>
            <div class="col-md-8">
                <table class="table table-bordered bg-white">
                    <thead class="table-dark">
                        <tr>
                            <th>Photo</th>
                            <th>Name (EN)</th>
                            <th>Team</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($players as $p): ?>
                        <tr>
                            <td><img src="../uploads/<?php echo e($p['photo']); ?> ?>" width="40"></td>
                            <td><?php echo e($p['name_en']); ?> ?></td>
                            <td><?php echo $p['team_name'] ?: 'N/A'; ?></td>
                            <td>
                                <button class="btn btn-sm btn-info" onclick='editPlayer(<?php echo json_encode($p); ?>)'>Edit</button>
                                <a href="?delete=<?php echo e($p['id']); ?> ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        function editPlayer(p) {
            document.getElementById('player_id').value = p.id;
            document.getElementById('player_team_id').value = p.team_id || 0;
            document.getElementById('player_name_en').value = p.name_en;
            document.getElementById('player_name_bn').value = p.name_bn;
            document.getElementById('player_bio_en').value = p.bio_en;
            document.getElementById('player_bio_bn').value = p.bio_bn;
            document.getElementById('existing_photo').value = p.photo;
        }
        function resetForm() {
            document.getElementById('player_id').value = 0;
            document.getElementById('player_team_id').value = 0;
            document.getElementById('player_name_en').value = '';
            document.getElementById('player_name_bn').value = '';
            document.getElementById('player_bio_en').value = '';
            document.getElementById('player_bio_bn').value = '';
            document.getElementById('existing_photo').value = '';
        }
    </script>
</body>
</html>
