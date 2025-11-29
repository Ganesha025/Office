<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>College Email Generator</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<style>
.card-module { margin: 40px auto; padding: 30px; border-radius: 20px; background:#f8f9fa; box-shadow:0 12px 40px rgba(0,0,0,0.12); max-width: 800px; }
.card-module h3 { font-weight:700; color:#0d6efd; display:flex; align-items:center; gap:12px; }
.input-field { text-align:center; border-radius:8px; font-weight:500; }
.btn-modern { border-radius:10px; font-weight:600; transition:0.3s; }
.btn-modern:hover { transform:scale(1.05); box-shadow:0 5px 15px rgba(0,0,0,0.15); }
.removeRowBtn { background:#dc3545; color:white; border:none; border-radius:8px; padding:6px 12px; cursor:pointer; }
.removeRowBtn:hover { background:#c82333; }
.fadeRow { animation: fadeIn 0.3s ease-in-out; }
@keyframes fadeIn { from {opacity:0; transform:translateY(12px);} to {opacity:1; transform:translateY(0);} }
.email-box { background:#0d6efd; color:white; padding:12px 20px; border-radius:10px; font-weight:bold; display:inline-block; margin-bottom:8px; }
</style>
</head>
<body>

<div class="container">
  <div class="card card-module">
    <h3><i class="bi bi-envelope"></i> College Email Generator</h3>
    <p class="text-secondary">Enter student name and roll number(s) to generate email IDs.</p>

    <button class="btn btn-info btn-modern mb-3" id="addRow"><i class="bi bi-plus-circle"></i> Add Student</button>

    <div class="table-responsive">
      <table class="table table-striped table-hover align-middle text-center" id="emailTable">
        <thead class="table-primary">
          <tr>
            <th>Student Name</th>
            <th>Roll Number</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <tr class="fadeRow">
            <td><input type="text" class="form-control input-field student-name" placeholder="e.g. John Doe" maxlength="20"></td>
            <td><input type="text" class="form-control input-field roll-number" placeholder="21EC603" maxlength="7"></td>
            <td><button class="removeRowBtn">Remove</button></td>
          </tr>
        </tbody>
      </table>
    </div>

    <button class="btn btn-success btn-modern mt-3" id="generateEmail"><i class="bi bi-envelope-check"></i> Generate Email</button>
    <div id="emailResult" class="mt-3"></div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
$(document).ready(function(){
    // Focus first student name input
    $('.student-name').first().focus();

    // Allow only letters and spaces for student name
    $(document).on('input', '.student-name', function() {
        this.value = this.value.replace(/[^a-zA-Z ]/g,'').slice(0,20);
    });

    // Allow only roll number format: 2 digits + 2 letters + 3 digits
    $(document).on('input', '.roll-number', function() {
        this.value = this.value.toUpperCase().replace(/[^0-9A-Z]/g,'').slice(0,7);
    });

    // Add new row
    $('#addRow').click(function(){
        $('#emailTable tbody').append(`
            <tr class="fadeRow">
                <td><input type="text" class="form-control input-field student-name" placeholder="e.g. John Doe" maxlength="20"></td>
                <td><input type="text" class="form-control input-field roll-number" placeholder="21EC603" maxlength="7"></td>
                <td><button class="removeRowBtn">Remove</button></td>
            </tr>
        `);
        $('.student-name').last().focus();
    });

    // Remove row
    $(document).on('click', '.removeRowBtn', function(){
        $(this).closest('tr').remove();
    });

    // Email generation
    function createEmail(name, roll){
        name = name.trim().toLowerCase().replace(/\s+/g,'');
        roll = roll.trim().toUpperCase();
        if(name === "" || roll === "") return "";
        return name + roll + "@college.com";
    }

    $('#generateEmail').click(function(){
        let valid = true;
        let emails = [];

        $('#emailTable tbody tr').each(function(){
            let name = $(this).find('.student-name').val().trim();
            let roll = $(this).find('.roll-number').val().trim();

            // Validate roll number format: 2 digits + 2 letters + 3 digits
            let rollPattern = /^[0-9]{2}[A-Z]{2}[0-9]{3}$/;
            if(name === "" || !rollPattern.test(roll)){
                valid = false;
            }
            emails.push(createEmail(name, roll));
        });

        if(!valid){
            $('#emailResult').html(`<div class="alert alert-danger">⚠ Please fill valid Student Name and Roll Number (Format: 21EC603) for all students.</div>`);
            return;
        }

        let html = "";
        emails.forEach((email, index) => {
            html += `<div class="email-box">Student ${index+1}: ${email}</div><br>`;
        });
        $('#emailResult').html(html);
    });

    // Press Enter to generate email
    $(document).on('keypress', '.student-name, .roll-number', function(e){
        if(e.which === 13){
            $('#generateEmail').click();
        }
    });
});
</script>

</body>
</html>
