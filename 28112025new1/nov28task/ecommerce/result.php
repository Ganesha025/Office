<?php
$customer = $_POST['customer'];
$item = $_POST['item'];
$amount = $_POST['amount'];
$member = $_POST['member'];
$destination = $_POST['destination'];
$weight = $_POST['weight'];

// Discount Logic
if($member=="yes") $discount=20;
elseif($amount>200) $discount=15;
elseif($amount>100) $discount=10;
else $discount=5;

$discountAmount = ($amount*$discount)/100;
$discountedPrice = $amount - $discountAmount;

// Shipping Logic
if($destination=="domestic" && $weight<5) $shipping=10;
elseif($destination=="domestic" && $weight>=5) $shipping=20;
elseif($destination=="international" && $weight<5) $shipping=30;
else $shipping=50;

// Total
$totalCost = $discountedPrice + $shipping;
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Calculation Result</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
<style>
body { margin:0; font-family:'Inter',sans-serif; background:#f5f7fa; display:flex; flex-direction:column; min-height:100vh; }
header { background: linear-gradient(135deg,#ff7e5f,#feb47b); padding:25px 0; text-align:center; color:#fff; font-size:28px; font-weight:600; }
.wrapper { width:70%; margin:30px auto; flex:1; }
.card { background:#fff; padding:35px 40px; border-radius:15px; box-shadow:0 10px 25px rgba(0,0,0,0.08); margin-bottom:30px; }
h2 { text-align:center; margin-bottom:25px; }
.info { font-size:18px; padding:12px; background:#f7f9fc; border-left:5px solid #ff7e5f; border-radius:6px; margin-bottom:15px; }
footer { background:#2d2d2d; color:#fff; text-align:center; padding:18px; margin-top:auto; }
</style>
</head>
<body>
<header>Calculation Results</header>
<div class="wrapper">
<div class="card">
<h2>Order & Discount Summary</h2>
<p class="info"><strong>Customer Name:</strong> <?= htmlspecialchars($customer) ?></p>
<p class="info"><strong>Ordered Item:</strong> <?= htmlspecialchars($item) ?></p>
<p class="info"><strong>Original Price:</strong> ₹<?= $amount ?></p>
<p class="info"><strong>Discount Applied:</strong> <?= $discount ?>%</p>
<p class="info"><strong>Price After Discount:</strong> ₹<?= $discountedPrice ?></p>
<p class="info"><strong>Shipping Cost:</strong> ₹<?= $shipping ?></p>
<p class="info" style="border-left:5px solid #feb47b;"><strong>Total Cost:</strong> ₹<?= $totalCost ?></p>
</div>
</div>
<footer>© 2025 E-Commerce System | Designed with ❤️</footer>
</body>
</html>
