<?php
require_once __DIR__ . '/auth.php';
checkAdmin();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

$db = new Database();
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['send_notif'])) {
    validateCSRF();
    $title = trim($_POST['title']);
    $msg = trim($_POST['message']);
    $type = $_POST['type'];
    $url = trim($_POST['url']);

    $db->query("INSERT INTO notifications (type, title, message, url, is_sent) VALUES (:type, :title, :message, :url, 1)");
    $db->bind(':type', $type);
    $db->bind(':title', $title);
    $db->bind(':message', $msg);
    $db->bind(':url', $url);
    if ($db->execute()) {
        $message = '<div class="alert alert-success">Notification sent successfully.</div>';
    }
}

$notifs = $db->query("SELECT * FROM notifications ORDER BY created_at DESC LIMIT 10");
$notifs = $db->resultSet();

$page_title = "Notifications";
$active_page = "notifications";
require_once __DIR__ . '/includes/header.php';
?>
<?php echo $message; ?>
<div class="row">
    <div class="col-md-5">
        <div class="card p-4 shadow-sm border-0">
            <h5>Send Notification</h5>
            <form method="POST">
                <?php csrf_field(); ?>
                <div class="mb-3"><label>Type</label>
                    <select name="type" class="form-select">
                        <option value="breaking">Breaking News</option>
                        <option value="match">Match Update</option>
                    </select>
                </div>
                <div class="mb-3"><label>Title</label><input type="text" name="title" class="form-control" required></div>
                <div class="mb-3"><label>Message</label><textarea name="message" class="form-control" rows="3" required></textarea></div>
                <div class="mb-3"><label>Target URL</label><input type="text" name="url" class="form-control" placeholder="http://..."></div>
                <button type="submit" name="send_notif" class="btn btn-primary w-100">Push Notification</button>
            </form>
        </div>
    </div>
    <div class="col-md-7">
        <div class="card p-4 shadow-sm border-0">
            <h5>Recent Notifications</h5>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead><tr><th>Title</th><th>Type</th><th>Date</th></tr></thead>
                    <tbody>
                        <?php foreach($notifs as $n): ?>
                        <tr>
                            <td><?php echo e($n['title']); ?></td>
                            <td><span class="badge bg-info"><?php echo ucfirst($n['type']); ?></span></td>
                            <td><?php echo date('M d, H:i', strtotime($n['created_at'])); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
