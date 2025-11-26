<?php
session_start();

// Initialize courses only once
if (!isset($_SESSION['courses'])) {
    $_SESSION['courses'] = [
        "CSE101" => "Data Structures",
        "CSE102" => "Algorithms",
        "CSE103" => "Database Systems"
    ];
}

// Handle adding a new course
if (isset($_POST['addCourse'])) {
    $code = strtoupper(trim($_POST['addCode']));
    $name = trim($_POST['addName']);
    if ($code != "" && $name != "") {
        $_SESSION['courses'][$code] = $name;
    }
}

// Handle removing a course
if (isset($_POST['removeCourse'])) {
    $codeToRemove = strtoupper(trim($_POST['removeCode']));
    unset($_SESSION['courses'][$codeToRemove]);
}

// Use the session courses array
$courses = $_SESSION['courses'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Course List Management</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <style>
        .error-msg { color: red; font-size: 0.9rem; display: none; }
    </style>
</head>
<body class="bg-light">

<div class="container mt-5">

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white text-center">
            <h4 class="mb-0">Course List Management</h4>
        </div>
        <div class="card-body">

            <!-- Add Course Form -->
            <form id="addForm" method="post" class="row g-1 mb-4">
                <div class="col-md-4">
                    <input type="text" class="form-control" id="addCode" name="addCode" placeholder="Course Code e.g. CSE104">
                    <span class="error-msg" id="addCodeError">Course Code is required</span>
                </div>
                <div class="col-md-6">
                    <input type="text" class="form-control" id="addName" name="addName" placeholder="Course Name e.g. Operating Systems">
                    <span class="error-msg" id="addNameError">Course Name is required</span>
                </div>
                <div class="col-md-2">
                    <button type="submit" name="addCourse" class="btn btn-success w-100">Add Course</button>
                </div>
            </form>

            <!-- Remove Course Form -->
            <form id="removeForm" method="post" class="row g-1 mb-4">
                <div class="col-md-10">
                    <input type="text" class="form-control" id="removeCode" name="removeCode" placeholder="Course Code to Remove e.g. CSE102">
                    <span class="error-msg" id="removeCodeError">Course Code is required</span>
                </div>
                <div class="col-md-2">
                    <button type="submit" name="removeCourse" class="btn btn-danger w-100">Remove Course</button>
                </div>
            </form>

            <!-- Display Course Table -->
            <h5 class="mb-3">Current Courses</h5>
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Course Code</th>
                        <th>Course Name</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($courses as $code => $name): ?>
                    <tr>
                        <td><?php echo $code; ?></td>
                        <td><?php echo $name; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        </div>
    </div>

</div>

<script>
$(document).ready(function(){

    // Add course validation
    $('#addForm').submit(function(e){
        let code = $('#addCode').val().trim();
        let name = $('#addName').val().trim();
        let valid = true;

        if(code === '') {
            $('#addCodeError').show();
            valid = false;
        } else {
            $('#addCodeError').hide();
        }

        if(name === '') {
            $('#addNameError').show();
            valid = false;
        } else {
            $('#addNameError').hide();
        }

        if(!valid) e.preventDefault();
    });

    // Remove course validation
    $('#removeForm').submit(function(e){
        let code = $('#removeCode').val().trim();
        if(code === '') {
            $('#removeCodeError').show();
            e.preventDefault();
        } else {
            $('#removeCodeError').hide();
        }
    });

    // Hide error messages on input
    $('#addCode, #addName').on('input', function(){
        if($(this).val().trim() !== '') $(this).next('.error-msg').hide();
    });

    $('#removeCode').on('input', function(){
        if($(this).val().trim() !== '') $(this).next('.error-msg').hide();
    });

});
</script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
