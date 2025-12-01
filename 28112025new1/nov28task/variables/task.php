<?php
session_start();
$message = '';
$errors = [
    'name' => '',
    'age' => '',
    'missionCode' => '',
    'missionLocation' => '',
    'missionTarget' => '',
    'currentDay' => '',
    'daysUntilMission' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $age = intval($_POST['age'] ?? -1);
    $missionCode = $_POST['missionCode'] ?? '';
    $missionLocation = $_POST['missionLocation'] ?? '';
    $missionTarget = $_POST['missionTarget'] ?? '';
    $currentDay = intval($_POST['currentDay'] ?? -1);
    $daysUntilMission = intval($_POST['daysUntilMission'] ?? -1);

    // Validation
    if ($name === '') {
        $errors['name'] = 'Name is required';
    } elseif (!preg_match("/^[a-zA-Z\s]{1,20}$/", $name)) {
        $errors['name'] = 'Only letters and spaces allowed (max 20)';
    }

    if ($age < 0 || $age > 100) {
        $errors['age'] = 'Age must be a number between 0 and 100';
    }

    if ($missionCode === '') $errors['missionCode'] = 'Select a mission code';
    if ($missionLocation === '') $errors['missionLocation'] = 'Select a mission location';
    if ($missionTarget === '') $errors['missionTarget'] = 'Select a mission target';

    if ($currentDay < 1 || $currentDay > 31) {
        $errors['currentDay'] = 'Current day must be between 1 and 31';
    }

    if ($daysUntilMission < 1 || $daysUntilMission > 31) {
        $errors['daysUntilMission'] = 'Days until mission must be between 1 and 31';
    }

    // If no errors, process
    if (!array_filter($errors)) {
        $missionDate = $currentDay + $daysUntilMission;
        if($missionDate > 31) $missionDate -= 31;

        $message = "Hello, my name is <b>$name</b> and I am <b>$age</b> years old.<br>";
        $message .= "Mission '<b>$missionCode</b>' is set in <b>$missionLocation</b> targeting <b>$missionTarget</b>.<br>";
        $message .= "The mission is scheduled in <b>$daysUntilMission</b> days, on day <b>$missionDate</b> of the month.";

        $_SESSION['message'] = $message;
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    }
}

