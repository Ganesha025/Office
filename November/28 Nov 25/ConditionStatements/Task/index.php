<?php
session_start();
include "db.php";

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$role = $_SESSION['role'];
if ($role == 1) $accessMsg = "Intern - View-only access";
elseif ($role == 2) $accessMsg = "Staff - Can edit orders";
else $accessMsg = "Manager - Full access";

$productQuery = $conn->query("SELECT name, status FROM products");
$productList = [];
while ($row = $productQuery->fetch_assoc()) {
    $productList[] = $row;
}

$profitQuery = $conn->query("SELECT SUM(score) as total_score FROM performance");
$totalProfit = $profitQuery->fetch_assoc()['total_score'];

if ($totalProfit > 0) $profitMsg = "Profit: $" . $totalProfit;
elseif ($totalProfit < 0) $profitMsg = "Loss: $" . abs($totalProfit);
else $profitMsg = "Break-even";

$user_id = $_SESSION['user_id'];
$perfQuery = $conn->query("SELECT score FROM performance WHERE user_id=$user_id");
$row = $perfQuery->fetch_assoc();
$score = $row ? $row['score'] : 0;

if ($score >= 90) $perfMsg = "Excellent";
elseif ($score >= 80) $perfMsg = "Good";
elseif ($score >= 70) $perfMsg = "Average";
elseif ($score >= 60) $perfMsg = "Below Average";
else $perfMsg = "Poor";
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ERP Dashboard</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>body{font-family:'Inter',sans-serif}</style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">

<header class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-50">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
<div class="flex justify-between items-center h-16">
<div class="flex items-center space-x-3">
<span class="material-icons text-blue-600 text-3xl">dashboard</span>
<h1 class="text-xl font-semibold text-gray-900">ERP Dashboard</h1>
</div>
<nav class="flex items-center space-x-6">
<div class="flex items-center text-gray-600">
<span class="material-icons text-sm mr-1">account_circle</span>
<span class="text-sm font-medium"><?php echo $_SESSION['username']; ?></span>
</div>
<?php if($role == 3): ?>
<a href="admin.php" class="flex items-center text-gray-600 hover:text-blue-600 transition">
<span class="material-icons text-sm mr-1">admin_panel_settings</span>
<span class="text-sm font-medium">Admin</span>
</a>
<?php endif; ?>
<a href="login.php" class="flex items-center text-gray-600 hover:text-red-600 transition">
<span class="material-icons text-sm mr-1">logout</span>
<span class="text-sm font-medium">Logout</span>
</a>
</nav>
</div>
</div>
</header>

<main class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full">

<div class="mb-8">
<h2 class="text-3xl font-bold text-gray-900 mb-2">Welcome back, <?php echo $_SESSION['username']; ?>!</h2>
<p class="text-gray-600">Here's what's happening with your business today.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition">
<div class="flex items-center justify-between mb-4">
<div class="bg-blue-100 rounded-lg p-3">
<span class="material-icons text-blue-600">security</span>
</div>
<span class="px-3 py-1 text-xs font-medium rounded-full <?=$role==3?'bg-purple-100 text-purple-800':($role==2?'bg-blue-100 text-blue-800':'bg-gray-100 text-gray-800')?>">
<?=$role==3?'Manager':($role==2?'Staff':'Intern')?>
</span>
</div>
<h3 class="text-sm font-medium text-gray-500 mb-1">Access Level</h3>
<p class="text-lg font-semibold text-gray-900"><?php echo $accessMsg; ?></p>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition">
<div class="flex items-center justify-between mb-4">
<div class="bg-green-100 rounded-lg p-3">
<span class="material-icons text-green-600">account_balance_wallet</span>
</div>
<span class="material-icons text-gray-400">trending_up</span>
</div>
<h3 class="text-sm font-medium text-gray-500 mb-1">Financial Status</h3>
<p class="text-lg font-semibold <?=$totalProfit>0?'text-green-600':($totalProfit<0?'text-red-600':'text-gray-900')?>"><?php echo $profitMsg; ?></p>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition">
<div class="flex items-center justify-between mb-4">
<div class="<?=$score>=80?'bg-blue-100':'bg-orange-100'?> rounded-lg p-3">
<span class="material-icons <?=$score>=80?'text-blue-600':'text-orange-600'?>">emoji_events</span>
</div>
<div class="text-right">
<div class="text-2xl font-bold text-gray-900"><?php echo $score; ?></div>
<div class="text-xs text-gray-500">out of 100</div>
</div>
</div>
<h3 class="text-sm font-medium text-gray-500 mb-1">Your Performance</h3>
<div class="flex items-center justify-between">
<p class="text-lg font-semibold <?=$score>=80?'text-blue-600':($score>=60?'text-orange-600':'text-red-600')?>"><?php echo $perfMsg; ?></p>
<div class="w-full max-w-[120px] bg-gray-200 rounded-full h-2 ml-4">
<div class="<?=$score>=80?'bg-blue-600':($score>=60?'bg-orange-600':'bg-red-600')?> h-2 rounded-full transition-all" style="width:<?php echo $score; ?>%"></div>
</div>
</div>
</div>

