<?php
session_start();

// Initialize products array if not exists
if (!isset($_SESSION['products'])) {
    $_SESSION['products'] = [];
}

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $price = trim($_POST['price']);
    $quantity = trim($_POST['quantity']);
    $image = $_FILES['image'] ?? null;

    // Product Name Validation
    if ($name === '') {
        $errors['name'] = "Product name is required";
    } elseif (!preg_match("/^[a-zA-Z ]{1,20}$/", $name)) {
        $errors['name'] = "Only alphabets and spaces allowed (max 20 chars)";
    }

    // Price Validation
    if ($price === '') {
        $errors['price'] = "Price is required";
    } elseif (!preg_match("/^\d{1,7}$/", $price)) {
        $errors['price'] = "Enter numbers only (max 7 digits)";
    } elseif ((int)$price > 9000000) {
        $errors['price'] = "Price cannot exceed 9,000,000";
    }

    // Quantity Validation
    if ($quantity === '') {
        $errors['quantity'] = "Quantity is required";
    } elseif (!preg_match("/^\d{1,5}$/", $quantity)) {
        $errors['quantity'] = "Enter numbers only (max 5 digits)";
    } elseif ((int)$quantity > 9999) {
        $errors['quantity'] = "Quantity cannot exceed 9999";
    }

    // Image Validation
    $imageName = '';
    if (!$image || $image['error'] !== 0) {
        $errors['image'] = "Product image is required";
    } else {
        $allowed = ['jpg','jpeg','png','gif'];
        $ext = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed)) {
            $errors['image'] = "Only JPG, PNG, GIF images allowed";
        } else {
            if (!is_dir(__DIR__.'/uploads')) mkdir(__DIR__.'/uploads', 0777, true);
            $imageName = uniqid() . '.' . $ext;
            move_uploaded_file($image['tmp_name'], __DIR__ . "/uploads/$imageName");
        }
    }

    // If no errors, add product
    if (empty($errors)) {
        $_SESSION['products'][] = [
            'name' => htmlspecialchars($name),
            'price' => number_format($price, 2),
            'quantity' => intval($quantity),
            'image' => $imageName
        ];
        $success = true;
        $_POST = [];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Add Product</title>
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
    <h2 class="text-3xl font-bold">Add New Product</h2>
</header>

<!-- FORM -->
<div class="flex justify-center mt-6">
    <div class="bg-white p-8 rounded-xl shadow-md w-full max-w-md">
        <?php if ($success): ?>
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4 text-center">Product added successfully!</div>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data" class="space-y-4">
            <!-- Name -->
            <div>
                <label class="block font-semibold mb-1">Product Name <span class="text-red-600">*</span></label>
                <input type="text" name="name" maxlength="20" autofocus
                    class="w-full p-3 border rounded focus:ring-2 focus:ring-teal-400"
                    value="<?= $_POST['name'] ?? '' ?>"
                    oninput="this.value = this.value.replace(/[^a-zA-Z ]/g,'');">
                <p class="text-red-600 text-sm"><?= $errors['name'] ?? '' ?></p>
            </div>

            <!-- Price -->
            <div>
                <label class="block font-semibold mb-1">Price (₹) <span class="text-red-600">*</span></label>
                <input type="text" name="price" maxlength="7"
                    class="w-full p-3 border rounded focus:ring-2 focus:ring-teal-400"
                    value="<?= $_POST['price'] ?? '' ?>"
                    oninput="this.value = this.value.replace(/[^0-9]/g,''); if(parseInt(this.value)>9000000)this.value='9000000';">
                <p class="text-red-600 text-sm"><?= $errors['price'] ?? '' ?></p>
            </div>

            <!-- Quantity -->
            <div>
                <label class="block font-semibold mb-1">Quantity <span class="text-red-600">*</span></label>
                <input type="text" name="quantity" maxlength="5"
                    class="w-full p-3 border rounded focus:ring-2 focus:ring-teal-400"
                    value="<?= $_POST['quantity'] ?? '' ?>"
                    oninput="this.value = this.value.replace(/[^0-9]/g,''); if(parseInt(this.value)>9999)this.value='9999';">
                <p class="text-red-600 text-sm"><?= $errors['quantity'] ?? '' ?></p>
            </div>

            <!-- Image -->
            <div>
                <label class="block font-semibold mb-1">Product Image <span class="text-red-600">*</span></label>
                <input type="file" name="image" class="w-full">
                <p class="text-red-600 text-sm"><?= $errors['image'] ?? '' ?></p>
            </div>

            <button type="submit" class="w-full bg-cyan-600 hover:bg-cyan-700 text-white p-3 rounded font-semibold">Add Product</button>
        </form>
    </div>
</div>

<!-- FOOTER -->
<footer class="bg-teal-700 text-white text-center p-4 mt-auto">
    &copy; <?= date("Y") ?> Product Manager | All rights reserved
</footer>
</body>
</html>
