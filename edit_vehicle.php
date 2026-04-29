<?php
require_once 'includes/db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

if (isset($_GET['id'])) {
    $vehicle_id = $_GET['id'];

    // Fetch vehicle and customer data
    $sql = "SELECT v.*, c.name, c.email, c.phone, c.address FROM vehicles v JOIN customers c ON v.customer_id = c.customer_id WHERE v.vehicle_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $vehicle_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "Vehicle not found.";
        exit;
    }
    $stmt->close();
} else {
    echo "No vehicle ID provided.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Vehicle - Vehicle Management</title>
    <link rel="stylesheet" href="css/admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
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
            <li><a href="admin_vehicles.php" class="active"><i class="fas fa-car" style="margin-right:10px;"></i> Vehicles</a></li>
            <li><a href="admin_employees.php"><i class="fas fa-user-tie" style="margin-right:10px;"></i> Employees</a></li>
            <li><a href="admin_requests.php"><i class="fas fa-tools" style="margin-right:10px;"></i> Service Requests</a></li>
            <li><a href="admin_reports.php"><i class="fas fa-chart-bar" style="margin-right:10px;"></i> Reports</a></li>
            <li style="margin-top: auto;"><a href="admin_logout.php"><i class="fas fa-sign-out-alt" style="margin-right:10px;"></i> Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="topbar">
            <h1>Edit Vehicle</h1>
            <div class="user-profile">
                <a href="admin_vehicles.php" class="btn-sm" style="text-decoration: none;"><i class="fas fa-arrow-left"></i> Back to Vehicles</a>
            </div>
        </div>

        <div class="data-card" style="max-width: 800px; margin: 0 auto;">
            <form action="update_vehicle.php" method="POST">
                <input type="hidden" name="vehicle_id" value="<?php echo $row['vehicle_id']; ?>">
                
                <h3 style="margin-bottom: 15px; color: var(--primary);">Customer Information</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($row['name']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($row['email']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone:</label>
                        <input type="text" id="phone" name="phone" class="form-control" value="<?php echo htmlspecialchars($row['phone']); ?>">
                    </div>
                    <div class="form-group">
                        <label for="address">Address:</label>
                        <textarea id="address" name="address" class="form-control" style="height: 42px;"><?php echo htmlspecialchars($row['address']); ?></textarea>
                    </div>
                </div>

                <hr style="border: 0; height: 1px; background: var(--border); margin: 25px 0;">

                <h3 style="margin-bottom: 15px; color: var(--primary);">Vehicle Information</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="brand">Brand:</label>
                        <input type="text" id="brand" name="brand" class="form-control" value="<?php echo htmlspecialchars($row['brand']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="model">Model:</label>
                        <input type="text" id="model" name="model" class="form-control" value="<?php echo htmlspecialchars($row['model']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="registration_no">Registration Number:</label>
                        <input type="text" id="registration_no" name="registration_no" class="form-control" value="<?php echo htmlspecialchars($row['registration_no']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="registration_date">Registration Date:</label>
                        <input type="date" id="registration_date" name="registration_date" class="form-control" value="<?php echo htmlspecialchars($row['registration_date']); ?>" required>
                    </div>
                    <div class="form-group" style="grid-column: span 2;">
                        <label for="mileage">Mileage:</label>
                        <input type="number" id="mileage" name="mileage" class="form-control" value="<?php echo htmlspecialchars($row['mileage']); ?>" required>
                    </div>
                </div>

                <div style="margin-top: 30px; text-align: right;">
                    <button type="submit" class="btn" style="width: auto; padding: 12px 30px;"><i class="fas fa-save"></i> Update Vehicle</button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>