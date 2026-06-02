<?php
session_start();
require_once 'includes/db.php';

$error = '';

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email_or_username = $_POST['email_or_username'];
    $password = $_POST['password'];

    // Query to find user by email or username
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? OR name = ? LIMIT 1");
    $stmt->execute([$email_or_username, $email_or_username]);
    $user = $stmt->fetch();

    // WARNING: Using plain text passwords for learning purposes as requested!
    if ($user && $user['password'] === $password) {
        // Login success
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['role'] = $user['role'];

        if ($user['role'] == 'admin') {
            header("Location: admin/index.php");
        } else {
            header("Location: user/index.php");
        }
        exit;
    } else {
        $error = "Invalid credentials. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Quiz System</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="auth-wrapper">
    <div class="auth-card">
        <h2>Quiz System</h2>
        <p class="text-center text-muted mb-4">Please login to continue</p>
        
        <?php if($error): ?>
            <div class="alert alert-danger text-center"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label for="email" class="form-label text-muted fw-bold">Email or Username</label>
                <input type="text" class="form-control" id="email" name="email_or_username" placeholder="e.g. haris" required>
            </div>
            <div class="mb-4">
                <label for="password" class="form-label text-muted fw-bold">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="e.g. haris123" required>
            </div>
            <button type="submit" class="btn btn-primary-custom w-100 py-2">Login</button>
        </form>
        
        <div class="mt-4 text-center text-muted" style="font-size: 0.9em;">
            <p class="mb-1">Demo Admin: admin / haris123</p>
            <p class="mb-0">Demo User: haris / haris123</p>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>