<?php
session_start();
$step = isset($_GET['step']) ? (int)$_GET['step'] : 1;
$error = '';

if ($step == 2 && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $host = $_POST['host'];
    $user = $_POST['user'];
    $pass = $_POST['pass'];
    $name = $_POST['name'];

    try {
        $conn = new PDO("mysql:host=$host", $user, $pass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->exec("CREATE DATABASE IF NOT EXISTS `$name` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

        $_SESSION['db_host'] = $host;
        $_SESSION['db_user'] = $user;
        $_SESSION['db_pass'] = $pass;
        $_SESSION['db_name'] = $name;

        header('Location: index.php?step=3');
        exit;
    } catch(PDOException $e) {
        $error = "Connection failed: " . $e->getMessage();
    }
}

if ($step == 3 && $_SERVER['REQUEST_METHOD'] == 'POST') {
    // Import SQL and Create Config
    $host = $_SESSION['db_host'];
    $user = $_SESSION['db_user'];
    $pass = $_SESSION['db_pass'];
    $name = $_SESSION['db_name'];

    try {
        $conn = new PDO("mysql:host=$host;dbname=$name", $user, $pass);
        $sql = file_get_contents('../database.sql');
        $conn->exec($sql);

        // Create Admin
        $adm_user = $_POST['adm_user'];
        $adm_pass = password_hash($_POST['adm_pass'], PASSWORD_DEFAULT);
        $adm_email = $_POST['adm_email'];

        $stmt = $conn->prepare("INSERT INTO admins (role_id, username, email, password) VALUES (1, ?, ?, ?)");
        $stmt->execute([$adm_user, $adm_email, $adm_pass]);

        // Write Config File
        $config_content = "<?php
define('DB_HOST', '$host');
define('DB_USER', '$user');
define('DB_PASS', '$pass');
define('DB_NAME', '$name');
define('SITE_NAME', 'PlayPulse Sports');
define('SITE_URL', 'http://' . \$_SERVER['HTTP_HOST'] . '/sports-portal');
define('ADMIN_URL', SITE_URL . '/admin');
define('SECRET_KEY', '" . bin2hex(random_bytes(32)) . "');
define('DEFAULT_LANG', 'en');
error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set('Asia/Dhaka');
?>";
        file_put_contents('../config/config.php', $config_content);

        header('Location: index.php?step=4');
        exit;
    } catch(PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>PlayPulse Installation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow mx-auto" style="max-width: 600px;">
            <div class="card-header bg-primary text-white text-center py-3">
                <h3>PlayPulse Installation</h3>
            </div>
            <div class="card-body p-4">
                <?php if ($error) echo "<div class='alert alert-danger'>$error</div>"; ?>

                <?php if ($step == 1): ?>
                    <h4>Welcome</h4>
                    <p>This wizard will help you set up PlayPulse Sports News Portal.</p>
                    <a href="index.php?step=2" class="btn btn-primary">Start Installation</a>

                <?php elseif ($step == 2): ?>
                    <h4>Database Configuration</h4>
                    <form method="POST">
                        <div class="mb-3"><label>Host</label><input type="text" name="host" class="form-control" value="localhost" required></div>
                        <div class="mb-3"><label>Username</label><input type="text" name="user" class="form-control" value="root" required></div>
                        <div class="mb-3"><label>Password</label><input type="text" name="pass" class="form-control"></div>
                        <div class="mb-3"><label>Database Name</label><input type="text" name="name" class="form-control" value="sports_portal" required></div>
                        <button type="submit" class="btn btn-primary">Next</button>
                    </form>

                <?php elseif ($step == 3): ?>
                    <h4>Admin Account</h4>
                    <form method="POST">
                        <div class="mb-3"><label>Admin Username</label><input type="text" name="adm_user" class="form-control" required></div>
                        <div class="mb-3"><label>Admin Email</label><input type="email" name="adm_email" class="form-control" required></div>
                        <div class="mb-3"><label>Admin Password</label><input type="password" name="adm_pass" class="form-control" required></div>
                        <button type="submit" class="btn btn-primary">Finish</button>
                    </form>

                <?php elseif ($step == 4): ?>
                    <div class="text-center">
                        <h4 class="text-success">Installation Successful!</h4>
                        <p>Your website is ready.</p>
                        <p class="text-danger">Important: Please delete the <strong>install</strong> directory for security.</p>
                        <a href="../index.php" class="btn btn-primary me-2">Go to Website</a>
                        <a href="../admin/login.php" class="btn btn-dark">Admin Panel</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
