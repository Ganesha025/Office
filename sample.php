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
        :root {
            --primary-dark: #1a237e;
            --primary-main: #283593;
            --primary-light: #3949ab;
            --accent: #ff4081;
            --success: #4caf50;
            --warning: #ff9800;
            --error: #f44336;
            --bg-light: #f5f7ff;
            --text-dark: #1a237e;
            --text-light: #5c6bc0;
        }
        
        body {
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-main) 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .main-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            overflow: hidden;
            margin: 30px 0;
        }
        
        .header-section {
            background: linear-gradient(135deg, var(--primary-main) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .content-section {
            padding: 30px;
        }
        
        .section-card {
            background: var(--bg-light);
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 25px;
            border: none;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }
        
        .section-title {
            color: var(--text-dark);
            font-weight: 700;
            margin-bottom: 20px;
            font-size: 1.3rem;
            border-bottom: 2px solid var(--primary-light);
            padding-bottom: 10px;
        }
        
        .form-control {
            border-radius: 10px;
            border: 2px solid #e1e5f2;
            padding: 12px 15px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: var(--primary-light);
            box-shadow: 0 0 0 3px rgba(40, 53, 147, 0.1);
        }
        
        .btn {
            border-radius: 10px;
            padding: 12px 25px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-main) 0%, var(--primary-light) 100%);
        }
        
        .btn-success {
            background: linear-gradient(135deg, var(--success) 0%, #66bb6a 100%);
        }
        
        .btn-danger {
            background: linear-gradient(135deg, var(--error) 0%, #ef5350 100%);
        }
        
        .btn-warning {
            background: linear-gradient(135deg, var(--warning) 0%, #ffb74d 100%);
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        .table-container {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }
        
        .table th {
            background: linear-gradient(135deg, var(--primary-main) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 15px;
            font-weight: 600;
            border: none;
        }
        
        .table td {
            padding: 15px;
            border-color: #e1e5f2;
            vertical-align: middle;
        }
        
        .table tbody tr:hover {
            background-color: #f8f9ff;
            transform: scale(1.01);
            transition: all 0.2s ease;
        }
        
        .course-code {
            font-weight: 700;
            color: var(--primary-dark);
            background: #e8eaf6;
            padding: 5px 10px;
            border-radius: 6px;
            display: inline-block;
        }
        
        .alert {
            border-radius: 10px;
            border: none;
            padding: 15px 20px;
            font-weight: 500;
        }
        
        .alert-success {
            background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
            color: #2e7d32;
        }
        
        .alert-danger {
            background: linear-gradient(135deg, #ffebee 0%, #ffcdd2 100%);
            color: #c62828;
        }
        
        .search-box {
            position: relative;
        }
        
        .search-box .form-control {
            padding-left: 45px;
        }
        
        .search-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
            z-index: 5;
        }
        
        .stats-card {
            background: linear-gradient(135deg, var(--primary-light) 0%, var(--primary-main) 100%);
            color: white;
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            margin-bottom: 20px;
        }
        
        .stats-number {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 5px;
        }
        
        .stats-label {
            font-size: 0.9rem;
            opacity: 0.9;
        }
        
        .constraint-info {
            background: #fff3e0;
            border-left: 4px solid var(--warning);
            padding: 10px 15px;
            border-radius: 0 8px 8px 0;
            margin: 15px 0;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>

<div class="container py-4">
    <div class="main-container">
        <div class="header-section">
            <h1 class="display-5 fw-bold"><i class="fas fa-graduation-cap me-3"></i>Academic Course Manager</h1>
            <p class="lead mb-0">Manage your university courses with ease and precision</p>
        </div>
        
        <div class="content-section">
            <?php if ($error): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i><?php echo $error; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i><?php echo $success; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="stats-number"><?php echo count($_SESSION['courses']); ?></div>
                        <div class="stats-label">Total Courses</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="stats-number">20</div>
                        <div class="stats-label">Max Limit</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <form method="get" class="search-box">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" class="form-control" name="search" placeholder="Search by course code or name..." value="<?php echo htmlspecialchars($search_term); ?>">
                    </form>
                </div>
            </div>
            
            <div class="row">
                <div class="col-lg-6">
                    <div class="section-card">
                        <h3 class="section-title"><i class="fas fa-plus-circle me-2"></i>Add New Course</h3>
                        
                        <div class="constraint-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Course code: 2-4 letters + 3 digits (e.g., CSE101). Name: 3-50 characters.
                        </div>
                        
                        <form id="addForm" method="post">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Course Code</label>
                                <input type="text" class="form-control" id="addCode" name="addCode" placeholder="e.g., CSE104, MAT201" maxlength="7">
                                <div class="form-text">Format: Department letters (2-4) + Course number (3 digits)</div>
                                <span class="error-msg" id="addCodeError"></span>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Course Name</label>
                                <input type="text" class="form-control" id="addName" name="addName" placeholder="e.g., Operating Systems, Calculus II" maxlength="50">
                                <span class="error-msg" id="addNameError"></span>
                            </div>
                            <button type="submit" name="addCourse" class="btn btn-success w-100">
                                <i class="fas fa-plus me-2"></i>Add Course
                            </button>
                        </form>
                    </div>
                </div>
                
                <div class="col-lg-6">
                    <div class="section-card">
                        <h3 class="section-title"><i class="fas fa-minus-circle me-2"></i>Remove Course</h3>
                        
                        <div class="constraint-info">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Enter the exact course code to remove a course. This action cannot be undone.
                        </div>
                        
                        <form id="removeForm" method="post">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Course Code to Remove</label>
                                <input type="text" class="form-control" id="removeCode" name="removeCode" placeholder="e.g., CSE102">
                                <span class="error-msg" id="removeCodeError"></span>
                            </div>
                            <button type="submit" name="removeCourse" class="btn btn-danger w-100">
                                <i class="fas fa-trash me-2"></i>Remove Course
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="section-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="section-title mb-0"><i class="fas fa-list me-2"></i>Course Catalog</h3>
                    <div>
                        <?php if ($search_term): ?>
                            <span class="badge bg-primary fs-6">Search: "<?php echo htmlspecialchars($search_term); ?>" (<?php echo count($courses); ?> results)</span>
                            <a href="?" class="btn btn-warning btn-sm ms-2"><i class="fas fa-times me-1"></i>Clear</a>
                        <?php endif; ?>
                    </div>
                </div>
                
                <?php if (empty($courses)): ?>
                    <div class="text-center py-5">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <h4 class="text-muted">No courses found</h4>
                        <p class="text-muted"><?php echo $search_term ? 'Try a different search term' : 'Add your first course using the form above'; ?></p>
                    </div>
                <?php else: ?>
                    <div class="table-container">
                        <table class="table table-hover mb-0">
                            <thead>
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
                                            <button type="submit" name="removeCourse" class="btn btn-outline-danger btn-sm" onclick="return confirm('Are you sure you want to remove <?php echo $code; ?>?')">
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

<script>
$(document).ready(function(){
    $('#addForm').submit(function(e){
        let code = $('#addCode').val().trim();
        let name = $('#addName').val().trim();
        let valid = true;
        
        $('.error-msg').hide();
        
        const codeRegex = /^[A-Z]{2,4}\d{3}$/;
        
        if(code === '') {
            $('#addCodeError').text('Course code is required').show();
            valid = false;
        } else if (!codeRegex.test(code)) {
            $('#addCodeError').text('Invalid format. Use 2-4 letters + 3 digits (e.g., CSE101)').show();
            valid = false;
        }
        
        if(name === '') {
            $('#addNameError').text('Course name is required').show();
            valid = false;
        } else if (name.length < 3 || name.length > 50) {
            $('#addNameError').text('Course name must be 3-50 characters').show();
            valid = false;
        }
        
        if(!valid) e.preventDefault();
    });
    
    $('#removeForm').submit(function(e){
        let code = $('#removeCode').val().trim();
        $('.error-msg').hide();
        
        if(code === '') {
            $('#removeCodeError').text('Course code is required').show();
            e.preventDefault();
        }
    });
    
    $('#addCode').on('input', function(){
        $(this).val($(this).val().toUpperCase());
        if($(this).val().trim() !== '') $('#addCodeError').hide();
    });
    
    $('#addName').on('input', function(){
        if($(this).val().trim() !== '') $('#addNameError').hide();
    });
    
    $('#removeCode').on('input', function(){
        $(this).val($(this).val().toUpperCase());
        if($(this).val().trim() !== '') $('#removeCodeError').hide();
    });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
