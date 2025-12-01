<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Subject Allotment Checker</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<style>
.card-module { margin: 40px auto; padding: 30px; border-radius: 20px; background:#f8f9fa; box-shadow:0 12px 40px rgba(0,0,0,0.12); max-width: 900px; }
.card-module h3 { font-weight:700; color:#0d6efd; display:flex; align-items:center; gap:12px; }
.input-field { text-align:center; border-radius:8px; font-weight:500; }
.btn-modern { border-radius:10px; font-weight:600; transition:0.3s; }
.btn-modern:hover { transform:scale(1.05); box-shadow:0 5px 15px rgba(0,0,0,0.15); }
.removeRowBtn { background:#dc3545; color:white; border:none; border-radius:8px; padding:6px 12px; cursor:pointer; }
.removeRowBtn:hover { background:#c82333; }
.fadeRow { animation: fadeIn 0.3s ease-in-out; }
@keyframes fadeIn { from {opacity:0; transform:translateY(12px);} to {opacity:1; transform:translateY(0);} }
.error-text { color: #dc3545; font-weight: 600; margin-top:5px; }
.success-text { color: #28a745; font-weight: 600; margin-top:5px; }
.result-cell { font-weight:600; }
</style>
</head>
<body>

<div class="container">
  <div class="card card-module">
    <h3><i class="bi bi-book"></i> Subject Allotment Checker</h3>
    <p class="text-secondary">Select 3 elective subjects per student. Duplicates or invalid subjects will show errors.</p>

    <div class="table-responsive">
      <table class="table table-striped table-hover align-middle text-center" id="subjectTable">
        <thead class="table-primary">
          <tr>
            <th>Student Name</th>
            <th>Subject 1</th>
            <th>Subject 2</th>
            <th>Subject 3</th>
            <th>Result</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <tr class="fadeRow">
            <td><input type="text" class="form-control input-field student-name" placeholder="John Doe" maxlength="20"></td>
            <td><select class="form-select subject-select"><option value="">Select</option></select></td>
            <td><select class="form-select subject-select"><option value="">Select</option></select></td>
            <td><select class="form-select subject-select"><option value="">Select</option></select></td>
            <td class="result-cell"></td>
            <td><button type="button" class="removeRowBtn">Remove</button></td>
          </tr>
        </tbody>
      </table>
    </div>

    <button type="button" class="btn btn-info btn-modern mb-3" id="addRow"><i class="bi bi-plus-circle"></i> Add Student</button>
    <br>
    <button type="button" class="btn btn-success btn-modern" id="checkAllotment"><i class="bi bi-check2-square"></i> Check Allotment</button>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
$(document).ready(function(){
  const availableSubjects = ['Math', 'Physics', 'Chemistry', 'Biology', 'Computer', 'Economics', 'History', 'English'];

  // Focus on first student name input
  $('.student-name:first').focus();

  // Fill all selects with available subjects
  function populateSelects() {
    $('.subject-select').each(function(){
      if($(this).children('option').length === 1){
        availableSubjects.forEach(sub => $(this).append(`<option value="${sub}">${sub}</option>`));
      }
    });
  }
  populateSelects();

  // Restrict student name input to letters and spaces, max 20 characters
  $(document).on('input', '.student-name', function(){
    let val = $(this).val();
    val = val.replace(/[^a-zA-Z\s]/g, ''); // remove numbers & special chars
    $(this).val(val.substring(0,20)); // enforce 20 char limit
  });

  // Add new row
  $('#addRow').click(function(){
    let row = `<tr class="fadeRow">
      <td><input type="text" class="form-control input-field student-name" placeholder="John Doe" maxlength="20"></td>
      <td><select class="form-select subject-select"><option value="">Select</option></select></td>
      <td><select class="form-select subject-select"><option value="">Select</option></select></td>
      <td><select class="form-select subject-select"><option value="">Select</option></select></td>
      <td class="result-cell"></td>
      <td><button type="button" class="removeRowBtn">Remove</button></td>
    </tr>`;
    $('#subjectTable tbody').append(row);
    populateSelects();
  });

  // Remove row
  $(document).on('click', '.removeRowBtn', function(){
    $(this).closest('tr').remove();
  });

  // Check allotment on button click
  $('#checkAllotment').click(function(){
    $('#subjectTable tbody tr').each(function(){
      const name = $(this).find('.student-name').val().trim();
      const subjects = $(this).find('.subject-select').map(function(){ return $(this).val(); }).get();
      let resultCell = $(this).find('.result-cell');
      let errors = [];

      // Check name
      if(name === '') errors.push("Name cannot be empty.");

      // Check duplicates
      let uniqueSubjects = [...new Set(subjects)];
      if(uniqueSubjects.length < 3) errors.push("Duplicate subjects selected.");

      // Check valid subjects
      subjects.forEach(sub => {
        if(!availableSubjects.includes(sub) || sub === '') errors.push("Invalid subject selected.");
      });

      // Show result
      if(errors.length === 0){
        resultCell.html("<span class='success-text'>Subjects valid ✓</span>");
      } else {
        resultCell.html("<span class='error-text'>" + errors.join(' ') + "</span>");
      }
    });
  });
});
</script>
</body>
</html>
