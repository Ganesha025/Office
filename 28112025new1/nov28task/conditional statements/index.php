<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Superhero Academy - Dashboard</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body class="bg-gray-50 font-sans flex flex-col min-h-screen">

<header class="bg-white shadow-md py-4">
    <div class="container mx-auto flex justify-between items-center px-4">
        <h1 class="text-2xl font-bold text-gray-800">Superhero Academy</h1>
        <nav class="space-x-4">
            <a href="mission.php" class="text-gray-600 hover:text-blue-600">Mission</a>
            <a href="energy.php" class="text-gray-600 hover:text-blue-600">Energy & Grade</a>
            <a href="logout.php" class="text-gray-600 hover:text-red-600">Logout</a>
        </nav>
    </div>
</header>

<main class="flex-grow flex flex-col items-center justify-center px-4">
    <h2 class="text-3xl font-bold text-gray-800 mt-10">Welcome to Superhero Academy</h2>
    <p class="text-gray-600 mt-2">Hello, <?php echo $_SESSION['username']; ?>!</p>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mt-10 w-full max-w-4xl">
        <a href="mission.php" class="flex flex-col items-center justify-center bg-white shadow-lg rounded-xl p-6 hover:shadow-2xl transition">
            <span class="material-icons text-blue-500 text-5xl mb-2">military_tech</span>
            <span class="text-gray-800 font-semibold">Mission Authorization</span>
        </a>
        <a href="energy.php" class="flex flex-col items-center justify-center bg-white shadow-lg rounded-xl p-6 hover:shadow-2xl transition">
            <span class="material-icons text-yellow-500 text-5xl mb-2">bolt</span>
            <span class="text-gray-800 font-semibold">Energy & Training Grade</span>
        </a>
        <a href="logout.php" class="flex flex-col items-center justify-center bg-white shadow-lg rounded-xl p-6 hover:shadow-2xl transition">
            <span class="material-icons text-red-500 text-5xl mb-2">logout</span>
            <span class="text-gray-800 font-semibold">Logout</span>
        </a>
    </div>
</main>

<footer class="bg-white shadow-inner py-6 mt-10">
    <div class="container mx-auto text-center text-gray-500">
        &copy; <?php echo date("Y"); ?> Superhero Academy. All rights reserved.
    </div>
</footer>

</body>
</html>
