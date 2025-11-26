<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Student Profile</title>
<script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="styles.css">
<style>
</style>
</head>
<body class="bg-blue-50 min-h-screen flex items-center justify-center p-6">

<?php
$regNo = isset($_POST['regNo']) ? $_POST['regNo'] : '';
$name = isset($_POST['name']) ? $_POST['name'] : '';
$email = isset($_POST['email']) ? $_POST['email'] : '';
$mobile = isset($_POST['mobile']) ? $_POST['mobile'] : '';
?>

<div class="w-full max-w-md bg-white rounded-2xl shadow-2xl p-6">
<h1 class="text-3xl font-bold text-center text-blue-700 mb-6">Student Profile</h1>

<form method="POST" class="space-y-4">
<input type="text" name="regNo" placeholder="Eg : 221XX1234" value="<?php echo $regNo; ?>" class="val-regid w-full border border-gray-300 p-2 rounded" required>
<input type="text" name="name" placeholder="Name" value="<?php echo $name; ?>" class="val-username w-full border border-gray-300 p-2 rounded" required>
<input type="email" name="email" placeholder="Email" value="<?php echo $email; ?>" class="val-email w-full border border-gray-300 p-2 rounded" required>
<input type="text" name="mobile" placeholder="Mobile" value="<?php echo $mobile; ?>" class="val-mobile w-full border border-gray-300 p-2 rounded" required>
<span></span>
<button type="submit" class="w-full bg-blue-500 text-white py-2 rounded font-semibold">Show Profile</button>
</form>

<?php if($regNo && $name && $email && $mobile): ?>
<div class="mt-6 p-4 bg-blue-50 rounded-xl text-left space-y-2">
<p class="text-lg font-semibold text-blue-600"><?php echo "Reg No: ".strtoupper($regNo); ?></p>
<p class="text-lg font-semibold text-blue-600"><?php echo "Name: ".ucwords($name); ?></p>
<p class="text-lg font-semibold text-blue-600"><?php echo "Email: ".strtolower($email); ?></p>
<p class="text-lg font-semibold text-blue-600"><?php echo "Mobile: ".preg_replace('/(\d{3})(\d{3})(\d{4})/', '$1-$2-$3', $mobile); ?></p>
</div>
<?php endif; ?>

</div>
<script src='./valid.js'></script>
</body>
</html>
