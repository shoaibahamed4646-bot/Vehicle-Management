<?php
require_once 'includes/db.php';

if (isset($_SESSION['admin_id'])) {
    header("Location: admin.php");
    exit();
}

$error = '';
$success = '';

// Handle Login
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'login') {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM admins WHERE username = '$username' OR email = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();
        if (password_verify($password, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            header("Location: admin.php");
            exit();
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "Admin not found.";
    }
}

// Handle Forgot Password Demo
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'forgot') {
    $email = $conn->real_escape_string($_POST['email']);
    
    $sql = "SELECT id FROM admins WHERE email = '$email'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $new_password = password_hash('newpass123', PASSWORD_DEFAULT);
        $update_sql = "UPDATE admins SET password = '$new_password' WHERE email = '$email'";
        if ($conn->query($update_sql)) {
            $success = "Password reset successful! Your new password is: <b>newpass123</b>";
        } else {
            $error = "Error resetting password.";
        }
    } else {
        $error = "Email address not found.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Vehicle Management</title>
    <link rel="stylesheet" href="css/admin_style.css">
    <style>
        #forgot-form { display: none; }
        .message { padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 0.9rem; }
        .message.error { background: rgba(239, 68, 68, 0.2); border: 1px solid var(--danger); color: #fca5a5; }
        .message.success { background: rgba(16, 185, 129, 0.2); border: 1px solid var(--success); color: #a7f3d0; }
    </style>
</head>
<body>

<div class="auth-container">
    <div class="auth-card" id="login-card">
        <h2>Admin Login</h2>
        
        <?php if($error) echo "<div class='message error'>$error</div>"; ?>
        <?php if($success) echo "<div class='message success'>$success</div>"; ?>

        <form id="login-form" method="POST" action="">
            <input type="hidden" name="action" value="login">
            <div class="form-group">
                <label>Username or Email</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn">Login</button>
            <div class="auth-links">
                <a href="#" onclick="toggleForm()">Forgot Password?</a>
            </div>
        </form>

        <form id="forgot-form" method="POST" action="">
            <input type="hidden" name="action" value="forgot">
            <p style="margin-bottom: 15px; font-size: 0.9rem; color: var(--text-muted);">Enter your email to reset your password.</p>
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <button type="submit" class="btn">Reset Password</button>
            <div class="auth-links">
                <a href="#" onclick="toggleForm()">Back to Login</a>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleForm() {
        var loginForm = document.getElementById('login-form');
        var forgotForm = document.getElementById('forgot-form');
        var title = document.querySelector('.auth-card h2');
        
        if (loginForm.style.display === 'none') {
            loginForm.style.display = 'block';
            forgotForm.style.display = 'none';
            title.innerText = 'Admin Login';
        } else {
            loginForm.style.display = 'none';
            forgotForm.style.display = 'block';
            title.innerText = 'Forgot Password';
        }
    }
</script>

</body>
</html>
