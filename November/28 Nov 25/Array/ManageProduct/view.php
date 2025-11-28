<?php
require 'config/db.php';
$inventory=getInventory();
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
<span class="material-icons text-white text-2xl">storefront</span>
</div>
<div>
<h1 class="text-xl font-semibold text-gray-900">ZenCom</h1>
<p class="text-xs text-gray-500">Premium Store</p>
</div>
</div>
<div class="flex items-center space-x-6">
<div class="text-right">
<p class="text-xs text-gray-500 uppercase tracking-wide">Total Value</p>
<p class="text-xl font-bold text-indigo-600">₹<?=number_format(totalInventoryValue(),2)?></p>
</div>
<div class="flex items-center space-x-2 text-sm text-gray-600">
<span class="material-icons text-lg">inventory_2</span>
<span><?=count($inventory)?> Products</span>
</div>
</div>
</div>
</div>
</nav>

<main class="max-w-7xl mx-auto px-6 py-8">

<div class="bg-white rounded-xl border border-gray-200 p-8">
<div class="flex items-center space-x-2 mb-6">
<span class="material-icons text-indigo-600 text-2xl">shopping_bag</span>
<h2 class="text-xl font-semibold text-gray-900">Available Products</h2>
</div>
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
<?php foreach($inventory as $p): ?>
<div class="bg-white border border-gray-200 rounded-xl overflow-hidden hover:border-indigo-300 hover:shadow-xl transition-all duration-200">
<div class="relative bg-gray-50 h-56 flex items-center justify-center p-6">
<img src="<?=htmlspecialchars($p['image'])?>" alt="<?=htmlspecialchars($p['name'])?>" class="max-h-full max-w-full object-contain">
<?php if($p['allowed_discount']>0): ?>
<div class="absolute top-3 right-3 bg-red-500 text-white text-xs font-semibold px-2.5 py-1 rounded-lg shadow-sm">
<?=$p['allowed_discount']?>% OFF
</div>
<?php endif; ?>
<!-- <div class="absolute top-3 left-3 flex space-x-1">
<a href="admin.php?product=<?=urlencode($p['name'])?>" class="w-7 h-7 flex items-center justify-center bg-white hover:bg-emerald-50 text-emerald-600 rounded-lg shadow-sm transition-colors" title="Update">
<span class="material-icons text-base">edit</span>
</a>
<button onclick="deleteProduct('<?=htmlspecialchars($p['name'])?>')" class="w-7 h-7 flex items-center justify-center bg-white hover:bg-red-50 text-red-600 rounded-lg shadow-sm transition-colors" title="Delete">
<span class="material-icons text-base">delete</span>
</button>
</div> -->
</div>
<div class="p-5">
<h3 class="text-base font-semibold text-gray-900 mb-3 truncate"><?=htmlspecialchars($p['name'])?></h3>
<div class="flex items-baseline justify-between mb-3">
<span class="text-2xl font-bold text-indigo-600">₹<?=number_format($p['price'],2)?></span>
<?php if($p['allowed_discount']>0): 
$final=round($p['price']*(1-$p['allowed_discount']/100),2);
?>
<span class="text-sm text-gray-500 line-through">₹<?=number_format($p['price'],2)?></span>
<?php endif; ?>
</div>
<div class="flex items-center justify-between pt-3 border-t border-gray-200">
<div class="flex items-center space-x-1.5 text-sm text-gray-600">
<span class="material-icons text-base">inventory</span>
<span>Stock</span>
</div>
<span class="text-sm font-semibold px-2.5 py-1 rounded-lg <?=$p['quantity']>10?'bg-emerald-50 text-emerald-700':($p['quantity']>0?'bg-amber-50 text-amber-700':'bg-red-50 text-red-700')?>">
<?=$p['quantity']?> units
</span>
</div>
</div>
</div>
<?php endforeach; ?>
</div>
</div>

</main>

<footer class="bg-white border-t border-gray-200 mt-12">
<div class="max-w-7xl mx-auto px-6 py-6">
<div class="flex flex-col md:flex-row justify-between items-center text-sm">
<p class="text-gray-600">&copy; 2024 ZenCom Product System</p>
<p class="text-gray-400 mt-2 md:mt-0">Powered by SavageInfo</p>
</div>
</div>
</footer>

<script>
async function deleteProduct(name){
if(!confirm(`Delete "${name}"?`)) return;
try{
const res=await fetch('admin.php',{
method:'POST',
headers:{'Content-Type':'application/json'},
body:JSON.stringify({action:'delete',name:name})
});
const result=await res.json();
if(result.success){
location.reload();
}else{
alert(result.message||'Delete failed');
}
}catch(e){
alert('Error: '+e.message);
}
}
</script>

</body>
</html>