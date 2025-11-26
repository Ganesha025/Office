<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Full Name Display</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
        <h1 class="text-2xl font-bold mb-6 text-center">Enter Your Name</h1>
        
        <form method="POST" class="space-y-4">
            <div>
                <label class="block mb-1 font-medium">First Name:</label>
                <input type="text" name="firstName" class="val-username w-full border border-gray-300 p-2 rounded" placeholder="Enter first name" required>
            </div>
            <div>
                <label class="block mb-1 font-medium">Last Name:</label>
                <input type="text" name="lastName" class="val-username w-full border border-gray-300 p-2 rounded" placeholder="Enter last name" required>
            </div>
            <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded hover:bg-blue-600 transition-colors">
                Show Full Name
            </button>
        </form>

        <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                // Get first and last name from the form
                $firstName = htmlspecialchars($_POST['firstName']);
                $lastName = htmlspecialchars($_POST['lastName']);

                // Concatenate with a space
                $fullName = $firstName . " " . $lastName;

                // Display the full name
                echo "<p class='mt-6 text-center text-xl font-semibold text-green-600'>Full Name: $fullName</p>";
            }
        ?>
    </div>
<script src="./valid.js"></script>
</body>
</html>
