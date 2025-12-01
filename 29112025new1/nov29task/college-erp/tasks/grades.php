<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ERP - Grade Module</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<style>
.card-module { margin: 40px auto; padding: 30px; border-radius: 20px; background:#f8f9fa; box-shadow:0 12px 40px rgba(0,0,0,0.12); max-width:900px; }
.card-module h3 { font-weight:700; color:#0d6efd; display:flex; align-items:center; gap:12px; }
.input-marks, .input-student { width:150px; text-align:center; border-radius:8px; font-weight:500; -moz-appearance:textfield; margin-bottom:4px; }
.input-marks::-webkit-outer-spin-button,
.input-marks::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
.grade-box { padding:12px; font-size:22px; border-radius:12px; font-weight:bold; min-width:120px; display:inline-block; text-align:center; }
.grade-A { background:#28a745; color:white; }
.grade-B { background:#0d6efd; color:white; }
.grade-C { background:#ffc107; color:black; }
.grade-D { background:#fd7e14; color:white; }
.grade-F { background:#dc3545; color:white; }
.btn-modern { border-radius:10px; font-weight:600; transition:0.3s; }
.btn-modern:hover { transform:scale(1.05); box-shadow:0 5px 15px rgba(0,0,0,0.15); }
.fadeRow { animation: fadeIn 0.3s ease-in-out; }
@keyframes fadeIn { from {opacity:0; transform:translateY(12px);} to {opacity:1; transform:translateY(0);} }
.removeRowBtn { background:#dc3545; color:white; border:none; border-radius:8px; padding:6px 12px; cursor:pointer; }
.removeRowBtn:hover { background:#c82333; }
.addSubjectBtn { background:#0d6efd; color:white; border:none; border-radius:8px; padding:6px 12px; cursor:pointer; margin-left:4px;}
.addSubjectBtn:hover { background:#0b5ed7; }
.subject-row { display:flex; gap:8px; align-items:center; margin-bottom:4px; }
</style>
</head>
<body>

<div class="container">
  <div class="card card-module">
    <h3><i class="bi bi-award"></i> Grade Assignment</h3>
    <p class="text-secondary mb-3">Add students and subjects. Enter marks (1–100). System calculates average and grade.</p>

    <button class="btn btn-info btn-modern mb-3" id="addStudent"><i class="bi bi-plus-circle"></i> Add Student</button>

    <div id="studentsContainer"></div>

    <button class="btn btn-success btn-modern mt-3" id="calculateGrade"><i class="bi bi-calculator"></i> Calculate Grades</button>

    <div id="gradeResult" class="mt-4"></div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
let studentCount = 0;

// Function to add a student
function addStudent(){
    studentCount++;
    let studentHTML = `
    <div class="student-card p-3 mb-3 border rounded fadeRow" data-student="${studentCount}">
        <div class="d-flex align-items-center mb-2 gap-2">
            <input type="text" class="form-control input-student student-name" placeholder="Student Name" maxlength="20" style="width:200px;">
            <button type="button" class="btn btn-secondary addSubjectBtn">Add Subject</button>
            <button type="button" class="removeRowBtn">Remove Student</button>
        </div>
        <div class="subjectsContainer">
            <div class="subject-row">
                <strong>Subject 1:</strong>
                <input type="text" class="form-control input-marks marks" placeholder="Marks" maxlength="3" style="width:80px;">
                <button type="button" class="removeRowBtn">Remove</button>
            </div>
        </div>
    </div>`;
    $('#studentsContainer').append(studentHTML);
    $('.student-name').last().focus();
}

// Add initial student
addStudent();

// Add student button
$('#addStudent').click(addStudent);

// Remove student or subject row
$(document).on('click', '.removeRowBtn', function(){
    let parent = $(this).closest('.subject-row, .student-card');
    if(parent.hasClass('student-card')){
        parent.remove();
    } else {
        parent.remove();
    }
});

// Add subject for a student
$(document).on('click', '.addSubjectBtn', function(){
    let studentCard = $(this).closest('.student-card');
    let subjectCount = studentCard.find('.subject-row').length + 1;
    let subjectHTML = `
        <div class="subject-row">
            <strong>Subject ${subjectCount}:</strong>
            <input type="text" class="form-control input-marks marks" placeholder="Marks" maxlength="3" style="width:80px;">
            <button type="button" class="removeRowBtn">Remove</button>
        </div>`;
    studentCard.find('.subjectsContainer').append(subjectHTML);
});

// Input validation
$(document).on('input','.student-name',function(){
    let val = $(this).val();
    val = val.replace(/[^a-zA-Z\s]/g,'');
    $(this).val(val.substring(0,20));
});

$(document).on('input','.marks',function(){
    let val = this.value.replace(/[^0-9]/g,'');
    if(parseInt(val)>100) val='100';
    if(parseInt(val)<1 && val!=='') val='1';
    this.value = val;
});

// Calculate grades
function calculateGrade(avg){
    if(avg >= 90) return "A";
    else if(avg >= 80) return "B";
    else if(avg >= 70) return "C";
    else if(avg >= 60) return "D";
    else return "F";
}

$('#calculateGrade').click(function(){
    let valid = true;
    let html = "";

    $('.student-card').each(function(){
        let studentName = $(this).find('.student-name').val().trim();
        let marksArr = [];
        $(this).find('.marks').each(function(){
            let mark = parseInt($(this).val());
            if(isNaN(mark) || mark <1 || mark>100) valid=false;
            marksArr.push(mark);
        });
        if(studentName === "") valid=false;
        if(!valid) return false;

        let sum = marksArr.reduce((a,b)=>a+b,0);
        let avg = (sum/marksArr.length).toFixed(2);
        let grade = calculateGrade(avg);
        let gradeClass = "grade-"+grade;

        html += `
        <div class="p-3 mb-2 bg-light rounded shadow-sm">
          <h5>Student: <strong>${studentName}</strong></h5>
          <h5>Subjects: <strong>${marksArr.length}</strong></h5>
          <h5>Average Marks: <strong>${avg}</strong></h5>
          <h5>Assigned Grade: <span class="grade-box ${gradeClass}">${grade}</span></h5>
        </div>`;
    });

    if(!valid){
        $('#gradeResult').html(`<div class='alert alert-danger'>⚠ Enter valid student names and marks (1–100) for all subjects.</div>`);
        return;
    }

    $('#gradeResult').html(html);
});
</script>
</body>
</html>
