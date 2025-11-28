<?php
session_start();
include 'db.php';
$message = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    if (isset($_POST['login'])) {
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        if ($result && password_verify($password, $result['password'])) {
            $_SESSION['username'] = $username;
            header("Location: index.php");
            exit();
        } else {
            $message = "Invalid username or password!";
        }
    } elseif (isset($_POST['signup'])) {
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $message = "Username already exists. Choose another!";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            $stmt->bind_param("ss", $username, $hash);
            if ($stmt->execute()) {
                $message = "Signup successful! You can now login.";
            } else {
                $message = "Signup failed. Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Superhero Academy</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body class="bg-gray-50 font-sans flex flex-col min-h-screen">

<header class="bg-white shadow-md py-4">
    <div class="container mx-auto flex justify-between items-center px-4">
        <h1 class="text-2xl font-bold text-gray-800">Superhero Academy</h1>
        <nav class="space-x-4">
            <a href="#" class="text-gray-600 hover:text-blue-600">Home</a>
            <a href="#" class="text-gray-600 hover:text-blue-600">About</a>
            <a href="#" class="text-gray-600 hover:text-blue-600">Contact</a>
        </nav>
    </div>
</header>

<main class="flex-grow flex items-center justify-center">
    <form method="POST" class="bg-white shadow-lg rounded-xl p-10 w-full max-w-md space-y-6">
        <h2 class="text-3xl font-bold text-gray-800 text-center">Login / Signup</h2>
        <div>
            <label class="block text-gray-700 mb-1">Username</label>
            <div class="flex items-center border rounded-lg px-3 py-2 focus-within:ring-2 focus-within:ring-blue-500">
                <span class="material-icons text-gray-400 mr-2">person</span>
                <input type="text" name="username" placeholder="Enter username" required class="w-full outline-none">
            </div>
        </div>
        <div>
            <label class="block text-gray-700 mb-1">Password</label>
            <div class="flex items-center border rounded-lg px-3 py-2 focus-within:ring-2 focus-within:ring-blue-500">
                <span class="material-icons text-gray-400 mr-2">lock</span>
                <input type="password" name="password" placeholder="Enter password" required class="w-full outline-none">
            </div>
        </div>
        <div class="flex justify-between">
            <button type="submit" name="login" class="w-1/2 mr-2 bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg font-semibold transition">Login</button>
            <button type="submit" name="signup" class="w-1/2 ml-2 bg-green-600 hover:bg-green-700 text-white py-2 rounded-lg font-semibold transition">Signup</button>
        </div>
        <?php if($message): ?>
            <p class="text-red-500 text-center"><?php echo $message; ?></p>
        <?php endif; ?>
    </form>
</main>

<footer class="bg-white shadow-inner py-6 mt-10">
    <div class="container mx-auto text-center text-gray-500">
        &copy; <?php echo date("Y"); ?> Superhero Academy. All rights reserved.
    </div>
</footer>

</body>
</html>