</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-200">
<div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
<div class="flex items-center">
<span class="material-icons text-blue-600 mr-3">inventory_2</span>
<h2 class="text-lg font-semibold text-gray-900">Product Inventory</h2>
</div>
<span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-medium"><?php echo count($productList); ?> Products</span>
</div>
<div class="overflow-x-auto">
<table class="w-full">
<thead class="bg-gray-50">
<tr>
<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product Name</th>
<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
<th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
</tr>
</thead>
<tbody class="bg-white divide-y divide-gray-200">
<?php if(empty($productList)): ?>
<tr>
<td colspan="3" class="px-6 py-12 text-center text-gray-500">
<span class="material-icons text-5xl text-gray-300 mb-2">inventory</span>
<p class="text-sm">No products available</p>
</td>
</tr>
<?php else: ?>
<?php foreach ($productList as $prod): ?>
<tr class="hover:bg-gray-50 transition">
<td class="px-6 py-4">
<div class="flex items-center">
<div class="bg-blue-100 rounded-lg p-2 mr-3">
<span class="material-icons text-blue-600 text-sm">shopping_bag</span>
</div>
<span class="text-sm font-medium text-gray-900"><?php echo $prod['name']; ?></span>
</div>
</td>
<td class="px-6 py-4">
<span class="px-3 py-1 text-xs font-medium rounded-full <?=$prod['status']=='in stock'?'bg-green-100 text-green-800':'bg-red-100 text-red-800'?>">
<span class="material-icons text-xs align-middle mr-1"><?=$prod['status']=='in stock'?'check_circle':'cancel'?></span>
<?php echo ucfirst($prod['status']); ?>
</span>
</td>
<td class="px-6 py-4 text-right">
<button class="text-blue-600 hover:text-blue-800 transition inline-flex items-center text-sm">
<span class="material-icons text-sm mr-1">visibility</span>
View Details
</button>
</td>
</tr>
<?php endforeach; ?>
<?php endif; ?>
</tbody>
</table>
</div>
</div>

<div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
<div class="flex items-center mb-4">
<span class="material-icons text-blue-600 mr-2">info</span>
<h3 class="text-sm font-semibold text-gray-900">Quick Info</h3>
</div>
<div class="space-y-3">
<div class="flex items-center justify-between py-2 border-b border-gray-100">
<span class="text-sm text-gray-600">Total Products</span>
<span class="text-sm font-semibold text-gray-900"><?php echo count($productList); ?></span>
</div>
<div class="flex items-center justify-between py-2 border-b border-gray-100">
<span class="text-sm text-gray-600">In Stock</span>
<span class="text-sm font-semibold text-green-600"><?php echo count(array_filter($productList, function($p){return $p['status']=='in stock';})); ?></span>
</div>
<div class="flex items-center justify-between py-2">
<span class="text-sm text-gray-600">Out of Stock</span>
<span class="text-sm font-semibold text-red-600"><?php echo count(array_filter($productList, function($p){return $p['status']=='out of stock';})); ?></span>
</div>
</div>
</div>

<div class="bg-blue-600 rounded-lg shadow-sm p-6 text-white">
<div class="flex items-center mb-4">
<span class="material-icons mr-2">lightbulb</span>
<h3 class="text-sm font-semibold">Performance Tip</h3>
</div>
<p class="text-sm opacity-90 mb-4">
<?php if($score >= 90): ?>
Outstanding work! Keep maintaining this excellence.
<?php elseif($score >= 70): ?>
You're doing well! Focus on consistency to reach excellence.
<?php else: ?>
Let's work together to improve your performance metrics.
<?php endif; ?>
</p>
<button class="bg-white text-blue-600 px-4 py-2 rounded-md text-xs font-medium hover:bg-blue-50 transition">
Learn More
</button>
</div>
</div>

</main>

<footer class="bg-white border-t border-gray-200 mt-auto">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
<div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
<div class="text-sm text-gray-500">
© 2024 ERP Dashboard. All rights reserved.
</div>
<div class="flex items-center space-x-6">
<a href="#" class="text-sm text-gray-600 hover:text-blue-600 transition flex items-center">
<span class="material-icons text-sm mr-1">help_outline</span>
Help Center
</a>
<a href="#" class="text-sm text-gray-600 hover:text-blue-600 transition flex items-center">
<span class="material-icons text-sm mr-1">support_agent</span>
Support
</a>
<a href="#" class="text-sm text-gray-600 hover:text-blue-600 transition flex items-center">
<span class="material-icons text-sm mr-1">description</span>
Documentation
</a>
</div>
</div>
</div>
</footer>

</body>
</html>