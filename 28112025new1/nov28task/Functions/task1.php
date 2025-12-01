<?php
session_start();

$maxNumber = "";
$errors = ["num1" => "", "num2" => "", "num3" => ""];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $num1 = $_POST['num1'] ?? "";
    $num2 = $_POST['num2'] ?? "";
    $num3 = $_POST['num3'] ?? "";

    function validateNumber($num) {
        if ($num === "") return "This field is required.";
        if (!is_numeric($num) || $num < 1 || $num > 100) return "Enter a valid number (1-100).";
        return "";
    }

    $errors["num1"] = validateNumber($num1);
    $errors["num2"] = validateNumber($num2);
    $errors["num3"] = validateNumber($num3);

    if (!$errors["num1"] && !$errors["num2"] && !$errors["num3"]) {
        $maxNumber = max($num1, $num2, $num3);
        $_SESSION['max'] = $maxNumber;

        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}

if (isset($_SESSION['max'])) {
    $maxNumber = $_SESSION['max'];
    unset($_SESSION['max']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Find Maximum of Three Numbers</title>
<style>
* { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
body { display: flex; justify-content: center; align-items: center; height: 100vh; background: linear-gradient(135deg, #ff758c, #ff7eb3); }
.container { background: white; padding: 30px 40px; border-radius: 20px; box-shadow: 0 15px 30px rgba(0,0,0,0.25); width: 100%; max-width: 400px; text-align: center; transition: transform 0.3s ease, box-shadow 0.3s ease; }
.container:hover { transform: translateY(-5px); box-shadow: 0 25px 40px rgba(0,0,0,0.3); }
h1 { margin-bottom: 25px; color: #333; font-size: 1.8em; }
label { display: block; text-align: left; font-weight: bold; margin-top: 10px; }
.text-danger-star { color: red; margin-left: 3px; }
input[type="number"] { width: 100%; padding: 12px; margin: 5px 0; border-radius: 12px; border: 1px solid #ccc; font-size: 16px; -moz-appearance: textfield; }
input[type="number"]::-webkit-outer-spin-button, input[type="number"]::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
input[type="number"]:focus { border-color: #ff7eb3; background: #fff0f5; outline: none; }
button { padding: 12px 20px; border: none; border-radius: 12px; background: #ff7eb3; color: white; font-size: 16px; cursor: pointer; margin-top: 15px; width: 100%; transition: background 0.3s ease, transform 0.2s ease; }
button:hover { background: #ff758c; transform: scale(1.05); }
.error { color: #ff4d88; font-size: 14px; margin-bottom: 5px; text-align: left; }
.result { margin-top: 25px; padding: 15px; background: #ffe6f0; border-radius: 15px; font-weight: bold; font-size: 18px; color: #ff4d88; animation: fadeIn 1s ease; }
@keyframes fadeIn { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
@media(max-width: 500px) { .container { padding: 20px; } h1 { font-size: 1.5em; } }
</style>
<script>
window.onload = function() {
    document.getElementsByName('num1')[0].focus();
};

function restrictInput(event, field) {
    let char = String.fromCharCode(event.which);
    if (!/[0-9]/.test(char)) { event.preventDefault(); return; }
    let value = field.value + char;
    if (parseInt(value) > 100 || value.length > 3) { event.preventDefault(); }
}
</script>
</head>
<body>
<div class="container">
    <h1>Find Maximum Number</h1>
    <form method="post" novalidate>
        <label>First Number <span class="text-danger-star">*</span></label>
        <input type="number" name="num1" placeholder="Enter first number" min="1" max="100" onkeypress="restrictInput(event,this)" required>
        <div class="error"><?php echo $errors['num1']; ?></div>

        <label>Second Number <span class="text-danger-star">*</span></label>
        <input type="number" name="num2" placeholder="Enter second number" min="1" max="100" onkeypress="restrictInput(event,this)" required>
        <div class="error"><?php echo $errors['num2']; ?></div>

        <label>Third Number <span class="text-danger-star">*</span></label>
        <input type="number" name="num3" placeholder="Enter third number" min="1" max="100" onkeypress="restrictInput(event,this)" required>
        <div class="error"><?php echo $errors['num3']; ?></div>

        <button type="submit">Find Maximum</button>
    </form>

    <?php if($maxNumber !== ""): ?>
        <div class="result">The maximum number is: <?php echo $maxNumber; ?></div>
    <?php endif; ?>
</div>
</body>
</html>
