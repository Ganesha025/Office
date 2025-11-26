<?php

$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "ecommerce";

// Base URL (optional, for reference)
$baseURL = "http://localhost/samplesavage/";

// Connect to MySQL
$conn = new mysqli($servername, $username, $password);
if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}
$conn->query("CREATE DATABASE IF NOT EXISTS $dbname");
$conn->select_db($dbname);
$conn->query("
    CREATE TABLE IF NOT EXISTS products (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255),
        price DECIMAL(10,2),
        description TEXT,
        image VARCHAR(500),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )
");
?>
