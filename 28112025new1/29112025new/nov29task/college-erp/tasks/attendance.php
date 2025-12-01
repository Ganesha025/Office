<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ERP - Attendance Module</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<style>
.card-module { margin: 40px auto; padding: 30px; border-radius: 20px; background:#f8f9fa; box-shadow:0 12px 40px rgba(0,0,0,0.12); }
.card-module h3 { font-weight:700; color:#0d6efd; display:flex; align-items:center; gap:12px; }
.progress { height:20px; border-radius:10px; }
.percentage-text { font-weight:600; }
.btn-modern { border-radius:10px; font-weight:600; transition:0.3s; }
.btn-modern:hover { transform:scale(1.05); box-shadow:0 5px 15px rgba(0,0,0,0.15); }
.fadeRow { animation: fadeIn 0.3s ease-in-out; }
@keyframes fadeIn { from {opacity:0; transform:translateY(12px);} to {opacity:1; transform:translateY(0);} }
.removeRowBtn { background:#dc3545; color:white; border:none; border-radius:8px; padding:6px 12px; cursor:pointer; }
.removeRowBtn:hover { background:#c82333; }
</style>
</head>
<body>

<div class="container">
  <div class="card card-module">
    <h3><i class="bi bi-check2-square"></i> Student Attendance</h3>
    <p class="text-secondary">Mark Present (P) or Absent (A) and calculate attendance percentage.</p>

    <button class="btn btn-info btn-modern mb-3" id="addStudent"><i class="bi bi-plus-circle"></i> Add Student</button>

    <div class="table-responsive">
      <table class="table table-striped table-hover align-middle text-center" id="attendanceTable">
        <thead class="table-primary">
          <tr>
            <th>Student</th><th>Mon</th><th>Tue</th><th>Wed</th><th>Thu</th><th>Fri</th><th>Attendance %</th><th>Action</th>
          </tr>
        </thead>
        <tbody>
          <tr class="fadeRow">
            <td><input type="text" class="form-control student-name" placeholder="Student Name" maxlength="20"></td>
            <td><select class="form-select attendance-select"><option>P</option><option>A</option></select></td>
            <td><select class="form-select attendance-select"><option>P</option><option>A</option></select></td>
            <td><select class="form-select attendance-select"><option>P</option><option>A</option></select></td>
            <td><select class="form-select attendance-select"><option>P</option><option>A</option></select></td>
            <td><select class="form-select attendance-select"><option>P</option><option>A</option></select></td>
            <td>
              <div class="percentage-text">0%</div>
              <div class="progress mt-1"><div class="progress-bar bg-primary" style="width:0%"></div></div>
            </td>
            <td><button class="removeRowBtn">Remove</button></td>
          </tr>
        </tbody>
      </table>
    </div>

    <button class="btn btn-success btn-modern mt-3" id="calculateAttendance"><i class="bi bi-calculator"></i> Calculate Attendance</button>
    <div id="attendanceResult" class="mt-3"></div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
let studentCount = 1;

$(document).ready(function() {
  // Focus on the first student name input
  $('.student-name').first().focus();

  // Allow only letters and spaces in student name
  $(document).on('input', '.student-name', function() {
    this.value = this.value.replace(/[^a-zA-Z ]/g, '');
  });
});

// ADD STUDENT ROW
$('#addStudent').click(function(){
  studentCount++;
  $('#attendanceTable tbody').append(`
    <tr class="fadeRow">
      <td><input type="text" class="form-control student-name" placeholder="Student Name" maxlength="20"></td>
      <td><select class="form-select attendance-select"><option>P</option><option>A</option></select></td>
      <td><select class="form-select attendance-select"><option>P</option><option>A</option></select></td>
      <td><select class="form-select attendance-select"><option>P</option><option>A</option></select></td>
      <td><select class="form-select attendance-select"><option>P</option><option>A</option></select></td>
      <td><select class="form-select attendance-select"><option>P</option><option>A</option></select></td>
      <td>
        <div class="percentage-text">0%</div>
        <div class="progress mt-1"><div class="progress-bar bg-primary" style="width:0%"></div></div>
      </td>
      <td><button class="removeRowBtn">Remove</button></td>
    </tr>
  `);
});

// REMOVE STUDENT ROW
$(document).on('click', '.removeRowBtn', function(){
  $(this).closest('tr').remove();
});

// UPDATE ATTENDANCE PERCENTAGE
function updateAttendanceRow(row){
  let total = row.find('.attendance-select').length;
  let present = row.find('.attendance-select').filter(function(){ return $(this).val() === 'P'; }).length;
  let percent = ((present/total)*100).toFixed(0);
  row.find('.percentage-text').text(percent+'%');
  row.find('.progress-bar').css('width', percent+'%');
  row.find('.percentage-text').removeClass('text-success text-warning text-danger');
  if(percent>=75) row.find('.percentage-text').addClass('text-success');
  else if(percent>=50) row.find('.percentage-text').addClass('text-warning');
  else row.find('.percentage-text').addClass('text-danger');
}

// CALCULATE ATTENDANCE BUTTON
$('#calculateAttendance').click(function(){
  let valid = true;
  $('#attendanceTable tbody tr').each(function(){
    let studentName = $(this).find('.student-name').val().trim();
    if(studentName === ''){
      valid = false;
      return false; // exit loop
    }
  });

  if(!valid){
    $('#attendanceResult').html('<div class="alert alert-danger">⚠ Please enter all student names before calculating attendance.</div>');
    return;
  }

  $('#attendanceTable tbody tr').each(function(){ updateAttendanceRow($(this)); });
  $('#attendanceResult').html('<div class="alert alert-success">✓ Attendance calculated successfully!</div>');
});

// REAL-TIME UPDATE WHEN SELECT CHANGED
$(document).on('change','.attendance-select',function(){ updateAttendanceRow($(this).closest('tr')); });
</script>

</body>
</html>
