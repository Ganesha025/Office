<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
include 'db.php';
$message = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $energy = floatval($_POST['energy']);
    $score = intval($_POST['score']);
    $username = $_SESSION['username'];
    $stmt = $conn->prepare("INSERT INTO energy_grades (username, energy, score) VALUES (?, ?, ?)");
    $stmt->bind_param("sdi", $username, $energy, $score);
    $stmt->execute();
    if ($energy > 0) $message .= "Energy Status: You are energized and ready!<br>";
    elseif ($energy < 0) $message .= "Energy Status: Warning! Energy is low.<br>";
    else $message .= "Energy Status: Neutral energy. Proceed with caution.<br>";
    if ($score >= 90) $message .= "Training Grade: A - Legendary Hero!<br>";
    elseif ($score >= 80) $message .= "Training Grade: B - Excellent!<br>";
    elseif ($score >= 70) $message .= "Training Grade: C - Good, but room for improvement.<br>";
    elseif ($score >= 60) $message .= "Training Grade: D - Needs more training.<br>";
    else $message .= "Training Grade: F - Back to the academy!<br>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Energy & Grade - Superhero Academy</title>
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

<main class="flex-grow flex items-center justify-center">
    <form method="POST" class="bg-white shadow-lg rounded-xl p-10 w-full max-w-md space-y-6">
        <h2 class="text-3xl font-bold text-gray-800 text-center">Energy & Training Grade</h2>
        <p class="text-gray-600 text-center">Welcome, <?php echo $_SESSION['username']; ?>!</p>

        <div>
            <label class="block text-gray-700 mb-1">Energy Level</label>
            <div class="flex items-center border rounded-lg px-3 py-2 focus-within:ring-2 focus-within:ring-blue-500">
                <span class="material-icons text-gray-400 mr-2">bolt</span>
                <input type="number" step="0.1" name="energy" placeholder="Enter energy level" required class="w-full outline-none">
            </div>
        </div>

        <div>
            <label class="block text-gray-700 mb-1">Training Score</label>
            <div class="flex items-center border rounded-lg px-3 py-2 focus-within:ring-2 focus-within:ring-blue-500">
                <span class="material-icons text-gray-400 mr-2">star</span>
                <input type="number" name="score" placeholder="Enter training score (0-100)" required class="w-full outline-none">
            </div>
        </div>

        <button type="submit" class="w-full bg-yellow-600 hover:bg-yellow-700 text-white py-2 rounded-lg font-semibold transition">Check Energy & Grade</button>

        <?php if($message): ?>
            <div class="text-gray-800 font-medium text-center whitespace-pre-line"><?php echo $message; ?></div>
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
