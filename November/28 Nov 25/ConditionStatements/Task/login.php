<?php
session_start();
include "db.php";

if (isset($_POST['signup'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = intval($_POST['role']); 

    $stmt = $conn->prepare("SELECT id FROM users WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $signup_error = "Username already exists!";
    } else {
        $stmt_insert = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
        $stmt_insert->bind_param("ssi", $username, $password, $role);
        $stmt_insert->execute();
        $signup_success = "Account created successfully! You can login now.";
    }
}

if (isset($_POST['login'])) {
    $username = $_POST['username_login'];
    $password = $_POST['password_login'];

    $stmt = $conn->prepare("SELECT id, role FROM users WHERE username=? AND password=?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $user['role'];
        $_SESSION['user_id'] = $user['id'];
        header("Location: index.php");
        exit();
    } else {
        $login_error = "Invalid username or password!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ERP - Login & Signup</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.slim.js"></script>
<link rel="stylesheet" href="../styles.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>body{font-family:'Inter',sans-serif}</style>
<script>
function switchTab(tab){
  document.getElementById('signupTab').classList.toggle('hidden', tab!=='signup');
  document.getElementById('loginTab').classList.toggle('hidden', tab!=='login');
  document.getElementById('signupBtn').classList.toggle('border-blue-600', tab==='signup');
  document.getElementById('signupBtn').classList.toggle('text-blue-600', tab==='signup');
  document.getElementById('signupBtn').classList.toggle('border-transparent', tab!=='signup');
  document.getElementById('signupBtn').classList.toggle('text-gray-600', tab!=='signup');
  document.getElementById('loginBtn').classList.toggle('border-blue-600', tab==='login');
  document.getElementById('loginBtn').classList.toggle('text-blue-600', tab==='login');
  document.getElementById('loginBtn').classList.toggle('border-transparent', tab!=='login');
  document.getElementById('loginBtn').classList.toggle('text-gray-600', tab!=='login');
}
</script>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">

<header class="bg-white shadow-sm border-b border-gray-200">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
<div class="flex justify-between items-center h-16">
<div class="flex items-center space-x-3">
<span class="material-icons text-blue-600 text-3xl">business</span>
<h1 class="text-xl font-semibold text-gray-900">ERP System</h1>
</div>
<nav class="flex items-center space-x-6">
<a href="#" class="flex items-center text-gray-600 hover:text-blue-600 transition">
<span class="material-icons text-sm mr-1">info</span>
<span class="text-sm font-medium">About</span>
</a>
<a href="#" class="flex items-center text-gray-600 hover:text-blue-600 transition">
<span class="material-icons text-sm mr-1">contact_support</span>
<span class="text-sm font-medium">Contact</span>
</a>
</nav>
</div>
</div>
</header>

<main class="flex-grow flex items-center justify-center px-4 sm:px-6 lg:px-8 py-12">
<div class="max-w-md w-full">

<div class="text-center mb-8">
<div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full mb-4">
<span class="material-icons text-blue-600 text-3xl">lock</span>
</div>
<h2 class="text-3xl font-bold text-gray-900 mb-2">Welcome</h2>
<p class="text-gray-600">Access your account or create a new one</p>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">

<div class="flex border-b border-gray-200">
<button id="loginBtn" onclick="switchTab('login')" class="flex-1 px-6 py-4 text-sm font-medium border-b-2 border-blue-600 text-blue-600 transition">
<span class="material-icons text-sm align-middle mr-1">login</span>
Login
</button>
<button id="signupBtn" onclick="switchTab('signup')" class="flex-1 px-6 py-4 text-sm font-medium border-b-2 border-transparent text-gray-600 hover:text-blue-600 transition">
<span class="material-icons text-sm align-middle mr-1">person_add</span>
Sign Up
</button>
</div>

<div id="loginTab" class="p-6">
<form method="POST" class="space-y-4">
<?php if(isset($login_error)): ?>
<div class="bg-red-50 border border-red-200 rounded-lg p-4 flex items-start">
<span class="material-icons text-red-600 text-sm mr-2">error</span>
<span class="text-sm text-red-800"><?php echo $login_error; ?></span>
</div>
<?php endif; ?>

<div>
<label class="block text-sm font-medium text-gray-700 mb-2">Username</label>
<div class="relative">
<span class="material-icons absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm">person</span>
<input type="text" name="username_login" required class="val-username w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
</div>
</div>

<div>
<label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
<div class="relative">
<span class="material-icons absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm">lock</span>
<input type="password" name="password_login" required class="val-password w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
</div>
</div>

<button type="submit" name="login" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg text-sm font-medium transition flex items-center justify-center">
<span class="material-icons text-sm mr-2">login</span>
Login to Account
</button>
</form>

<div class="mt-6 text-center">
<a href="#" class="text-sm text-blue-600 hover:text-blue-700 transition">Forgot password?</a>
</div>
</div>

<div id="signupTab" class="p-6 hidden">
<form method="POST" class="space-y-4">
<?php if(isset($signup_error)): ?>
<div class="bg-red-50 border border-red-200 rounded-lg p-4 flex items-start">
<span class="material-icons text-red-600 text-sm mr-2">error</span>
<span class="text-sm text-red-800"><?php echo $signup_error; ?></span>
</div>
<?php endif; ?>
<?php if(isset($signup_success)): ?>
<div class="bg-green-50 border border-green-200 rounded-lg p-4 flex items-start">
<span class="material-icons text-green-600 text-sm mr-2">check_circle</span>
<span class="text-sm text-green-800"><?php echo $signup_success; ?></span>
</div>
<?php endif; ?>

<div>
<label class="block text-sm font-medium text-gray-700 mb-2">Username</label>
<div class="relative">
<span class="material-icons absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm">person</span>
<input type="text" name="username" required class="val-username w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
</div>
</div>

<div>
<label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
<div class="relative">
<span class="material-icons absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm">lock</span>
<input type="password" name="password" required class="val-password w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
</div>
</div>

<div>
<label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
<div class="relative">
<span class="material-icons absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm">badge</span>
<select name="role" class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm appearance-none">
<option value="1">Intern</option>
<option value="2">Staff</option>
<option value="3">Manager</option>
</select>
<span class="material-icons absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm pointer-events-none">expand_more</span>
</div>
</div>

<button type="submit" name="signup" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg text-sm font-medium transition flex items-center justify-center">
<span class="material-icons text-sm mr-2">person_add</span>
Create Account
</button>
</form>

<div class="mt-6 text-center text-sm text-gray-600">
By signing up, you agree to our <a href="#" class="text-blue-600 hover:text-blue-700">Terms</a> and <a href="#" class="text-blue-600 hover:text-blue-700">Privacy Policy</a>
</div>
</div>

</div>

<div class="mt-6 grid grid-cols-3 gap-4 text-center">
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
<span class="material-icons text-blue-600 text-2xl mb-1">security</span>
<p class="text-xs text-gray-600">Secure</p>
</div>
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
<span class="material-icons text-blue-600 text-2xl mb-1">speed</span>
<p class="text-xs text-gray-600">Fast</p>
</div>
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
<span class="material-icons text-blue-600 text-2xl mb-1">verified</span>
<p class="text-xs text-gray-600">Reliable</p>
</div>
</div>

</div>
</main>

<footer class="bg-white border-t border-gray-200">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
<div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
<div class="text-sm text-gray-500">
© 2024 ERP System. All rights reserved.
</div>
<div class="flex items-center space-x-6">
<a href="#" class="text-sm text-gray-600 hover:text-blue-600 transition flex items-center">
<span class="material-icons text-sm mr-1">privacy_tip</span>
Privacy
</a>
<a href="#" class="text-sm text-gray-600 hover:text-blue-600 transition flex items-center">
<span class="material-icons text-sm mr-1">description</span>
Terms
</a>
<a href="#" class="text-sm text-gray-600 hover:text-blue-600 transition flex items-center">
<span class="material-icons text-sm mr-1">help</span>
Help
</a>
</div>
</div>
</div>
</footer>
<script src="../valid.js"></script>
</body>
</html>