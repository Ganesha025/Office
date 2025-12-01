<?php
// Handle AJAX request for fine calculation
if(isset($_POST['action']) && $_POST['action'] === 'calculateFine'){
    $studentName = $_POST['studentName'] ?? '';
    $bookName = $_POST['bookName'] ?? '';
    $borrowDate = $_POST['borrowDate'] ?? '';
    $dueDate = $_POST['dueDate'] ?? '';
    $returnDate = $_POST['returnDate'] ?? '';

    $errors = [];

    // Validate Student Name
    if(!$studentName || !preg_match("/^[A-Za-z ]{1,20}$/", $studentName)){
        $errors['studentName'] = 'Enter valid Student Name (alphabets & spaces only, max 20 chars).';
    }

    // Validate Book Name
    if(!$bookName || !preg_match("/^[A-Za-z ]{1,20}$/", $bookName)){
        $errors['bookName'] = 'Enter valid Book Name (alphabets & spaces only, max 20 chars).';
    }

    // Validate Dates
    if(!$borrowDate) $errors['borrowDate'] = 'Borrow Date is required.';
    if(!$dueDate) $errors['dueDate'] = 'Due Date is required.';
    if(!$returnDate) $errors['returnDate'] = 'Return Date is required.';

    if($borrowDate && $borrowDate > date('Y-m-d')){
        $errors['borrowDate'] = 'Borrow Date cannot be in the future.';
    }
    if($borrowDate && $dueDate && $dueDate < $borrowDate){
        $errors['dueDate'] = 'Due Date cannot be before Borrow Date.';
    }
    if($borrowDate && $returnDate && $returnDate < $borrowDate){
        $errors['returnDate'] = 'Return Date cannot be before Borrow Date.';
    }

    if(!empty($errors)){
        echo json_encode(['errors'=>$errors]);
        exit;
    }

    // Calculate fine
    list($dy, $dm, $dd) = explode('-', $dueDate);
    list($ry, $rm, $rd) = explode('-', $returnDate);

    $dueTotalDays = intval($dd) + intval($dm)*30 + intval($dy)*365;
    $returnTotalDays = intval($rd) + intval($rm)*30 + intval($ry)*365;

    $daysLate = max(0, $returnTotalDays - $dueTotalDays);
    $fine = $daysLate * 2;

    echo json_encode([
        'daysLate'=>$daysLate,
        'fine'=>$fine
    ]);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Library Book Due Fine Calculator</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<style>
.card-module {
    margin: 40px auto;
    padding: 30px;
    border-radius: 20px;
    background:#f8f9fa;
    box-shadow:0 12px 40px rgba(0,0,0,0.12);
    max-width:500px;
}
.card-module h3 {
    font-weight:700;
    color:#0d6efd;
    display:flex;
    align-items:center;
    gap:12px;
}
.input-field, .input-date {
    width: 100%;
    border-radius:10px;
    border:2px solid #ced4da;
    font-weight:500;
    transition:0.3s;
    height:45px;
    padding:5px 10px;
    margin-bottom:5px;
}
.input-field:focus, .input-date:focus {
    border-color:#0d6efd;
    box-shadow:0 0 8px rgba(13,110,253,0.3);
}
.input-error {
    border-color:#dc3545 !important;
    box-shadow:0 0 8px rgba(220,53,69,0.4);
}
.btn-modern {
    border-radius:10px;
    font-weight:600;
    transition:0.3s;
    width:100%;
}
.btn-modern:hover {
    transform:scale(1.05);
    box-shadow:0 5px 15px rgba(0,0,0,0.15);
}
.result-box {
    padding:15px;
    font-size:18px;
    border-radius:12px;
    font-weight:bold;
    margin-top:20px;
    text-align:center;
    width:100%;
}
.result-ok { background:#28a745; color:white;}
.result-error { background:#dc3545; color:white;}
.error-msg {color:red; font-size:14px; margin-bottom:8px;}
</style>
</head>
<body>
<div class="container">
  <div class="card card-module">
    <h3><i class="bi bi-journal-bookmark"></i> Library Book Due Fine Calculator</h3>
    <p class="text-secondary mb-3">Fine is ₹2/day after due date. Fill all fields correctly to calculate fine.</p>

    <label>Student Name</label>
    <input type="text" class="form-control input-field" id="studentName" maxlength="20" placeholder="Enter Student Name">
    <div id="error_studentName" class="error-msg"></div>

    <label>Book Name</label>
    <input type="text" class="form-control input-field" id="bookName" maxlength="20" placeholder="Enter Book Name">
    <div id="error_bookName" class="error-msg"></div>

    <label>Borrow Date</label>
    <input type="date" class="form-control input-date" id="borrowDate" max="<?php echo date('Y-m-d'); ?>">
    <div id="error_borrowDate" class="error-msg"></div>

    <label>Due Date</label>
    <input type="date" class="form-control input-date" id="dueDate">
    <div id="error_dueDate" class="error-msg"></div>

    <label>Return Date</label>
    <input type="date" class="form-control input-date" id="returnDate">
    <div id="error_returnDate" class="error-msg"></div>

    <button class="btn btn-success btn-modern mt-2" id="calculateFine"><i class="bi bi-calculator"></i> Calculate Fine</button>

    <div id="result" class="mt-3"></div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
// Focus on first field
$(document).ready(function(){
    $('#studentName').focus();

    // Set min for Due Date and Return Date
    $('#borrowDate').change(function(){
        let borrowVal = $(this).val();
        $('#dueDate').attr('min', borrowVal);
        $('#returnDate').attr('min', borrowVal);
    });
});

// Restrict Student & Book Name to alphabets & spaces only
$('#studentName, #bookName').on('input', function(){
    let val = $(this).val();
    val = val.replace(/[^A-Za-z ]/g,'');
    $(this).val(val);
});

// Calculate Fine
$('#calculateFine').click(function(){
    // Clear previous errors and result
    $('.error-msg').text('');
    $('#result').html('');

    let studentName = $('#studentName').val().trim();
    let bookName = $('#bookName').val().trim();
    let borrowDate = $('#borrowDate').val();
    let dueDate = $('#dueDate').val();
    let returnDate = $('#returnDate').val();

    $.ajax({
        url:'<?php echo $_SERVER['PHP_SELF']; ?>',
        type:'POST',
        data:{
            action:'calculateFine',
            studentName: studentName,
            bookName: bookName,
            borrowDate: borrowDate,
            dueDate: dueDate,
            returnDate: returnDate
        },
        success:function(response){
            let data = JSON.parse(response);
            if(data.errors){
                for(let key in data.errors){
                    $('#error_'+key).text(data.errors[key]);
                    $('#'+key).addClass('input-error');
                }
                return;
            } else {
                $('#result').html('<div class="result-box '+(data.fine>0 ? 'result-error':'result-ok')+'">Days Late: '+data.daysLate+'<br>Total Fine: ₹'+data.fine+'</div>');
            }
        }
    });
});
</script>
</body>
</html>
