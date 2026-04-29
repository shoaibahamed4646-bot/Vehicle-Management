<?php
require_once 'includes/db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Handle Status Update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'update_status') {
    $id = (int)$_POST['request_id'];
    $status = $conn->real_escape_string($_POST['status']);
    
    $conn->query("UPDATE service_requests SET status = '$status' WHERE id = $id");
    header("Location: admin_requests.php");
    exit();
}

// Fetch Requests
$requests = $conn->query("
    SELECT sr.*, v.registration_no, c.name as customer_name 
    FROM service_requests sr 
    LEFT JOIN vehicles v ON sr.vehicle_id = v.registration_no 
    LEFT JOIN customers c ON v.customer_id = c.customer_id 
    ORDER BY sr.id DESC
");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Requests - Vehicle Management</title>
    <link rel="stylesheet" href="css/admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        select.status-dropdown {
            background: rgba(15, 23, 42, 0.8);
            color: white;
            border: 1px solid var(--border);
            padding: 4px 8px;
            border-radius: 4px;
        }
    </style>
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
            <li><a href="admin_requests.php" class="active"><i class="fas fa-tools" style="margin-right:10px;"></i> Service Requests</a></li>
            <li><a href="admin_reports.php"><i class="fas fa-chart-bar" style="margin-right:10px;"></i> Reports</a></li>
            <li style="margin-top: auto;"><a href="admin_logout.php"><i class="fas fa-sign-out-alt" style="margin-right:10px;"></i> Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="topbar">
            <h1>Service Requests Management</h1>
            <div class="user-profile">
                <div style="background: rgba(255,255,255,0.1); padding: 8px 16px; border-radius: 20px; display: flex; align-items: center; gap: 10px;">
                    <i class="fas fa-user-circle" style="font-size: 1.5rem;"></i>
                    <span><?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
                </div>
            </div>
        </div>

        <div class="data-card">
            <div class="data-card-header">
                <h2>All Requests</h2>
            </div>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Customer</th>
                            <th>Vehicle Reg</th>
                            <th>Service Type</th>
                            <th>Complaint</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Update</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($requests->num_rows > 0): ?>
                            <?php while($row = $requests->fetch_assoc()): ?>
                            <tr>
                                <td>#<?php echo $row['id']; ?></td>
                                <td><?php echo htmlspecialchars($row['customer_name'] ?? 'Unknown'); ?></td>
                                <td><?php echo htmlspecialchars($row['registration_no'] ?? 'N/A'); ?></td>
                                <td><span style="font-weight: 500; color: var(--text-main);"><?php echo htmlspecialchars($row['service_type'] ?? 'Maintenance'); ?></span></td>
                                <td><?php echo htmlspecialchars($row['complaint_text']); ?></td>
                                <td><?php echo htmlspecialchars($row['priority']); ?></td>
                                <td>
                                    <span class="badge <?php echo strtolower($row['status']); ?>">
                                        <?php echo htmlspecialchars($row['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <form method="POST" style="display: flex; gap: 8px; align-items: center;">
                                        <input type="hidden" name="action" value="update_status">
                                        <input type="hidden" name="request_id" value="<?php echo $row['id']; ?>">
                                        <select name="status" class="status-dropdown">
                                            <option value="Pending" <?php if($row['status']=='Pending') echo 'selected'; ?>>Pending</option>
                                            <option value="In Progress" <?php if($row['status']=='In Progress') echo 'selected'; ?>>In Progress</option>
                                            <option value="Completed" <?php if($row['status']=='Completed') echo 'selected'; ?>>Completed</option>
                                        </select>
                                        <button type="submit" class="btn-sm edit">Save</button>
                                    </form>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="7">No service requests found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>
</html>
