<?php
$host = 'localhost';
$db   = 'ecommerces';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

try {
    // Connect to MySQL server (without specifying DB first)
    $pdo = new PDO("mysql:host=$host;charset=$charset", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create database if it doesn't exist
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$db` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
    $pdo->exec("USE `$db`");

    // Create 'users' table
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(50) NOT NULL,
        email VARCHAR(50) NOT NULL UNIQUE,
        is_premium TINYINT(1) DEFAULT 0
    )");

    // Create 'products' table
    $pdo->exec("CREATE TABLE IF NOT EXISTS products (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(50) NOT NULL,
        price DECIMAL(10,2) NOT NULL
    )");

    // Create 'orders' table
    $pdo->exec("CREATE TABLE IF NOT EXISTS orders (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        product_id INT NOT NULL,
        product_name VARCHAR(50) NOT NULL,
        quantity INT NOT NULL,
        destination VARCHAR(20) NOT NULL,
        weight DECIMAL(5,2) NOT NULL,
        total_price DECIMAL(10,2) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    // Optionally, insert sample products if table is empty
  $stmt = $pdo->query("SELECT COUNT(*) FROM products");
if ($stmt->fetchColumn() == 0) {
    $pdo->exec("INSERT INTO products (name, price) VALUES
        ('Rice', 40),
        ('Wheat', 35),
        ('Dhall', 60),
        ('Sugar', 30)
    ");
}


    // Connection ready
    // echo "Database and tables ready!";
} catch (PDOException $e) {
    die("DB Error: " . $e->getMessage());
}
?>
