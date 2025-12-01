<?php
$customer  = $_POST['customer_name'];
$orderDate = $_POST['order_date'];
$itemName  = $_POST['item_name'];
$itemPrint = $_POST['item_print'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Order Summary</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-r from-pink-400 to-yellow-400 min-h-screen flex flex-col">

<!-- HEADER -->
<header class="bg-white shadow-md py-4">
    <div class="container mx-auto flex justify-between items-center px-4">
        <h1 class="text-xl font-bold text-pink-700">MyShop</h1>
        <nav>
            <a href="order_form.php" class="text-gray-700 hover:text-pink-700 mx-2">New Order</a>
            <a href="#" class="text-gray-700 hover:text-pink-700 mx-2">Orders</a>
        </nav>
    </div>
</header>

<!-- MAIN CONTENT -->
<main class="flex-grow flex items-center justify-center py-10">
    <div class="bg-white rounded-2xl shadow-lg p-8 w-full max-w-3xl">
        <h2 class="text-2xl font-semibold text-center text-pink-700 mb-6">Order Summary</h2>

        <p class="mb-2"><strong>Customer Name:</strong> <?= htmlspecialchars($customer) ?></p>
        <p class="mb-4"><strong>Order Date:</strong> <?= htmlspecialchars($orderDate) ?></p>

        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-pink-700 text-white">
                    <th class="p-3 text-left">Item Name</th>
                    <th class="p-3 text-left">Print Details</th>
                </tr>
            </thead>
            <tbody>
            <?php for ($i=0; $i<count($itemName); $i++): ?>
                <tr class="even:bg-gray-100">
                    <td class="p-3"><?= htmlspecialchars($itemName[$i]) ?></td>
                    <td class="p-3"><?= htmlspecialchars($itemPrint[$i]) ?></td>
                </tr>
            <?php endfor; ?>
            </tbody>
        </table>
    </div>
</main>

<!-- FOOTER -->
<footer class="bg-white shadow-inner py-6 mt-10">
    <div class="container mx-auto text-center text-gray-600">
        &copy; <?= date('Y') ?> MyShop. All rights reserved.
    </div>
</footer>

</body>
</html>
