<?php
require_once 'includes/db.php';

// If already logged in as customer, redirect to customer dashboard
if (isset($_SESSION['customer_id'])) {
    header("Location: customer_dashboard.php");
    exit();
}

// If already logged in as admin, redirect to admin dashboard
if (isset($_SESSION['admin_id'])) {
    header("Location: admin.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome - Vehicle Management System</title>
    <link rel="stylesheet" href="css/admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .portal-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            max-width: 900px;
            width: 100%;
        }
        
        .portal-card {
            background: var(--bg-card);
            backdrop-filter: blur(12px);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 50px 30px;
            text-align: center;
            transition: all 0.3s;
            cursor: pointer;
            text-decoration: none;
            color: var(--text-main);
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .portal-card:hover {
            transform: translateY(-10px);
            border-color: var(--primary);
            box-shadow: 0 20px 40px rgba(79, 70, 229, 0.2);
        }

        .portal-card i {
            font-size: 4rem;
            color: var(--primary);
            margin-bottom: 20px;
        }

        .portal-card h2 {
            font-size: 1.8rem;
            margin-bottom: 10px;
        }

        .portal-card p {
            color: var(--text-muted);
            font-size: 1rem;
            line-height: 1.5;
        }

        @media (max-width: 768px) {
            .portal-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

<div class="auth-container" style="flex-direction: column;">
    <div style="text-align: center; margin-bottom: 50px; animation: slideUp 0.5s ease-out;">
        <i class="fas fa-car-side" style="font-size: 3rem; color: var(--primary); margin-bottom: 15px;"></i>
        <h1 style="font-size: 2.5rem; font-weight: 700;">Vehicle Management System</h1>
        <p style="color: var(--text-muted); margin-top: 10px; font-size: 1.2rem;">Choose your portal to continue</p>
    </div>

    <div class="portal-grid" style="animation: slideUp 0.6s ease-out;">
        <a href="customer_login.php" class="portal-card">
            <i class="fas fa-user"></i>
            <h2>Customer Portal</h2>
            <p>View your vehicles, track service requests, and submit new complaints.</p>
        </a>

        <a href="admin_login.php" class="portal-card">
            <i class="fas fa-user-shield"></i>
            <h2>Admin Portal</h2>
            <p>Manage customers, employees, vehicles, and process service requests.</p>
        </a>
    </div>
</div>

</body>
</html>
