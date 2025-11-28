<?php

$host = "localhost";
$user = "root";
$pass = "";
$db   = "school";

/* ----------------------------------------------------
   1. CONNECT TO MYSQL SERVER
-----------------------------------------------------*/
$conn = mysqli_connect($host, $user, $pass);

if (!$conn) {
    die("❌ Database Connection Failed: " . mysqli_connect_error());
}

/* ----------------------------------------------------
   2. CREATE DATABASE IF NOT EXISTS
-----------------------------------------------------*/
$dbQuery = "CREATE DATABASE IF NOT EXISTS `$db`";
if (!mysqli_query($conn, $dbQuery)) {
    die("❌ Database Creation Failed: " . mysqli_error($conn));
}

/* ----------------------------------------------------
   3. SELECT THE DATABASE
-----------------------------------------------------*/
mysqli_select_db($conn, $db);

/* ----------------------------------------------------
   4. CREATE students TABLE
-----------------------------------------------------*/
$studentsTable = "
CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    age INT NOT NULL
);
";

if (!mysqli_query($conn, $studentsTable)) {
    die("❌ Failed to create 'students' table: " . mysqli_error($conn));
}

/* ----------------------------------------------------
   5. CREATE subjects TABLE
-----------------------------------------------------*/
$subjectsTable = "
CREATE TABLE IF NOT EXISTS subjects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    subject_name VARCHAR(100) NOT NULL
);
";

if (!mysqli_query($conn, $subjectsTable)) {
    die("❌ Failed to create 'subjects' table: " . mysqli_error($conn));
}

/* ----------------------------------------------------
   6. CREATE marks TABLE
-----------------------------------------------------*/
$marksTable = "
CREATE TABLE IF NOT EXISTS marks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    subject_id INT,
    marks INT,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE CASCADE
);
";

if (!mysqli_query($conn, $marksTable)) {
    die("❌ Failed to create 'marks' table: " . mysqli_error($conn));
}

/* ----------------------------------------------------
   7. Insert default subjects if empty
      Tamil, English, Maths, Science, Social
-----------------------------------------------------*/
$checkSubjects = mysqli_query($conn, "SELECT COUNT(*) AS total FROM subjects");
$row = mysqli_fetch_assoc($checkSubjects);

if ($row['total'] == 0) {
    mysqli_query($conn, "
        INSERT INTO subjects (subject_name) VALUES
        ('Tamil'),
        ('English'),
        ('Maths'),
        ('Science'),
        ('Social')
    ");
}

?>
