<?php
// Handle AJAX request
if(isset($_POST['action']) && $_POST['action']=="calculateSalary"){

    $name = trim($_POST['name'] ?? "");
    $base = $_POST['baseSalary'] ?? "";
    $allowances = $_POST['allowances'] ?? "";
    $deductions = $_POST['deductions'] ?? "";

    $errors = [];

    // Validate name
    if($name=="" || !preg_match("/^[A-Za-z ]{1,20}$/",$name)){
        $errors['name'] = "Enter valid name (letters & spaces only, max 20 chars)";
    }

    // Validate base salary
    if(!is_numeric($base) || $base<10000 || $base>100000){
        $errors['baseSalary'] = "Base Salary must be between 10,000 - 1,00,000";
    }

    // Validate allowances
    if(!is_numeric($allowances) || $allowances<100 || $allowances>10000){
        $errors['allowances'] = "Allowances must be between 100 - 10,000";
    }

    // Validate deductions
    if(!is_numeric($deductions) || $deductions<100 || $deductions>10000){
        $errors['deductions'] = "Deductions must be between 100 - 10,000";
    }

    if(!empty($errors)){
        echo json_encode(['status'=>'error','errors'=>$errors]);
        exit;
    }

    // Calculate final salary
    $finalSalary = $base + $allowances - $deductions;
    $result = "
        Staff Name: $name<br>
        Base Salary: ₹$base<br>
        Total Allowances: ₹$allowances<br>
        Total Deductions: ₹$deductions<br>
        <strong>Final Salary: ₹$finalSalary</strong>
    ";

    echo json_encode(['status'=>'success','result'=>$result]);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Staff Salary Slip Generator</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<style>
/* Card and Layout */
.card-module { margin:40px auto; padding:30px; border-radius:20px; background:#f8f9fa; box-shadow:0 12px 40px rgba(0,0,0,0.12); max-width:700px; }
.card-module h3 { font-weight:700; color:#0d6efd; display:flex; align-items:center; gap:12px; }
/* Input fields */
.input-field { width:100%; margin-bottom:15px; border-radius:8px; padding:10px; font-weight:500; }
/* Remove number input arrows */
input[type=number]::-webkit-inner-spin-button, 
input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
input[type=number] { -moz-appearance: textfield; }
/* Button */
.btn-modern { border-radius:10px; font-weight:600; transition:0.3s; width:100%; }
.btn-modern:hover { transform:scale(1.05); box-shadow:0 5px 15px rgba(0,0,0,0.15); }
/* Error message */
.error-msg { color:#dc3545; font-size:0.9rem; margin-top:-10px; margin-bottom:10px; }
/* Result box */
.result-box { margin-top:20px; padding:20px; background:#e8f1ff; border-left:6px solid #0d6efd; border-radius:12px; font-size:1.1rem; display:none; }
</style>
</head>
<body>
<div class="container">
<div class="card card-module">
<h3><i class="bi bi-cash-stack"></i> Staff Salary Slip Generator</h3>

<input type="text" id="name" class="input-field" placeholder="Staff Name (Letters & spaces only)" maxlength="20">
<div id="nameError" class="error-msg"></div>

<input type="number" id="baseSalary" class="input-field" placeholder="Base Salary (10,000 - 1,00,000)" min="10000" max="100000">
<div id="baseError" class="error-msg"></div>

<input type="number" id="allowances" class="input-field" placeholder="Allowances (100 - 10,000)" min="100" max="10000">
<div id="allowanceError" class="error-msg"></div>

<input type="number" id="deductions" class="input-field" placeholder="Deductions (100 - 10,000)" min="100" max="10000">
<div id="deductionError" class="error-msg"></div>

<button class="btn btn-success btn-modern" id="calculateSalaryBtn"><i class="bi bi-calculator"></i> Calculate Salary</button>

<div id="salaryResult" class="result-box"></div>
</div>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
$(document).ready(function(){

    // Focus first field
    $("#name").focus();

    // Validate name input (letters & spaces only)
    $("#name").on('input', function(){
        this.value = this.value.replace(/[^A-Za-z ]/g,'');
    });

    // Validate numeric inputs
    $("#baseSalary, #allowances, #deductions").on('input', function(){
        this.value = this.value.replace(/[^0-9]/g,'');
        if($(this).attr('id')=="baseSalary" && this.value>100000) this.value="100000";
        if($(this).attr('id')=="allowances" && this.value>10000) this.value="10000";
        if($(this).attr('id')=="deductions" && this.value>10000) this.value="10000";
    });

    // Calculate Salary
    $("#calculateSalaryBtn").click(function(){
        $(".error-msg").text("");
        $("#salaryResult").hide().html("");

        let name = $("#name").val().trim();
        let baseSalary = $("#baseSalary").val();
        let allowances = $("#allowances").val();
        let deductions = $("#deductions").val();

        $.ajax({
            url:"<?php echo $_SERVER['PHP_SELF']; ?>",
            type:"POST",
            data:{
                action:"calculateSalary",
                name:name,
                baseSalary:baseSalary,
                allowances:allowances,
                deductions:deductions
            },
            success:function(response){
                let data = JSON.parse(response);
                if(data.status=="error"){
                    if(data.errors.name) $("#nameError").text(data.errors.name);
                    if(data.errors.baseSalary) $("#baseError").text(data.errors.baseSalary);
                    if(data.errors.allowances) $("#allowanceError").text(data.errors.allowances);
                    if(data.errors.deductions) $("#deductionError").text(data.errors.deductions);
                }else{
                    $("#salaryResult").html(data.result).show();
                    $("#name, #baseSalary, #allowances, #deductions").val("");
                    $("#name").focus();
                }
            }
        });
    });
});
</script>
</body>
</html>
