<?php
// Configuration Settings

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'sports_portal');

// Site Configuration - Automatically detect Site URL
$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https://" : "http://";
$domain = $_SERVER['HTTP_HOST'];

// Get the base directory relative to document root
$script_path = str_replace('\\', '/', dirname(__DIR__));
$doc_root = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);
$base_path = str_replace($doc_root, '', $script_path);
$base_path = rtrim($base_path, '/');

$auto_site_url = $protocol . $domain . $base_path;

define('SITE_NAME', 'PlayPulse Sports');
define('SITE_URL', $auto_site_url);
define('ADMIN_URL', SITE_URL . '/admin');

// Security
define('SECRET_KEY', 'your-secret-key-change-it-later');

// Language Settings
define('DEFAULT_LANG', 'en');

// Error Reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Timezone
date_default_timezone_set('Asia/Dhaka');
?>
