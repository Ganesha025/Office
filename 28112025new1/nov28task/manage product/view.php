<?php
session_start();

// Handle deletion
if (isset($_GET['delete'])) {
    $index = intval($_GET['delete']);
    if (isset($_SESSION['products'][$index])) {
        $img = $_SESSION['products'][$index]['image'];
        if ($img && file_exists(__DIR__."/uploads/$img")) unlink(__DIR__."/uploads/$img");
        array_splice($_SESSION['products'], $index, 1);
    }
    header("Location: view.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>View Products</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-r from-teal-200 via-cyan-200 to-blue-200 min-h-screen flex flex-col">

<!-- NAVBAR -->
<nav class="bg-teal-700 text-white p-4 flex justify-between items-center">
    <h1 class="font-bold text-xl">Product Manager</h1>
    <div>
        <a href="add.php" class="px-3 py-1 hover:bg-teal-800 rounded">Add Product</a>
        <a href="view.php" class="px-3 py-1 hover:bg-teal-800 rounded">View Products</a>
    </div>
</nav>

<!-- HEADER -->
<header class="text-center py-8 bg-cyan-700 text-white">
    <h2 class="text-3xl font-bold">Product Inventory</h2>
</header>

<!-- PRODUCTS GRID -->
<div class="w-full max-w-6xl mx-auto mt-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 px-4">
    <?php if (!empty($_SESSION['products'])): ?>
        <?php foreach ($_SESSION['products'] as $index => $product): ?>
            <div class="bg-white rounded-xl shadow-lg p-6 flex flex-col items-center">
                <?php if ($product['image'] && file_exists(__DIR__."/uploads/".$product['image'])): ?>
                    <img src="uploads/<?= $product['image'] ?>" class="h-40 w-full object-cover rounded mb-4">
                <?php else: ?>
                    <div class="h-40 w-full bg-gray-200 rounded mb-4 flex items-center justify-center text-gray-400">No Image</div>
                <?php endif; ?>

                <h2 class="text-xl font-semibold text-teal-700">
                    <?= $product['name'] ?> <span class="text-red-600">*</span>
                </h2>
                <p class="text-gray-600 mb-2">
                    Price: ₹<?= $product['price'] ?> <span class="text-red-600">*</span> |
                    Qty: <?= $product['quantity'] ?> <span class="text-red-600">*</span>
                </p>
                <a href="?delete=<?= $index ?>" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 mt-2 w-full text-center" onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="text-center col-span-3 text-gray-500">No products added yet.</p>
    <?php endif; ?>
</div>

<!-- FOOTER -->
<footer class="bg-teal-700 text-white text-center p-4 mt-auto">
    &copy; <?= date("Y") ?> Product Manager | All rights reserved
</footer>
</body>
</html>
