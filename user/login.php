<?php
require_once __DIR__ . '/../templates/header.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') { validateCSRF();
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = 'Please fill in all fields.';
    } else {
        $db->query("SELECT * FROM users WHERE email = :email");
        $db->bind(':email', $email);
        $user = $db->single();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            redirect('../index.php');
        } else {
            $error = 'Invalid email or password.';
        }
    }
}
?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark fw-bold"><?php echo $texts['login']; ?></div>
                <div class="card-body p-4">
                    <?php if($error) echo "<div class='alert alert-danger'>$error</div>"; ?>
                    <form method="POST"> <?php csrf_field(); ?>
                        <div class="mb-3">
                            <label>Email Address</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100"><?php echo $texts['login']; ?></button>
                    </form>
                    <div class="mt-3 text-center">
                        Don't have an account? <a href="register.php">Register here</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/../templates/footer.php'; ?>
