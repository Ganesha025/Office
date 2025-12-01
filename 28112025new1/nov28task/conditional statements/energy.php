<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
include 'db.php';

$energyErr = $scoreErr = "";
$energyMsg = $scoreMsg = "";
$message = "";
$energy = $score = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $energy = $_POST['energy'];
    $score = $_POST['score'];

    // Validate Energy
    if ($energy === "") {
        $energyErr = "Energy level is required.";
    } elseif (!is_numeric($energy)) {
        $energyErr = "Energy must be a number.";
    } elseif ($energy < -100 || $energy > 100) {
        $energyErr = "Energy must be between -100 and 100.";
    }

    // Validate Score
    if ($score === "") {
        $scoreErr = "Training score is required.";
    } elseif (!is_numeric($score) || intval($score) != $score) {
        $scoreErr = "Score must be an integer.";
    } elseif ($score < 1 || $score > 100) {
        $scoreErr = "Score must be between 1 and 100.";
    }

    // If both valid, save to DB and calculate message
    if (!$energyErr && !$scoreErr) {
        $username = $_SESSION['username'];
        $stmt = $conn->prepare("INSERT INTO energy_grades (username, energy, score) VALUES (?, ?, ?)");
        $stmt->bind_param("sdi", $username, $energy, $score);
        $stmt->execute();

        // Energy message
        if ($energy > 0) $energyMsg = "Energy Status: You are energized and ready!";
        elseif ($energy < 0) $energyMsg = "Energy Status: Warning! Energy is low.";
        else $energyMsg = "Energy Status: Neutral energy. Proceed with caution.";

        // Score message
        if ($score >= 90) $scoreMsg = "Training Grade: A - Legendary Hero!";
        elseif ($score >= 80) $scoreMsg = "Training Grade: B - Excellent!";
        elseif ($score >= 70) $scoreMsg = "Training Grade: C - Good, but room for improvement.";
        elseif ($score >= 60) $scoreMsg = "Training Grade: D - Needs more training.";
        else $scoreMsg = "Training Grade: F - Back to the academy!";

        $message = $energyMsg . "<br>" . $scoreMsg;
    }
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
<style>
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
input[type=number] {
  -moz-appearance: textfield;
}
</style>
<script>
function enforceMax(input, max) {
    input.addEventListener('input', function() {
        let val = parseFloat(input.value);
        if (!isNaN(val) && val > max) input.value = max;
    });
}
window.addEventListener('DOMContentLoaded', () => {
    const energyInput = document.querySelector('input[name="energy"]');
    const scoreInput = document.querySelector('input[name="score"]');
    energyInput.focus();
    enforceMax(energyInput, 100);
    enforceMax(scoreInput, 100);
});
</script>
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
            <label class="block text-gray-700 mb-1">Energy Level (-100 to 100) <span class="text-red-600">*</span></label>
            <div class="flex items-center border rounded-lg px-3 py-2 focus-within:ring-2 focus-within:ring-blue-500">
                <span class="material-icons text-gray-400 mr-2">bolt</span>
                <input type="number" step="0.1" name="energy" placeholder="Enter energy level" value="<?php echo htmlspecialchars($energy); ?>" required class="w-full outline-none">
            </div>
            <?php if($energyErr): ?>
                <p class="text-red-500 text-sm mt-1"><?php echo $energyErr; ?></p>
            <?php endif; ?>
        </div>

        <div>
            <label class="block text-gray-700 mb-1">Training Score (1 to 100) <span class="text-red-600">*</span></label>
            <div class="flex items-center border rounded-lg px-3 py-2 focus-within:ring-2 focus-within:ring-blue-500">
                <span class="material-icons text-gray-400 mr-2">star</span>
                <input type="number" name="score" placeholder="Enter training score" value="<?php echo htmlspecialchars($score); ?>" required class="w-full outline-none">
            </div>
            <?php if($scoreErr): ?>
                <p class="text-red-500 text-sm mt-1"><?php echo $scoreErr; ?></p>
            <?php endif; ?>
        </div>

        <button type="submit" class="w-full bg-yellow-600 hover:bg-yellow-700 text-white py-2 rounded-lg font-semibold transition">Check Energy & Grade</button>

        <?php if($message): ?>
            <div class="text-gray-800 font-medium text-center whitespace-pre-line mt-4"><?php echo $message; ?></div>
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
