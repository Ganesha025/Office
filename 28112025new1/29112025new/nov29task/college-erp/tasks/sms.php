<?php 
// PHP for server-side SMS formatting
if(isset($_POST['action']) && $_POST['action'] === 'formatSMS'){
    $template = $_POST['template'] ?? '';
    $placeholders = $_POST['placeholders'] ?? [];

    $errors = [];
    if(trim($template) === '') $errors['template'] = 'Template is required.';

    // Check each placeholder value
    foreach($placeholders as $key => $value){
        if(trim($value) === ''){
            $errors[$key] = ucfirst($key) . ' is required.';
        }
    }

    if(!empty($errors)){
        echo json_encode(['errors'=>$errors]);
        exit;
    }

    // Replace all placeholders dynamically
    foreach($placeholders as $key => $value){
        $template = str_replace("{".$key."}", $value, $template);
    }

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
.error-msg {
    color: red;
    font-size: 14px;
    margin-top: 4px;
}
.dynamic-placeholder-container {
    margin-top: 15px;
}
</style>
</head>
<body>
<div class="container">
  <div class="card card-module">
    <h3><i class="bi bi-chat-text"></i> SMS Template Formatter</h3>

    <div class="mb-3">
      <label>SMS Template (max 250 chars) <span class="text-danger">*</span>:</label>
      <input type="text" id="smsTemplate" class="form-control input-field" maxlength="250" placeholder="Hi {name}, your fee due is {amount}.">
      <div id="templateError" class="error-msg"></div>
    </div>

    <!-- Dynamic Placeholder Inputs will appear here -->
    <div class="dynamic-placeholder-container" id="placeholderInputs"></div>

    <button class="btn btn-success btn-modern mt-2" id="formatSMS"><i class="bi bi-pencil-square"></i> Format SMS</button>

    <div id="smsResult" class="mt-4"></div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
$(document).ready(function(){
    $('#smsTemplate').focus();
});

// Function to detect placeholders dynamically and create input fields
$('#smsTemplate').on('input', function(){
    let template = $(this).val().trim();
    $('#placeholderInputs').empty(); // Clear previous inputs

    // Find all unique placeholders
    let matches = template.match(/\{(.*?)\}/g);
    if(matches){
        let placeholders = [...new Set(matches.map(m => m.replace(/[{}]/g,'')))];

        placeholders.forEach(ph => {
            let html = `<div class="mb-3">
                            <label>${ph.charAt(0).toUpperCase()+ph.slice(1)} <span class="text-danger">*</span>:</label>
                            <input type="text" class="form-control input-field dynamic-placeholder" data-placeholder="${ph}" placeholder="Enter ${ph}">
                            <div class="error-msg" id="error_${ph}"></div>
                        </div>`;
            $('#placeholderInputs').append(html);
        });
    }
});

// Format SMS via AJAX
$('#formatSMS').click(function(){
    // Clear previous errors and output
    $('#templateError, .error-msg, #smsResult').text('');

    let template = $('#smsTemplate').val().trim();
    if(template === ''){
        $('#templateError').text('Template is required.');
        return;
    }

    // Collect all placeholder values
    let placeholders = {};
    $('.dynamic-placeholder').each(function(){
        let key = $(this).data('placeholder');
        let val = $(this).val().trim();
        placeholders[key] = val;
    });

    $.ajax({
        url:'<?php echo $_SERVER['PHP_SELF']; ?>',
        type:'POST',
        data:{
            action:'formatSMS',
            template: template,
            placeholders: placeholders
        },
        success:function(response){
            let data = JSON.parse(response);
            if(data.errors){
                if(data.errors.template) $('#templateError').text(data.errors.template);
                for(let key in data.errors){
                    $('#error_'+key).text(data.errors[key]);
                }
            } else if(data.formattedSMS){
                $('#smsResult').html(`<div class="sms-box">${data.formattedSMS}</div>`);
            }
        }
    });
});
</script>
</body>
</html>
