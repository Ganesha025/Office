<?php
if(isset($_POST['action']) && $_POST['action']==='validateForm'){
    $name = trim($_POST['name'] ?? '');
    $mobile = trim($_POST['mobile'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $age = trim($_POST['age'] ?? '');

    $errors = [];

    // Name validation
    if($name===''){
        $errors['name']='⚠ Name cannot be empty.';
    } elseif(!preg_match("/^[A-Za-z ]{1,20}$/",$name)){
        $errors['name']='⚠ Name can contain only letters and spaces, max 20 chars.';
    }

    // Mobile validation
    if(!preg_match("/^[6-9][0-9]{9}$/",$mobile)){
        $errors['mobile']='⚠ Mobile must be 10 digits starting with 6-9.';
    }

    // Email validation
    if($email===''){
        $errors['email']='⚠ Email cannot be empty.';
    } elseif(!filter_var($email,FILTER_VALIDATE_EMAIL)){
        $errors['email']='⚠ Invalid email format.';
    }

    // Age validation
    if($age===''){
        $errors['age']='⚠ Age cannot be empty.';
    } elseif(!preg_match("/^\d{1,2}$/",$age) || intval($age)<1 || intval($age)>100){
        $errors['age']='⚠ Age must be a number between 1-100.';
    }

    if(!empty($errors)){
        echo json_encode(['errors'=>$errors]);
        exit;
    }

    echo json_encode(['success'=>'✅ Form submitted successfully!']);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admission Form - Age Restricted</title>
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
.input-field {
    border-radius:10px;
    border:2px solid #ced4da;
    font-weight:500;
    transition:0.3s;
    height:45px;
    padding:5px 10px;
    margin-bottom:5px;
    width:100%;
    -moz-appearance:textfield;
}
.input-field::-webkit-outer-spin-button,
.input-field::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}
.input-field:focus {
    border-color:#0d6efd;
    box-shadow:0 0 8px rgba(13,110,253,0.3);
}
.input-error {
    border-color:#dc3545 !important;
    box-shadow:0 0 8px rgba(220,53,69,0.4);
}
.error-msg {
    color:#dc3545;
    font-size:0.9rem;
    margin-bottom:10px;
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
    font-size:16px;
    border-radius:12px;
    font-weight:bold;
    margin-top:15px;
    text-align:center;
}
.result-success { background:#28a745; color:white;}
</style>
</head>
<body>
<div class="container">
  <div class="card card-module">
    <h3><i class="bi bi-person-plus"></i> Admission Form</h3>
    <p class="text-secondary mb-3">Fill all fields correctly before submission.</p>

    <label>Name</label>
    <input type="text" class="form-control input-field" id="name" maxlength="20">
    <div id="nameError" class="error-msg"></div>

    <label>Mobile</label>
    <input type="text" class="form-control input-field" id="mobile" maxlength="10">
    <div id="mobileError" class="error-msg"></div>

    <label>Email</label>
    <input type="email" class="form-control input-field" id="email" maxlength="50">
    <div id="emailError" class="error-msg"></div>

    <label>Age</label>
    <input type="text" class="form-control input-field" id="age" maxlength="3" placeholder="1-100">
    <div id="ageError" class="error-msg"></div>

    <button class="btn btn-success btn-modern mt-2" id="submitForm"><i class="bi bi-send"></i> Submit</button>

    <div id="result" class="mt-3"></div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
$(document).ready(function(){
    $('#name').focus();

    // Name live validation
    $('#name').on('input', function(){
        this.value = this.value.replace(/[^A-Za-z ]/g,'').slice(0,20);
        $('#nameError').text(this.value.length===0 ? '⚠ Name cannot be empty.' : '');
        $(this).toggleClass('input-error', this.value.length===0);
    });

    // Mobile live validation
    $('#mobile').on('input', function(){
        this.value = this.value.replace(/[^0-9]/g,'').slice(0,10);
        let valid = /^[6-9][0-9]{0,9}$/.test(this.value);
        $('#mobileError').text(valid ? '' : '⚠ Mobile must be 10 digits starting with 6-9.');
        $(this).toggleClass('input-error', !valid);
    });

    // Email live validation
    $('#email').on('input', function(){
        let val = $(this).val();
        if(val.length===0){
            $('#emailError').text('⚠ Email cannot be empty.');
            $(this).addClass('input-error');
            return;
        }
        let pattern = /^[^\s@]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/;
        let valid = pattern.test(val);
        $('#emailError').text(valid ? '' : '⚠ Invalid email format.');
        $(this).toggleClass('input-error', !valid);
    });

    // Age live validation: 1-100 only, max 2 digits typed
    $('#age').on('input', function(){
        this.value = this.value.replace(/[^0-9]/g,''); // remove letters
        if(parseInt(this.value) > 100) this.value = '100';
        if(parseInt(this.value) < 1 && this.value !== '') this.value = '1';
        if(this.value.length > 2) this.value = this.value.slice(0,2);
        let valid = this.value !== '' && parseInt(this.value) >=1 && parseInt(this.value) <=100;
        $('#ageError').text(valid ? '' : '⚠ Age must be 1-100.');
        $(this).toggleClass('input-error', !valid);
    });

    // Submit button
    $('#submitForm').click(function(){
        let name = $('#name').val().trim();
        let mobile = $('#mobile').val().trim();
        let email = $('#email').val().trim();
        let age = $('#age').val().trim();

        $.ajax({
            url: '<?php echo $_SERVER['PHP_SELF']; ?>',
            type: 'POST',
            data: {action:'validateForm', name:name, mobile:mobile, email:email, age:age},
            success: function(response){
                let data = JSON.parse(response);

                if(data.errors){
                    $.each(data.errors, function(key, value){
                        $('#'+key).addClass('input-error');
                        $('#'+key+'Error').text(value);
                    });
                    $('#name').focus();
                    return;
                }

                $('#result').html('<div class="result-box result-success">'+data.success+'</div>');

                // Clear fields
                $('.input-field').val('');
                $('#name').focus();

                // Hide success after 3 seconds
                setTimeout(function(){
                    $('#result').fadeOut('slow', function(){ $(this).html('').show(); });
                }, 3000);
            }
        });
    });
});
</script>
</body>
</html>
