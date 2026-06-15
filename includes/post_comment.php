<?php
session_start();
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
validateCSRF();

$db = new Database();
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_id'])) {
    $article_id = (int)$_POST['article_id'];
    $comment = trim($_POST['comment']);
    $user_id = $_SESSION['user_id'];
    $name = $_SESSION['username'];

    if (!empty($comment)) {
        $db->query("INSERT INTO comments (article_id, user_id, name, comment, status) VALUES (:article_id, :user_id, :name, :comment, 'pending')");
        $db->bind(':article_id', $article_id);
        $db->bind(':user_id', $user_id);
        $db->bind(':name', $name);
        $db->bind(':comment', $comment);
        $db->execute();
    }
}
header('Location: ' . $_SERVER['HTTP_REFERER']);
exit;
?>
