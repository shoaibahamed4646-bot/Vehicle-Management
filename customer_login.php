<?php
require_once 'includes/db.php';

if (isset($_SESSION['customer_id'])) {
    header("Location: customer_dashboard.php");
    exit();
}

$error = '';
$success = '';

// Handle Registration
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'register') {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if email already exists
    $check = $conn->query("SELECT * FROM customers WHERE email = '$email'");
    if ($check->num_rows > 0) {
        $error = "Email already registered. Please login.";
    } else {
        $sql = "INSERT INTO customers (name, email, phone, password) VALUES ('$name', '$email', '$phone', '$password')";
        if ($conn->query($sql)) {
            $success = "Registration successful! You can now login.";
        } else {
            $error = "Error during registration.";
        }
    }
}

// Handle Login
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'login') {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM customers WHERE email = '$email' OR phone = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $customer = $result->fetch_assoc();
        
        // If the customer was added by admin before the password feature, allow them to set one now?
        // Let's just assume they registered properly or admin set a default password.
        if (password_verify($password, $customer['password'])) {
            $_SESSION['customer_id'] = $customer['customer_id'];
            $_SESSION['customer_name'] = $customer['name'];
            header("Location: customer_dashboard.php");
            exit();
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "Customer not found.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Login - Vehicle Management</title>
    <link rel="stylesheet" href="css/admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        #register-form { display: none; }
        .message { padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 0.9rem; }
        .message.error { background: rgba(239, 68, 68, 0.2); border: 1px solid var(--danger); color: #fca5a5; }
        .message.success { background: rgba(16, 185, 129, 0.2); border: 1px solid var(--success); color: #a7f3d0; }
        .back-btn { position: absolute; top: 30px; left: 30px; color: var(--text-main); text-decoration: none; display: flex; align-items: center; gap: 8px; font-weight: 500; transition: color 0.3s; }
        .back-btn:hover { color: var(--primary); }
    </style>
</head>
<body>

<a href="index.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to Home</a>

<div class="auth-container">
    <div class="auth-card" id="auth-card">
        <h2>Customer Login</h2>
        
        <?php if($error) echo "<div class='message error'>$error</div>"; ?>
        <?php if($success) echo "<div class='message success'>$success</div>"; ?>

        <!-- Login Form -->
        <form id="login-form" method="POST" action="">
            <input type="hidden" name="action" value="login">
            <div class="form-group">
                <label>Email or Phone</label>
                <input type="text" name="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn">Login</button>
            <div class="auth-links">
                <a href="#" onclick="toggleForm()">Don't have an account? Register</a>
            </div>
        </form>

        <!-- Register Form -->
        <form id="register-form" method="POST" action="">
            <input type="hidden" name="action" value="register">
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Phone Number</label>
                <input type="text" name="phone" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn">Register</button>
            <div class="auth-links">
                <a href="#" onclick="toggleForm()">Already have an account? Login</a>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleForm() {
        var loginForm = document.getElementById('login-form');
        var registerForm = document.getElementById('register-form');
        var title = document.querySelector('.auth-card h2');
        
        if (loginForm.style.display === 'none') {
            loginForm.style.display = 'block';
            registerForm.style.display = 'none';
            title.innerText = 'Customer Login';
        } else {
            loginForm.style.display = 'none';
            registerForm.style.display = 'block';
            title.innerText = 'Customer Registration';
        }
    }
</script>

</body>
</html>
