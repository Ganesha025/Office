<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
include 'db.php';
$missionMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $heroRank = intval($_POST['heroRank']);
    $villainStatus = strtolower($_POST['villainStatus']);
    $username = $_SESSION['username'];

    $stmt = $conn->prepare("INSERT INTO missions (username, heroRank, villainStatus) VALUES (?, ?, ?)");
    $stmt->bind_param("sis", $username, $heroRank, $villainStatus);
    $stmt->execute();

    // Hero Rank Check
    if ($heroRank == 1) $missionMessage .= "Access Denied: Trainees cannot take missions.<br>";
    elseif ($heroRank == 2) $missionMessage .= "Access Granted: Hero authorized for mission.<br>";
    elseif ($heroRank == 3) $missionMessage .= "Access Granted: Master Hero authorized with full privileges.<br>";
    else $missionMessage .= "Invalid rank.<br>";

    // Villain Status Check
    if ($villainStatus == "active") $missionMessage .= "Villain is still active. Stay alert!<br>";
    elseif ($villainStatus == "defeated") $missionMessage .= "Villain has been defeated. Great job!<br>";
    else $missionMessage .= "Unknown villain status.<br>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Mission - Superhero Academy</title>
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

        <h2 class="text-3xl font-bold text-gray-800 text-center">Mission Control</h2>
        <p class="text-gray-600 text-center">Welcome, <?php echo $_SESSION['username']; ?>!</p>

        <!-- HERO RANK DROPDOWN -->
        <div>
            <label class="block text-gray-700 mb-1">Hero Rank <span class="text-red-600">*</span></label>
            <div class="flex items-center border rounded-lg px-3 py-2">
                <span class="material-icons text-gray-400 mr-2">military_tech</span>
                <select name="heroRank" required autofocus class="w-full outline-none">
                    <option value="" disabled selected>Select your rank</option>
                    <option value="1">Trainee</option>
                    <option value="2">Hero</option>
                    <option value="3">Master Hero</option>
                </select>
            </div>
        </div>

        <!-- VILLAIN STATUS DROPDOWN -->
        <div>
            <label class="block text-gray-700 mb-1">Villain Status <span class="text-red-600">*</span></label>
            <div class="flex items-center border rounded-lg px-3 py-2">
                <span class="material-icons text-gray-400 mr-2">warning</span>
                <select name="villainStatus" required class="w-full outline-none">
                    <option value="" disabled selected>Select villain status</option>
                    <option value="active">Active</option>
                    <option value="defeated">Defeated</option>
                </select>
            </div>
        </div>

        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white py-2 rounded-lg font-semibold transition">
            Submit Mission
        </button>

        <?php if($missionMessage): ?>
            <div class="text-gray-800 font-medium text-center whitespace-pre-line"><?php echo $missionMessage; ?></div>
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
