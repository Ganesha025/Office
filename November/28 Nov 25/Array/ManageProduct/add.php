<?php
require './config/db.php';
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $action = $data['action'] ?? '';

    if ($action === 'add') {
        addProduct([
            'name' => $data['name'],
            'price' => floatval($data['price']),
            'quantity' => intval($data['quantity']),
            'allowed_discount' => floatval($data['discount']),
            'image' => $data['image']
        ]);
        echo json_encode(['success'=>true,'message'=>"Product '{$data['name']}' added successfully!"]);
    } elseif ($action === 'update') {
        if(updateProduct($data['name'], [
            'price'=>floatval($data['price']),
            'quantity'=>intval($data['quantity']),
            'allowed_discount'=>floatval($data['discount']),
            'image'=>$data['image']
        ])){
            echo json_encode(['success'=>true,'message'=>"Product '{$data['name']}' updated successfully!"]);
        } else {
            echo json_encode(['success'=>false,'message'=>"Product not found."]);
        }
    } elseif ($action === 'delete') {
        if(deleteProduct($data['name'])){
            echo json_encode(['success'=>true,'message'=>"Product '{$data['name']}' deleted successfully!"]);
        } else {
            echo json_encode(['success'=>false,'message'=>"Product not found."]);
        }
    }
    exit;
}

$products = getInventory();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ZenCom</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.slim.js"></script>
    <link rel="stylesheet" href="../styles.css">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .material-icons { font-size: inherit; vertical-align: middle; }
    </style>
</head>
<body class="bg-gray-50">
    
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-indigo-600 rounded-lg flex items-center justify-center">
                        <span class="material-icons text-white text-2xl">inventory_2</span>
                    </div>
                    <div>
                        <h1 class="text-xl font-semibold text-gray-900">ZenCom</h1>
                        <p class="text-xs text-gray-500">Inventory Management</p>
                    </div>
                </div>
                <div class="flex items-center space-x-2 text-sm text-gray-600">
                    <span class="material-icons text-lg">store</span>
                    <span><?= count($products) ?> Products</span>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-6 py-8">
        
        <div id="msg" class="hidden mb-6 p-4 rounded-lg flex items-center space-x-2"></div>

        <div class="bg-white rounded-xl border border-gray-200 p-8 mb-8">
            <div class="flex items-center space-x-2 mb-6">
                <span class="material-icons text-indigo-600 text-2xl">add_box</span>
                <h2 class="text-xl font-semibold text-gray-900">Add New Product</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                <div class="lg:col-span-2">
                    <label class="block text-xs font-medium text-gray-600 mb-2 uppercase tracking-wide">Product Name *</label>
                    <input type="text" id="name" class="val-product-name w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-gray-50">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-2 uppercase tracking-wide">Price (₹) *</label>
                    <input type="number" step="0.01" id="price" class="val-salary w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-gray-50">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-2 uppercase tracking-wide">Quantity *</label>
                    <input type="number" id="quantity" class="val-stock w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-gray-50">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-2 uppercase tracking-wide">Discount (%)</label>
                    <input type="number" step="0.01" id="discount" value="0" class="val-discount w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-gray-50">
                </div>
                <div class="md:col-span-2 lg:col-span-5">
                    <label class="block text-xs font-medium text-gray-600 mb-2 uppercase tracking-wide">Image URL</label>
                    <input type="text" id="image" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-gray-50">
                </div>
            </div>
            <button onclick="submitProduct('add')" class="mt-6 bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-6 py-3 rounded-lg transition-all duration-200 flex items-center space-x-2 shadow-sm hover:shadow">
                <span class="material-icons text-xl">add_circle</span>
                <span>Add Product</span>
            </button>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-8">
            <div class="flex items-center space-x-2 mb-6">
                <span class="material-icons text-indigo-600 text-2xl">inventory</span>
                <h2 class="text-xl font-semibold text-gray-900">Current Inventory</h2>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-5">
                <?php foreach ($products as $product): ?>
                <div class="border border-gray-200 rounded-xl p-6 hover:border-indigo-300 hover:shadow-lg transition-all duration-200 bg-white" data-name="<?= htmlspecialchars($product['name']) ?>">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1">
                            <label class="block text-xs font-medium text-gray-500 mb-1 uppercase tracking-wide">Product</label>
                            <div class="pname text-gray-900 font-semibold text-lg"><?= htmlspecialchars($product['name']) ?></div>
                        </div>
                        <div class="flex space-x-1">
                            <button onclick="submitProduct('update','<?= htmlspecialchars($product['name']) ?>')" class="w-8 h-8 flex items-center justify-center bg-emerald-50 hover:bg-emerald-100 text-emerald-600 rounded-lg transition-colors" title="Update">
                                <span class="material-icons text-lg">check_circle</span>
                            </button>
                            <button onclick="submitProduct('delete','<?= htmlspecialchars($product['name']) ?>')" class="w-8 h-8 flex items-center justify-center bg-red-50 hover:bg-red-100 text-red-600 rounded-lg transition-colors" title="Delete">
                                <span class="material-icons text-lg">delete</span>
                            </button>
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-3 mb-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Price (₹)</label>
                            <input type="number" step="0.01" class="pprice w-full border border-gray-200 rounded-lg px-3 py-2 text-gray-900 font-medium text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-gray-50" value="<?= $product['price'] ?>">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Qty</label>
                            <input type="number" class="pquantity w-full border border-gray-200 rounded-lg px-3 py-2 text-gray-900 font-medium text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-gray-50" value="<?= $product['quantity'] ?>">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Disc (%)</label>
                            <input type="number" step="0.01" class="pdiscount w-full border border-gray-200 rounded-lg px-3 py-2 text-gray-900 font-medium text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-gray-50" value="<?= $product['allowed_discount'] ?>">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Image URL</label>
                        <input type="text" class="pimage w-full border border-gray-200 rounded-lg px-3 py-2 text-gray-700 text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-gray-50" value="<?= htmlspecialchars($product['image']) ?>">
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>

    <footer class="bg-white border-t border-gray-200 mt-12">
        <div class="max-w-7xl mx-auto px-6 py-6">
            <div class="flex flex-col md:flex-row justify-between items-center text-sm">
                <p class="text-gray-600">&copy; 2024 ZenCom Management System</p>
                <p class="text-gray-400 mt-2 md:mt-0">Powered by SavageInfo</p>
            </div>
        </div>
    </footer>

