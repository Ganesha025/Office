<?php
session_start();

// Grade Calculation
function calculateGrade($marks) {
    $average = array_sum($marks)/count($marks);
    if ($average >= 90) return "A";
    elseif ($average >= 80) return "B";
    elseif ($average >= 70) return "C";
    elseif ($average >= 60) return "D";
    else return "F";
}

// Initialize errors
$errors = ["name"=>"", "age"=>"", "marks1"=>"", "marks2"=>"", "marks3"=>""];
$searchError = "";
$searchResult = null;
$averageAge = null;

// Initialize students session
if (!isset($_SESSION['students'])) $_SESSION['students'] = [];

// Handle POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Add Student
    if (isset($_POST['add_student'])) {
        $name = trim($_POST['name'] ?? "");
        $age = trim($_POST['age'] ?? "");
        $marks1 = trim($_POST['marks1'] ?? "");
        $marks2 = trim($_POST['marks2'] ?? "");
        $marks3 = trim($_POST['marks3'] ?? "");

        // Validation
        if ($name === "" || !preg_match("/^[a-zA-Z\s]{1,15}$/", $name)) $errors['name'] = "Enter valid name (letters & spaces only, max 15 chars)";
        if ($age === "" || !is_numeric($age) || $age < 1 || $age > 100) $errors['age'] = "Enter valid age (1-100)";
        if ($marks1 === "" || !is_numeric($marks1) || $marks1 < 1 || $marks1 > 100) $errors['marks1'] = "Enter valid marks (1-100)";
        if ($marks2 === "" || !is_numeric($marks2) || $marks2 < 1 || $marks2 > 100) $errors['marks2'] = "Enter valid marks (1-100)";
        if ($marks3 === "" || !is_numeric($marks3) || $marks3 < 1 || $marks3 > 100) $errors['marks3'] = "Enter valid marks (1-100)";

        if (!array_filter($errors)) { // No errors
            $marks = [$marks1, $marks2, $marks3];
            $grade = calculateGrade($marks);
            $_SESSION['students'][] = [
                'name'=>$name, 'age'=>$age, 'marks'=>$marks, 'grade'=>$grade
            ];
            $_SESSION['success'] = "Student added successfully!";
            header("Location: ".$_SERVER['PHP_SELF']); // Redirect to avoid resubmit
            exit();
        } else {
            $_SESSION['errors'] = $errors; // Keep errors to display after redirect
            header("Location: ".$_SERVER['PHP_SELF']);
            exit();
        }
    }

    // Search Student
    if (isset($_POST['search_student'])) {
        $searchName = trim($_POST['search_name'] ?? "");
        if ($searchName === "" || !preg_match("/^[a-zA-Z\s]{1,15}$/", $searchName)) {
            $_SESSION['searchError'] = "Enter valid name (letters & spaces only, max 15 chars)";
        } else {
            foreach($_SESSION['students'] as $student) {
                if (strcasecmp($student['name'], $searchName) == 0) {
                    $_SESSION['searchResult'] = $student;
                    break;
                }
            }
            if (!isset($_SESSION['searchResult'])) $_SESSION['searchError'] = "No student found with that name.";
        }
        header("Location: ".$_SERVER['PHP_SELF']); // Redirect to avoid resubmit
        exit();
    }

    // Average Age
    if (isset($_POST['average_age'])) {
        $ages = array_column($_SESSION['students'], 'age');
        if ($ages) $_SESSION['averageAge'] = round(array_sum($ages)/count($ages), 2);
        header("Location: ".$_SERVER['PHP_SELF']); // Redirect to avoid resubmit
        exit();
    }
}

