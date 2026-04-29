<?php
require_once 'includes/db.php';

if (!isset($_SESSION['customer_id'])) {
    header("Location: customer_login.php");
    exit();
}

$customer_id = $_SESSION['customer_id'];
$success = '';
$error = '';

// Handle Service Request Submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'submit_request') {
    // Vehicle Info
    $vehicle_model = $conn->real_escape_string($_POST['vehicle_model']);
    $registration_no = $conn->real_escape_string($_POST['registration_no']);
    $mileage = (int)$_POST['vehicle_mileage'];

    // Service Info
    $service_type = $conn->real_escape_string($_POST['service_type']);
    $complaint = $conn->real_escape_string($_POST['complaint']);
    $priority = $conn->real_escape_string($_POST['priority']);

    // Check if vehicle exists for this registration number
    $check_vehicle = $conn->query("SELECT vehicle_id, customer_id FROM vehicles WHERE registration_no = '$registration_no'");
    
    if ($check_vehicle->num_rows > 0) {
        $v_row = $check_vehicle->fetch_assoc();
        // If it belongs to another customer, we probably shouldn't allow it, but let's assume it's theirs or they just bought it.
        // For simplicity, we just use the vehicle ID. If they don't own it, we should throw an error.
        if ($v_row['customer_id'] != $customer_id) {
            $error = "This registration number is already registered to another account.";
        } else {
            $vehicle_id = $v_row['vehicle_id'];
            // Update mileage and model just in case
            $conn->query("UPDATE vehicles SET model = '$vehicle_model', mileage = $mileage WHERE vehicle_id = $vehicle_id");
        }
    } else {
        // Register new vehicle
        $brand = "Unknown"; // Defaulting brand
        $v_id = uniqid('V_'); // Generate unique ID since vehicle_id is varchar(255) and not auto-increment
        $reg_date = date('Y-m-d');
        $age = 0;
        
        $insert_vehicle = "INSERT INTO vehicles (vehicle_id, customer_id, brand, model, registration_no, registration_date, mileage, vehicle_age) VALUES ('$v_id', $customer_id, '$brand', '$vehicle_model', '$registration_no', '$reg_date', $mileage, $age)";
        if ($conn->query($insert_vehicle)) {
            $vehicle_id = $conn->insert_id;
        } else {
            $error = "Failed to register new vehicle: " . $conn->error;
        }
    }

    // Insert Service Request if we have a valid vehicle_id
    if (isset($vehicle_id) && !$error) {
        // Note: In this schema, service_requests.vehicle_id actually stores the registration_no
        $sql = "INSERT INTO service_requests (vehicle_id, service_type, complaint_text, priority, status) VALUES ('$registration_no', '$service_type', '$complaint', '$priority', 'Pending')";
        if ($conn->query($sql)) {
            $success = "Service request submitted successfully! Our team will review it soon.";
        } else {
            $error = "Failed to submit service request: " . $conn->error;
        }
    }
}

