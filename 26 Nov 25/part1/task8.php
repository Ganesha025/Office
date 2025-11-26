<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Associative Array - Students</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-r from-purple-100 to-pink-100 min-h-screen flex items-center justify-center p-6">

<?php
// Associative array of students
$students = [
    [
        "Name" => "savage Boruto",
        "Age" => 21,
        "Grade" => "A",
        "Email" => "savage@sav.com"
    ],
    [
        "Name" => "king John",
        "Age" => 22,
        "Grade" => "B+",
        "Email" => "king@sav.com"
    ],
    [
        "Name" => "Savage King",
        "Age" => 20,
        "Grade" => "A-",
        "Email" => "savageer@save.com"
    ]
];
?>

<div class="w-full max-w-3xl space-y-6">
    <h1 class="text-4xl font-bold text-center text-purple-700 mb-6">Students Information</h1>

    <?php foreach ($students as $student): ?>
        <div class="bg-white rounded-2xl shadow-2xl p-6 space-y-3">
            <?php foreach ($student as $key => $value): ?>
                <div class="flex justify-between text-lg">
                    <span class="font-semibold text-purple-600"><?php echo $key; ?>:</span>
                    <span class="text-gray-700"><?php echo $value; ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>

</div>

</body>
</html>
