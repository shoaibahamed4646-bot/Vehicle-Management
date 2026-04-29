<?php
require_once 'includes/db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM customers WHERE customer_id = $id");
    header("Location: admin_customers.php");
    exit();
}

// Fetch Customers
$customers = $conn->query("SELECT * FROM customers ORDER BY customer_id DESC");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Customers - Vehicle Management</title>
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
            <li><a href="admin_customers.php" class="active"><i class="fas fa-users" style="margin-right:10px;"></i> Customers</a></li>
            <li><a href="admin_vehicles.php"><i class="fas fa-car" style="margin-right:10px;"></i> Vehicles</a></li>
            <li><a href="admin_employees.php"><i class="fas fa-user-tie" style="margin-right:10px;"></i> Employees</a></li>
            <li><a href="admin_requests.php"><i class="fas fa-tools" style="margin-right:10px;"></i> Service Requests</a></li>
            <li><a href="admin_reports.php"><i class="fas fa-chart-bar" style="margin-right:10px;"></i> Reports</a></li>
            <li style="margin-top: auto;"><a href="admin_logout.php"><i class="fas fa-sign-out-alt" style="margin-right:10px;"></i> Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="topbar">
            <h1>Manage Customers</h1>
            <div class="user-profile">
                <div style="background: rgba(255,255,255,0.1); padding: 8px 16px; border-radius: 20px; display: flex; align-items: center; gap: 10px;">
                    <i class="fas fa-user-circle" style="font-size: 1.5rem;"></i>
                    <span><?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
                </div>
            </div>
        </div>

        <div class="data-card">
            <div class="data-card-header">
                <h2>Customer List</h2>
                <!-- Future enhancement: Add Customer button opening a modal -->
            </div>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($customers->num_rows > 0): ?>
                            <?php while($row = $customers->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['customer_id']; ?></td>
                                <td><?php echo htmlspecialchars($row['name']); ?></td>
                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                <td><?php echo htmlspecialchars($row['phone']); ?></td>
                                <td>
                                    <a href="#" class="btn-sm edit" onclick="alert('Edit functionality goes here.')"><i class="fas fa-edit"></i> Edit</a>
                                    <a href="?delete=<?php echo $row['customer_id']; ?>" class="btn-sm delete" onclick="return confirm('Are you sure you want to delete this customer?');"><i class="fas fa-trash"></i> Delete</a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="5">No customers found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>
</html>
