<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Grade Assignment</title>
<script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<link rel="stylesheet" href="styles.css">
</head>
<body class="bg-purple-50 min-h-screen flex items-center justify-center p-6">

<?php
$subjects = ["Tamil","English","Math","Science","Social"];
$marks = isset($_POST['marks']) ? $_POST['marks'] : [];
$total=$average=0;
$pass=true;
$grade="";
if(count($marks)==5){
    $marks = array_map('intval',$marks);
    $total = array_sum($marks);
    $average = round($total/5,2);
    foreach($marks as $m) if($m<=35) $pass=false;
    if(!$pass) $grade="Fail";
    else if($average>=90) $grade="A+";
    else if($average>=80) $grade="A";
    else if($average>=70) $grade="B";
    else if($average>=60) $grade="C";
    else $grade="Fail";
}
?>

<div class="w-full max-w-md bg-white rounded-2xl shadow-2xl p-6">
<h1 class="text-3xl font-bold text-center text-purple-700 mb-6">Grade Assignment</h1>

<form method="POST" class="space-y-4">
<?php foreach($subjects as $i=>$sub): ?>
<div class="flex justify-between items-center">
<label class="font-semibold text-purple-800"><?php echo $sub; ?>:</label>
<input type="number" name="marks[]" value="<?php echo $marks[$i]??''; ?>" class="val-mark w-20 border border-gray-300 p-2 rounded text-center" required>
</div>
<?php endforeach; ?>
<button type="submit" class="w-full bg-purple-600 text-white py-2 rounded font-semibold mt-4">Calculate</button>
</form>

<?php if($marks && count($marks)==5): ?>
<div class="mt-6 p-4 bg-purple-100 rounded-xl text-center space-y-2">
<p class="text-lg font-semibold text-purple-700">Total Marks: <?php echo $total; ?></p>
<p class="text-lg font-semibold text-purple-700">Average: <?php echo $average; ?></p>
<p class="text-lg font-semibold <?php echo $pass?'text-green-600':'text-red-600'; ?>">Result: <?php echo $pass?'Pass':'Fail'; ?></p>
<p class="text-lg font-semibold text-purple-800">Grade: <?php echo $grade; ?></p>
</div>
<?php endif; ?>

</div>
<script src='./valid.js'></script>
</body>
</html>
