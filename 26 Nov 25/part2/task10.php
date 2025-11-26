<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Mini Report Generator</title>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="styles.css">
</head>
<body class="bg-blue-50 min-h-screen flex items-center justify-center p-6">

<?php
$students = ["2025CSE001"=>"Anu Kumar","2025CSE002"=>"Arun Kumar","2025CSE003"=>"Kavi Sharma"];
$subjects = ["Tamil","English","Math","Science","Social"];
?>

<div class="w-full max-w-2xl bg-white rounded-2xl shadow-2xl p-6">
<h1 class="text-3xl font-bold text-center text-blue-700 mb-6">Mini Report Generator</h1>

<form method="POST" class="space-y-4">
<select name="student" class="w-full p-2 border border-gray-300 rounded" required>
<option value="">Select Student</option>
<?php foreach($students as $reg=>$name) echo "<option value='$reg'>$name ($reg)</option>"; ?>
</select>

<select name="attendance" class="w-full p-2 border border-gray-300 rounded" required>
<option value="">Select Attendance</option>
<?php for($i=0;$i<=100;$i+=5) echo "<option value='$i'>$i%</option>"; ?>
</select>

<table class="w-full border-collapse border border-gray-300 text-center">
<tr class="bg-blue-100 font-semibold"><th class="border p-2">Subject</th><th class="border p-2">Marks</th></tr>
<?php foreach($subjects as $sub): ?>
<tr>
<td class="border p-2"><?php echo $sub; ?></td>
<td class="border p-2">
<input type="number" name="marks[<?php echo $sub; ?>]" min="0" max="100" placeholder="Enter Mark " class="val-mark w-full p-1 border border-gray-300 rounded" required>
</td>
</tr>
<?php endforeach; ?>
</table>

<button type="submit" class="w-full bg-blue-600 text-white py-2 rounded font-semibold">Generate Report</button>
</form>

<?php
if($_SERVER['REQUEST_METHOD']=="POST" && !empty($_POST['student'])){
    $reg = $_POST['student'];
    $name = $students[$reg] ?? "Unknown";
    $attendance = (int)$_POST['attendance'];
    $marks = $_POST['marks'];
    $totalMarks = array_sum($marks);
    $maxTotal = count($subjects)*100;
    $avg = $totalMarks / count($subjects);

    if($avg>=90) $grade="A+";
    elseif($avg>=80) $grade="A";
    elseif($avg>=70) $grade="B";
    elseif($avg>=60) $grade="C";
    else $grade="Fail";
?>
<div class="mt-6 p-6 bg-blue-100 rounded-xl text-center">
<h2 class="text-2xl font-bold mb-4">Report Card</h2>
<p class="text-lg"><strong>Name:</strong> <?php echo $name; ?></p>
<p class="text-lg"><strong>Reg No:</strong> <?php echo $reg; ?></p>
<p class="text-lg"><strong>Attendance:</strong> <?php echo $attendance; ?>%</p>
<p class="text-lg"><strong>Total Marks:</strong> <?php echo $totalMarks; ?> / <?php echo $maxTotal; ?></p>
<p class="text-lg font-semibold text-blue-700"><strong>Grade:</strong> <?php echo $grade; ?></p>
</div>
<?php } ?>

</div>
<script src='./valid.js'></script>
</body>
</html>
