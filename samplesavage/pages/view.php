<?php 
require "../config/db.php";
$result = $conn->query("SELECT * FROM products ORDER BY id DESC");
?>
<!DOCTYPE html>
<html>
<head>
<title>Products</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

<header class="bg-blue-700 text-white p-4 shadow">
    <div class="max-w-6xl mx-auto flex justify-between items-center">
        <h1 class="text-2xl font-bold">My Store</h1>
        <nav>
            <a href="view.php" class="px-3 hover:underline">Products</a>
            <a href="add.php" class="px-3 hover:underline">Add Product</a>
        </nav>
    </div>
</header>

<div class="max-w-6xl mx-auto p-8">

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-3xl font-bold">Products</h2>
        <a href="add.php"
           class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700">
            + Add Product
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-7">

        <?php while($row = $result->fetch_assoc()){ ?>
        <div class="bg-white rounded-xl overflow-hidden shadow hover:shadow-xl transition">

            <div class="h-48 bg-gray-200 flex items-center justify-center overflow-hidden">
                <?php if(!empty($row['image'])){ ?>
                    <img src="<?= $row['image'] ?>" class="w-full h-full object-cover">
                <?php } else { ?>
                    <span class="text-gray-400 text-sm">No Image Available</span>
                <?php } ?>
            </div>

            <div class="p-4">
                <h3 class="text-lg font-semibold"><?= $row['name'] ?></h3>
                <p class="text-gray-600 text-sm mt-1 line-clamp-3"><?= $row['description'] ?></p>

                <div class="flex justify-between items-center mt-4">
                    <span class="text-xl font-bold text-green-600">₹<?= $row['price'] ?></span>
                    <span class="text-gray-400 text-sm"><?= date("d M Y", strtotime($row['created_at'])) ?></span>
                </div>
            </div>

        </div>
        <?php } ?>

    </div>
</div>

<footer class="bg-gray-800 text-white p-4 mt-10">
    <div class="max-w-6xl mx-auto text-center text-sm">
        © <?= date("Y") ?> My Store. All rights reserved.
    </div>
</footer>

</body>
</html>
