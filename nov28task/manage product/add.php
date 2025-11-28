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
        $errors['price'] = "Price cannot exceed 90,00,000";
    }

    // Quantity Validation
    if ($quantity === '') {
        $errors['quantity'] = "Quantity is required";
    } elseif (!preg_match("/^\d{1,5}$/", $quantity)) {
        $errors['quantity'] = "Enter numbers only (max 5 digits)";
    } elseif ((int)$quantity > 9999) {
        $errors['quantity'] = "Quantity cannot exceed 9999";
    }

    // Image Validation (Required)
    $imageName = '';
    if (!$image || $image['error'] !== 0) {
        $errors['image'] = "Product image is required";
    } else {
        $allowed = ['jpg','jpeg','png','gif'];
        $ext = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed)) {
            $errors['image'] = "Only JPG, PNG, GIF images allowed";
        } else {
            if (!is_dir('uploads')) mkdir('uploads', 0777, true);
            $imageName = uniqid() . '.' . $ext;
            move_uploaded_file($image['tmp_name'], "uploads/$imageName");
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
<body class="bg-gradient-to-r from-purple-200 via-pink-200 to-yellow-200 min-h-screen flex items-center justify-center py-10">

<div class="bg-white shadow-xl rounded-xl w-full max-w-md p-8">
    <h1 class="text-3xl font-bold text-purple-700 mb-6 text-center">Add New Product</h1>

    <?php if ($success): ?>
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4 text-center">
            Product added successfully!
        </div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data" class="space-y-4">
        <!-- Product Name -->
        <div>
            <label class="block font-semibold text-gray-700 mb-1">Product Name</label>
            <input type="text" name="name" autofocus
                class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-purple-400"
                maxlength="20"
                value="<?php echo $_POST['name'] ?? ''; ?>"
                oninput="this.value = this.value.replace(/[^a-zA-Z ]/g,'');">
            <p class="text-red-600 text-sm"><?php echo $errors['name'] ?? ''; ?></p>
        </div>

        <!-- Price -->
        <div>
            <label class="block font-semibold text-gray-700 mb-1">Price ($)</label>
            <input type="text" name="price"
                class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-purple-400"
                value="<?php echo $_POST['price'] ?? ''; ?>"
                maxlength="7"
                oninput="this.value = this.value.replace(/[^0-9]/g,''); if(parseInt(this.value)>9000000)this.value='9000000';">
            <p class="text-red-600 text-sm"><?php echo $errors['price'] ?? ''; ?></p>
        </div>

        <!-- Quantity -->
        <div>
            <label class="block font-semibold text-gray-700 mb-1">Quantity</label>
            <input type="text" name="quantity"
                class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-purple-400"
                value="<?php echo $_POST['quantity'] ?? ''; ?>"
                maxlength="5"
                oninput="this.value = this.value.replace(/[^0-9]/g,''); if(parseInt(this.value)>9999)this.value='9999';">
            <p class="text-red-600 text-sm"><?php echo $errors['quantity'] ?? ''; ?></p>
        </div>

        <!-- Image -->
        <div>
            <label class="block font-semibold text-gray-700 mb-1">Product Image</label>
            <input type="file" name="image" class="w-full">
            <p class="text-red-600 text-sm"><?php echo $errors['image'] ?? ''; ?></p>
        </div>

        <button type="submit" class="w-full bg-purple-500 text-white p-3 rounded-lg hover:bg-purple-600 font-semibold">Add Product</button>
    </form>

    <p class="mt-4 text-center">
        <a href="view.php" class="text-purple-700 font-semibold hover:underline">View Products</a>
    </p>
</div>
</body>
</html>
