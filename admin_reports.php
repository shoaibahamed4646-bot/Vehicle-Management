<?php
require_once 'includes/db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Analytics Queries
// 1. Service Reports (Status breakdown)
$status_counts = [];
$res = $conn->query("SELECT status, COUNT(*) as count FROM service_requests GROUP BY status");
while ($row = $res->fetch_assoc()) {
    $status_counts[$row['status']] = $row['count'];
}

// 2. Employee Performance (Simple count of active employees)
$res = $conn->query("SELECT position, COUNT(*) as count FROM employees GROUP BY position");
$employee_roles = [];
while ($row = $res->fetch_assoc()) {
    $employee_roles[$row['position']] = $row['count'];
}

// 3. Revenue Reports (Total payments by month)
$res = $conn->query("SELECT MONTHNAME(payment_date) as month, SUM(amount) as total FROM payments GROUP BY MONTH(payment_date)");
$revenue_data = [];
while ($row = $res->fetch_assoc()) {
    $revenue_data[] = $row;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports - Vehicle Management</title>
    <link rel="stylesheet" href="css/admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <div class="sidebar">
        <div class="sidebar-header">
            <h2><i class="fas fa-car-side"></i> VMS Admin</h2>
        </div>
        <ul class="nav-links">
            <li><a href="admin.php"><i class="fas fa-home" style="margin-right:10px;"></i> Dashboard</a></li>
            <li><a href="admin_customers.php"><i class="fas fa-users" style="margin-right:10px;"></i> Customers</a></li>
            <li><a href="admin_vehicles.php"><i class="fas fa-car" style="margin-right:10px;"></i> Vehicles</a></li>
            <li><a href="admin_employees.php"><i class="fas fa-user-tie" style="margin-right:10px;"></i> Employees</a></li>
            <li><a href="admin_requests.php"><i class="fas fa-tools" style="margin-right:10px;"></i> Service Requests</a></li>
            <li><a href="admin_reports.php" class="active"><i class="fas fa-chart-bar" style="margin-right:10px;"></i> Reports</a></li>
            <li style="margin-top: auto;"><a href="admin_logout.php"><i class="fas fa-sign-out-alt" style="margin-right:10px;"></i> Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="topbar">
            <h1>Analytics & Reports</h1>
            <div class="user-profile">
                <div style="background: rgba(255,255,255,0.1); padding: 8px 16px; border-radius: 20px; display: flex; align-items: center; gap: 10px;">
                    <i class="fas fa-user-circle" style="font-size: 1.5rem;"></i>
                    <span><?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
                </div>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px;">
            <!-- Service Reports -->
            <div class="data-card">
                <h2>Service Request Breakdown</h2>
                <div style="margin-top: 20px;">
                    <ul style="list-style:none;">
                        <li style="padding: 10px 0; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between;">
                            <span>Pending</span>
                            <span class="badge pending"><?php echo $status_counts['Pending'] ?? 0; ?></span>
                        </li>
                        <li style="padding: 10px 0; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between;">
                            <span>In Progress</span>
                            <span class="badge" style="background: rgba(59, 130, 246, 0.2); color: #60a5fa;"><?php echo $status_counts['In Progress'] ?? 0; ?></span>
                        </li>
                        <li style="padding: 10px 0; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between;">
                            <span>Completed</span>
                            <span class="badge completed"><?php echo $status_counts['Completed'] ?? 0; ?></span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Employee Reports -->
            <div class="data-card">
                <h2>Employee Distribution</h2>
                <div style="margin-top: 20px;">
                    <ul style="list-style:none;">
                        <?php if(!empty($employee_roles)): ?>
                            <?php foreach($employee_roles as $role => $count): ?>
                            <li style="padding: 10px 0; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between;">
                                <span><?php echo htmlspecialchars($role); ?></span>
                                <span style="font-weight: bold;"><?php echo $count; ?></span>
                            </li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li>No employees registered.</li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Revenue Report -->
        <div class="data-card">
            <div class="data-card-header">
                <h2>Monthly Revenue</h2>
            </div>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Month</th>
                            <th>Total Revenue ($)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($revenue_data)): ?>
                            <?php foreach($revenue_data as $row): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['month']); ?></td>
                                <td style="color: var(--success); font-weight: bold;">$<?php echo number_format($row['total'], 2); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="2">No revenue data available. Generate payments to see reports.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</body>
</html>
