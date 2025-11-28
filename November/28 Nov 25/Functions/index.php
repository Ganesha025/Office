<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "school";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function addStudent($conn, $name, $age) {
    $stmt = $conn->prepare("INSERT INTO students (name, age) VALUES (?, ?)");
    $stmt->bind_param("si", $name, $age);
    $stmt->execute();
    $stmt->close();
}

function getStudents($conn) {
    $result = $conn->query("SELECT * FROM students ORDER BY id DESC");
    return $result->fetch_all(MYSQLI_ASSOC);
}

function getAverageAge($conn) {
    $result = $conn->query("SELECT AVG(age) as avg_age FROM students");
    $row = $result->fetch_assoc();
    return $row['avg_age'] ?? 0;
}

function findStudent($conn, $name) {
    $stmt = $conn->prepare("SELECT * FROM students WHERE name LIKE ?");
    $searchName = "%".$name."%";
    $stmt->bind_param("s", $searchName);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

function getSubjects($conn) {
    $result = $conn->query("SELECT * FROM subjects");
    return $result->fetch_all(MYSQLI_ASSOC);
}

function saveMarks($conn, $student_id, $marks) {
    foreach ($marks as $subject_id => $mark) {
        $stmt = $conn->prepare("INSERT INTO marks (student_id, subject_id, marks) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE marks = ?");
        $stmt->bind_param("iiii", $student_id, $subject_id, $mark, $mark);
        $stmt->execute();
        $stmt->close();
    }
}

function getStudentMarks($conn, $student_id) {
    $result = $conn->query("SELECT marks FROM marks WHERE student_id = $student_id");
    $marks = [];
    while ($row = $result->fetch_assoc()) {
        $marks[] = $row['marks'];
    }
    return $marks;
}

function getStudentTotalMarks($conn, $student_id) {
    $result = $conn->query("SELECT SUM(marks) as total FROM marks WHERE student_id = $student_id");
    $row = $result->fetch_assoc();
    return $row['total'] ?? 0;
}

function calculateGrade($marks) {
    if (empty($marks)) return 'N/A';
    $average = array_sum($marks) / count($marks);
    if ($average >= 90) return 'A';
    if ($average >= 80) return 'B';
    if ($average >= 70) return 'C';
    if ($average >= 60) return 'D';
    return 'F';
}

function getTopStudent($conn) {
    $result = $conn->query("SELECT s.name, SUM(m.marks) as total FROM students s JOIN marks m ON s.id = m.student_id GROUP BY s.id ORDER BY total DESC LIMIT 1");
    return $result->fetch_assoc();
}

$subjects = getSubjects($conn);
$students = getStudents($conn);

if (isset($_POST['addBtn'])) {
    addStudent($conn, $_POST['sname'], $_POST['sage']);
    header("Location: ".$_SERVER['PHP_SELF']); exit;
}

if (isset($_POST['saveMarksBtn'])) {
    saveMarks($conn, $_POST['student_id'], $_POST['marks']);
    header("Location: ".$_SERVER['PHP_SELF']); exit;
}

if (isset($_POST['searchBtn'])) {
    $searchStudent = findStudent($conn, $_POST['searchName']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Student Management System</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link rel="stylesheet" href="./styles.css">
    <script src="https://code.jquery.com/jquery-3.7.1.slim.js"></script>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
body { font-family: 'Inter', sans-serif; }
</style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">

<header class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-50">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
<div class="flex items-center justify-between">
<div class="flex items-center space-x-3">
<span class="material-icons text-blue-600 text-3xl">school</span>
<h1 class="text-2xl font-bold text-gray-900">Student Management System</h1>
</div>
<div class="flex items-center space-x-4">
<span class="material-icons text-gray-500 cursor-pointer hover:text-gray-700">notifications</span>
<span class="material-icons text-gray-500 cursor-pointer hover:text-gray-700">account_circle</span>
</div>
</div>
</div>
</header>

<main class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full">

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
<div class="flex items-center mb-4">
<span class="material-icons text-blue-600 mr-2">person_add</span>
<h2 class="text-xl font-semibold text-gray-800">Add Student</h2>
</div>
<form method="post" class="space-y-4">
<input type="text" name="sname" placeholder="Student Name" required id="StudentName" class="val-username w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition">
<span class="text-sm"></span>
<input type="number" name="sage" placeholder="Age" required class="val-age w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition">
<span class="text-sm text-red"></span>  
<button name="addBtn" class="w-full bg-blue-600 text-white px-6 py-2.5 rounded-lg hover:bg-blue-700 transition font-medium flex items-center justify-center">
<span class="material-icons mr-2 text-sm">add</span>
Add Student
</button>
</form>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
<div class="flex items-center mb-4">
<span class="material-icons text-green-600 mr-2">search</span>
<h2 class="text-xl font-semibold text-gray-800">Find Student</h2>
</div>
<form method="post" class="space-y-4">
<input type="text" name="searchName" placeholder="Enter Student Name" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition">
<button name="searchBtn" class="w-full bg-green-600 text-white px-6 py-2.5 rounded-lg hover:bg-green-700 transition font-medium flex items-center justify-center">
<span class="material-icons mr-2 text-sm">search</span>
Search
</button>
</form>
<?php if (isset($searchStudent)): ?>
<div class="mt-4 p-4 <?= $searchStudent ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200' ?> border rounded-lg">
<?php if ($searchStudent): ?>
<p class="text-green-800 font-medium"><?= $searchStudent['name'] ?> (Age: <?= $searchStudent['age'] ?>)</p>
<?php else: ?>
<p class="text-red-800 font-medium">No student found!</p>
<?php endif; ?>
</div>
<?php endif; ?>
</div>

</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
<div class="flex items-center mb-4">
<span class="material-icons text-purple-600 mr-2">edit</span>
<h2 class="text-xl font-semibold text-gray-800">Assign Marks</h2>
</div>
<form method="post" class="space-y-4">
<select name="student_id" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent outline-none transition">
<option value="">Select Student</option>
<?php foreach ($students as $s): ?>
<option value="<?= $s['id'] ?>"><?= $s['name'] ?></option>
<?php endforeach; ?>
</select>
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
<?php foreach ($subjects as $sub): ?>
<input type="number" name="marks[<?= $sub['id'] ?>]" placeholder="<?= $sub['subject_name'] ?>" required class="val-mark px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent outline-none transition">
<?php endforeach; ?>
</div>
<button name="saveMarksBtn" class="bg-purple-600 text-white px-6 py-2.5 rounded-lg hover:bg-purple-700 transition font-medium flex items-center">
<span class="material-icons mr-2 text-sm">save</span>
Save Marks
</button>
</form>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
<div class="flex items-center justify-between">
<div>
<p class="text-gray-500 text-sm font-medium mb-1">Average Age</p>
<p class="text-3xl font-bold text-gray-900"><?= number_format(getAverageAge($conn), 1) ?></p>
</div>
<span class="material-icons text-blue-600 text-4xl">bar_chart</span>
</div>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
<div class="flex items-center justify-between">
<div>
<p class="text-gray-500 text-sm font-medium mb-1">Total Students</p>
<p class="text-3xl font-bold text-gray-900"><?= count($students) ?></p>
</div>
<span class="material-icons text-green-600 text-4xl">people</span>
</div>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
<?php $top = getTopStudent($conn); ?>
<div class="flex items-center justify-between">
<div>
<p class="text-gray-500 text-sm font-medium mb-1">Top Scorer</p>
<?php if ($top): ?>
<p class="text-lg font-bold text-gray-900"><?= $top['name'] ?></p>
<p class="text-sm text-gray-600"><?= $top['total'] ?> marks</p>
<?php else: ?>
<p class="text-sm text-gray-600">No data yet</p>
<?php endif; ?>
</div>
<span class="material-icons text-yellow-600 text-4xl">emoji_events</span>
</div>
</div>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
<div class="flex items-center mb-4">
<span class="material-icons text-indigo-600 mr-2">list</span>
<h2 class="text-xl font-semibold text-gray-800">All Students</h2>
</div>
<div class="overflow-x-auto">
<table class="w-full">
<thead>
<tr class="border-b border-gray-200">
<th class="text-left py-3 px-4 font-semibold text-gray-700 text-sm">ID</th>
<th class="text-left py-3 px-4 font-semibold text-gray-700 text-sm">Name</th>
<th class="text-left py-3 px-4 font-semibold text-gray-700 text-sm">Age</th>
<th class="text-left py-3 px-4 font-semibold text-gray-700 text-sm">Total Marks</th>
<th class="text-left py-3 px-4 font-semibold text-gray-700 text-sm">Grade</th>
</tr>
</thead>
<tbody>
<?php foreach ($students as $s):
$total_marks = getStudentTotalMarks($conn, $s['id']);
$student_grade = calculateGrade(getStudentMarks($conn, $s['id']));
?>
<tr class="border-b border-gray-100 hover:bg-gray-50 transition">
<td class="py-3 px-4 text-sm text-gray-700"><?= $s['id'] ?></td>
<td class="py-3 px-4 text-sm font-medium text-gray-900"><?= $s['name'] ?></td>
<td class="py-3 px-4 text-sm text-gray-700"><?= $s['age'] ?></td>
<td class="py-3 px-4 text-sm text-gray-700"><?= $total_marks ?></td>
<td class="py-3 px-4">
<span class="px-3 py-1 rounded-full text-xs font-medium <?php
if ($student_grade == 'A') echo 'bg-green-100 text-green-800';
elseif ($student_grade == 'B') echo 'bg-blue-100 text-blue-800';
elseif ($student_grade == 'C') echo 'bg-yellow-100 text-yellow-800';
elseif ($student_grade == 'D') echo 'bg-orange-100 text-orange-800';
else echo 'bg-red-100 text-red-800';
?>"><?= $student_grade ?></span>
</td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>
</div>

</main>

<footer class="bg-white border-t border-gray-200 mt-auto">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
<div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
<div class="flex items-center space-x-2 text-gray-600 text-sm">
<span class="material-icons text-sm">copyright</span>
<span>2025 SavageInfo System. All rights reserved.</span>
</div>
<div class="flex items-center space-x-6 text-sm text-gray-600">
<a href="#" class="hover:text-gray-900 transition flex items-center">
<span class="material-icons text-sm mr-1">help</span>
Help
</a>
<a href="#" class="hover:text-gray-900 transition flex items-center">
<span class="material-icons text-sm mr-1">privacy_tip</span>
Privacy
</a>
<a href="#" class="hover:text-gray-900 transition flex items-center">
<span class="material-icons text-sm mr-1">description</span>
Terms
</a>
</div>
</div>
</div>
</footer>
<script src="./valid.js"></script>
<script>
    $(document).ready(function(){
        $('#StudentName').focus();
    })
</script>
</body>
</html>
<?php $conn->close();