// Fetch Customer's Service Requests
$requests = $conn->query("
    SELECT sr.*, v.model, v.registration_no 
    FROM service_requests sr 
    JOIN vehicles v ON sr.vehicle_id = v.registration_no 
    WHERE v.customer_id = $customer_id 
    ORDER BY sr.id DESC
");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard - Vehicle Management</title>
    <link rel="stylesheet" href="css/admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .section-title {
            margin: 20px 0 15px 0;
            padding-bottom: 5px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            font-size: 1.1rem;
            color: var(--primary);
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="sidebar-header">
            <h2><i class="fas fa-user"></i> Customer Portal</h2>
        </div>
        <ul class="nav-links">
            <li><a href="customer_dashboard.php" class="active"><i class="fas fa-home" style="margin-right:10px;"></i> Dashboard</a></li>
            <li style="margin-top: auto;"><a href="customer_logout.php"><i class="fas fa-sign-out-alt" style="margin-right:10px;"></i> Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="topbar">
            <h1>Welcome, <?php echo htmlspecialchars($_SESSION['customer_name']); ?>!</h1>
        </div>

        <?php if($success) echo "<div style='background:rgba(16, 185, 129, 0.2); border:1px solid var(--success); color:#a7f3d0; padding:15px; border-radius:8px; margin-bottom:20px;'>$success</div>"; ?>
        <?php if($error) echo "<div style='background:rgba(239, 68, 68, 0.2); border:1px solid var(--danger); color:#fca5a5; padding:15px; border-radius:8px; margin-bottom:20px;'>$error</div>"; ?>

        <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 24px;">
            
            <!-- Submit Service Request Form -->
            <div class="data-card">
                <div class="data-card-header">
                    <h2><i class="fas fa-plus-circle"></i> New Service Request</h2>
                </div>
                <form method="POST" action="">
                    <input type="hidden" name="action" value="submit_request">
                    
                    <h3 class="section-title">Vehicle Information</h3>
                    <div class="form-group">
                        <label>Vehicle Model (গাড়ির মডেল নাম)</label>
                        <input type="text" name="vehicle_model" class="form-control" required placeholder="e.g. Toyota Corolla">
                    </div>
                    <div class="form-group">
                        <label>Registration Number (নিবন্ধন নম্বর)</label>
                        <input type="text" name="registration_no" class="form-control" required placeholder="e.g. DHK-1234">
                    </div>
                    <div class="form-group">
                        <label>Mileage (কিলোমিটার)</label>
                        <input type="number" name="vehicle_mileage" class="form-control" required placeholder="e.g. 15000">
                    </div>

                    <h3 class="section-title">Service Details</h3>
                    <div class="form-group">
                        <label>Service Type (সেবা টাইপ)</label>
                        <select name="service_type" class="form-control" required style="background: rgba(15,23,42,0.8);">
                            <option value="Maintenance">Maintenance (মেইন্টেনেন্স)</option>
                            <option value="Repair">Repair (মেরামত)</option>
                            <option value="Inspection">Inspection (ইন্সপেকশন)</option>
                            <option value="Customization">Customization (কাস্টমাইজেশন)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Service Description (বিবরণ)</label>
                        <textarea name="complaint" class="form-control" rows="3" required placeholder="What do you need help with?"></textarea>
                    </div>

                    <div class="form-group">
                        <label>Priority (প্রাধান্য)</label>
                        <select name="priority" class="form-control" required style="background: rgba(15,23,42,0.8);">
                            <option value="Low">Low</option>
                            <option value="Medium" selected>Medium</option>
                            <option value="High">High</option>
                        </select>
                    </div>

                    <button type="submit" class="btn"><i class="fas fa-paper-plane"></i> Submit Request</button>
                </form>
            </div>

            <!-- Previous Service Requests -->
            <div class="data-card">
                <div class="data-card-header">
                    <h2><i class="fas fa-history"></i> Your Service History</h2>
                </div>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Vehicle</th>
                                <th>Service Type</th>
                                <th>Description</th>
                                <th>Priority</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($requests && $requests->num_rows > 0): ?>
                                <?php while($row = $requests->fetch_assoc()): ?>
                                <tr>
                                    <td>#<?php echo $row['id']; ?></td>
                                    <td><?php echo htmlspecialchars($row['model']); ?><br><small style="color:var(--text-muted);"><?php echo htmlspecialchars($row['registration_no']); ?></small></td>
                                    <td><span style="font-weight: 500; color: var(--text-main);"><?php echo htmlspecialchars($row['service_type'] ?? 'Maintenance'); ?></span></td>
                                    <td><?php echo htmlspecialchars($row['complaint_text']); ?></td>
                                    <td><?php echo htmlspecialchars($row['priority']); ?></td>
                                    <td>
                                        <span class="badge <?php echo strtolower($row['status']); ?>">
                                            <?php echo htmlspecialchars($row['status']); ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="6" style="text-align: center; padding: 30px;">You have no service requests yet.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

</body>
</html>
