<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Palindrome Checker</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>'
    
     <link rel="stylesheet" href="styles.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>'
</head>
<body class="bg-gradient-to-r from-purple-200 to-pink-200 min-h-screen flex items-center justify-center">

    <div class="bg-white rounded-3xl shadow-2xl p-8 max-w-lg w-full transform hover:scale-105 transition-transform duration-300">
        <h1 class="text-3xl font-bold text-center text-purple-700 mb-6">Palindrome Checker</h1>

        <form method="POST" class="space-y-4">
            <label class="block font-medium text-gray-700">Enter a String:</label>
            <input type="text" name="userString" placeholder="madam" required
                class="val-len-of-str w-full border border-gray-300 p-2 rounded focus:outline-none focus:ring-2 focus:ring-purple-400">
            <button type="submit"
                class="w-full bg-purple-500 text-white py-2 rounded hover:bg-purple-600 transition-colors font-semibold">
                Check Palindrome
            </button>
        </form>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['userString'])) {
            $str = htmlspecialchars($_POST['userString']);
            
            // Normalize string: lowercase, remove spaces
            $cleanStr = strtolower(str_replace(' ', '', $str));
            
            // Check palindrome
            $isPalindrome = $cleanStr === strrev($cleanStr);
        ?>
            <div class="mt-6 p-4 bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl shadow-inner space-y-3 text-center">
                <p class="text-lg"><span class="font-semibold text-purple-700">Input String:</span> "<?php echo $str; ?>"</p>
                <p class="text-xl font-bold <?php echo $isPalindrome ? 'text-green-600' : 'text-red-600'; ?>">
                    <?php echo $isPalindrome ? "✅ It's a palindrome!" : "❌ Not a palindrome." ?>
                </p>
            </div>
        <?php } ?>

    </div>
<script src='./valid.js'></script>

</body>
</html>
