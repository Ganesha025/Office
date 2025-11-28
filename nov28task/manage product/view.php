<?php
session_start();

// Handle deletion
if (isset($_GET['delete'])) {
    $index = intval($_GET['delete']);
    if (isset($_SESSION['products'][$index])) {
        $img = $_SESSION['products'][$index]['image'];
        if ($img && file_exists("uploads/$img")) unlink("uploads/$img");
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
<body class="bg-gradient-to-r from-purple-200 via-pink-200 to-yellow-200 min-h-screen flex flex-col items-center py-10">

<div class="w-full max-w-6xl">
    <h1 class="text-3xl font-bold text-purple-700 mb-6 text-center">Product Inventory</h1>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
        <?php if (!empty($_SESSION['products'])): ?>
            <?php foreach ($_SESSION['products'] as $index => $product): ?>
                <div class="bg-white rounded-xl shadow-lg p-6 flex flex-col items-center <?php echo $index === 0 ? 'ring-2 ring-purple-400' : ''; ?>">
                    <?php if ($product['image']): ?>
                        <img src="uploads/<?php echo $product['image']; ?>" class="h-40 w-full object-cover rounded mb-4">
                    <?php else: ?>
                        <div class="h-40 w-full bg-gray-200 rounded mb-4 flex items-center justify-center text-gray-400">No Image</div>
                    <?php endif; ?>
                    <h2 class="text-xl font-semibold text-purple-700"><?php echo $product['name']; ?></h2>
                    <p class="text-gray-600 mb-2">$<?php echo $product['price']; ?> | Qty: <?php echo $product['quantity']; ?></p>
                    <a href="?delete=<?php echo $index; ?>" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 mt-2 w-full text-center" onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center col-span-3 text-gray-500">No products added yet.</p>
        <?php endif; ?>
    </div>

    <p class="mt-6 text-center">
        <a href="add.php" class="text-purple-700 font-semibold hover:underline">Add New Product</a>
    </p>
</div>
</body>
</html>
