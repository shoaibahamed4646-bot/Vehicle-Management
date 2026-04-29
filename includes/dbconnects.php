<?php
require_once 'db.php';


// Collect form data
$vehicle_id = $_POST['vehicle_id'];
$complaint = $_POST['complaint'];
$priority = $_POST['priority'];

// Insert data into service_requests table
$sql = "INSERT INTO service_requests (vehicle_id, complaint_text, priority, status) 
        VALUES ('$vehicle_id', '$complaint', '$priority', 'Pending')";

if ($conn->query($sql) === TRUE) {
    echo "New vehicle service request submitted successfully!";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>