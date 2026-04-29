<?php
require_once 'includes/db.php';

// Collect form data
$vehicle_id = $_POST['vehicle_id'];
$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$address = $_POST['address'];
$brand = $_POST['brand'];
$model = $_POST['model'];
$registration_no = $_POST['registration_no'];
$registration_date = $_POST['registration_date'];
$mileage = $_POST['mileage'];

// Calculate vehicle age
$reg_date_timestamp = strtotime($registration_date);
$current_timestamp = time();
$vehicle_age = floor(($current_timestamp - $reg_date_timestamp) / (60 * 60 * 24 * 365));

// Get current customer_id
$stmt = $conn->prepare("SELECT customer_id FROM vehicles WHERE vehicle_id = ?");
$stmt->bind_param("s", $vehicle_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$customer_id = $row['customer_id'];
$stmt->close();

// Update customer
$stmt = $conn->prepare("UPDATE customers SET name = ?, email = ?, phone = ?, address = ? WHERE customer_id = ?");
$stmt->bind_param("ssssi", $name, $email, $phone, $address, $customer_id);
if (!$stmt->execute()) {
    echo "Error updating customer: " . $stmt->error;
    $conn->close();
    exit;
}
$stmt->close();

// Check if new registration 

$stmt = $conn->prepare("SELECT registration_no FROM vehicles WHERE registration_no = ? AND vehicle_id != ?");
$stmt->bind_param("ss", $registration_no, $vehicle_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    echo "Error: Registration number already exists for another vehicle.";
    $conn->close();
    exit;
}
$stmt->close();

// Update vehicle
$stmt = $conn->prepare("UPDATE vehicles SET brand = ?, model = ?, registration_no = ?, registration_date = ?, mileage = ?, vehicle_age = ? WHERE vehicle_id = ?");
$stmt->bind_param("sssssis", $brand, $model, $registration_no, $registration_date, $mileage, $vehicle_age, $vehicle_id);
if ($stmt->execute()) {
    header("Location: admin_vehicles.php");
    exit();
} else {
    echo "Error updating vehicle: " . $stmt->error;
}

$conn->close();
?>