<script>
async function submitProduct(action, name=''){
    let data = { action };

    if(action==='add'){
        data.name = document.getElementById('name').value.trim();
        data.price = document.getElementById('price').value.trim();
        data.quantity = document.getElementById('quantity').value.trim();
        data.discount = document.getElementById('discount').value.trim();
        data.image = document.getElementById('image').value.trim();

        if(!data.name || !data.price || !data.quantity){
            showMessage('Please fill in all required fields (Name, Price, Quantity)', false);
            return;
        }
        if(isNaN(data.price) || parseFloat(data.price) <= 0){
            showMessage('Please enter a valid price', false);
            return;
        }
        if(isNaN(data.quantity) || parseInt(data.quantity) < 0){
            showMessage('Please enter a valid quantity', false);
            return;
        }
    } else {
        const card = document.querySelector(`.product-card[data-name='${name}']`) || document.querySelector(`[data-name='${name}']`);
        data.name = name;
        if(action==='update'){
            data.price = card.querySelector('.pprice').value.trim();
            data.quantity = card.querySelector('.pquantity').value.trim();
            data.discount = card.querySelector('.pdiscount').value.trim();
            data.image = card.querySelector('.pimage').value.trim();

            if(!data.price || !data.quantity){
                showMessage('Please fill in all required fields', false);
                return;
            }
            if(isNaN(data.price) || parseFloat(data.price) <= 0){
                showMessage('Please enter a valid price', false);
                return;
            }
            if(isNaN(data.quantity) || parseInt(data.quantity) < 0){
                showMessage('Please enter a valid quantity', false);
                return;
            }
        }
    }

    if(action==='delete' && !confirm('Are you sure you want to delete this product?')) return;

    const res = await fetch('',{
        method:'POST',
        headers:{'Content-Type':'application/json'},
        body:JSON.stringify(data)
    });
    const result = await res.json();
    showMessage(result.message, result.success);
    if(result.success) setTimeout(()=>window.location.reload(), 1000);
}

function showMessage(message, isSuccess){
    const msgDiv = document.getElementById('msg');
    const icon = isSuccess ? 'check_circle' : 'error';
    const colorClass = isSuccess ? 'bg-emerald-50 text-emerald-800 border-emerald-200' : 'bg-red-50 text-red-800 border-red-200';
    msgDiv.className = `mb-6 p-4 rounded-lg flex items-center space-x-2 border ${colorClass}`;
    msgDiv.innerHTML = `<span class="material-icons text-xl">${icon}</span><span>${message}</span>`;
    msgDiv.classList.remove('hidden');
    setTimeout(()=>msgDiv.classList.add('hidden'), 5000);
}
</script>
</body>
<script src='../valid.js'></script>
</html>