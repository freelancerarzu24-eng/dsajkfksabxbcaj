<?php
require_once __DIR__ . '/../templates/header.php';

if (!isset($_SESSION['user_id'])) {
    redirect('login.php');
}

$db->query("SELECT * FROM users WHERE id = :id");
$db->bind(':id', $_SESSION['user_id']);
$user = $db->single();

if (isset($_GET['logout'])) {
    session_destroy();
    redirect('../index.php');
}
?>
<div class="container mt-5">
    <div class="row">
        <div class="col-md-4">
            <div class="card p-4 text-center shadow-sm">
                <img src="../uploads/<?php echo $user['profile_pic'] ?: 'default_user.png'; ?>" class="rounded-circle mx-auto mb-3" width="120">
                <h4><?php echo $user['full_name'] ?: $user['username']; ?></h4>
                <p class="text-muted"><?php echo e($user['email']); ?></p>
                <hr>
                <a href="?logout=1" class="btn btn-outline-danger w-100"><?php echo $texts['logout']; ?></a>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card p-4 shadow-sm">
                <h4>Your Activity</h4>
                <hr>
                <p>Member since: <?php echo date('M d, Y', strtotime($user['created_at'])); ?></p>

                <h5 class="mt-4">Your Comments</h5>
                <?php
                $db->query("SELECT c.*, a.title_en as article_title, a.slug FROM comments c JOIN articles a ON c.article_id = a.id WHERE c.user_id = :uid ORDER BY c.created_at DESC");
                $db->bind(':uid', $_SESSION['user_id']);
                $my_comments = $db->resultSet();
                if (empty($my_comments)) {
                    echo "<p class='text-muted'>No comments yet.</p>";
                } else {
                    foreach($my_comments as $mc) {
                        echo "<div class='border-bottom pb-2 mb-2'>
                                <small class='text-muted'>On <a href='../news/index.php?slug={$mc['slug']}'>{$mc['article_title']}</a></small>
                                <p class='mb-0'>{$mc['comment']}</p>
                              </div>";
                    }
                }
                ?>
            </div>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/../templates/footer.php'; ?>
