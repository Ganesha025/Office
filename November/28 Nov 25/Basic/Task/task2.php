<?php
$missionSummary = "";
$validCodeNames = ["Sol","Luna","Shadow","Ghost"];
$validLocations = ["Juarez","Tijuana","Monterrey","Mexico City"];
$validTargets = ["Capo","Lieutenant","Boss","Informant"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $codeName = $_POST['codeName'] ?? "";
    $location = $_POST['location'] ?? "";
    $target = $_POST['target'] ?? "";

    if (in_array($codeName,$validCodeNames) && in_array($location,$validLocations) && in_array($target,$validTargets)) {
        $missionSummary = "🕵️ Mission Summary: Agent $codeName is assigned to $location to neutralize the target $target.";
    } else {
        $missionSummary = "⚠️ Invalid input. Please use only the suggested options.";
    }

    // Save the mission summary in a temporary session variable
    session_start();
    $_SESSION['missionSummary'] = $missionSummary;

    // Redirect to the same page to prevent form resubmission
    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}

// Show the mission summary after redirect
session_start();
if (isset($_SESSION['missionSummary'])) {
    $missionSummary = $_SESSION['missionSummary'];
    unset($_SESSION['missionSummary']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sicario Mission Details</title>
<script src="https://cdn.tailwindcss.com"></script>

<link rel="stylesheet" href="../styles.css">
    <script src="https://code.jquery.com/jquery-3.7.1.slim.js"></script>
</head>
<body class="min-h-screen flex items-center justify-center bg-gray-900">
<div class="bg-gray-800/80 backdrop-blur-md rounded-3xl p-10 max-w-md w-full text-center shadow-2xl border-4 border-blue-500 animate-fadeIn">
<h2 class="text-3xl font-bold text-white mb-6">🕵️ Sicario Mission Details</h2>
<form method="post" id="missionForm" class="flex flex-col gap-4">
<input list="codeNames" id="currentDay" name="codeName" placeholder="Enter Code Name" class="p-4 rounded-xl bg-gray-700 text-white placeholder-gray-300 focus:bg-gray-600 focus:outline-none transition" oninput="validateForm()">
<datalist id="codeNames">
<?php foreach($validCodeNames as $c){echo "<option value='$c'>";} ?>
</datalist>

<input list="locations" name="location" placeholder="Enter Location" class="p-4 rounded-xl bg-gray-700 text-white placeholder-gray-300 focus:bg-gray-600 focus:outline-none transition" oninput="validateForm()">
<datalist id="locations">
<?php foreach($validLocations as $l){echo "<option value='$l'>";} ?>
</datalist>

<input list="targets" name="target" placeholder="Enter Target" class="p-4 rounded-xl bg-gray-700 text-white placeholder-gray-300 focus:bg-gray-600 focus:outline-none transition" oninput="validateForm()">
<datalist id="targets">
<?php foreach($validTargets as $t){echo "<option value='$t'>";} ?>
</datalist>

<button type="submit" id="submitBtn" disabled class="mt-4 py-3 rounded-full bg-blue-600 text-white font-bold transform hover:scale-105 transition shadow-lg disabled:bg-gray-500 disabled:text-gray-300 disabled:cursor-not-allowed">Generate Summary</button>
</form>
<?php if($missionSummary != ""): ?>
<div class="mt-6 text-white text-lg font-semibold animate-popIn"><?php echo $missionSummary; ?></div>
<?php endif; ?>
</div>
<style>
@keyframes fadeIn {0%{opacity:0;transform:translateY(-20px);}100%{opacity:1;transform:translateY(0);}}
@keyframes popIn {0%{transform:scale(0);}100%{transform:scale(1);}}
.animate-fadeIn {animation: fadeIn 1s ease-in-out;}
.animate-popIn {animation: popIn 0.5s ease-in-out;}
</style>
<script>
window.onload = function(){document.getElementById('missionForm').reset();validateForm();
    $('#currentDay').focus();

}
function validateForm(){
let codeName = document.querySelector('input[name="codeName"]').value;
let location = document.querySelector('input[name="location"]').value;
let target = document.querySelector('input[name="target"]').value;
let validCode = ["Sol","Luna","Shadow","Ghost"];
let validLoc = ["Juarez","Tijuana","Monterrey","Mexico City"];
let validTarget = ["Capo","Lieutenant","Boss","Informant"];
document.getElementById('submitBtn').disabled = !(validCode.includes(codeName) && validLoc.includes(location) && validTarget.includes(target));
}
</script>
<script src="../valid.js"></script>
</body>
</html>
