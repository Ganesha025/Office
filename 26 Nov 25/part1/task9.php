<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Array Functions Demo</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-r from-indigo-100 to-purple-100 min-h-screen flex items-center justify-center p-6">

<?php
$numbers = [23, 45, 12, 67, 34, 89];

// Maximum value
$maxValue = max($numbers);

// Minimum value
$minValue = min($numbers);

// Sort ascending
$ascending = $numbers;
sort($ascending);

// Sort descending
$descending = $numbers;
rsort($descending);
?>

<div class="w-full max-w-2xl space-y-6">
    <h1 class="text-4xl font-bold text-center text-indigo-700 mb-6">Array Functions Demo</h1>

    <div class="bg-white rounded-2xl shadow-2xl p-6 space-y-4">
        <p class="text-lg"><span class="font-semibold text-indigo-600">Original Array:</span> <?php echo '' . implode(', ', $numbers) . ''; ?></p>
        <p class="text-lg"><span class="font-semibold text-indigo-600">Maximum Value:</span> <?php echo $maxValue; ?></p>
        <p class="text-lg"><span class="font-semibold text-indigo-600">Minimum Value:</span> <?php echo $minValue; ?></p>
        <p class="text-lg"><span class="font-semibold text-indigo-600">Ascending Order:</span> <?php echo '' . implode(', ', $ascending) . ''; ?></p>
        <p class="text-lg"><span class="font-semibold text-indigo-600">Descending Order:</span> <?php echo '' . implode(', ', $descending) . ''; ?></p>
    </div>
</div>

</body>
</html>
