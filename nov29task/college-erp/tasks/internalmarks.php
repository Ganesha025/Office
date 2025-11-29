<?php
// Handle AJAX request
if(isset($_POST['action']) && $_POST['action']==='calculateMarks'){
    $students = $_POST['students'] ?? [];
    $errors = [];
    $results = [];

    foreach($students as $index => $student){
        $name = trim($student['name'] ?? '');
        $attendance = $student['attendance'] ?? '';
        $assignment = $student['assignment'] ?? '';

        // Validate fields
        if($name === '') $errors[$index]['name'] = '⚠ Name required';
        if($attendance === '') $errors[$index]['attendance'] = '⚠ Select attendance';
        if($assignment === '') $errors[$index]['assignment'] = '⚠ Select assignment';

        // Calculate internal marks if valid
        if(empty($errors[$index])){
            $marks = 0;
            if($attendance === 'full') $marks += 3;
            if($assignment === 'all') $marks += 2;
            $results[$index] = $marks;
        }
    }

    echo json_encode(['errors'=>$errors, 'results'=>$results]);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Auto-Fill Internal Marks</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
.card-module {
    max-width:800px;
    margin:40px auto;
    padding:30px;
    border-radius:20px;
    background:#f8f9fa;
    box-shadow:0 12px 40px rgba(0,0,0,0.12);
}
.card-module h3 {
    font-weight:700;
    color:#0d6efd;
    display:flex;
    align-items:center;
    gap:12px;
}
.input-field { margin-bottom:10px; }
.input-error { border-color:#dc3545 !important; }
.error-msg { color:#dc3545; font-size:0.85rem; margin-bottom:5px; }
.btn-modern { border-radius:10px; font-weight:600; width:100%; margin-top:10px; }
.btn-modern:hover { transform:scale(1.05); box-shadow:0 5px 15px rgba(0,0,0,0.15); }
.result-box { padding:15px; font-size:16px; border-radius:12px; font-weight:bold; margin-top:15px; text-align:center; }
.result-success { background:#28a745; color:white; }
.remove-btn { border:none; background:#dc3545; color:white; padding:4px 8px; border-radius:6px; cursor:pointer; }
.remove-btn:hover { background:#c82333; }
</style>
</head>
<body>
<div class="container">
  <div class="card card-module">
    <h3>Auto-Fill Internal Marks</h3>
    <p class="text-secondary mb-3">Add students, select attendance and assignment status to auto-calculate marks (Max 5).</p>

    <button class="btn btn-info btn-modern" id="addStudent">+ Add Student</button>

    <div class="table-responsive mt-3">
      <table class="table table-bordered text-center" id="studentTable">
        <thead class="table-primary">
          <tr>
            <th>Name</th>
            <th>Attendance</th>
            <th>Assignment</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>
              <input type="text" class="form-control input-field student-name" placeholder="Enter Name" maxlength="20">
              <div class="error-msg name-error"></div>
            </td>
            <td>
              <select class="form-select input-field student-attendance">
                <option value="">-- Select --</option>
                <option value="full">Full Attendance</option>
                <option value="partial">Partial Attendance</option>
              </select>
              <div class="error-msg attendance-error"></div>
            </td>
            <td>
              <select class="form-select input-field student-assignment">
                <option value="">-- Select --</option>
                <option value="all">All Submitted</option>
                <option value="partial">Partial/None</option>
              </select>
              <div class="error-msg assignment-error"></div>
            </td>
            <td>
              <button class="remove-btn">Remove</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <button class="btn btn-success btn-modern" id="calculateMarks">Calculate Marks</button>
    <div id="result" class="mt-3"></div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
$(document).ready(function(){
    $('.student-name:first').focus();

    // Restrict name input to alphabets and spaces only
    $(document).on('input', '.student-name', function(){
        this.value = this.value.replace(/[^a-zA-Z ]/g,'').slice(0,20);
    });

    // Add new student row
    $('#addStudent').click(function(){
        $('#studentTable tbody').append(`
            <tr>
              <td>
                <input type="text" class="form-control input-field student-name" placeholder="Enter Name" maxlength="20">
                <div class="error-msg name-error"></div>
              </td>
              <td>
                <select class="form-select input-field student-attendance">
                  <option value="">-- Select --</option>
                  <option value="full">Full Attendance</option>
                  <option value="partial">Partial Attendance</option>
                </select>
                <div class="error-msg attendance-error"></div>
              </td>
              <td>
                <select class="form-select input-field student-assignment">
                  <option value="">-- Select --</option>
                  <option value="all">All Submitted</option>
                  <option value="partial">Partial/None</option>
                </select>
                <div class="error-msg assignment-error"></div>
              </td>
              <td>
                <button class="remove-btn">Remove</button>
              </td>
            </tr>
        `);
        $('.student-name:last').focus();
    });

    // Remove student
    $(document).on('click','.remove-btn', function(){
        $(this).closest('tr').remove();
    });

    // Calculate Marks
    $('#calculateMarks').click(function(){
        let students = [];

        $('#studentTable tbody tr').each(function(){
            students.push({
                name: $(this).find('.student-name').val(),
                attendance: $(this).find('.student-attendance').val(),
                assignment: $(this).find('.student-assignment').val()
            });
        });

        $.ajax({
            url:'<?php echo $_SERVER['PHP_SELF']; ?>',
            type:'POST',
            data:{action:'calculateMarks', students:students},
            success:function(response){
                let data = JSON.parse(response);
                $('#result').html('');
                let hasError = false;

                $('#studentTable tbody tr').each(function(index){
                    $(this).find('.input-field').removeClass('input-error');
                    $(this).find('.error-msg').text('');

                    if(data.errors[index]){
                        hasError = true;
                        if(data.errors[index].name){
                            $(this).find('.student-name').addClass('input-error');
                            $(this).find('.name-error').text(data.errors[index].name);
                        }
                        if(data.errors[index].attendance){
                            $(this).find('.student-attendance').addClass('input-error');
                            $(this).find('.attendance-error').text(data.errors[index].attendance);
                        }
                        if(data.errors[index].assignment){
                            $(this).find('.student-assignment').addClass('input-error');
                            $(this).find('.assignment-error').text(data.errors[index].assignment);
                        }
                    }
                });

                if(!hasError){
                    let resultHtml = '<div class="result-box result-success">';
                    data.results.forEach((marks,index)=>{
                        let name = $('#studentTable tbody tr').eq(index).find('.student-name').val();
                        resultHtml += `${name}: ${marks} / 5<br>`;
                    });
                    resultHtml += '</div>';
                    $('#result').html(resultHtml);

                    // Clear all fields
                    $('#studentTable tbody').html('');
                    $('#addStudent').click(); // Add initial row

                    // Hide result after 3 seconds
                    setTimeout(function(){
                        $('#result').fadeOut('slow', function(){ $(this).html('').show(); });
                    }, 3000);
                }
            }
        });
    });
});
</script>
</body>
</html>
