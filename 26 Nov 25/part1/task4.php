<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>String Functions Demo</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-r from-blue-200 to-green-200 min-h-screen flex items-center justify-center">

    <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-lg w-full transform hover:scale-105 transition-transform duration-300">
        <h1 class="text-3xl font-bold text-center text-green-700 mb-6">String Functions Demo</h1>

        <form method="POST" class="space-y-4">
            <label class="block font-medium text-gray-700">Enter a String:</label>
            <input type="text" name="userString" placeholder="Hello PHP World"  required
                class="val-len-of-str w-full border border-gray-300 p-2 rounded focus:outline-none focus:ring-2 focus:ring-green-400">
            <button type="submit"
                class="w-full bg-green-500 text-white py-2 rounded hover:bg-green-600 transition-colors font-semibold">
                Analyze String
            </button>
        </form>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['userString'])) {
            $str = htmlspecialchars($_POST['userString']);
            $length = strlen($str);
            $upper = strtoupper($str);
            $lower = strtolower($str);
            $wordCount = str_word_count($str);
            ?>

            <div class="mt-6 p-4 bg-gradient-to-r from-green-50 to-blue-50 rounded-xl shadow-inner space-y-3">
                <p class="text-lg"><span class="font-semibold text-green-700">Original String:</span> "<?php echo $str; ?>"</p>
                <p class="text-lg"><span class="font-semibold text-green-700">Length:</span> <?php echo $length; ?> characters</p>
                <p class="text-lg"><span class="font-semibold text-green-700">Uppercase:</span> <?php echo $upper; ?></p>
                <p class="text-lg"><span class="font-semibold text-green-700">Lowercase:</span> <?php echo $lower; ?></p>
                <p class="text-lg"><span class="font-semibold text-green-700">Word Count:</span> <?php echo $wordCount; ?> words</p>
            </div>

        <?php } ?>
    </div>
<script src='./valid.js'></script>
</body>
</html>