// Display messages after redirect
if(isset($_SESSION['success'])) { $successMsg = $_SESSION['success']; unset($_SESSION['success']); }
if(isset($_SESSION['errors'])) { $errors = $_SESSION['errors']; unset($_SESSION['errors']); }
if(isset($_SESSION['searchError'])) { $searchError = $_SESSION['searchError']; unset($_SESSION['searchError']); }
if(isset($_SESSION['searchResult'])) { $searchResult = $_SESSION['searchResult']; unset($_SESSION['searchResult']); }
if(isset($_SESSION['averageAge'])) { $averageAge = $_SESSION['averageAge']; unset($_SESSION['averageAge']); }

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Student Management System</title>
<style>
* { box-sizing: border-box; margin:0; padding:0; font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
body { background:#f0f4f8; display:flex; justify-content:center; min-height:100vh; padding:20px; }
.container { max-width:900px; width:100%; background:#fff; padding:30px; border-radius:15px; box-shadow:0 10px 25px rgba(0,0,0,0.1);}
h1,h2 { margin-bottom:20px; color:#2c3e50;}
form { display:flex; flex-wrap:wrap; gap:15px; margin-bottom:25px;}
input[type="text"], input[type="number"] { flex:1 1 200px; padding:10px; border:1px solid #ccc; border-radius:8px; font-size:16px;}
input[type="number"]::-webkit-inner-spin-button,
input[type="number"]::-webkit-outer-spin-button { -webkit-appearance:none; margin:0; }
button { padding:10px 20px; border:none; border-radius:8px; background:#3498db; color:#fff; cursor:pointer; font-size:16px; transition:0.3s;}
button:hover { background:#2980b9;}
table { width:100%; border-collapse:collapse; margin-top:20px;}
table th, table td { padding:10px; border:1px solid #ddd; text-align:center;}
table th { background:#3498db; color:#fff;}
.error { color:#e74c3c; font-size:14px;}
.result-box { margin-top:20px; padding:15px; border-radius:10px; background:#ecf0f1; font-weight:bold;}
@media(max-width:600px) { form{flex-direction:column;} input[type="text"], input[type="number"]{flex:1;} }
</style>
<script>
// Letters and spaces only
function onlyAlphabets(e) {
    let char = String.fromCharCode(e.which);
    if (!/[a-zA-Z\s]/.test(char)) { e.preventDefault(); return false; }
}

// Numbers 1-100 only
function onlyOneToThreeDigits(e, field) {
    let char = String.fromCharCode(e.which);
    if (!/[0-9]/.test(char)) { e.preventDefault(); return false; }
    let value = field.value + char;
    if (parseInt(value) > 100) { e.preventDefault(); return false; }
    return true;
}

// Focus first field on page load
window.onload = function() {
    let firstInput = document.querySelector('input[name="name"]');
    if(firstInput) firstInput.focus();
};
</script>
</head>
<body>
<div class="container">
<h1>Student Management System</h1>

<?php if(isset($successMsg)): ?><div class="result-box"><?php echo $successMsg; ?></div><?php endif; ?>

<h2>Add Student</h2>
<form method="post" novalidate>
    <div>
        <input type="text" name="name" placeholder="Student Name" maxlength="15" onkeypress="return onlyAlphabets(event)" required>
        <?php if($errors['name']): ?><div class="error"><?php echo $errors['name']; ?></div><?php endif; ?>
    </div>

    <div>
        <input type="number" name="age" placeholder="Age" min="1" max="100" onkeypress="return onlyOneToThreeDigits(event,this)" required>
        <?php if($errors['age']): ?><div class="error"><?php echo $errors['age']; ?></div><?php endif; ?>
    </div>

    <div>
        <input type="number" name="marks1" placeholder="Subject1" min="1" max="100" onkeypress="return onlyOneToThreeDigits(event,this)" required>
        <?php if($errors['marks1']): ?><div class="error"><?php echo $errors['marks1']; ?></div><?php endif; ?>
    </div>

    <div>
        <input type="number" name="marks2" placeholder="Subject2 " min="1" max="100" onkeypress="return onlyOneToThreeDigits(event,this)" required>
        <?php if($errors['marks2']): ?><div class="error"><?php echo $errors['marks2']; ?></div><?php endif; ?>
    </div>

    <div>
        <input type="number" name="marks3" placeholder="Subject3" min="1" max="100" onkeypress="return onlyOneToThreeDigits(event,this)" required>
        <?php if($errors['marks3']): ?><div class="error"><?php echo $errors['marks3']; ?></div><?php endif; ?>
    </div>

    <button type="submit" name="add_student">Add Student</button>
</form>

<h2>Search Student by Name</h2>
<form method="post" novalidate>
    <div>
        <input type="text" name="search_name" placeholder="Enter name to search" maxlength="15" onkeypress="return onlyAlphabets(event)" required>
        <?php if($searchError): ?><div class="error"><?php echo $searchError; ?></div><?php endif; ?>
    </div>
    <button type="submit" name="search_student">Search</button>
</form>


<form method="post">
    <button type="submit" name="average_age">Calculate Average Age</button>
</form>

<?php if($searchResult): ?>
<div class="result-box">
<p><strong>Name:</strong> <?php echo $searchResult['name']; ?></p>
<p><strong>Age:</strong> <?php echo $searchResult['age']; ?></p>
<p><strong>Marks:</strong> <?php echo implode(", ", $searchResult['marks']); ?></p>
<p><strong>Grade:</strong> <?php echo $searchResult['grade']; ?></p>
</div>
<?php endif; ?>

<?php if($averageAge !== null): ?>
<div class="result-box">Average Age of Students: <?php echo $averageAge; ?></div>
<?php endif; ?>

<?php if(!empty($_SESSION['students'])): ?>
<h2>All Students</h2>
<table>
<tr>
<th>Name</th>
<th>Age</th>
<th>Marks</th>
<th>Grade</th>
</tr>
<?php foreach($_SESSION['students'] as $student): ?>
<tr>
<td><?php echo $student['name']; ?></td>
<td><?php echo $student['age']; ?></td>
<td><?php echo implode(", ", $student['marks']); ?></td>
<td><?php echo $student['grade']; ?></td>
</tr>
<?php endforeach; ?>
</table>
<?php endif; ?>
</div>
</body>
</html>
