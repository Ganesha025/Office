<?php
include 'db.php';

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    deleteOrder($conn, $id);
    header("Location: orders.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_id'])) {
    $id = (int)$_POST['update_id'];
    $items = [];
    for ($i = 0; $i < count($_POST['item_name']); $i++) {
        $item_name = trim($_POST['item_name'][$i]);
        $price = (float)$_POST['price'][$i];
        if ($item_name !== '' && $price > 0) {
            $items[] = [
                'item_name' => $item_name,
                'price' => $price
            ];
        }
    }

    $orderData = [
        'customer_name' => trim($_POST['customer_name']),
        'order_date' => $_POST['order_date'],
        'mobile' => trim($_POST['mobile']),
        'email' => trim($_POST['email']),
        'door_flat_no' => trim($_POST['door_flat_no']),
        'street_name' => trim($_POST['street_name']),
        'city' => trim($_POST['city']),
        'pincode' => trim($_POST['pincode']),
        'items' => $items
    ];

    updateOrder($conn, $id, $orderData);
    header("Location: orders.php");
    exit;
}

$editingOrder = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $editingOrder = getOrderById($conn, $id);
}

$orders = getOrders($conn);

function getTotalPrice($items) {
    $total = 0;
    foreach ($items as $item) {
        $total += $item['price'];
    }
    return $total;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">

    <header class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Order Management</h1>
                    <p class="text-sm text-gray-500 mt-1">Manage and track all customer orders</p>
                </div>
                <a href="add.php" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg font-medium transition-colors duration-200 flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>New Order</span>
                </a>
            </div>
        </div>
    </header>

    <main class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full">

        <?php if ($editingOrder): ?>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-semibold text-gray-900">Edit Order #<?= $editingOrder['id'] ?></h2>
                <a href="orders.php" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </a>
            </div>
            
            <form method="POST" class="space-y-6">
                <input type="hidden" name="update_id" value="<?= $editingOrder['id'] ?>">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Customer Name</label>
                        <input type="text" name="customer_name" value="<?= $editingOrder['customer_name'] ?>" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Order Date</label>
                        <input type="date" name="order_date" value="<?= $editingOrder['order_date'] ?>" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Mobile</label>
                        <input type="text" name="mobile" value="<?= $editingOrder['mobile'] ?>" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" name="email" value="<?= $editingOrder['email'] ?>" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Door/Flat No</label>
                        <input type="text" name="door_flat_no" value="<?= $editingOrder['door_flat_no'] ?>" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Street Name</label>
                        <input type="text" name="street_name" value="<?= $editingOrder['street_name'] ?>" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">City</label>
                        <input type="text" name="city" value="<?= $editingOrder['city'] ?>" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pincode</label>
                        <input type="text" name="pincode" value="<?= $editingOrder['pincode'] ?>" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>

                <div class="border-t border-gray-200 pt-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Order Items</h3>
                        <button type="button" onclick="addItemRow()" class="text-blue-600 hover:text-blue-700 font-medium text-sm flex items-center space-x-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            <span>Add Item</span>
                        </button>
                    </div>
                    
                    <div id="items-container" class="space-y-3">
                        <?php foreach ($editingOrder['items'] as $item): ?>
                            <div class="item-row flex flex-col sm:flex-row gap-3 p-4 bg-gray-50 rounded-lg border border-gray-200">
                                <div class="flex-1">
                                    <input type="text" name="item_name[]" value="<?= $item['item_name'] ?>" required placeholder="Item Name" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                                <div class="w-full sm:w-48">
                                    <input type="number" name="price[]" step="0.01" value="<?= $item['price'] ?>" required placeholder="Price" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                                <button type="button" onclick="this.closest('.item-row').remove()" class="px-4 py-2.5 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg font-medium transition-colors duration-200">Remove</button>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t border-gray-200">
                    <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200">Update Order</button>
                    <a href="orders.php" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-3 rounded-lg font-medium text-center transition-colors duration-200">Cancel</a>
                </div>
            </form>
        </div>
        <?php endif; ?>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">All Orders</h2>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Customer</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Contact</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Address</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Items</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php foreach ($orders as $order): ?>
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#<?= $order['id'] ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= $order['customer_name'] ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600"><?= date('d M Y', strtotime($order['order_date'])) ?></td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    <div><?= $order['mobile'] ?></div>
                                    <div class="text-gray-500"><?= $order['email'] ?></div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 max-w-xs">
                                    <?= $order['door_flat_no'] ?>, <?= $order['street_name'] ?>, <?= $order['city'] ?> - <?= $order['pincode'] ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    <div class="space-y-1">
                                        <?php foreach ($order['items'] as $item): ?>
                                            <div class="flex justify-between">
                                                <span><?= $item['item_name'] ?></span>
                                                <span class="font-medium ml-4">₹<?= number_format($item['price'], 2) ?></span>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">₹<?= number_format(getTotalPrice($order['items']), 2) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <div class="flex space-x-2">
                                        <a href="orders.php?edit=<?= $order['id'] ?>" class="text-blue-600 hover:text-blue-700 font-medium">Edit</a>
                                        <span class="text-gray-300">|</span>
                                        <a href="orders.php?delete=<?= $order['id'] ?>" onclick="return confirm('Are you sure you want to delete this order?')" class="text-red-600 hover:text-red-700 font-medium">Delete</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </main>

    <footer class="bg-white border-t border-gray-200 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex flex-col sm:flex-row justify-between items-center space-y-4 sm:space-y-0">
                <p class="text-sm text-gray-600">© 2024 SavageInfo System. All rights reserved.</p>
                <div class="flex space-x-6 text-sm text-gray-600">
                    <a href="#" class="hover:text-gray-900 transition-colors duration-200">Privacy Policy</a>
                    <a href="#" class="hover:text-gray-900 transition-colors duration-200">Terms of Service</a>
                    <a href="#" class="hover:text-gray-900 transition-colors duration-200">Support</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        function addItemRow() {
            const container = document.getElementById('items-container');
            const div = document.createElement('div');
            div.className = 'item-row flex flex-col sm:flex-row gap-3 p-4 bg-gray-50 rounded-lg border border-gray-200';
            div.innerHTML = `
                <div class="flex-1">
                    <input type="text" name="item_name[]" required placeholder="Item Name" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div class="w-full sm:w-48">
                    <input type="number" name="price[]" step="0.01" required placeholder="Price" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <button type="button" onclick="this.closest('.item-row').remove()" class="px-4 py-2.5 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg font-medium transition-colors duration-200">Remove</button>
            `;
            container.appendChild(div);
        }
    </script>

</body>
</html>