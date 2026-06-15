<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

$q = $_GET['q'] ?? '';
$lang = getLang();

if (strlen($q) > 2) {
    $db = new Database();
    $db->query("SELECT title_en, title_bn, slug FROM articles WHERE (title_en LIKE :q OR title_bn LIKE :q) AND status = 'published' LIMIT 5");
    $db->bind(':q', "%$q%");
    $results = $db->resultSet();

    foreach ($results as $res) {
        echo "<a href='".SITE_URL."/news/index.php?slug={$res['slug']}' class='list-group-item list-group-item-action'>";
        echo e($res['title_' . $lang]);
        echo "</a>";
    }
}
?>
