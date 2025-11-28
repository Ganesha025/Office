<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$products = $pdo->query("SELECT * FROM products")->fetchAll();

$ordersStmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
$ordersStmt->execute([$_SESSION['user']['id']]);
$orders = $ordersStmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Selection & Orders</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.slim.js"></script>
<link rel="stylesheet" href="../styles.css">
</head>
<body class="min-h-screen flex flex-col bg-gray-50">

    <header class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center space-x-3">
                    <span class="material-icons text-blue-600 text-3xl">shopping_cart</span>
                    <span class="text-xl font-bold text-gray-900">ShopHub</span>
                </div>
                <div class="flex items-center space-x-6">
                    <div class="hidden md:flex items-center space-x-2 bg-blue-50 px-4 py-2 rounded-lg">
                        <span class="material-icons text-blue-600 text-sm">person</span>
                        <span class="text-sm font-medium text-gray-700"><?= htmlspecialchars($_SESSION['user']['name']) ?></span>
                        <?php if ($_SESSION['user']['is_premium']): ?>
                        <span class="material-icons text-yellow-500 text-sm">stars</span>
                        <?php endif; ?>
                    </div>
                    <a href="logout.php" class="flex items-center space-x-1 text-gray-600 hover:text-red-600 transition">
                        <span class="material-icons text-xl">logout</span>
                        <span class="hidden sm:inline text-sm font-medium">Logout</span>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Welcome Back!</h1>
            <p class="text-gray-600">Select products and complete your checkout with exclusive discounts</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
            
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
                    <div class="bg-blue-600 px-6 py-4">
                        <h2 class="text-xl font-bold text-white flex items-center space-x-2">
                            <span class="material-icons">add_shopping_cart</span>
                            <span>New Order</span>
                        </h2>
                    </div>
                    
                    <form method="POST" action="checkout.php" class="p-6 space-y-5">
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Select Product</label>
                            <div class="relative">
                                <span class="material-icons absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">inventory_2</span>
                                <select name="product_id" required class="w-full pl-11 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition appearance-none bg-white">
                                    <?php foreach ($products as $p): ?>
                                    <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['name']) ?> - $<?= $p['price'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Quantity</label>
                            <div class="relative">
                                <span class="material-icons absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">format_list_numbered</span>
                                <input type="number" name="quantity" value="1" min="1" required class="val-mark w-full pl-11 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Destination</label>
                            <div class="relative">
                                <span class="material-icons absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">public</span>
                                <select name="destination" required class="w-full pl-11 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition appearance-none bg-white">
                                    <option value="domestic">Domestic</option>
                                    <option value="international">International</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Weight (kg)</label>
                            <div class="relative">
                                <span class="material-icons absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">scale</span>
                                <input type="number" name="weight" step="0.1" min="0.1" required class="val-mark w-full pl-11 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition">
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition flex items-center justify-center space-x-2 shadow-md hover:shadow-lg">
                            <span>Proceed to Checkout</span>
                            <span class="material-icons">arrow_forward</span>
                        </button>
                    </form>
                </div>

                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mt-6">
                    <h3 class="font-semibold text-gray-900 mb-3 flex items-center space-x-2">
                        <span class="material-icons text-blue-600">local_offer</span>
                        <span>Discount Rules</span>
                    </h3>
                    <ul class="space-y-2 text-sm text-gray-700">
                        <li class="flex items-start space-x-2">
                            <span class="material-icons text-yellow-500 text-base">stars</span>
                            <span>Premium members: 20% off</span>
                        </li>
                        <li class="flex items-start space-x-2">
                            <span class="material-icons text-green-600 text-base">check_circle</span>
                            <span>Above $200: 15% off</span>
                        </li>
                        <li class="flex items-start space-x-2">
                            <span class="material-icons text-green-600 text-base">check_circle</span>
                            <span>Above $100: 10% off</span>
                        </li>
                        <li class="flex items-start space-x-2">
                            <span class="material-icons text-blue-600 text-base">check_circle</span>
                            <span>Default: 5% off</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
                    <div class="bg-gray-900 px-6 py-4 flex items-center justify-between">
                        <h2 class="text-xl font-bold text-white flex items-center space-x-2">
                            <span class="material-icons">history</span>
                            <span>Order History</span>
                        </h2>
                        <span class="bg-white text-gray-900 text-xs font-bold px-3 py-1 rounded-full"><?= count($orders) ?> Orders</span>
                    </div>

                    <?php if (count($orders) === 0): ?>
                    <div class="p-12 text-center">
                        <span class="material-icons text-gray-300 text-6xl mb-4">shopping_bag</span>
                        <p class="text-gray-500 font-medium">No orders yet</p>
                        <p class="text-gray-400 text-sm mt-1">Start shopping to see your orders here</p>
                    </div>
                    <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Order ID</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Product</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Qty</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Destination</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Weight</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Total</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Date</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <?php foreach ($orders as $o): ?>
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm font-mono text-gray-900">#<?= $o['id'] ?></span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-sm font-medium text-gray-900"><?= htmlspecialchars($o['product_name']) ?></span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm text-gray-700"><?= $o['quantity'] ?></span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center space-x-1 px-2 py-1 rounded-full text-xs font-medium <?= $o['destination'] === 'domestic' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' ?>">
                                            <span class="material-icons text-sm"><?= $o['destination'] === 'domestic' ? 'home' : 'flight' ?></span>
                                            <span><?= ucfirst($o['destination']) ?></span>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm text-gray-700"><?= $o['weight'] ?> kg</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm font-bold text-gray-900">$<?= number_format($o['total_price'], 2) ?></span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm text-gray-600"><?= date('M d, Y', strtotime($o['created_at'])) ?></span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <footer class="bg-white border-t border-gray-200 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center space-x-2 mb-4">
                        <span class="material-icons text-blue-600 text-2xl">shopping_cart</span>
                        <span class="text-lg font-bold text-gray-900">ShopHub</span>
                    </div>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        Your trusted e-commerce platform with exclusive discounts and worldwide shipping. Shop smart, save more.
                    </p>
                </div>
                
                <div>
                    <h3 class="font-semibold text-gray-900 mb-4">Shop</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="text-gray-600 hover:text-blue-600 transition">Products</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-blue-600 transition">Categories</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-blue-600 transition">Deals</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-blue-600 transition">Premium</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="font-semibold text-gray-900 mb-4">Support</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="text-gray-600 hover:text-blue-600 transition">Help Center</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-blue-600 transition">Shipping Info</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-blue-600 transition">Returns</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-blue-600 transition">Contact Us</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-200 mt-8 pt-6 flex flex-col sm:flex-row justify-between items-center space-y-4 sm:space-y-0">
                <p class="text-gray-500 text-sm">© 2025 SavageInfo. All rights reserved.</p>
                <div class="flex items-center space-x-6">
                    <a href="#" class="text-gray-400 hover:text-gray-600 transition">
                        <span class="material-icons">facebook</span>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-gray-600 transition">
                        <span class="material-icons">link</span>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-gray-600 transition">
                        <span class="material-icons">shopping_bag</span>
                    </a>
                </div>
            </div>
        </div>
    </footer>
<script src="../valid.js"></script>
</body>
</html>