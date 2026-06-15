<?php
// Configuration Settings

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'sports_portal');

// Site Configuration
define('SITE_NAME', 'PlayPulse Sports');
define('SITE_URL', 'http://localhost/sports-portal'); // Change this in production
define('ADMIN_URL', SITE_URL . '/admin');

// Security
define('SECRET_KEY', 'your-secret-key-change-it');

// Language Settings
define('DEFAULT_LANG', 'en');

// Error Reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Timezone
date_default_timezone_set('Asia/Dhaka');
?>
