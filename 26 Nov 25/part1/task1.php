<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Info</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-r from-blue-100 to-purple-100 min-h-screen flex items-center justify-center">

    <div class="bg-white shadow-2xl rounded-xl p-8 max-w-md w-full transform hover:scale-105 transition-transform duration-300">
        <?php
        $student = [
            "name" => "Alice",
            "age" => 20,
            "height" => 5.6,
            "isStudent" => true,
            "marks" => [
                "Math" => 85,
                "Science" => 90,
                "English" => 78,
                "History" => 88
            ]
        ];

        $totalMarks = array_sum($student["marks"]);
        ?>

        <h1 class="text-3xl font-bold text-center text-purple-600 mb-6">Student Information</h1>

        <div class="space-y-2">
            <p class="text-lg"><span class="font-semibold text-gray-700">Name:</span> <?php echo $student["name"]; ?></p>
            <p class="text-lg"><span class="font-semibold text-gray-700">Age:</span> <?php echo $student["age"]; ?></p>
            <p class="text-lg"><span class="font-semibold text-gray-700">Height:</span> <?php echo $student["height"]; ?> ft</p>
            <p class="text-lg"><span class="font-semibold text-gray-700">Is Student?</span> <?php echo $student["isStudent"] ? " Yes" : " No"; ?></p>
        </div>

        <h2 class="text-2xl font-semibold mt-6 text-purple-500 mb-3 text-center">Marks</h2>
        <table class="w-full text-left border-collapse">
            <thead>
                <tr>
                    <th class="border-b-2 border-purple-300 pb-2 text-gray-700">Subject</th>
                    <th class="border-b-2 border-purple-300 pb-2 text-gray-700">Marks</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($student["marks"] as $subject => $marks): ?>
                    <tr class="hover:bg-purple-50 transition-colors">
                        <td class="py-2 px-1"><?php echo $subject; ?></td>
                        <td class="py-2 px-1 font-semibold text-purple-600"><?php echo $marks; ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr class="bg-purple-100 font-bold">
                    <td class="py-2 px-1">Total</td>
                    <td class="py-2 px-1 text-purple-800"><?php echo $totalMarks; ?></td>
                </tr>
            </tbody>
        </table>
    </div>

</body>
</html>
