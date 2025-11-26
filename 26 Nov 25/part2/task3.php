<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Department Code Extractor</title>
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="styles.css">
</head>
<body class="bg-green-50 min-h-screen flex items-center justify-center p-6">

<?php
$regID = isset($_POST['regID']) ? $_POST['regID'] : '';
$year=$dept=$roll=$deptMsg='';
$departments = ["CSE","ECE","AID","BCA","EEE"];
if($regID && strlen($regID)==10){
    $year = substr($regID,0,4);
    $dept = strtoupper(substr($regID,4,3));
    $roll = substr($regID,7,3);
    $deptMsg = in_array($dept, $departments) ? "Department: $dept" : "❌ Department does not exist";
}
?>

<div class="w-full max-w-md bg-white rounded-2xl shadow-2xl p-6">
<h1 class="text-3xl font-bold text-center text-green-700 mb-6">Department Code Extractor</h1>

<form method="POST" class="space-y-4">
<input type="text" id="regID" name="regID" placeholder="2025CSE001" maxlength="10" value="<?php echo $regID; ?>" class="w-full border border-gray-300 p-2 rounded" required>
<button type="submit" class="w-full bg-green-600 text-white py-2 rounded font-semibold">Extract</button>
</form>

<?php if($regID && strlen($regID)==10): ?>
<div class="mt-6 p-4 bg-green-50 rounded-xl text-center space-y-2">
<p class="text-lg font-semibold text-green-600"><?php echo "Year: ".$year; ?></p>
<p class="text-lg font-semibold <?php echo in_array($dept, $departments)?'text-green-600':'text-red-600'; ?>">
<?php echo $deptMsg; ?></p>
<p class="text-lg font-semibold text-green-600"><?php echo "Roll: ".$roll; ?></p>
</div>
<?php elseif($regID): ?>
<div class="mt-6 p-4 text-center text-red-600 font-semibold">❌ Invalid Registration ID. Must be 10 characters.</div>
<?php endif; ?>

</div>

<script>
$(document).ready(function(){
    $('#regID').on('keypress', function(e){
        let val = $(this).val();
        if(val.length < 4){ 
            if(!/[0-9]/.test(e.key)) e.preventDefault(); 
        } else if(val.length < 7){ 
            if(!/[A-Za-z]/.test(e.key)) e.preventDefault(); 
        } else if(val.length < 10){ 
            if(!/[0-9]/.test(e.key)) e.preventDefault(); 
        } else { 
            e.preventDefault(); 
        }
    });

    $('#regID').on('input', function(){
        this.value = this.value.toUpperCase();
    });
});

</script>

</body>
</html>
