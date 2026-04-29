<?php
$conn = new mysqli('localhost', 'root', '', 'vehicle management');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$address = $_POST['address'];
$vehicle_id = $_POST['vehicle_id'];
$brand = $_POST['brand'];
$model = $_POST['model'];
$registration_no = $_POST['registration_no'];
$registration_date = $_POST['registration_date'];
$mileage = $_POST['mileage'];
$priority = $_POST['priority'];

// Calculate vehicle age
$reg_date_timestamp = strtotime($registration_date);
$current_timestamp = time();
$vehicle_age = floor(($current_timestamp - $reg_date_timestamp) / (60 * 60 * 24 * 365));

// Check if customer exists
$stmt = $conn->prepare("SELECT customer_id FROM customers WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $customer_id = $row['customer_id'];
} else {
    // Insert customer
    $stmt = $conn->prepare("INSERT INTO customers (name, email, phone, address) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $phone, $address);
    if ($stmt->execute()) {
        $customer_id = $conn->insert_id;
    } else {
        echo "Error inserting customer: " . $stmt->error;
        $conn->close();
        exit;
    }
}
$stmt->close();

// Check if registration_no exists
$stmt = $conn->prepare("SELECT registration_no FROM vehicles WHERE registration_no = ?");
$stmt->bind_param("s", $registration_no);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    echo "Error: Registration number already exists.";
    $conn->close();
    exit;
}
$stmt->close();

// Insert vehicle
$stmt = $conn->prepare("INSERT INTO vehicles (vehicle_id, customer_id, brand, model, registration_no, registration_date, mileage, vehicle_age) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sissssii", $vehicle_id, $customer_id, $brand, $model, $registration_no, $registration_date, $mileage, $vehicle_age);
if ($stmt->execute()) {
    echo "Vehicle registered successfully!";

    // If priority is set, insert service request
    if (!empty($priority)) {
        $complaint = "Vehicle registration service request";
        $stmt2 = $conn->prepare("INSERT INTO service_requests (vehicle_id, complaint_text, priority) VALUES (?, ?, ?)");
        $stmt2->bind_param("sss", $registration_no, $complaint, $priority);
        $stmt2->execute();
        $stmt2->close();
        echo " Service request submitted.";
    }
} else {
    echo "Error registering vehicle: " . $stmt->error;
}

$conn->close();
?>