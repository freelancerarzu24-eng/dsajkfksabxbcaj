<?php
require_once __DIR__ . '/auth.php';
checkAdmin();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
validateCSRF();

$db = new Database();
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_ad'])) {
    $id = (int)$_POST['id'];
    $position = $_POST['position'];
    $ad_type = $_POST['ad_type'];
    $ad_code = $_POST['ad_code'];
    $ad_link = $_POST['ad_link'];
    $status = isset($_POST['status']) ? 1 : 0;
    $ad_image = $_POST['existing_image'];

    if ($ad_type == 'image' && !empty($_FILES['ad_image']['name'])) {
        $upload = uploadImage($_FILES['ad_image']);
        if ($upload['success']) {
            $ad_image = $upload['file_name'];
        }
    }

    if ($id > 0) {
        $db->query("UPDATE advertisements SET position = :position, ad_type = :ad_type, ad_code = :ad_code, ad_image = :ad_image, ad_link = :ad_link, status = :status WHERE id = :id");
        $db->bind(':id', $id);
    } else {
        $db->query("INSERT INTO advertisements (position, ad_type, ad_code, ad_image, ad_link, status) VALUES (:position, :ad_type, :ad_code, :ad_image, :ad_link, :status)");
    }
    $db->bind(':position', $position);
    $db->bind(':ad_type', $ad_type);
    $db->bind(':ad_code', $ad_code);
    $db->bind(':ad_image', $ad_image);
    $db->bind(':ad_link', $ad_link);
    $db->bind(':status', $status);

    if ($db->execute()) {
        $message = '<div class="alert alert-success">Ad saved successfully.</div>';
    }
}

$ads = $db->query("SELECT * FROM advertisements");
$ads = $db->resultSet();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Ads - Admin</title>
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
            <li><a href="players.php" class="nav-link"><i class="fas fa-user-ninja me-2"></i> Players</a></li>
            <li><a href="streaming.php" class="nav-link"><i class="fas fa-play-circle me-2"></i> Streaming</a></li>
            <li><a href="ads.php" class="nav-link active"><i class="fas fa-ad me-2"></i> Advertisements</a></li>
            <li><a href="comments.php" class="nav-link"><i class="fas fa-comments me-2"></i> Comments</a></li>
            <li><a href="newsletter.php" class="nav-link"><i class="fas fa-envelope me-2"></i> Newsletter</a></li>
        </ul>
    </div>

    <div class="main-content">
        <h3>Manage Advertisements</h3>
        <?php echo e($message); ?> ?>

        <div class="row">
            <div class="col-md-4">
                <div class="card p-3">
                    <form action="ads.php" method="POST" enctype="multipart/form-data"> <?php csrf_field(); ?>
                        <input type="hidden" name="id" id="ad_id" value="0">
                        <input type="hidden" name="existing_image" id="ad_existing_image" value="">
                        <div class="mb-3">
                            <label>Position</label>
                            <select name="position" class="form-select" required>
                                <option value="header">Header</option>
                                <option value="sidebar">Sidebar</option>
                                <option value="footer">Footer</option>
                                <option value="article">Article</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Type</label>
                            <select name="ad_type" id="ad_type" class="form-select">
                                <option value="code">Code (JS/HTML)</option>
                                <option value="image">Image</option>
                            </select>
                        </div>
                        <div class="mb-3" id="code_area">
                            <label>Ad Code</label>
                            <textarea name="ad_code" class="form-control"></textarea>
                        </div>
                        <div class="mb-3 d-none" id="image_area">
                            <label>Ad Image</label>
                            <input type="file" name="ad_image" class="form-control">
                            <label class="mt-2">Ad Link</label>
                            <input type="text" name="ad_link" class="form-control">
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" name="status" class="form-check-input" checked>
                            <label class="form-check-label">Active</label>
                        </div>
                        <button type="submit" name="save_ad" class="btn btn-primary">Save Ad</button>
                    </form>
                </div>
            </div>
            <div class="col-md-8">
                <table class="table table-bordered bg-white">
                    <thead class="table-dark">
                        <tr>
                            <th>Position</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($ads as $ad): ?>
                        <tr>
                            <td><?php echo ucfirst($ad['position']); ?></td>
                            <td><?php echo ucfirst($ad['ad_type']); ?></td>
                            <td><?php echo $ad['status'] ? 'Active' : 'Inactive'; ?></td>
                            <td>
                                <a href="?delete=<?php echo e($ad['id']); ?> ?>" class="btn btn-sm btn-danger">Delete</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('ad_type').addEventListener('change', function() {
            if (this.value === 'image') {
                document.getElementById('image_area').classList.remove('d-none');
                document.getElementById('code_area').classList.add('d-none');
            } else {
                document.getElementById('image_area').classList.add('d-none');
                document.getElementById('code_area').classList.remove('d-none');
            }
        });
    </script>
</body>
</html>
