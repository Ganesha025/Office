<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Registration ID Strict Validator</title>
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="styles.css">
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-6">

<?php
$regID = isset($_POST['regID']) ? $_POST['regID'] : '';
$message = '';
if($regID){
    $pattern = '/^\d{4}[A-Za-z]{3}\d{3}$/';
    $message = preg_match($pattern, $regID) 
        ? "✅ Registration ID <span class='font-bold'>$regID</span> is valid!" 
        : "❌ Invalid Registration ID. E.g., 2025CSE123";
}
?>

<div class="w-full max-w-md bg-white rounded-2xl shadow-2xl p-6">
<h1 class="text-3xl font-bold text-center text-gray-700 mb-6">Registration ID Validator</h1>

<form method="POST" class="space-y-4">
<input type="text" id="regID" name="regID" placeholder="2025CSE001" maxlength="10" value="<?php echo $regID; ?>" class="w-full border border-gray-300 p-2 rounded" required>
<button type="submit" class="w-full bg-gray-700 text-white py-2 rounded font-semibold">Validate</button>
</form>

<?php if($message): ?>
<div class="mt-6 p-4 text-center <?php echo strpos($message,'✅')!==false?'text-green-600':'text-red-600'; ?> font-semibold">
<?php echo $message; ?>
</div>
<?php endif; ?>

</div>

<script>
$(document).ready(function(){
    $('#regID').on('keypress', function(e){
        let val = $(this).val();
        if(val.length < 4){ if(!/[0-9]/.test(e.key)) e.preventDefault(); }
        else if(val.length < 7){ if(!/[A-Za-z]/.test(e.key)) e.preventDefault(); }
        else if(val.length < 10){ if(!/[0-9]/.test(e.key)) e.preventDefault(); }
        else{ e.preventDefault(); }
    });
});
</script>

</body>
</html>
