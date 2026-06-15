<?php
require_once __DIR__ . '/../templates/header.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    validateCSRF();
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($username) || empty($email) || empty($password)) {
        $error = 'All fields are required.';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match.';
    } else {
        $db->query("SELECT id FROM users WHERE email = :email OR username = :username");
        $db->bind(':email', $email);
        $db->bind(':username', $username);
        if ($db->single()) {
            $error = 'Username or Email already exists.';
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $token = bin2hex(random_bytes(16));
            $db->query("INSERT INTO users (username, email, password, verification_code, is_verified) VALUES (:username, :email, :password, :token, 0)");
            $db->bind(':username', $username);
            $db->bind(':email', $email);
            $db->bind(':password', $hashed_password);
            $db->bind(':token', $token);
            if ($db->execute()) {
                // In production, send verification email here.
                echo "<div class='container mt-5'><div class='alert alert-success'>Registration successful! Please verify your email. (Token: $token)</div></div>";
                require_once __DIR__ . '/../templates/footer.php';
                exit;
            }
        }
    }
}
?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark fw-bold"><?php echo $texts['register']; ?></div>
                <div class="card-body p-4">
                    <?php if($error) echo "<div class='alert alert-danger'>$error</div>"; ?>
                    <form method="POST"> <?php csrf_field(); ?>
                        <div class="mb-3">
                            <label>Username</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Email Address</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Confirm Password</label>
                            <input type="password" name="confirm_password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100"><?php echo $texts['register']; ?></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/../templates/footer.php'; ?>
