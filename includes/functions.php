<?php
// Utility functions

/**
 * Sanitize output
 */
function e($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

/**
 * Generate Slug
 */
function slugify($text) {
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = trim($text, '-');
    $text = preg_replace('~-+~', '-', $text);
    $text = strtolower($text);
    if (empty($text)) {
        return 'n-a';
    }
    return $text;
}

/**
 * Image Upload
 */
function uploadImage($file, $targetDir = '../uploads/') {
    $targetFile = $targetDir . basename($file["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    $check = getimagesize($file["tmp_name"]);
    if($check !== false) {
        $uploadOk = 1;
    } else {
        return ["success" => false, "message" => "File is not an image."];
    }

    // Check file size (e.g., 5MB limit)
    if ($file["size"] > 5000000) {
        return ["success" => false, "message" => "Sorry, your file is too large."];
    }

    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" && $imageFileType != "webp") {
        return ["success" => false, "message" => "Sorry, only JPG, JPEG, PNG, WEBP & GIF files are allowed."];
    }

    // Rename file to prevent overwriting
    $newFileName = uniqid() . '.' . $imageFileType;
    $targetPath = $targetDir . $newFileName;

    if (move_uploaded_file($file["tmp_name"], $targetPath)) {
        return ["success" => true, "file_name" => $newFileName];
    } else {
        return ["success" => false, "message" => "Sorry, there was an error uploading your file."];
    }
}

/**
 * Redirect
 */
function redirect($url) {
    header('Location: ' . $url);
    exit;
}

/**
 * CSRF Token Generation
 */
function generateCSRFToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * CSRF Token Verification
 */
function verifyCSRFToken($token) {
    if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
        return false;
    }
    return true;
}

/**
 * Output CSRF Hidden Field
 */
function csrf_field() {
    echo '<input type="hidden" name="csrf_token" value="' . generateCSRFToken() . '">';
}

/**
 * Validate CSRF or Die
 */
function validateCSRF() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
            die('CSRF token validation failed');
        }
    }
}

/**
 * Get current language
 */
function getLang() {
    if (isset($_GET['lang']) && in_array($_GET['lang'], ['en', 'bn'])) {
        $_SESSION['lang'] = $_GET['lang'];
    }
    return isset($_SESSION['lang']) ? $_SESSION['lang'] : DEFAULT_LANG;
}

/**
 * Load language file
 */
function loadLang($langCode) {
    require __DIR__ . "/../lang/{$langCode}.php";
    return $lang;
}
?>
