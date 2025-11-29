<?php
// PHP for server-side SMS formatting
if(isset($_POST['action']) && $_POST['action'] === 'formatSMS'){
    $template = $_POST['template'] ?? '';
    $name = $_POST['name'] ?? '';
    $amount = $_POST['amount'] ?? '';

    $template = str_replace("{name}", $name, $template);
    $template = str_replace("{amount}", $amount, $template);

    echo json_encode(['formattedSMS'=>$template]);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SMS Template Formatter</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<style>
.card-module {
    margin: 40px auto;
    padding: 30px;
    border-radius: 20px;
    background: #f8f9fa;
    box-shadow: 0 12px 40px rgba(0,0,0,0.12);
    max-width: 700px;
}
.card-module h3 {
    font-weight: 700;
    color: #0d6efd;
    display: flex;
    align-items: center;
    gap: 12px;
}
.input-field {
    width: 100%;
    border-radius: 8px;
    font-weight: 500;
}
.btn-modern {
    border-radius: 10px;
    font-weight: 600;
    transition: 0.3s;
}
.btn-modern:hover {
    transform: scale(1.05);
    box-shadow: 0 5px 15px rgba(0,0,0,0.15);
}
.sms-box {
    padding: 12px;
    font-size: 18px;
    border-radius: 12px;
    font-weight: bold;
    background: #0d6efd;
    color: white;
    margin-top: 12px;
    display: block;
}
</style>
</head>
<body>
<div class="container">
  <div class="card card-module">
    <h3><i class="bi bi-chat-text"></i> SMS Template Formatter</h3>

    <div class="mb-3 row align-items-center">
      <label class="col-sm-3 col-form-label">Select SMS Template:</label>
      <div class="col-sm-9">
        <select id="smsTemplate" class="form-control input-field">
          <option value="Hi {name}, your fee due is {amount}.">Hi {name}, your fee due is {amount}.</option>
          <option value="Hello {name}, your payment of {amount} is pending.">Hello {name}, your payment of {amount} is pending.</option>
          <option value="Dear {name}, please pay {amount} before the due date.">Dear {name}, please pay {amount} before the due date.</option>
        </select>
      </div>
    </div>

    <h5>Placeholder Values</h5>
    <div class="mb-3 row align-items-center">
        <label class="col-sm-3 col-form-label">Name:</label>
        <div class="col-sm-9">
            <input type="text" id="name" class="form-control input-field" maxlength="20" placeholder="Enter Name">
        </div>
    </div>
    <div class="mb-3 row align-items-center">
        <label class="col-sm-3 col-form-label">Amount:</label>
        <div class="col-sm-9">
            <input type="text" id="amount" class="form-control input-field" maxlength="10" placeholder="Enter Amount">
        </div>
    </div>

    <button class="btn btn-success btn-modern mt-2" id="formatSMS"><i class="bi bi-pencil-square"></i> Format SMS</button>

    <div id="smsResult" class="mt-4"></div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
// Focus on template dropdown on page load
$(document).ready(function(){
    $('#smsTemplate').focus();
});

// Allow only alphabets and spaces for Name
$('#name').on('input', function(){
    let val = $(this).val();
    val = val.replace(/[^A-Za-z ]/g,'');
    $(this).val(val);
});

// Allow only numbers for Amount
$('#amount').on('input', function(){
    let val = $(this).val();
    val = val.replace(/[^0-9]/g,'');
    $(this).val(val);
});

// Format SMS via AJAX + PHP
$('#formatSMS').click(function(){
    let template = $('#smsTemplate').val();
    let name = $('#name').val();
    let amount = $('#amount').val();

    $.ajax({
        url:'<?php echo $_SERVER['PHP_SELF']; ?>',
        type:'POST',
        data:{
            action:'formatSMS',
            template:template,
            name:name,
            amount:amount
        },
        success:function(response){
            let data = JSON.parse(response);
            $('#smsResult').html(`<div class="sms-box">${data.formattedSMS}</div>`);
        }
    });
});
</script>
</body>
</html>
