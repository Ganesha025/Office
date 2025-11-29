<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ERP - Grade Module</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<style>
.card-module { margin: 40px auto; padding: 30px; border-radius: 20px; background:#f8f9fa; box-shadow:0 12px 40px rgba(0,0,0,0.12); max-width:700px; }
.card-module h3 { font-weight:700; color:#0d6efd; display:flex; align-items:center; gap:12px; }
.input-marks { width:120px; text-align:center; border-radius:8px; font-weight:500; -moz-appearance:textfield; }
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
</style>
</head>
<body>

<div class="container">
  <div class="card card-module">
    <h3><i class="bi bi-award"></i> Grade Assignment</h3>
    <p class="text-secondary mb-3">Add subjects and enter marks (1–100). System calculates average and grade automatically.</p>

    <button class="btn btn-info btn-modern mb-3" id="addSubject"><i class="bi bi-plus-circle"></i> Add Subject</button>

    <div class="table-responsive">
      <table class="table table-striped table-hover align-middle text-center" id="gradeTable">
        <thead class="table-primary">
          <tr>
            <th>Subject</th>
            <th>Marks (1–100)</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <tr class="fadeRow">
            <td><strong>Subject 1</strong></td>
            <td><input type="text" class="form-control input-marks marks" maxlength="3"></td>
            <td></td>
          </tr>
        </tbody>
      </table>
    </div>

    <button class="btn btn-success btn-modern mt-3" id="calculateGrade"><i class="bi bi-calculator"></i> Calculate Grade</button>

    <div id="gradeResult" class="mt-4"></div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
let subjectCount = 1;

// Focus first field on load
$(document).ready(function(){
    $('.marks:first').focus();
});

// Add new subject row
$('#addSubject').click(function(){
  subjectCount++;
  $('#gradeTable tbody').append(`
    <tr class="fadeRow">
      <td><strong>Subject ${subjectCount}</strong></td>
      <td><input type="text" class="form-control input-marks marks" maxlength="3"></td>
      <td><button class="removeRowBtn">Remove</button></td>
    </tr>
  `);
});

// Remove row
$(document).on('click','.removeRowBtn',function(){
  $(this).closest('tr').remove();
});

// Allow only numbers 1-100
$(document).on('input','.marks',function(){
  this.value = this.value.replace(/[^0-9]/g,''); // remove letters
  if(parseInt(this.value) > 100) this.value = '100';
  if(parseInt(this.value) < 1 && this.value !== '') this.value = '1';
});

// Calculate grade
function calculateGrade(avg){
  if(avg >= 90) return "A";
  else if(avg >= 80) return "B";
  else if(avg >= 70) return "C";
  else if(avg >= 60) return "D";
  else return "F";
}

$('#calculateGrade').click(function(){
  let marks = [];
  let valid = true;

  $('.marks').each(function(){
    let val = parseInt($(this).val());
    if(isNaN(val) || val < 1 || val > 100){ valid=false; }
    marks.push(val);
  });

  if(!valid){
    $('#gradeResult').html(`<div class='alert alert-danger'>⚠ Enter valid marks (1–100) for all subjects.</div>`);
    return;
  }

  let sum = marks.reduce((a,b)=>a+b,0);
  let avg = (sum/marks.length).toFixed(2);
  let grade = calculateGrade(avg);
  let gradeClass = "grade-"+grade;

  $('#gradeResult').html(`
    <div class="p-4 bg-light rounded shadow-sm">
      <h5>Total Subjects: <strong>${marks.length}</strong></h5>
      <h5>Average Marks: <strong>${avg}</strong></h5>
      <h5>Assigned Grade: <span class="grade-box ${gradeClass}">${grade}</span></h5>
    </div>
  `);
});
</script>

</body>
</html>
