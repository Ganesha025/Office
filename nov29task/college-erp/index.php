<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ERP Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Custom CSS -->
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', sans-serif;
        }

        #sidebar {
            min-height: 100vh;
            background-color: #343a40;
            color: #fff;
            transition: all 0.3s;
        }

        #sidebar h3 {
            padding: 20px 10px;
            text-align: center;
            font-weight: bold;
            font-size: 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        #sidebar .nav-link {
            color: #adb5bd;
            font-weight: 500;
            padding: 12px 20px;
            transition: all 0.3s;
        }

        #sidebar .nav-link:hover, #sidebar .nav-link.active {
            background-color: #495057;
            color: #fff;
            border-radius: 5px;
        }

        #content {
            padding: 30px;
            background-color: #fff;
            min-height: 100vh;
            box-shadow: inset 0 0 10px rgba(0,0,0,0.03);
        }

        .task-card {
            padding: 20px;
            margin-bottom: 25px;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.05);
            background-color: #fff;
        }

        .table th {
            text-align: center;
            vertical-align: middle;
        }

        .table td {
            text-align: center;
            vertical-align: middle;
        }

        .btn-primary {
            transition: 0.3s;
        }

        .btn-primary:hover {
            background-color: #0b5ed7;
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-2" id="sidebar">
            <h3>ERP Dashboard</h3>
            <ul class="nav flex-column">
                <li class="nav-item"><a href="#" class="nav-link" data-task="attendance">Attendance</a></li>
                <li class="nav-item"><a href="#" class="nav-link" data-task="grades">Grades</a></li>
                <li class="nav-item"><a href="#" class="nav-link" data-task="Email">Email Generator</a></li>
                <li class="nav-item"><a href="#" class="nav-link" data-task="subject">Subject Allotment</a></li>
                <li class="nav-item"><a href="#" class="nav-link" data-task="timetable">Class Timetable</a></li>
                <li class="nav-item"><a href="#" class="nav-link" data-task="salary">Salary Slip</a></li>
                <li class="nav-item"><a href="#" class="nav-link" data-task="feedback">Feedback Count</a></li>
                <li class="nav-item"><a href="#" class="nav-link" data-task="holiday">Holiday Checker</a></li>
                <li class="nav-item"><a href="#" class="nav-link" data-task="rank">Rank Students</a></li>
                <li class="nav-item"><a href="#" class="nav-link" data-task="studentid">Student ID Generator</a></li>
                <li class="nav-item"><a href="#" class="nav-link" data-task="internalmarks">Auto Internal Marks</a></li>
                <li class="nav-item"><a href="#" class="nav-link" data-task="validate">Validate Form</a></li>
                <li class="nav-item"><a href="#" class="nav-link" data-task="library">Library Fine</a></li>
                <li class="nav-item"><a href="#" class="nav-link" data-task="teachinghours">Teaching Hours</a></li>
                <li class="nav-item"><a href="#" class="nav-link" data-task="sms">SMS Template</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="col-md-10" id="content">
            <h2>Welcome to ERP Dashboard</h2>
            <p>Select a task from the sidebar to start.</p>
        </div>
    </div>
</div>

<!-- jQuery to load task dynamically -->
<script>
$(document).ready(function(){
    $('#sidebar .nav-link').click(function(e){
        e.preventDefault();

        // Highlight active link
        $('#sidebar .nav-link').removeClass('active');
        $(this).addClass('active');

        var task = $(this).data('task');
        $('#content').html('<p>Loading '+task+'...</p>');

        // Load task PHP file dynamically
        $('#content').load('tasks/' + task + '.php');
    });
});
</script>

</body>
</html>
