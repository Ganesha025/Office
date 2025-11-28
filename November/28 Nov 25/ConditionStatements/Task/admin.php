<?php
session_start();
include "db.php";

if(isset($_POST['add_user'])){
    $stmt=$conn->prepare("INSERT INTO users(username,password,role) VALUES(?,?,?)");
    $stmt->bind_param("ssi",$_POST['username'],$_POST['password'],$_POST['role']);
    $stmt->execute();
    header("Location: ".$_SERVER['PHP_SELF']); exit;
}
if(isset($_POST['edit_user'])){
    $result=$conn->query("SELECT * FROM users WHERE id=".intval($_POST['user_id']));
    $edit_user=$result->fetch_assoc();
}
if(isset($_POST['update_user'])){
    $stmt=$conn->prepare("UPDATE users SET username=?,password=?,role=? WHERE id=?");
    $stmt->bind_param("ssii",$_POST['username'],$_POST['password'],$_POST['role'],$_POST['user_id']);
    $stmt->execute();
    header("Location: ".$_SERVER['PHP_SELF']); exit;
}
if(isset($_POST['delete_user'])){
    $stmt=$conn->prepare("DELETE FROM users WHERE id=?");
    $stmt->bind_param("i",$_POST['user_id']);
    $stmt->execute();
    header("Location: ".$_SERVER['PHP_SELF']); exit;
}

if(isset($_POST['add_product'])){
    $stmt=$conn->prepare("INSERT INTO products(name,status) VALUES(?,?)");
    $stmt->bind_param("ss",$_POST['product_name'],$_POST['product_status']);
    $stmt->execute();
    header("Location: ".$_SERVER['PHP_SELF']); exit;
}
if(isset($_POST['edit_product'])){
    $result=$conn->query("SELECT * FROM products WHERE id=".intval($_POST['product_id']));
    $edit_product=$result->fetch_assoc();
}
if(isset($_POST['update_product'])){
    $stmt=$conn->prepare("UPDATE products SET name=?,status=? WHERE id=?");
    $stmt->bind_param("ssi",$_POST['product_name'],$_POST['product_status'],$_POST['product_id']);
    $stmt->execute();
    header("Location: ".$_SERVER['PHP_SELF']); exit;
}
if(isset($_POST['delete_product'])){
    $stmt=$conn->prepare("DELETE FROM products WHERE id=?");
    $stmt->bind_param("i",$_POST['product_id']);
    $stmt->execute();
    header("Location: ".$_SERVER['PHP_SELF']); exit;
}

if(isset($_POST['update_score'])){
    $stmt=$conn->prepare("INSERT INTO performance(user_id,score) VALUES(?,?) ON DUPLICATE KEY UPDATE score=?");
    $stmt->bind_param("idd",$_POST['user_id'],$_POST['score'],$_POST['score']);
    $stmt->execute();
    header("Location: ".$_SERVER['PHP_SELF']); exit;
}

$users=$conn->query("SELECT * FROM users");
$products=$conn->query("SELECT * FROM products");
$performance=$conn->query("SELECT * FROM performance");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Panel</title>
<link rel="stylesheet" href="../styles.css">
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.slim.js"></script>

<style>body{font-family:'Inter',sans-serif}</style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">

<header class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-50">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
<div class="flex justify-between items-center h-16">
<div class="flex items-center space-x-3">
<span class="material-icons text-blue-600 text-3xl">admin_panel_settings</span>
<h1 class="text-xl font-semibold text-gray-900">Admin Panel</h1>
</div>
<nav class="flex items-center space-x-6">
<a href="index.php" class="flex items-center text-gray-600 hover:text-blue-600 transition">
<span class="material-icons text-sm mr-1">dashboard</span>
<span class="text-sm font-medium">Dashboard</span>
</a>
<button class="flex items-center text-gray-600 hover:text-blue-600 transition">
<span class="material-icons text-sm mr-1">account_circle</span>
<span class="text-sm font-medium">Profile</span>
</button>
</nav>
</div>
</div>
</header>

<main class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full">

