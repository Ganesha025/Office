<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Student Attendance Tracker</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="styles.css">
</head>
<body class="bg-yellow-50 min-h-screen flex items-center justify-center p-6">

<?php
$days = ["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"];
$attendance = isset($_POST['attendance']) ? $_POST['attendance'] : [];
$present=$absent=$percent=0;
if(count($attendance) == 7){
    $present = count(array_filter($attendance, fn($v)=>strtoupper($v)=='P'));
    $absent = 7 - $present;
    $percent = round(($present/7)*100,2);
}
?>

<div class="w-full max-w-md bg-white rounded-2xl shadow-2xl p-6">
<h1 class="text-3xl font-bold text-center text-yellow-700 mb-6">Student Attendance</h1>

<form method="POST" class="space-y-4">
<?php foreach($days as $i=>$day): ?>
<div class="flex justify-between items-center">
<label class="font-semibold text-yellow-800"><?php echo $day; ?>:</label>
<input type="text" name="attendance[]" maxlength="1" value="<?php echo $attendance[$i]??''; ?>" placeholder="P/A" class="w-16 border border-gray-300 p-2 rounded text-center uppercase" required>
</div>
<?php endforeach; ?>
<button type="submit" class="w-full bg-yellow-600 text-white py-2 rounded font-semibold mt-4">Calculate</button>
</form>

<?php if($attendance && count($attendance)==7): ?>
<div class="mt-6 p-4 bg-yellow-100 rounded-xl text-center space-y-2">
<p class="text-lg font-semibold text-yellow-700">Days Present: <?php echo $present; ?></p>
<p class="text-lg font-semibold text-yellow-700">Days Absent: <?php echo $absent; ?></p>
<p class="text-lg font-semibold text-yellow-700">Attendance %: <?php echo $percent; ?>%</p>
</div>
<?php endif; ?>

</div>

<script>
document.querySelectorAll('input[name="attendance[]"]').forEach(input=>{
    input.addEventListener('input', function(){
        this.value = this.value.toUpperCase();
        if(!['P','A'].includes(this.value)) this.value='';
    });
});
</script>

</body>
</html>