if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Mission Dashboard</title>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
<style>
* { box-sizing: border-box; margin:0; padding:0; font-family: 'Roboto', sans-serif; }
body { 
    background: linear-gradient(135deg, #0f2027, #203a43, #2c5364); 
    display:flex; justify-content:center; align-items:flex-start; 
    min-height:100vh; padding:50px 20px; color:#fff;
}
.container {
    background:#1f1f1f; 
    padding:40px 30px; 
    border-radius:15px; 
    box-shadow:0 20px 50px rgba(0,0,0,0.5);
    max-width:550px; 
    width:100%;
}
h2 { text-align:center; margin-bottom:30px; font-size:28px; color:#00f0ff; text-shadow: 1px 1px 3px #000; }
form { display: grid; grid-template-columns: 1fr 2fr; row-gap: 18px; column-gap: 15px; align-items: center; }
label { font-weight:500; font-size:16px; color:#00f0ff; justify-self:end; }
label .required-star { color:#e74c3c; margin-left:3px; }
input, select { padding:14px; border-radius:10px; border:none; font-size:16px; outline:none; width:100%; transition: all 0.3s ease; }
input:focus, select:focus { box-shadow: 0 0 10px #00f0ff; background:#2c2c2c; color:#fff; }
.error { color:#e74c3c; font-size:14px; grid-column:2; min-height:18px; }
button { grid-column: span 2; padding:14px; border:none; border-radius:10px; background: linear-gradient(45deg, #00f0ff, #ff00f0); color:#1f1f1f; font-size:16px; font-weight:500; cursor:pointer; transition:0.3s ease; }
button:hover { background: linear-gradient(45deg, #ff00f0, #00f0ff); color:#fff; transform: scale(1.05);}
.result-box { grid-column: span 2; margin-top:25px; padding:20px; background: rgba(255,255,255,0.1); border-left:5px solid #00f0ff; border-radius:10px; line-height:1.6; font-size:16px; }
@media(max-width:600px){ form { display:flex; flex-direction:column; } label { justify-self:start; margin-bottom:5px; } button, .result-box { width:100%; } }
</style>
<script>
function validateName(e) {
    const re = /^[a-zA-Z\s]{0,20}$/;
    if(!re.test(e.value)) { e.value = e.value.slice(0,-1); }
    checkError(e, /^[a-zA-Z\s]{1,20}$/, 'Name must be letters (max 20)');
}

function restrictNumber(e, min, max) {
    e.value = e.value.replace(/\D/g,'');
    let num = parseInt(e.value);
    if(!isNaN(num)){
        if(num > max) e.value = max;
        if(num < min && e.value !== '') e.value = min;
    }
    if(e.value.length > 2) e.value = e.value.slice(0,2);
    checkError(e, new RegExp(`^([${min}-${max}]|[1-2][0-9]|3[0-1])$`), `Value must be ${min}-${max}`);
}

function checkError(element, pattern, msg){
    const errorDiv = element.nextElementSibling;
    if(pattern.test(element.value)) {
        errorDiv.textContent = '';
    }
}

window.onload = function() {
    document.getElementById('nameField').focus();
};
</script>
</head>
<body>
<div class="container">
<h2>Mission Input Dashboard</h2>
<form method="post" autocomplete="off" novalidate>

    <label>Character Name <span class="required-star">*</span></label>
    <input type="text" id="nameField" name="name" placeholder="Enter character name" maxlength="20" oninput="validateName(this)" required>
    <div class="error"><?php echo $errors['name'];?></div>

    <label>Age <span class="required-star">*</span></label>
    <input type="text" name="age" placeholder="0-100" maxlength="3" oninput="restrictNumber(this,0,100)" required>
    <div class="error"><?php echo $errors['age'];?></div>

    <label>Mission Code Name <span class="required-star">*</span></label>
    <select name="missionCode" required onchange="checkError(this, /.+/, 'Select a mission code')">
        <option value="" disabled selected>Select Code Name</option>
        <option value="Phantom">Phantom</option>
        <option value="Eclipse">Eclipse</option>
        <option value="Nightfall">Nightfall</option>
        <option value="Sol">Sol</option>
        <option value="Shadow">Shadow</option>
    </select>
    <div class="error"><?php echo $errors['missionCode'];?></div>

    <label>Mission Location <span class="required-star">*</span></label>
    <select name="missionLocation" required onchange="checkError(this, /.+/, 'Select a mission location')">
        <option value="" disabled selected>Select Location</option>
        <option value="Juarez">Juarez</option>
        <option value="El Paso">El Paso</option>
        <option value="Nogales">Nogales</option>
        <option value="Tijuana">Tijuana</option>
        <option value="Ciudad Juarez">Ciudad Juarez</option>
    </select>
    <div class="error"><?php echo $errors['missionLocation'];?></div>

    <label>Mission Target <span class="required-star">*</span></label>
    <select name="missionTarget" required onchange="checkError(this, /.+/, 'Select a mission target')">
        <option value="" disabled selected>Select Target</option>
        <option value="Capo">Capo</option>
        <option value="Lieutenant">Lieutenant</option>
        <option value="Operative">Operative</option>
        <option value="Boss">Boss</option>
        <option value="Enforcer">Enforcer</option>
    </select>
    <div class="error"><?php echo $errors['missionTarget'];?></div>

    <label>Current Day of Month <span class="required-star">*</span></label>
    <input type="text" name="currentDay" placeholder="1-31" maxlength="2" oninput="restrictNumber(this,1,31)" required>
    <div class="error"><?php echo $errors['currentDay'];?></div>

    <label>Days Until Mission <span class="required-star">*</span></label>
    <input type="text" name="daysUntilMission" placeholder="1-31" maxlength="2" oninput="restrictNumber(this,1,31)" required>
    <div class="error"><?php echo $errors['daysUntilMission'];?></div>

    <button type="submit">Submit Mission</button>

    <?php if($message): ?>
    <div class="result-box"><?php echo $message; ?></div>
    <?php endif; ?>
</form>
</div>
</body>
</html>
