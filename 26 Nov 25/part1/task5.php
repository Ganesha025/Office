<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>String Search & Replace</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-r from-blue-200 to-green-200 min-h-screen flex items-center justify-center">

    <div class="bg-white rounded-3xl shadow-2xl p-8 max-w-lg w-full transform hover:scale-105 transition-transform duration-300">
        <h1 class="text-3xl font-bold text-center text-green-700 mb-6">String Search & Replace</h1>

        <?php
            $originalString = "I love Java";
            $modifiedString = str_replace("Java", "PHP", $originalString);
        ?>

        <div class="p-6 bg-gradient-to-r from-green-50 to-blue-50 rounded-xl shadow-inner space-y-4">
            <p class="text-lg"><span class="font-semibold text-green-700">Original String:</span> "<?php echo $originalString; ?>"</p>
            <p class="text-lg"><span class="font-semibold text-green-700">Modified String:</span> "<?php echo $modifiedString; ?>"</p>
        </div>
    </div>

</body>
</html>
