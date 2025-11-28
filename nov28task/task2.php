<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Character Info</title>
    <style>
        body {
            font-family: "Poppins", sans-serif;
            background: linear-gradient(135deg, #4e54c8, #8f94fb);
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .card {
            background: #ffffff;
            width: 350px;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
            text-align: center;
            animation: fadeIn 0.8s ease-in-out;
        }

        h2 {
            margin-bottom: 12px;
            color: #333;
        }

        p {
            font-size: 18px;
            color: #555;
            margin: 8px 0;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>

<body>

<div class="card">
    <?php
        
        $name = "Abirami";
        $age = 21;

        
        echo "<h2>Character Information</h2>";
        echo "<p>Hello, my name is " . $name . ".</p>";
        echo "<p>I am " . $age . " years old.</p>";
    ?>
</div>

</body>
</html>