<div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-8">
<div class="px-6 py-4 border-b border-gray-200 flex items-center">
<span class="material-icons text-blue-600 mr-3">people</span>
<h2 class="text-lg font-semibold text-gray-900">User Management</h2>
</div>
<div class="overflow-x-auto">
<table class="w-full">
<thead class="bg-gray-50">
<tr>
<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Username</th>
<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
<th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
</tr>
</thead>
<tbody class="bg-white divide-y divide-gray-200">
<?php while($row=$users->fetch_assoc()): ?>
<tr class="hover:bg-gray-50 transition">
<td class="px-6 py-4 text-sm text-gray-900"><?=$row['id']?></td>
<td class="px-6 py-4 text-sm font-medium text-gray-900"><?=$row['username']?></td>
<td class="px-6 py-4 text-sm">
<span class="px-2 py-1 text-xs font-medium rounded-full <?=$row['role']==3?'bg-purple-100 text-purple-800':($row['role']==2?'bg-blue-100 text-blue-800':'bg-gray-100 text-gray-800')?>">
<?=$row['role']==3?'Manager':($row['role']==2?'Staff':'Intern')?>
</span>
</td>
<td class="px-6 py-4 text-sm text-right space-x-2">
<form method="POST" class="inline">
<input type="hidden" name="user_id" value="<?=$row['id']?>">
<button type="submit" name="edit_user" class="text-blue-600 hover:text-blue-800 transition inline-flex items-center">
<span class="material-icons text-sm">edit</span>
</button>
</form>
<form method="POST" class="inline">
<input type="hidden" name="user_id" value="<?=$row['id']?>">
<button type="submit" name="delete_user" onclick="return confirm('Are you sure?')" class="text-red-600 hover:text-red-800 transition inline-flex items-center">
<span class="material-icons text-sm">delete</span>
</button>
</form>
</td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
</div>
<div class="px-6 py-6 bg-gray-50 border-t border-gray-200">
<h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center">
<span class="material-icons text-sm mr-2"><?=isset($edit_user)?'edit':'add_circle'?></span>
<?=isset($edit_user)?'Edit User':'Add New User'?>
</h3>
<form method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4">
<input type="hidden" name="user_id" value="<?=@$edit_user['id']?>">
<div>
<label class="block text-xs font-medium text-gray-700 mb-1">Username</label>
<input type="text" name="username" required value="<?=@$edit_user['username']?>" class="val-username w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
</div>
<div>
<label class="block text-xs font-medium text-gray-700 mb-1">Password</label>
<input type="text" name="password" required value="<?=@$edit_user['password']?>" class="val-password w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
</div>
<div>
<label class="block text-xs font-medium text-gray-700 mb-1">Role</label>
<select name="role" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
<option value="1" <?=(@$edit_user['role']==1)?'selected':''?>>Intern</option>
<option value="2" <?=(@$edit_user['role']==2)?'selected':''?>>Staff</option>
<option value="3" <?=(@$edit_user['role']==3)?'selected':''?>>Manager</option>
</select>
</div>
<div class="flex items-end">
<button type="submit" name="<?=isset($edit_user)?'update_user':'add_user'?>" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition flex items-center justify-center">
<span class="material-icons text-sm mr-1"><?=isset($edit_user)?'check':'add'?></span>
<?=isset($edit_user)?'Update':'Add User'?>
</button>
</div>
</form>
</div>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-8">
<div class="px-6 py-4 border-b border-gray-200 flex items-center">
<span class="material-icons text-blue-600 mr-3">inventory_2</span>
<h2 class="text-lg font-semibold text-gray-900">Product Management</h2>
</div>
<div class="overflow-x-auto">
<table class="w-full">
<thead class="bg-gray-50">
<tr>
<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product Name</th>
<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
<th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
</tr>
</thead>
<tbody class="bg-white divide-y divide-gray-200">
<?php while($row=$products->fetch_assoc()): ?>
<tr class="hover:bg-gray-50 transition">
<td class="px-6 py-4 text-sm text-gray-900"><?=$row['id']?></td>
<td class="px-6 py-4 text-sm font-medium text-gray-900"><?=$row['name']?></td>
<td class="px-6 py-4 text-sm">
<span class="px-2 py-1 text-xs font-medium rounded-full <?=$row['status']=='in stock'?'bg-green-100 text-green-800':'bg-red-100 text-red-800'?>">
<?=ucfirst($row['status'])?>
</span>
</td>
<td class="px-6 py-4 text-sm text-right space-x-2">
<form method="POST" class="inline">
<input type="hidden" name="product_id" value="<?=$row['id']?>">
<button type="submit" name="edit_product" class="text-blue-600 hover:text-blue-800 transition inline-flex items-center">
<span class="material-icons text-sm">edit</span>
</button>
</form>
<form method="POST" class="inline">
<input type="hidden" name="product_id" value="<?=$row['id']?>">
<button type="submit" name="delete_product" onclick="return confirm('Are you sure?')" class="text-red-600 hover:text-red-800 transition inline-flex items-center">
<span class="material-icons text-sm">delete</span>
</button>
</form>
</td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
</div>
<div class="px-6 py-6 bg-gray-50 border-t border-gray-200">
<h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center">
<span class="material-icons text-sm mr-2"><?=isset($edit_product)?'edit':'add_circle'?></span>
<?=isset($edit_product)?'Edit Product':'Add New Product'?>
</h3>
<form method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-4">
<input type="hidden" name="product_id" value="<?=@$edit_product['id']?>">
<div>
<label class="block text-xs font-medium text-gray-700 mb-1">Product Name</label>
<input type="text" name="product_name" required value="<?=@$edit_product['name']?>" class="val-product-name w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
</div>
<div>
<label class="block text-xs font-medium text-gray-700 mb-1">Status</label>
<select name="product_status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
<option value="in stock" <?=(@$edit_product['status']=='in stock')?'selected':''?>>In Stock</option>
<option value="out of stock" <?=(@$edit_product['status']=='out of stock')?'selected':''?>>Out of Stock</option>
</select>
</div>
<div class="flex items-end">
<button type="submit" name="<?=isset($edit_product)?'update_product':'add_product'?>" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition flex items-center justify-center">
<span class="material-icons text-sm mr-1"><?=isset($edit_product)?'check':'add'?></span>
<?=isset($edit_product)?'Update':'Add Product'?>
</button>
</div>
</form>
</div>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-200">
<div class="px-6 py-4 border-b border-gray-200 flex items-center">
<span class="material-icons text-blue-600 mr-3">bar_chart</span>
<h2 class="text-lg font-semibold text-gray-900">Performance Tracking</h2>
</div>
<div class="overflow-x-auto">
<table class="w-full">
<thead class="bg-gray-50">
<tr>
<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User ID</th>
<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Score</th>
</tr>
</thead>
<tbody class="bg-white divide-y divide-gray-200">
<?php while($row=$performance->fetch_assoc()): ?>
<tr class="hover:bg-gray-50 transition">
<td class="px-6 py-4 text-sm text-gray-900"><?=$row['user_id']?></td>
<td class="px-6 py-4 text-sm font-semibold text-blue-600"><?=$row['score']?></td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
</div>
<div class="px-6 py-6 bg-gray-50 border-t border-gray-200">
<h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center">
<span class="material-icons text-sm mr-2">update</span>
Update Performance Score
</h3>
<form method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-4">
<div>
<label class="block text-xs font-medium text-gray-700 mb-1">User ID</label>
<input type="number" name="user_id" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
</div>
<div>
<label class="block text-xs font-medium text-gray-700 mb-1">Score</label>
<input type="number" name="score" required class="val-mark w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
</div>
<div class="flex items-end">
<button type="submit" name="update_score" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition flex items-center justify-center">
<span class="material-icons text-sm mr-1">save</span>
Update Score
</button>
</div>
</form>
</div>
</div>

</main>

<footer class="bg-white border-t border-gray-200 mt-auto">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
<div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
<div class="text-sm text-gray-500">
© 2024 Admin Panel. All rights reserved.
</div>
<div class="flex items-center space-x-6">
<a href="#" class="text-sm text-gray-600 hover:text-blue-600 transition flex items-center">
<span class="material-icons text-sm mr-1">help_outline</span>
Help
</a>
<a href="#" class="text-sm text-gray-600 hover:text-blue-600 transition flex items-center">
<span class="material-icons text-sm mr-1">privacy_tip</span>
Privacy
</a>
<a href="#" class="text-sm text-gray-600 hover:text-blue-600 transition flex items-center">
<span class="material-icons text-sm mr-1">description</span>
Terms
</a>
</div>
</div>
</div>
</footer>
<script src="../valid.js"></script>
</body>
</html>