<?php
// db.php - Database connection, table creation, and CRUD functions

// Database credentials
$servername = "localhost";
$username = "root";  // change if needed
$password = "";      // change if needed
$dbname = "customer_orders";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if it doesn't exist
$conn->query("CREATE DATABASE IF NOT EXISTS $dbname");
$conn->select_db($dbname);

// Create orders table if it doesn't exist
$conn->query("
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_name VARCHAR(100) NOT NULL,
    order_date DATE NOT NULL,
    mobile VARCHAR(20),
    email VARCHAR(100),
    door_flat_no VARCHAR(50),
    street_name VARCHAR(100),
    city VARCHAR(50),
    pincode VARCHAR(10)
) ENGINE=InnoDB;
");

// Create order_items table if it doesn't exist
$conn->query("
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    item_name VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
) ENGINE=InnoDB;
");

/* ===== Helper Functions ===== */

// Add a new order with items
function addOrder($conn, $data) {
    $stmt = $conn->prepare("INSERT INTO orders 
        (customer_name, order_date, mobile, email, door_flat_no, street_name, city, pincode)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param(
        "ssssssss",
        $data['customer_name'],
        $data['order_date'],
        $data['mobile'],
        $data['email'],
        $data['door_flat_no'],
        $data['street_name'],
        $data['city'],
        $data['pincode']
    );
    $stmt->execute();
    $order_id = $stmt->insert_id;

    // Insert items
    $stmt_item = $conn->prepare("INSERT INTO order_items (order_id, item_name, price) VALUES (?, ?, ?)");
    foreach ($data['items'] as $item) {
        $stmt_item->bind_param("isd", $order_id, $item['item_name'], $item['price']);
        $stmt_item->execute();
    }

    $stmt->close();
    $stmt_item->close();
    return $order_id;
}

// Get all orders with items
function getOrders($conn) {
    $orders = [];
    $res = $conn->query("SELECT * FROM orders ORDER BY order_date DESC");
    while ($row = $res->fetch_assoc()) {
        $order_id = $row['id'];
        $items_res = $conn->query("SELECT * FROM order_items WHERE order_id=$order_id");
        $items = [];
        while ($item = $items_res->fetch_assoc()) {
            $items[] = $item;
        }
        $row['items'] = $items;
        $orders[] = $row;
    }
    return $orders;
}

// Get a single order by ID
function getOrderById($conn, $id) {
    $stmt = $conn->prepare("SELECT * FROM orders WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $order = $stmt->get_result()->fetch_assoc();
    if ($order) {
        $items_res = $conn->query("SELECT * FROM order_items WHERE order_id=$id");
        $items = [];
        while ($item = $items_res->fetch_assoc()) {
            $items[] = $item;
        }
        $order['items'] = $items;
    }
    $stmt->close();
    return $order;
}

// Update an order
function updateOrder($conn, $id, $data) {
    $stmt = $conn->prepare("UPDATE orders SET customer_name=?, order_date=?, mobile=?, email=?, door_flat_no=?, street_name=?, city=?, pincode=? WHERE id=?");
    $stmt->bind_param(
        "ssssssssi",
        $data['customer_name'],
        $data['order_date'],
        $data['mobile'],
        $data['email'],
        $data['door_flat_no'],
        $data['street_name'],
        $data['city'],
        $data['pincode'],
        $id
    );
    $stmt->execute();

    // Delete old items and insert new ones
    $conn->query("DELETE FROM order_items WHERE order_id=$id");
    $stmt_item = $conn->prepare("INSERT INTO order_items (order_id, item_name, price) VALUES (?, ?, ?)");
    foreach ($data['items'] as $item) {
        $stmt_item->bind_param("isd", $id, $item['item_name'], $item['price']);
        $stmt_item->execute();
    }

    $stmt->close();
    $stmt_item->close();
}

// Delete an order
function deleteOrder($conn, $id) {
    $stmt = $conn->prepare("DELETE FROM orders WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}
?>
