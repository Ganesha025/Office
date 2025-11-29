<?php
// faculty_hours.php

// Handle AJAX request for weekly hours calculation
if(isset($_POST['action']) && $_POST['action'] === 'checkHours'){
    $hours = $_POST['hours'] ?? [];
    $weeklyLimit = 20; // Weekly limit
    $valid = true;

    // Check if any input is empty or invalid
    foreach($hours as $h){
        if($h === "" || !is_numeric($h) || $h < 0){
            $valid = false;
            break;
        }
    }

    if(!$valid){
        echo json_encode(['error'=>'Please fill all fields with valid numbers (0 or more).']);
        exit;
    }

    $total = array_sum($hours);
    $exceed = $total > $weeklyLimit ? true : false;

    echo json_encode([
        'totalHours'=>$total,
        'exceed'=>$exceed,
        'weeklyLimit'=>$weeklyLimit
    ]);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Faculty Teaching Hours Checker</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<style>
.card-module {
    margin: 40px auto;
    padding: 30px;
    border-radius: 20px;
    background:#f8f9fa;
    box-shadow:0 12px 40px rgba(0,0,0,0.12);
    max-width:600px;
}
.card-module h3 {
    font-weight:700;
    color:#0d6efd;
    display:flex;
    align-items:center;
    gap:12px;
}
.input-hours {
    width:100%;
    text-align:center;
    border-radius:10px;
    border:2px solid #ced4da;
    font-weight:500;
    transition:0.3s;
}
.input-hours:focus {
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
}
.btn-modern:hover {
    transform:scale(1.05);
    box-shadow:0 5px 15px rgba(0,0,0,0.15);
}
.result-box {
    padding:12px;
    font-size:18px;
    border-radius:12px;
    font-weight:bold;
    margin-top:12px;
    display:inline-block;
    min-width:200px;
    text-align:center;
}
.result-ok { background:#28a745; color:white;}
.result-exceed { background:#dc3545; color:white;}
.result-error { background:#ffc107; color:black;}
</style>
</head>
<body>
<div class="container">
  <div class="card card-module">
    <h3><i class="bi bi-clock"></i> Faculty Teaching Hours Checker</h3>
    <p class="text-secondary mb-3">Enter teaching hours for Monday to Friday. Weekly limit: 20 hours.</p>

    <div class="row mb-3">
        <?php 
        $days = ['Monday','Tuesday','Wednesday','Thursday','Friday'];
        foreach($days as $day){
            echo '<div class="col-6 col-md-4 mb-3">
                    <label class="form-label">'.$day.':</label>
                    <input type="text" class="form-control input-hours dayHour" maxlength="2" placeholder="0">
                  </div>';
        }
        ?>
    </div>

    <button class="btn btn-success btn-modern mt-2" id="checkHours"><i class="bi bi-check2-circle"></i> Check Weekly Hours</button>

    <button class="btn btn-secondary btn-modern mt-2 ms-2" id="resetPage"><i class="bi bi-arrow-clockwise"></i> Reset</button>

    <div id="result" class="mt-4"></div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
// Reset page inputs and result
function resetPage(){
    $('.dayHour').val('');
    $('.dayHour').removeClass('input-error');
    $('#result').html('');
    $('.dayHour:first').focus();
}

// Reset on page load
$(document).ready(function(){
    resetPage();
});

// Allow only numbers
$(document).on('input','.dayHour',function(){
    let val = $(this).val();
    val = val.replace(/[^0-9]/g,'');
    $(this).val(val);
});

// Check weekly hours
$('#checkHours').click(function(){
    let hours = [];
    let valid = true;

    $('.dayHour').each(function(){
        let val = $(this).val();
        if(val === "" || isNaN(val) || val < 0){
            $(this).addClass('input-error');
            valid = false;
        } else{
            $(this).removeClass('input-error');
            hours.push(parseInt(val));
        }
    });

    if(!valid){
        $('#result').html('<div class="result-box result-error">⚠ Please fill all fields with valid numbers!</div>');
        return;
    }

    $.ajax({
        url:'<?php echo $_SERVER['PHP_SELF']; ?>',
        type:'POST',
        data:{
            action:'checkHours',
            hours:hours
        },
        success:function(response){
            let data = JSON.parse(response);
            if(data.error){
                $('#result').html('<div class="result-box result-error">'+data.error+'</div>');
                return;
            }
            let boxClass = data.exceed ? 'result-exceed' : 'result-ok';
            let msg = `Total Weekly Hours: ${data.totalHours}<br>Weekly Limit: ${data.weeklyLimit}<br>`;
            msg += data.exceed ? '<strong>Limit Exceeded!</strong>' : '<strong>Within Limit</strong>';
            $('#result').html('<div class="result-box '+boxClass+'">'+msg+'</div>');
        }
    });
});

// Reset button
$('#resetPage').click(function(){
    resetPage();
});
</script>
</body>
</html>
