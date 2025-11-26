<?php
session_start();

if (!isset($_SESSION['courses'])) {
    $_SESSION['courses'] = [
        "CSE101" => "Data Structures",
        "CSE102" => "Algorithms",
        "CSE103" => "Database Systems",
        "MAT201" => "Calculus I",
        "PHY101" => "Physics Fundamentals"
    ];
}

$error = "";
$success = "";

if (isset($_POST['addCourse'])) {
    $code = strtoupper(trim($_POST['addCode']));
    $name = trim($_POST['addName']);
    
    if ($code == "" || $name == "") {
        $error = "Both course code and name are required";
    } elseif (!preg_match('/^[A-Z]{2,4}\d{3}$/', $code)) {
        $error = "Course code must be 2-4 letters followed by 3 digits (e.g., CSE101)";
    } elseif (strlen($name) < 3 || strlen($name) > 50) {
        $error = "Course name must be between 3 and 50 characters";
    } elseif (array_key_exists($code, $_SESSION['courses'])) {
        $error = "Course code already exists";
    } elseif (count($_SESSION['courses']) >= 20) {
        $error = "Maximum limit of 20 courses reached";
    } else {
        $_SESSION['courses'][$code] = $name;
        $success = "Course added successfully";
    }
}

if (isset($_POST['removeCourse'])) {
    $codeToRemove = strtoupper(trim($_POST['removeCode']));
    
    if ($codeToRemove == "") {
        $error = "Course code is required for removal";
    } elseif (!array_key_exists($codeToRemove, $_SESSION['courses'])) {
        $error = "Course code not found";
    } else {
        unset($_SESSION['courses'][$codeToRemove]);
        $success = "Course removed successfully";
    }
}

$courses = $_SESSION['courses'];

$search_term = "";
if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $search_term = strtoupper(trim($_GET['search']));
    $filtered_courses = [];
    foreach ($courses as $code => $name) {
        if (strpos($code, $search_term) !== false || stripos($name, $search_term) !== false) {
            $filtered_courses[$code] = $name;
        }
    }
    $courses = $filtered_courses;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Academic Course Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <style>
        .course-code {
            font-weight: 600;
            background: #e9ecef;
            padding: 4px 8px;
            border-radius: 4px;
            font-family: monospace;
        }
        .stats-card {
            border-left: 4px solid #0d6efd;
        }
        .constraint-box {
            background: #f8f9fa;
            border-left: 4px solid #ffc107;
        }
    </style>
</head>
<body class="bg-light">

<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm mb-4">

                </div>

                    <div class="row">
                        <div class="col-lg-6 mb-4">
                            <div class="card h-100">
                                <div class="card-header bg-success text-white">
                                    <h5 class="mb-0"><i class="fas fa-plus-circle me-2"></i>Add New Course</h5>
                                </div>
                                <div class="card-body">
                                    <form id="addForm" method="post">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Course Code</label>
                                            <input type="text" class="form-control" id="addCode" name="addCode" placeholder="e.g., CSE104, MAT201" maxlength="7">
                                            <div class="form-text">Format: 2-4 uppercase letters + 3 digits</div>
                                            <div class="invalid-feedback" id="addCodeError"></div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Course Name</label>
                                            <input type="text" class="form-control" id="addName" name="addName" placeholder="e.g., Operating Systems" maxlength="50">
                                            <div class="invalid-feedback" id="addNameError"></div>
                                        </div>
                                        <button type="submit" name="addCourse" class="btn btn-success w-100">
                                            <i class="fas fa-plus me-2"></i>Add Course
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-6 mb-4">
                            <div class="card h-100">
                                <div class="card-header bg-danger text-white">
                                    <h5 class="mb-0"><i class="fas fa-minus-circle me-2"></i>Remove Course</h5>
                                </div>
                                <div class="card-body">
                                    <form id="removeForm" method="post">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Course Code</label>
                                            <input type="text" class="form-control" id="removeCode" name="removeCode" placeholder="Enter course code to remove">
                                            <div class="invalid-feedback" id="removeCodeError"></div>
                                        </div>
                                        <button type="submit" name="removeCourse" class="btn btn-danger w-100">
                                            <i class="fas fa-trash me-2"></i>Remove Course
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                        
                        <div class="card-body">
                            <?php if (empty($courses)): ?>
                                <div class="text-center py-5">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">No courses found</h5>
                                    <p class="text-muted"><?php echo $search_term ? 'Try a different search term' : 'Add your first course using the form above'; ?></p>
                                </div>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-dark">
                                            <tr>
                                                <th width="25%">Course Code</th>
                                                <th width="60%">Course Name</th>
                                                <th width="15%" class="text-center">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($courses as $code => $name): ?>
                                            <tr>
                                                <td><span class="course-code"><?php echo $code; ?></span></td>
                                                <td><?php echo $name; ?></td>
                                                <td class="text-center">
                                                    <form method="post" class="d-inline">
                                                        <input type="hidden" name="removeCode" value="<?php echo $code; ?>">
                                                        <button type="submit" name="removeCourse" class="btn btn-outline-danger btn-sm" onclick="return confirm('Remove <?php echo $code; ?>?')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function(){
    $('#addForm').submit(function(e){
        let code = $('#addCode').val().trim();
        let name = $('#addName').val().trim();
        let valid = true;
        
        $('#addCode, #addName').removeClass('is-invalid');
        
        const codeRegex = /^[A-Z]{2,4}\d{3}$/;
        
        if(code === '') {
            $('#addCode').addClass('is-invalid');
            $('#addCodeError').text('Course code is required');
            valid = false;
        } else if (!codeRegex.test(code)) {
            $('#addCode').addClass('is-invalid');
            $('#addCodeError').text('Invalid format. Use 2-4 letters + 3 digits (e.g., CSE101)');
            valid = false;
        }
        
        if(name === '') {
            $('#addName').addClass('is-invalid');
            $('#addNameError').text('Course name is required');
            valid = false;
        } else if (name.length < 3 || name.length > 50) {
            $('#addName').addClass('is-invalid');
            $('#addNameError').text('Course name must be 3-50 characters');
            valid = false;
        }
        
        if(!valid) e.preventDefault();
    });
    
    $('#removeForm').submit(function(e){
        let code = $('#removeCode').val().trim();
        $('#removeCode').removeClass('is-invalid');
        
        if(code === '') {
            $('#removeCode').addClass('is-invalid');
            $('#removeCodeError').text('Course code is required');
            e.preventDefault();
        }
    });
    
    $('#addCode').on('input', function(){
        $(this).val($(this).val().toUpperCase());
        $(this).removeClass('is-invalid');
    });
    
    $('#addName').on('input', function(){
        $(this).removeClass('is-invalid');
    });
    
    $('#removeCode').on('input', function(){
        $(this).val($(this).val().toUpperCase());
        $(this).removeClass('is-invalid');
    });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
