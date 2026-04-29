<?php
require_once 'includes/db.php';

if (isset($_GET['id'])) {
    $vehicle_id = $_GET['id'];

    // Delete service requests first
    $stmt = $conn->prepare("DELETE FROM service_requests WHERE vehicle_id = (SELECT registration_no FROM vehicles WHERE vehicle_id = ?)");
    $stmt->bind_param("s", $vehicle_id);
    $stmt->execute();
    $stmt->close();

    // Delete vehicle
    $stmt = $conn->prepare("DELETE FROM vehicles WHERE vehicle_id = ?");
    $stmt->bind_param("s", $vehicle_id);
    if ($stmt->execute()) {
        header("Location: admin_vehicles.php");
        exit();
    } else {
        echo "Error deleting vehicle: " . $stmt->error;
    }
    $stmt->close();
}

$conn->close();
?>