<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "mini_erp";

$conn = new mysqli($host, $user, $pass);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
$conn->query($sql);

$conn->select_db($dbname);

$sqlUsers = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role INT NOT NULL
)";
$conn->query($sqlUsers);

$sqlProducts = "CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    status VARCHAR(20) NOT NULL
)";
$conn->query($sqlProducts);

$sqlPerformance = "CREATE TABLE IF NOT EXISTS performance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    score FLOAT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";
$conn->query($sqlPerformance);

$result = $conn->query("SELECT COUNT(*) AS total FROM users");
$row = $result->fetch_assoc();
if ($row['total'] == 0) {
    $conn->query("INSERT INTO users (username, password, role) VALUES
        ('admin', 'admin123', 3),
        ('staff1', 'staff123', 2),
        ('intern1', 'intern123', 1)
    ");
}

$result = $conn->query("SELECT COUNT(*) AS total FROM products");
$row = $result->fetch_assoc();
if ($row['total'] == 0) {
    $conn->query("INSERT INTO products (name, status) VALUES
        ('Laptop', 'in stock'),
        ('Monitor', 'out of stock'),
        ('Keyboard', 'in stock')
    ");
}

$result = $conn->query("SELECT COUNT(*) AS total FROM performance");
$row = $result->fetch_assoc();
if ($row['total'] == 0) {
    $conn->query("INSERT INTO performance (user_id, score) VALUES
        (2, 85),
        (3, 70)
    ");
}
?>
