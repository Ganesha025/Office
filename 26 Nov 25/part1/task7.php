<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fruits Array Demo</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-r from-yellow-200 to-orange-200 min-h-screen flex items-center justify-center">

    <div class="bg-white rounded-3xl shadow-2xl p-8 max-w-md w-full transform hover:scale-105 transition-transform duration-300">
        <h1 class="text-3xl font-bold text-center text-yellow-700 mb-6">Fruits Array Demo</h1>

        <?php
        $fruits = ["Apple", "Banana", "Mango", "Orange", "Grapes"];
        $firstFruit = $fruits[0];
        $lastFruit = $fruits[count($fruits) - 1];
        $totalFruits = count($fruits);
        ?>

        <div class="p-6 bg-gradient-to-r from-yellow-50 to-orange-50 rounded-xl shadow-inner space-y-4">
            <p class="text-lg"><span class="font-semibold text-yellow-700">First Fruit:</span> <?php echo $firstFruit; ?></p>
            <p class="text-lg"><span class="font-semibold text-yellow-700">Last Fruit:</span> <?php echo $lastFruit; ?></p>
            <p class="text-lg"><span class="font-semibold text-yellow-700">Total Fruits:</span> <?php echo $totalFruits; ?></p>
        </div>

    </div>

</body>
</html>
