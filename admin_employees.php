<?php
require_once 'includes/db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Handle Add/Edit
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] == 'add') {
        $name = $conn->real_escape_string($_POST['name']);
        $position = $conn->real_escape_string($_POST['position']);
        $location = $conn->real_escape_string($_POST['location']);
        $conn->query("INSERT INTO employees (name, position, location) VALUES ('$name', '$position', '$location')");
    }
    header("Location: admin_employees.php");
    exit();
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM employees WHERE id = $id");
    header("Location: admin_employees.php");
    exit();
}

// Fetch Employees
$employees = $conn->query("SELECT * FROM employees ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Employees - Vehicle Management</title>
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
            <li><a href="admin_employees.php" class="active"><i class="fas fa-user-tie" style="margin-right:10px;"></i> Employees</a></li>
            <li><a href="admin_requests.php"><i class="fas fa-tools" style="margin-right:10px;"></i> Service Requests</a></li>
            <li><a href="admin_reports.php"><i class="fas fa-chart-bar" style="margin-right:10px;"></i> Reports</a></li>
            <li style="margin-top: auto;"><a href="admin_logout.php"><i class="fas fa-sign-out-alt" style="margin-right:10px;"></i> Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="topbar">
            <h1>Manage Employees</h1>
            <div class="user-profile">
                <div style="background: rgba(255,255,255,0.1); padding: 8px 16px; border-radius: 20px; display: flex; align-items: center; gap: 10px;">
                    <i class="fas fa-user-circle" style="font-size: 1.5rem;"></i>
                    <span><?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
                </div>
            </div>
        </div>

        <div class="data-card">
            <div class="data-card-header">
                <h2>Employee List</h2>
                <button onclick="document.getElementById('addModal').style.display='block'" class="btn" style="width: auto;"><i class="fas fa-plus"></i> Add Employee</button>
            </div>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Position</th>
                            <th>Location</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($employees->num_rows > 0): ?>
                            <?php while($row = $employees->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo htmlspecialchars($row['name']); ?></td>
                                <td><?php echo htmlspecialchars($row['position']); ?></td>
                                <td><?php echo htmlspecialchars($row['location']); ?></td>
                                <td><span class="badge active"><?php echo htmlspecialchars($row['status']); ?></span></td>
                                <td>
                                    <!-- Assignment feature would link to an assignment page -->
                                    <a href="#" class="btn-sm edit" onclick="alert('Assignment feature placeholder.')"><i class="fas fa-tasks"></i> Assign</a>
                                    <a href="?delete=<?php echo $row['id']; ?>" class="btn-sm delete" onclick="return confirm('Are you sure you want to delete this employee?');"><i class="fas fa-trash"></i> Delete</a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="6">No employees found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Modal -->
    <div id="addModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="document.getElementById('addModal').style.display='none'">&times;</span>
            <h2 style="margin-bottom: 20px;">Add Employee</h2>
            <form method="POST">
                <input type="hidden" name="action" value="add">
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Position</label>
                    <input type="text" name="position" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Location</label>
                    <input type="text" name="location" class="form-control" required>
                </div>
                <button type="submit" class="btn">Save Employee</button>
            </form>
        </div>
    </div>

</body>
</html>
