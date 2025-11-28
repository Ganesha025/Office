<?php
session_start();

$missionMessage = "";

// After redirect, show the message if it exists
if (isset($_SESSION['missionMessage'])) {
    $missionMessage = $_SESSION['missionMessage'];
    unset($_SESSION['missionMessage']); // Clear message after showing
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $currentDay = isset($_POST['currentDay']) ? (int)$_POST['currentDay'] : 0;
    $daysUntilMission = isset($_POST['daysUntilMission']) ? (int)$_POST['daysUntilMission'] : 0;

    if ($currentDay > 0 && $daysUntilMission >= 0) {
        $missionDay = $currentDay + $daysUntilMission;
        $_SESSION['missionMessage'] = "📅 Current Day: $currentDay<br>⏳ Days Until Mission: $daysUntilMission<br>🚀 Mission Day (Day of the Month): $missionDay";
    } else {
        $_SESSION['missionMessage'] = "⚠️ Please enter valid numbers for current day and days until mission.";
    }

    // Redirect to the same page to avoid resubmission
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Mission Countdown</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="../styles.css">
    <script src="https://code.jquery.com/jquery-3.7.1.slim.js"></script>
</head>
<body class="min-h-screen flex items-center justify-center bg-gray-900">
<div class="bg-gray-800/80 backdrop-blur-md rounded-3xl p-10 max-w-md w-full text-center shadow-2xl border-4 border-green-500 animate-fadeIn">
<h2 class="text-3xl font-bold text-white mb-6">🕒 Mission Countdown</h2>
<form method="post" class="flex flex-col gap-4">
<input type="number" name="currentDay" id="currentDay" placeholder="Enter Current Day" value="<?php echo htmlspecialchars($currentDay); ?>" class="val-mark p-4 rounded-xl bg-gray-700 text-white placeholder-gray-300 focus:bg-gray-600 focus:outline-none transition">
<input type="number" name="daysUntilMission" placeholder="Enter Days Until Mission" value="<?php echo htmlspecialchars($daysUntilMission); ?>" class="val-mark p-4 rounded-xl bg-gray-700 text-white placeholder-gray-300 focus:bg-gray-600 focus:outline-none transition">
<span></span>
<button type="submit" class="mt-4 py-3 rounded-full bg-green-600 text-white font-bold transform hover:scale-105 transition shadow-lg">Calculate</button>
</form>
<?php if($missionMessage != ""): ?>
<div class="mt-6 text-white text-lg font-semibold animate-popIn"><?php echo $missionMessage; ?></div>
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
wndow.onload = function() {
    document.querySelector('form').reset();
    $('#currentDay').focus();
};
</script>
</body>
</html>
