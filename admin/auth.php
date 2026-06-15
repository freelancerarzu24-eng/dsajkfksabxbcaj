<?php
session_start();
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/functions.php';

function checkAdmin() {
    if (!isset($_SESSION['admin_id'])) {
        redirect('login.php');
    }
}

function isAdmin() {
    return isset($_SESSION['admin_role']) && $_SESSION['admin_role'] == 1;
}

function isEditor() {
    return isset($_SESSION['admin_role']) && in_array($_SESSION['admin_role'], [1, 2]);
}

if (isset($_GET['logout'])) {
    session_destroy();
    redirect('login.php');
}
?>
