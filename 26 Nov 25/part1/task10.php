<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Students Marks Table</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-yellow-100 min-h-screen flex items-center justify-center p-6">

<?php
$students = [
    ["Name" => "Savage King", "Math" => 85, "Science" => 90, "English" => 78],
    ["Name" => "AB Gaming", "Math" => 75, "Science" => 88, "English" => 82],
    ["Name" => "Pushpa the fire", "Math" => 92, "Science" => 81, "English" => 89]
];
?>

<div class="w-full max-w-4xl">
    <h1 class="text-4xl font-bold text-center text-pink-700 mb-6">Students Marks Table</h1>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white rounded-xl shadow-2xl text-left">
            <thead class="bg-pink-200">
                <tr>
                    <th class="py-3 px-6 text-lg font-semibold">Name</th>
                    <th class="py-3 px-6 text-lg font-semibold">Math</th>
                    <th class="py-3 px-6 text-lg font-semibold">Science</th>
                    <th class="py-3 px-6 text-lg font-semibold">English</th>
                    <th class="py-3 px-6 text-lg font-semibold">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($students as $student): 
                    $total = $student["Math"] + $student["Science"] + $student["English"];
                ?>
                <tr class="border-b border-gray-200">
                    <td class="py-3 px-6 font-semibold text-pink-700"><?php echo $student["Name"]; ?></td>
                    <td class="py-3 px-6"><?php echo $student["Math"]; ?></td>
                    <td class="py-3 px-6"><?php echo $student["Science"]; ?></td>
                    <td class="py-3 px-6"><?php echo $student["English"]; ?></td>
                    <td class="py-3 px-6 font-semibold text-green-600"><?php echo $total; ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
