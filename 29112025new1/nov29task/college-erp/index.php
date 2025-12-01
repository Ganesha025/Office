<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>ERP Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>

<style>
body {
    background: #f0f2f5;
    font-family: 'Segoe UI', sans-serif;
}

/* Sidebar */
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

/* Main Content */
#content {
    padding: 30px;
    min-height: 100vh;
    background: linear-gradient(135deg, #ffffff, #e9ecef);
}

/* Welcome Card */
.welcome-card {
    background: linear-gradient(135deg, #6a11cb, #2575fc);
    color: #fff;
    padding: 50px 30px;
    border-radius: 20px;
    text-align: center;
    box-shadow: 0 8px 30px rgba(0,0,0,0.1);
    transition: transform 0.3s, box-shadow 0.3s;
}

.welcome-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(0,0,0,0.2);
}

.welcome-card h1 {
    font-size: 2.5rem;
    margin-bottom: 15px;
}

.welcome-card p {
    font-size: 1.2rem;
    margin-bottom: 10px;
}

/* Info Panels */
.info-panels {
    margin-top: 40px;
}
.info-panel {
    background: #fff;
    border-radius: 15px;
    padding: 25px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.05);
    text-align: center;
    transition: transform 0.3s, box-shadow 0.3s;
}
.info-panel:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 30px rgba(0,0,0,0.1);
}
.info-panel i {
    font-size: 2.5rem;
    color: #6a11cb;
    margin-bottom: 15px;
}
.info-panel h4 {
    margin-bottom: 10px;
    font-weight: 600;
}
.info-panel p {
    color: #6c757d;
}

/* Fade animation for dynamic tasks */
#content > .task-content {
    animation: fadeIn 0.5s ease-in-out;
}
@keyframes fadeIn {
    0% {opacity:0; transform: translateY(10px);}
    100% {opacity:1; transform: translateY(0);}
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
            <!-- Welcome Section -->
            <div class="welcome-card">
                <i class="fas fa-chalkboard-teacher"></i>
                <h1>Welcome to ERP Dashboard</h1>
                <p>Manage students, staff, attendance, and reports all in one place.</p>
                <p style="font-style: italic;">“Efficiency is doing things right; effectiveness is doing the right things.”</p>
            </div>

            <!-- Info Panels -->
            <div class="row info-panels g-4 mt-4">
                <div class="col-md-4">
                    <div class="info-panel">
                        <i class="fas fa-user-graduate"></i>
                        <h4>Total Students</h4>
                        <p>150 students enrolled</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-panel">
                        <i class="fas fa-calendar-alt"></i>
                        <h4>Upcoming Holidays</h4>
                        <p>5 holidays this month</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-panel">
                        <i class="fas fa-chart-line"></i>
                        <h4>Average Attendance</h4>
                        <p>88% attendance rate</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- jQuery for dynamic task loading -->
<script>
$(document).ready(function(){
    $('#sidebar .nav-link').click(function(e){
        e.preventDefault();
        $('#sidebar .nav-link').removeClass('active');
        $(this).addClass('active');

        var task = $(this).data('task');
        $('#content').html('<p>Loading '+task+'...</p>');

        $('#content').load('tasks/' + task + '.php', function(){
            $('#content').addClass('task-content');
        });
    });
});
</script>

</body>
</html>
