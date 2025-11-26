<?php 
require "../config/db.php";

if(isset($_POST['submit'])){

    $name  = $_POST['name'];
    $price = $_POST['price'];
    $desc  = $_POST['description'];
    $image = $_POST['image_url']; // url input

    $conn->query("INSERT INTO products (name, price, description, image)
                  VALUES ('$name', '$price', '$desc', '$image')");

    header("Location: view.php");
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Add Product</title>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

<header class="bg-blue-700 text-white p-4 shadow">
    <div class="max-w-5xl mx-auto flex justify-between items-center">
        <h1 class="text-2xl font-bold">My Store</h1>
        <a href="view.php" class="hover:underline">View Products</a>
    </div>
</header>

<div class="max-w-xl mx-auto mt-10 bg-white p-8 rounded-2xl shadow-lg">

    <h2 class="text-3xl font-bold mb-6 text-center">Add Product</h2>

    <form method="POST" id="productForm" class="space-y-5">

        <div>
            <label class="font-semibold text-gray-700">Product Name</label>
            <input type="text" name="name" required
                class="w-full border rounded-lg p-3 bg-gray-50">
        </div>

        <div>
            <label class="font-semibold text-gray-700">Price (₹)</label>
            <input type="number" name="price" step="0.01" required
                class="w-full border rounded-lg p-3 bg-gray-50">
        </div>

        <div>
            <label class="font-semibold text-gray-700">Description</label>
            <textarea name="description" rows="4"
                class="w-full border rounded-lg p-3 bg-gray-50"></textarea>
        </div>

        <div>
            <label class="font-semibold text-gray-700">Image URL</label>
            <input type="url" name="image_url" required
                placeholder="https://example.com/image.jpg"
                class="w-full border rounded-lg p-3 bg-gray-50">
        </div>

        <button name="submit"
            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg shadow-md">
            + Add Product
        </button>

    </form>
</div>

<footer class="bg-gray-800 text-white p-4 mt-12">
    <div class="max-w-5xl mx-auto text-center text-sm">
        © <?= date("Y") ?> My Store.
    </div>
</footer>

<script>
$(function(){ 
    $("#productForm").on("submit", ()=> alert("Product Added Successfully!")); 
});
</script>

</body>
</html>