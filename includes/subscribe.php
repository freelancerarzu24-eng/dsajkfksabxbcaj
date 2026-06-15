<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/functions.php';
validateCSRF();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $db = new Database();
        $db->query("INSERT IGNORE INTO newsletter_subscribers (email) VALUES (:email)");
        $db->bind(':email', $email);
        $db->execute();
    }
}
header('Location: ' . $_SERVER['HTTP_REFERER']);
exit;
?>
