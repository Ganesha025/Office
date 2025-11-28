<?php
// db.php - Database connection and table setup for Library Management System

$host = "localhost";       // Usually localhost
$username = "root";        // Your MySQL username
$password = "";            // Your MySQL password
$database = "library_db";  // Database name

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Optional: set character set to UTF-8 for Indian languages
$conn->set_charset("utf8");

// SQL to create 'books' table if it doesn't exist
$tableSql = "CREATE TABLE IF NOT EXISTS books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(255) NOT NULL,
    year INT NOT NULL,
    category VARCHAR(50) NOT NULL,
    status ENUM('available','borrowed') DEFAULT 'available',
    borrower VARCHAR(255),
    borrow_date DATE,
    due_date DATE,
    return_date DATE,
    penalty DECIMAL(10,2) DEFAULT 0
)";

if ($conn->query($tableSql) === FALSE) {
    die("Error creating table: " . $conn->error);
}

?>
