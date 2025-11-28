<?php
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = isset($_POST['name']) ? $_POST['name'] : "";
    $age = isset($_POST['age']) ? $_POST['age'] : "";

    if (!empty($name) && !empty($age)) {
        // Save the message in session or temporary variable
        session_start();
        $_SESSION['message'] = "🌟 Hello, my name is " . htmlspecialchars($name) . " and I am " . htmlspecialchars($age) . " years old! 🌟";

        // Redirect to the same page to prevent re-submission
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } else {
        $message = "⚠️ Please enter both name and age!";
    }
}

// Display message after redirect
session_start();
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']); // Clear it after displaying
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Character Greeting</title>
<script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.slim.js"></script>
<link rel="stylesheet" href="../styles.css">
</head>
<body class="min-h-screen flex items-center justify-center bg-gray-900">
    <div class="bg-gray-800/80 backdrop-blur-md rounded-3xl p-10 max-w-md w-full text-center shadow-2xl border-4 border-blue-500 animate-fadeIn">
        <h2 class="text-3xl font-bold text-white mb-6">✨ Character Greeting ✨</h2>
        <form method="post" id="characterForm" class="flex flex-col gap-4">
            <input type="text" name="name" id="currentDay" placeholder="Enter character name" class="val-username p-4 rounded-xl bg-gray-700 text-white placeholder-gray-300 focus:bg-gray-600 focus:outline-none transition" value="" required>
            <input type="number" name="age" placeholder="Enter age" class="val-mark p-4 rounded-xl bg-gray-700 text-white placeholder-gray-300 focus:bg-gray-600 focus:outline-none transition" value="" required>
           <span></span>
            <button type="submit" class="mt-4 py-3 rounded-full bg-blue-600 text-white font-bold transform hover:scale-105 transition shadow-lg">Greet Me</button>
        </form>
        <?php if($message != ""): ?>
            <div class="mt-6 text-white text-lg font-semibold animate-popIn"><?php echo $message; ?></div>
        <?php endif; ?>
    </div>
    <style>
        @keyframes fadeIn {0%{opacity:0;transform:translateY(-20px);}100%{opacity:1;transform:translateY(0);}}
        @keyframes popIn {0%{transform:scale(0);}100%{transform:scale(1);}}
        .animate-fadeIn {animation: fadeIn 1s ease-in-out;}
        .animate-popIn {animation: popIn 0.5s ease-in-out;}
    </style>
    <script src="../valid.js"></script>
    <script>
        window.onload = function() {
            document.getElementById('characterForm').reset();
    $('#currentDay').focus();

        }
    </script>
</body>
</html>
