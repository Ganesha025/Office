<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Calculator</title>
    <!-- Tailwind CSS CDN -->
     <link rel="stylesheet" href="styles.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
        <h1 class="text-2xl font-bold mb-6 text-center">Simple Calculator</h1>
        
        <form method="post" action="" class="space-y-4">
            <div>
                <label class="block mb-1 font-medium">Enter first number:</label>
                <input type="number" name="num1" required class="val-salary w-full border border-gray-300 p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            <div>
                <label class="block mb-1 font-medium">Enter second number:</label>
                <input type="number" name="num2" required class="val-salary w-full border border-gray-300 p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded hover:bg-blue-600 transition-colors">
                Calculate
            </button>
        </form>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $num1 = isset($_POST['num1']) ? (int)$_POST['num1'] : 0;
            $num2 = isset($_POST['num2']) ? (int)$_POST['num2'] : 0;

            $addition = $num1 + $num2;
            $subtraction = $num1 - $num2;
            $multiplication = $num1 * $num2;
            $division = $num2 != 0 ? $num1 / $num2 : "Division by zero error";
            $modulus = $num2 != 0 ? $num1 % $num2 : "Modulus by zero error";

            echo "<div class='mt-6 bg-gray-50 p-4 rounded border border-gray-200'>";
            echo "<p class='mb-2'><strong>Addition:</strong> $addition</p>";
            echo "<p class='mb-2'><strong>Subtraction:</strong> $subtraction</p>";
            echo "<p class='mb-2'><strong>Multiplication:</strong> $multiplication</p>";
            echo "<p class='mb-2'><strong>Division:</strong> $division</p>";
            echo "<p><strong>Modulus:</strong> $modulus</p>";
            echo "</div>";
        }
        ?>

    </div>
<script src="./valid.js"></script>
</body>
</html>
