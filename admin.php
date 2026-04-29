<?php
require_once 'includes/db.php';

// Check if user is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch dashboard statistics
// 1. Total Customers
$result = $conn->query("SELECT COUNT(*) as total FROM customers");
$total_customers = $result->fetch_assoc()['total'];

// 2. Total Employees
$result = $conn->query("SELECT COUNT(*) as total FROM employees");
$total_employees = $result->fetch_assoc()['total'];

// 3. Pending Service Requests
$result = $conn->query("SELECT COUNT(*) as total FROM service_requests WHERE status = 'Pending'");
$pending_requests = $result->fetch_assoc()['total'];

// 4. Recent Notifications (Last 5 service requests)
$recent_requests = $conn->query("SELECT sr.*, v.registration_no FROM service_requests sr LEFT JOIN vehicles v ON sr.vehicle_id = v.registration_no ORDER BY sr.id DESC LIMIT 5");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Vehicle Management</title>
    <link rel="stylesheet" href="css/admin_style.css">
    <!-- FontAwesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <!-- Sidebar Navigation -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h2><i class="fas fa-car-side"></i> VMS Admin</h2>
        </div>
        <ul class="nav-links">
            <li><a href="admin.php" class="active"><i class="fas fa-home" style="margin-right:10px;"></i> Dashboard</a></li>
            <li><a href="admin_customers.php"><i class="fas fa-users" style="margin-right:10px;"></i> Customers</a></li>
            <li><a href="admin_vehicles.php"><i class="fas fa-car" style="margin-right:10px;"></i> Vehicles</a></li>
            <li><a href="admin_employees.php"><i class="fas fa-user-tie" style="margin-right:10px;"></i> Employees</a></li>
            <li><a href="admin_requests.php"><i class="fas fa-tools" style="margin-right:10px;"></i> Service Requests</a></li>
            <li><a href="admin_reports.php"><i class="fas fa-chart-bar" style="margin-right:10px;"></i> Reports</a></li>
            <li style="margin-top: auto;"><a href="admin_logout.php"><i class="fas fa-sign-out-alt" style="margin-right:10px;"></i> Logout</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="topbar">
            <h1>Dashboard Overview</h1>
            <div class="user-profile">
                <i class="fas fa-bell" style="font-size: 1.2rem; color: var(--text-muted); cursor: pointer;"></i>
                <div style="background: rgba(255,255,255,0.1); padding: 8px 16px; border-radius: 20px; display: flex; align-items: center; gap: 10px;">
                    <i class="fas fa-user-circle" style="font-size: 1.5rem;"></i>
                    <span><?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
                </div>
            </div>
        </div>

        <!-- Widgets -->
        <div class="dashboard-grid">
            <div class="widget">
                <h3>Total Customers</h3>
                <div class="value"><?php echo $total_customers; ?></div>
            </div>
            <div class="widget">
                <h3>Total Employees</h3>
                <div class="value"><?php echo $total_employees; ?></div>
            </div>
            <div class="widget">
                <h3>Pending Requests</h3>
                <div class="value" style="color: var(--warning);"><?php echo $pending_requests; ?></div>
            </div>
        </div>

        <!-- Quick Links & Notifications -->
        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px;">
            
            <div class="data-card">
                <div class="data-card-header">
                    <h2>Recent Service Requests</h2>
                    <a href="admin_requests.php" class="btn-sm">View All</a>
                </div>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Vehicle Reg</th>
                                <th>Complaint</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($recent_requests->num_rows > 0): ?>
                                <?php while($row = $recent_requests->fetch_assoc()): ?>
                                <tr>
                                    <td>#<?php echo $row['id']; ?></td>
                                    <td><?php echo htmlspecialchars($row['registration_no'] ?? 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars($row['complaint_text']); ?></td>
                                    <td>
                                        <span class="badge <?php echo strtolower($row['status']); ?>">
                                            <?php echo htmlspecialchars($row['status']); ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="4">No recent requests.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="data-card">
                <h2>Quick Links</h2>
                <div style="margin-top: 20px; display: flex; flex-direction: column; gap: 12px;">
                    <a href="admin_customers.php" class="btn" style="text-align: center; background: rgba(255,255,255,0.05); border: 1px solid var(--border);">Manage Customers</a>
                    <a href="admin_employees.php" class="btn" style="text-align: center; background: rgba(255,255,255,0.05); border: 1px solid var(--border);">Assign Employees</a>
                    <a href="admin_reports.php" class="btn" style="text-align: center; background: rgba(255,255,255,0.05); border: 1px solid var(--border);">Generate Reports</a>
                </div>
            </div>

        </div>
    </div>

</body>
</html>