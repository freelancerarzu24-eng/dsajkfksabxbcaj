<?php
require_once __DIR__ . '/../templates/header.php';

$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    validateCSRF();
    $email = trim($_POST['email']);

    $db->query("SELECT id FROM users WHERE email = :email");
    $db->bind(':email', $email);
    if ($db->single()) {
        $token = bin2hex(random_bytes(32));
        $db->query("UPDATE users SET verification_code = :token WHERE email = :email");
        $db->bind(':token', $token);
        $db->bind(':email', $email);
        $db->execute();

        // In real production, send email here. For demo, we just show message.
        $message = "<div class='alert alert-success'>Password reset link has been sent to your email. (Token for demo: $token)</div>";
    } else {
        $message = "<div class='alert alert-danger'>Email not found.</div>";
    }
}
?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark fw-bold">Forgot Password</div>
                <div class="card-body p-4">
                    <?php echo $message; ?>
                    <form method="POST">
                        <?php csrf_field(); ?>
                        <div class="mb-3">
                            <label>Email Address</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Reset Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/../templates/footer.php'; ?>
