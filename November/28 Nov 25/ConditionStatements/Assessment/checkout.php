<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' ||
    !isset($_POST['product_id'], $_POST['quantity'], $_POST['destination'], $_POST['weight'])) {
    header('Location: index.php');
    exit;
}

$product_id = (int)$_POST['product_id'];
$quantity = max(1, (int)$_POST['quantity']);
$destination = $_POST['destination'];
$weight = max(0.1, (float)$_POST['weight']);

$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch();

if (!$product) {
    die("Product not found.");
}

$price = $product['price'] * $quantity;
$isPremium = $_SESSION['user']['is_premium'];

if ($isPremium) $discount = 0.20;
elseif ($price > 200) $discount = 0.15;
elseif ($price > 100) $discount = 0.10;
else $discount = 0.05;

$discountedPrice = $price * (1 - $discount);

if ($destination === 'domestic') {
    $shipping = ($weight < 5) ? 10 : 20;
} else {
    $shipping = ($weight < 5) ? 30 : 50;
}

$totalPrice = $discountedPrice + $shipping;

$stmt = $pdo->prepare("INSERT INTO orders 
    (user_id, product_id, product_name, quantity, destination, weight, total_price) 
    VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->execute([
    $_SESSION['user']['id'],
    $product_id,
    $product['name'],
    $quantity,
    $destination,
    $weight,
    $totalPrice
]);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Checkout Summary</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="font-inter bg-gray-50 min-h-screen flex flex-col">
    <header class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <span class="material-icons text-indigo-600 text-2xl mr-2">shopping_bag</span>
                    <h1 class="text-xl font-semibold text-gray-900">ShopHub</h1>
                </div>
                <nav class="flex space-x-8">
                    <a href="index.php" class="text-gray-600 hover:text-gray-900 transition-colors duration-200 flex items-center">
                        <span class="material-icons text-lg mr-1">home</span>
                        Home
                    </a>
                </nav>
            </div>
        </div>
    </header>

    <main class="flex-grow flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="bg-indigo-600 px-6 py-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-white">Order Confirmed</h2>
                    <span class="material-icons text-white">check_circle</span>
                </div>
            </div>
            
            <div class="p-6">
                <div class="flex items-center justify-center mb-6">
                    <div class="bg-green-50 rounded-full p-3">
                        <span class="material-icons text-green-500 text-3xl">check</span>
                    </div>
                </div>

                <div class="space-y-4 mb-6">
                    <div class="flex justify-between items-center py-3 border-b border-gray-100">
                        <span class="text-gray-600 font-medium">Product</span>
                        <span class="text-gray-900 font-semibold"><?= htmlspecialchars($product['name']) ?></span>
                    </div>
                    
                    <div class="flex justify-between items-center py-3 border-b border-gray-100">
                        <span class="text-gray-600 font-medium">Quantity</span>
                        <span class="text-gray-900 font-semibold"><?= $quantity ?></span>
                    </div>
                    
                    <div class="flex justify-between items-center py-3 border-b border-gray-100">
                        <span class="text-gray-600 font-medium">Base Price</span>
                        <span class="text-gray-900 font-semibold">$<?= number_format($price, 2) ?></span>
                    </div>
                    
                    <div class="flex justify-between items-center py-3 border-b border-gray-100">
                        <span class="text-gray-600 font-medium">Discount</span>
                        <span class="text-green-600 font-semibold"><?= $discount * 100 ?>%</span>
                    </div>
                    
                    <div class="flex justify-between items-center py-3 border-b border-gray-100">
                        <span class="text-gray-600 font-medium">Discounted Price</span>
                        <span class="text-gray-900 font-semibold">$<?= number_format($discountedPrice, 2) ?></span>
                    </div>
                    
                    <div class="flex justify-between items-center py-3 border-b border-gray-100">
                        <span class="text-gray-600 font-medium">Shipping</span>
                        <span class="text-gray-900 font-semibold">$<?= number_format($shipping, 2) ?></span>
                    </div>
                    
                    <div class="flex justify-between items-center py-3 bg-gray-50 -mx-6 px-6">
                        <span class="text-lg font-semibold text-gray-900">Total Amount</span>
                        <span class="text-xl font-bold text-indigo-600">$<?= number_format($totalPrice, 2) ?></span>
                    </div>
                </div>

                <div class="flex space-x-4">
                    <a href="index.php" class="flex-1 bg-indigo-600 text-white py-3 px-4 rounded-lg font-medium text-center hover:bg-indigo-700 transition-colors duration-200 flex items-center justify-center">
                        <span class="material-icons text-lg mr-2">shopping_cart</span>
                        Continue Shopping
                    </a>
                    <a href="orders.php" class="flex-1 border border-gray-300 text-gray-700 py-3 px-4 rounded-lg font-medium text-center hover:bg-gray-50 transition-colors duration-200 flex items-center justify-center">
                        <span class="material-icons text-lg mr-2">receipt</span>
                        View Orders
                    </a>
                </div>
            </div>
        </div>
    </main>

    <footer class="bg-white border-t border-gray-200 mt-auto">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="col-span-1">
                    <div class="flex items-center">
                        <span class="material-icons text-indigo-600 text-2xl mr-2">shopping_bag</span>
                        <span class="text-xl font-semibold text-gray-900">ShopEase</span>
                    </div>
                    <p class="mt-4 text-gray-600 text-sm">Your trusted partner for seamless online shopping experiences.</p>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider">Shop</h3>
                    <ul class="mt-4 space-y-2">
                        <li><a href="#" class="text-gray-600 hover:text-gray-900 text-sm">All Products</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-gray-900 text-sm">New Arrivals</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-gray-900 text-sm">Featured</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider">Support</h3>
                    <ul class="mt-4 space-y-2">
                        <li><a href="#" class="text-gray-600 hover:text-gray-900 text-sm">Contact Us</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-gray-900 text-sm">Shipping Info</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-gray-900 text-sm">Returns</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider">Company</h3>
                    <ul class="mt-4 space-y-2">
                        <li><a href="#" class="text-gray-600 hover:text-gray-900 text-sm">About Us</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-gray-900 text-sm">Careers</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-gray-900 text-sm">Privacy Policy</a></li>
                    </ul>
                </div>
            </div>
            <div class="mt-8 pt-8 border-t border-gray-200 flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-500 text-sm">&copy; 2024 SavageInfo. All rights reserved.</p>
                <div class="mt-4 md:mt-0 flex space-x-6">
                    <a href="#" class="text-gray-400 hover:text-gray-500">
                        <span class="material-icons">facebook</span>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-gray-500">
                        <span class="material-icons">twitter</span>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-gray-500">
                        <span class="material-icons">instagram</span>
                    </a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>