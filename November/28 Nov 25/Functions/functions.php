<?php
include "db.php"; // Ensure database connection is available

/* ------------------------------
   Task 1: Find Maximum of Three
-------------------------------*/
function findMax($a, $b, $c) {
    return max($a, $b, $c);
}

/* ------------------------------
   Task 2: Grade Calculation
   Input: associative array of marks
   Output: grade (A-F)
-------------------------------*/
function calculateGrade($marks) {
    if (count($marks) == 0) return "No marks";

    $average = array_sum($marks) / count($marks);

    if ($average >= 90) return "A";
    if ($average >= 80) return "B";
    if ($average >= 70) return "C";
    if ($average >= 60) return "D";
    return "F";
}

/* ------------------------------
   Task 3: Student Management
-------------------------------*/

// Add a student
function addStudent($conn, $name, $age) {
    $name = mysqli_real_escape_string($conn, $name);
    mysqli_query($conn, "INSERT INTO students (name, age) VALUES ('$name', $age)");
}

// Get all students
function getStudents($conn) {
    $result = mysqli_query($conn, "SELECT * FROM students");
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

// Get average age of students
function getAverageAge($conn) {
    $result = mysqli_query($conn, "SELECT AVG(age) AS avg_age FROM students");
    $row = mysqli_fetch_assoc($result);
    return round($row['avg_age'], 2);
}

// Find student by name
function findStudent($conn, $name) {
    $name = mysqli_real_escape_string($conn, $name);
    $result = mysqli_query($conn, "SELECT * FROM students WHERE name='$name'");
    return mysqli_fetch_assoc($result);
}

// Get all subjects
function getSubjects($conn) {
    $result = mysqli_query($conn, "SELECT * FROM subjects");
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

// Save marks for a student
function saveMarks($conn, $student_id, $marks) {
    foreach ($marks as $subject_id => $mark) {
        $subject_id = (int)$subject_id;
        $mark = (int)$mark;
        mysqli_query($conn, "INSERT INTO marks (student_id, subject_id, marks) VALUES ($student_id, $subject_id, $mark)");
    }
}

// Get marks for a student
function getStudentMarks($conn, $student_id) {
    $sql = "
        SELECT subjects.subject_name, marks.marks 
        FROM marks 
        JOIN subjects ON marks.subject_id = subjects.id 
        WHERE marks.student_id = $student_id
    ";
    $result = mysqli_query($conn, $sql);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

/* ------------------------------
   Total Marks & Top Scorer
-------------------------------*/

// Calculate total marks per student
function getStudentTotalMarks($conn, $student_id) {
    $sql = "SELECT SUM(marks) as total FROM marks WHERE student_id = $student_id";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    return (int)$row['total'];
}

// Get all students with total marks
function getAllStudentTotals($conn) {
    $students = getStudents($conn);
    $totals = [];
    foreach ($students as $s) {
        $totals[$s['name']] = getStudentTotalMarks($conn, $s['id']);
    }
    return $totals;
}

// Get student with highest total marks
function getTopStudent($conn) {
    $totals = getAllStudentTotals($conn);
    if(empty($totals)) return null;

    $maxTotal = max($totals); // PHP max() finds highest value
    $topStudents = array_keys($totals, $maxTotal); // In case multiple students have same total
    return [
        'name' => $topStudents[0],
        'total' => $maxTotal
    ];
}

?>
