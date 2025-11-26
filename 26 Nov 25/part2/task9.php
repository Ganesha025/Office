<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Multi-Dimensional Marks Sheet</title>
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-green-50 min-h-screen flex items-center justify-center p-6">

<?php
$students = [
    "Anu"=>["Tamil"=>rand(50,100),"English"=>rand(50,100),"Math"=>rand(50,100)],
    "Kavi"=>["Tamil"=>rand(50,100),"English"=>rand(50,100),"Math"=>rand(50,100)],
    "Deepa"=>["Tamil"=>rand(50,100),"English"=>rand(50,100),"Math"=>rand(50,100)]
];

$totals=[];
foreach($students as $name=>$marks) $totals[$name]=array_sum($marks);

$highestSubject=[];
foreach(array_keys($students[array_key_first($students)]) as $sub){
    $max=0; $topper="";
    foreach($students as $name=>$marks){
        if($marks[$sub]>$max){ $max=$marks[$sub]; $topper=$name; }
    }
    $highestSubject[$sub]=["name"=>$topper,"marks"=>$max];
}

$overallTopper=array_keys($totals, max($totals))[0];
?>

<div class="w-full max-w-3xl bg-white rounded-2xl shadow-2xl p-6">
<h1 class="text-3xl font-bold text-center text-green-700 mb-6">Marks Sheet</h1>

<table class="w-full border-collapse border border-gray-300 text-center">
<tr class="bg-green-100">
<th class="border border-gray-300 p-2">Student</th>
<?php foreach(array_keys($students[array_key_first($students)]) as $sub) echo "<th class='border border-gray-300 p-2'>$sub</th>"; ?>
<th class="border border-gray-300 p-2">Total</th>
</tr>
<?php foreach($students as $name=>$marks): ?>
<tr class="odd:bg-white even:bg-green-50">
<td class="border border-gray-300 p-2"><?php echo $name; ?></td>
<?php foreach($marks as $m) echo "<td class='border border-gray-300 p-2'>$m</td>"; ?>
<td class="border border-gray-300 p-2 font-semibold"><?php echo $totals[$name]; ?></td>
</tr>
<?php endforeach; ?>
<tr class="bg-green-200 font-semibold">
<td class="border border-gray-300 p-2">Highest per Subject</td>
<?php foreach($highestSubject as $sub) echo "<td class='border border-gray-300 p-2'>{$sub['name']} ({$sub['marks']})</td>"; ?>
<td class="border border-gray-300 p-2"><?php echo $overallTopper; ?></td>
</tr>
</table>
</div>

</body>
</html>
