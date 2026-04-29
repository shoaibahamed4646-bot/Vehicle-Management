<?php
require_once 'includes/db.php';

$tables = [
    "CREATE TABLE IF NOT EXISTS admins (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        email VARCHAR(100) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
    "CREATE TABLE IF NOT EXISTS employees (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        position VARCHAR(50) NOT NULL,
        location VARCHAR(100) NOT NULL,
        status ENUM('Active', 'Inactive') DEFAULT 'Active',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
    "CREATE TABLE IF NOT EXISTS customers (
        customer_id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL,
        phone VARCHAR(20) NOT NULL,
        password VARCHAR(255) NOT NULL,
        address TEXT
    )",
    "CREATE TABLE IF NOT EXISTS vehicles (
        vehicle_id INT AUTO_INCREMENT PRIMARY KEY,
        customer_id INT,
        brand VARCHAR(50),
        model VARCHAR(50),
        registration_no VARCHAR(50) UNIQUE,
        FOREIGN KEY (customer_id) REFERENCES customers(customer_id) ON DELETE CASCADE
    )",
    "CREATE TABLE IF NOT EXISTS service_requests (
        id INT AUTO_INCREMENT PRIMARY KEY,
        vehicle_id INT,
        service_type VARCHAR(50),
        complaint_text TEXT,
        priority VARCHAR(20),
        status VARCHAR(20) DEFAULT 'Pending',
        FOREIGN KEY (vehicle_id) REFERENCES vehicles(vehicle_id) ON DELETE CASCADE
    )",
    "CREATE TABLE IF NOT EXISTS payments (
        id INT AUTO_INCREMENT PRIMARY KEY,
        customer_id INT,
        amount DECIMAL(10, 2),
        payment_date DATE,
        status VARCHAR(20) DEFAULT 'Completed',
        FOREIGN KEY (customer_id) REFERENCES customers(customer_id) ON DELETE CASCADE
    )"
];

$success = true;
foreach ($tables as $sql) {
    if (!$conn->query($sql)) {
        echo "Error creating table: " . $conn->error . "<br>";
        $success = false;
    }
}

// Insert default admin
$password = password_hash('admin123', PASSWORD_DEFAULT);
$admin_sql = "INSERT INTO admins (username, email, password) VALUES ('admin', 'admin@example.com', '$password') ON DUPLICATE KEY UPDATE id=id";
if (!$conn->query($admin_sql)) {
    echo "Error inserting default admin: " . $conn->error . "<br>";
    $success = false;
}

if ($success) {
    echo "<h1>Database setup complete!</h1>";
    echo "<p>Database and tables have been created successfully.</p>";
    echo "<p>Default Admin credentials:</p>";
    echo "<ul><li>Username: admin</li><li>Password: admin123</li></ul>";
    echo "<a href='admin_login.php'>Go to Admin Login</a>";
}
?>
