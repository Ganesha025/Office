<?php
// Handle AJAX request for generating IDs
if(isset($_POST['action']) && $_POST['action'] === 'generateIDs'){
    $students = $_POST['students'] ?? [];
    $year = date('Y'); // Current year
    $results = [];
    $counter = 1;

    foreach($students as $student){
        $name = trim($student['name'] ?? '');
        $results[] = [
            'name' => $name,
            'id' => 'STD'.$year.str_pad($counter,3,'0',STR_PAD_LEFT)
        ];
        $counter++;
    }

    echo json_encode($results);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Unique Student ID Generator</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
.card-module { max-width:800px; margin:40px auto; padding:30px; border-radius:20px; background:#f8f9fa; box-shadow:0 12px 40px rgba(0,0,0,0.12);}
.card-module h3 { color:#0d6efd; font-weight:700; margin-bottom:20px; }
.input-field { margin-bottom:10px; }
.input-error { border-color:#dc3545 !important; }
.error-msg { color:#dc3545; font-size:0.85rem; margin-bottom:5px; }
.btn-modern { border-radius:10px; font-weight:600; width:100%; margin-top:10px; }
.btn-modern:hover { transform:scale(1.05); box-shadow:0 5px 15px rgba(0,0,0,0.15); }
.result-box { padding:15px; font-size:16px; border-radius:12px; font-weight:bold; margin-top:15px; text-align:center; background:#28a745; color:white; }
.remove-btn { border:none; background:#dc3545; color:white; padding:4px 8px; border-radius:6px; cursor:pointer; }
.remove-btn:hover { background:#c82333; }
</style>
</head>
<body>
<div class="container">
  <div class="card card-module">
    <h3>Unique Student ID Generator</h3>
    <p class="text-secondary mb-3">Add student names and generate unique IDs for each.</p>

    <button class="btn btn-info btn-modern" id="addStudent">+ Add Student</button>

    <div class="table-responsive mt-3">
      <table class="table table-bordered text-center" id="studentTable">
        <thead class="table-primary">
          <tr>
            <th>Name</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>
              <input type="text" class="form-control input-field student-name" placeholder="Enter Name" maxlength="20">
              <div class="error-msg name-error"></div>
            </td>
            <td><button class="remove-btn">Remove</button></td>
          </tr>
        </tbody>
      </table>
    </div>

    <button class="btn btn-success btn-modern" id="generateIDs">Generate IDs</button>
    <div id="result" class="mt-3"></div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
$(document).ready(function(){
    $('.student-name:first').focus();

    // Allow only alphabets and spaces for name
    $(document).on('input', '.student-name', function(){
        this.value = this.value.replace(/[^a-zA-Z ]/g,'').slice(0,20);
    });

    // Add new student
    $('#addStudent').click(function(){
        $('#studentTable tbody').append(`
            <tr>
              <td>
                <input type="text" class="form-control input-field student-name" placeholder="Enter Name" maxlength="20">
                <div class="error-msg name-error"></div>
              </td>
              <td><button class="remove-btn">Remove</button></td>
            </tr>
        `);
        $('.student-name:last').focus();
    });

    // Remove student
    $(document).on('click', '.remove-btn', function(){
        $(this).closest('tr').remove();
    });

    // Generate IDs
    $('#generateIDs').click(function(){
        let students = [];
        let hasError = false;

        $('#studentTable tbody tr').each(function(){
            let name = $(this).find('.student-name').val().trim();
            $(this).find('.student-name').removeClass('input-error');
            $(this).find('.name-error').text('');

            if(name === ''){
                $(this).find('.student-name').addClass('input-error');
                $(this).find('.name-error').text('⚠ Name required');
                hasError = true;
            }
            students.push({name:name});
        });

        if(hasError) return;

        $.ajax({
            url:'<?php echo $_SERVER['PHP_SELF']; ?>',
            type:'POST',
            data:{action:'generateIDs', students:students},
            success:function(response){
                let data = JSON.parse(response);
                let resultHtml = '<div class="result-box">';
                data.forEach(function(student){
                    resultHtml += `${student.name}: ${student.id}<br>`;
                });
                resultHtml += '</div>';
                $('#result').html(resultHtml);

                // Clear all input fields but keep output
                $('#studentTable tbody').html('');
                $('#addStudent').click(); // Add initial row
            }
        });
    });
});
</script>
</body>
</html>
