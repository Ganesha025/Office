<?php
// Handle AJAX request for fine calculation
if(isset($_POST['action']) && $_POST['action'] === 'calculateFine'){
    $borrowDate = $_POST['borrowDate'] ?? '';
    $dueDate = $_POST['dueDate'] ?? '';
    $returnDate = $_POST['returnDate'] ?? '';

    if(!$borrowDate || !$dueDate || !$returnDate){
        echo json_encode(['error'=>'⚠ Please select Borrow, Due, and Return dates.']);
        exit;
    }

    // Validate date rules
    if($borrowDate > date('Y-m-d')){
        echo json_encode(['error'=>'⚠ Borrow Date cannot be in the future.']);
        exit;
    }
    if($dueDate < $borrowDate){
        echo json_encode(['error'=>'⚠ Due Date cannot be before Borrow Date.']);
        exit;
    }
    if($returnDate < $borrowDate){
        echo json_encode(['error'=>'⚠ Return Date cannot be before Borrow Date.']);
        exit;
    }

    // Convert dates to "days" for simple arithmetic
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
.input-date {
    width: 100%;
    border-radius:10px;
    border:2px solid #ced4da;
    font-weight:500;
    transition:0.3s;
    height:45px;
    padding:5px 10px;
    margin-bottom:15px;
}
.input-date:focus {
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
</style>
</head>
<body>
<div class="container">
  <div class="card card-module">
    <h3><i class="bi bi-journal-bookmark"></i> Library Book Due Fine Calculator</h3>
    <p class="text-secondary mb-3">Fine is ₹2/day after due date. Select all dates following rules and click Calculate Fine.</p>

    <label>Borrow Date</label>
    <input type="date" class="form-control input-date" id="borrowDate" max="<?php echo date('Y-m-d'); ?>">

    <label>Due Date</label>
    <input type="date" class="form-control input-date" id="dueDate">

    <label>Return Date</label>
    <input type="date" class="form-control input-date" id="returnDate">

    <button class="btn btn-success btn-modern mt-2" id="calculateFine"><i class="bi bi-calculator"></i> Calculate Fine</button>

    <div id="result" class="mt-3"></div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
// Focus on Borrow Date
$(document).ready(function(){
    $('#borrowDate').focus();

    // Dynamically set min date for Due Date and Return Date based on Borrow Date
    $('#borrowDate').change(function(){
        let borrowVal = $(this).val();
        $('#dueDate').attr('min', borrowVal);
        $('#returnDate').attr('min', borrowVal);
    });
});

// Calculate fine
$('#calculateFine').click(function(){
    let borrowDate = $('#borrowDate').val();
    let dueDate = $('#dueDate').val();
    let returnDate = $('#returnDate').val();

    // Validation
    let valid = true;
    $('.input-date').each(function(){
        if(!$(this).val()){
            $(this).addClass('input-error');
            valid = false;
        } else { $(this).removeClass('input-error'); }
    });

    if(!valid){
        $('#result').html('<div class="result-box result-error">⚠ Please select all dates!</div>');
        return;
    }

    // AJAX call
    $.ajax({
        url:'<?php echo $_SERVER['PHP_SELF']; ?>',
        type:'POST',
        data:{action:'calculateFine', borrowDate:borrowDate, dueDate:dueDate, returnDate:returnDate},
        success:function(response){
            let data = JSON.parse(response);
            if(data.error){
                $('#result').html('<div class="result-box result-error">'+data.error+'</div>');
                return;
            }
            let boxClass = data.fine>0 ? 'result-error' : 'result-ok';
            let msg = `Days Late: ${data.daysLate}<br>Total Fine: ₹${data.fine}`;
            $('#result').html('<div class="result-box '+boxClass+'">'+msg+'</div>');

            // Clear fields
            $('.input-date').val('');
            $('#borrowDate').focus();
        }
    });
});
</script>
</body>
</html>
