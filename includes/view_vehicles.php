<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'vehicle management');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch vehicles with customer info
$sql = "SELECT v.vehicle_id, v.brand, v.model, v.registration_no, v.registration_date, v.mileage, v.vehicle_age, c.name, c.email, c.phone, c.address
        FROM vehicles v
        JOIN customers c ON v.customer_id = c.customer_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<h2>Registered Vehicles</h2>";
    echo "<table border='1'>";
    echo "<tr><th>Vehicle ID</th><th>Brand</th><th>Model</th><th>Registration No</th><th>Registration Date</th><th>Mileage</th><th>Vehicle Age</th><th>Customer Name</th><th>Email</th><th>Phone</th><th>Address</th></tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["vehicle_id"] . "</td>";
        echo "<td>" . $row["brand"] . "</td>";
        echo "<td>" . $row["model"] . "</td>";
        echo "<td>" . $row["registration_no"] . "</td>";
        echo "<td>" . $row["registration_date"] . "</td>";
        echo "<td>" . $row["mileage"] . "</td>";
        echo "<td>" . $row["vehicle_age"] . "</td>";
        echo "<td>" . $row["name"] . "</td>";
        echo "<td>" . $row["email"] . "</td>";
        echo "<td>" . $row["phone"] . "</td>";
        echo "<td>" . $row["address"] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "No vehicles registered yet.";
}

$conn->close();
?>