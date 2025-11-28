<?php
include 'db.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $city = trim($_POST['city']);
    $pincode = trim($_POST['pincode']);
    $addressVerified = false;
    $foundCity = $foundPincode = false;
    $query = http_build_query(['city'=>$city,'postalcode'=>$pincode,'country'=>'India','format'=>'json','addressdetails'=>1,'limit'=>10]);
    $url = "https://nominatim.openstreetmap.org/search?$query";
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'MyOrderApp/1.0');
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);
    $resp = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if ($httpCode !== 200 || !$resp) $error = "Unable to verify address. API request failed.";
    else {
        $data = json_decode($resp,true);
        if(empty($data)) $error="No matching address found.";
        else {
            foreach($data as $r){
                $addr=$r['address']??[];
                $rc=$addr['city']??$addr['town']??$addr['village']??'';
                $rp=$addr['postcode']??'';
                if(strcasecmp($rc,$city)===0)$foundCity=true;
                if($rp===$pincode)$foundPincode=true;
                if(strcasecmp($rc,$city)===0 && $rp===$pincode){$addressVerified=true; break;}
            }
            if(!$addressVerified){
                if(!$foundCity && !$foundPincode)$error="City and pincode not found.";
                elseif(!$foundCity)$error="City not found or does not match pincode.";
                elseif(!$foundPincode)$error="Pincode not found or does not match city.";
                else $error="City and pincode do not match.";
            }
        }
    }
    if($addressVerified){
        $items=[];
        for($i=0;$i<count($_POST['item_name']);$i++){
            $in=trim($_POST['item_name'][$i]);
            $pr=(float)$_POST['price'][$i];
            if($in!=='' && $pr>0)$items[]= ['item_name'=>$in,'price'=>$pr];
        }
        $orderData=['customer_name'=>trim($_POST['customer_name']),'order_date'=>$_POST['order_date'],'mobile'=>trim($_POST['mobile']),'email'=>trim($_POST['email']),'door_flat_no'=>trim($_POST['door_flat_no']),'street_name'=>trim($_POST['street_name']),'city'=>$city,'pincode'=>$pincode,'items'=>$items];
        addOrder($conn,$orderData);
        $success="Order added successfully!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Add Customer Order</title>
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://code.jquery.com/jquery-3.7.1.slim.js"></script>
<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
body{font-family:'Inter',sans-serif;}
</style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">

<header class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-50">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
<div class="flex items-center justify-between">
<div class="flex items-center space-x-3">
<div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
<svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
</div>
<div>
<h1 class="text-xl font-bold text-gray-900">Order Management</h1>
<p class="text-xs text-gray-500">Professional Order System</p>
</div>
</div>
<a href="orders.php" class="hidden sm:flex items-center space-x-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors">
<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
<span class="text-sm font-medium">View Orders</span>
</a>
</div>
</div>
</header>

<main class="flex-1 max-w-4xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8 mb-20">

<?php if($error):?>
<div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-lg flex items-start space-x-3">
<svg class="w-5 h-5 text-red-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"/></svg>
<div><p class="text-sm font-medium text-red-800"><?=$error?></p></div>
</div>
<?php endif;?>

<?php if(isset($success)):?>
<div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-r-lg flex items-start space-x-3">
<svg class="w-5 h-5 text-green-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/></svg>
<div><p class="text-sm font-medium text-green-800"><?=$success?></p></div>
</div>
<?php endif;?>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
<div class="bg-gray-50 border-b border-gray-200 px-6 py-4">
<h2 class="text-lg font-semibold text-gray-900">Create New Order</h2>
<p class="text-sm text-gray-600 mt-1">Fill in customer details and order items</p>
</div>

<form action="" method="POST" class="p-6 space-y-8">

<div class="space-y-6">
<h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wide flex items-center">
<svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
Customer Information
</h3>
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
<div>
<label class="block text-sm font-medium text-gray-700 mb-2">Customer Name *</label>
<input type="text" name="customer_name" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" placeholder="John Doe">
</div>
<div>
<label class="block text-sm font-medium text-gray-700 mb-2">Order Date *</label>
<input type="date" name="order_date" required value="<?=date('Y-m-d')?>" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
</div>
<div>
<label class="block text-sm font-medium text-gray-700 mb-2">Mobile Number *</label>
<input type="text" name="mobile" class="val-mobile w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" required placeholder="9876543210">
</div>
<div>
<label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
<input type="email" name="email" class="val-email w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" placeholder="john@example.com">
</div>
</div>
</div>

<div class="space-y-6">
<h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wide flex items-center">
<svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
Delivery Address
</h3>
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
<div>
<label class="block text-sm font-medium text-gray-700 mb-2">Door/Flat Number</label>
<input type="text" name="door_flat_no" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" placeholder="Flat 101">
</div>
<div>
<label class="block text-sm font-medium text-gray-700 mb-2">Street Name</label>
<input type="text" name="street_name" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" placeholder="MG Road">
</div>
<div>
<label class="block text-sm font-medium text-gray-700 mb-2">City *</label>
<input type="text" name="city" class="val-location w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" required oninput="scheduleVerification()" placeholder="Coimbatore">
</div>
<div>
<label class="block text-sm font-medium text-gray-700 mb-2">Pincode *</label>
<input type="text" name="pincode" class="val-pincode w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" required oninput="scheduleVerification()" pattern="[0-9]{6}" maxlength="6" placeholder="641001">
</div>
</div>
<div id="verification-status" class="hidden p-4 rounded-lg"></div>
</div>

<div class="space-y-6">
<div class="flex items-center justify-between">
<h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wide flex items-center">
<svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
Order Items
</h3>
<button type="button" onclick="addItemRow()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors flex items-center space-x-2">
<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
<span>Add Item</span>
</button>
</div>
<div id="items-container" class="space-y-3">
<div class="item-row p-4 bg-gray-50 border border-gray-200 rounded-lg grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
<div class="md:col-span-7">
<label class="block text-xs font-medium text-gray-700 mb-1.5">Item Name *</label>
<input type="text" name="item_name[]" class="val-com-name w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm" required placeholder="Product name">
</div>
<div class="md:col-span-4">
<label class="block text-xs font-medium text-gray-700 mb-1.5">Price (₹) *</label>
<input type="number" name="price[]" class="val-salary w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm" step="0.01" required placeholder="0.00">
</div>
</div>
</div>
</div>

<div class="flex flex-col sm:flex-row gap-3 pt-4 border-t border-gray-200">
<button type="submit" class="flex-1 sm:flex-initial px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors flex items-center justify-center space-x-2">
<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
<span>Submit Order</span>
</button>
<a href="orders.php" class="flex-1 sm:flex-initial px-8 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors flex items-center justify-center space-x-2">
<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
<span>View Orders</span>
</a>
</div>

</form>
</div>

</main>

<footer class="bg-white border-t border-gray-200 mt-auto">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
<div class="flex flex-col md:flex-row items-center justify-between space-y-4 md:space-y-0">
<div class="flex items-center space-x-2 text-sm text-gray-600">
<svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z"/></svg>
<span>SavageInfo System</span>
</div>
<div class="flex items-center space-x-6 text-sm text-gray-600">
<a href="#" class="hover:text-blue-600 transition-colors">Help</a>
<a href="#" class="hover:text-blue-600 transition-colors">Privacy</a>
<a href="#" class="hover:text-blue-600 transition-colors">Terms</a>
</div>
<div class="text-sm text-gray-500">© 2025 All rights reserved</div>
</div>
</div>
</footer>

<script>
let verifyTimeout;
function addItemRow(){
const c=document.getElementById('items-container');
const d=document.createElement('div');
d.className='item-row p-4 bg-gray-50 border border-gray-200 rounded-lg grid grid-cols-1 md:grid-cols-12 gap-4 items-end';
d.innerHTML=`<div class="md:col-span-7"><label class="block text-xs font-medium text-gray-700 mb-1.5">Item Name *</label><input type="text" name="item_name[]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm" required placeholder="Product name"></div><div class="md:col-span-4"><label class="block text-xs font-medium text-gray-700 mb-1.5">Price (₹) *</label><input type="number" name="price[]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm" step="0.01" required placeholder="0.00"></div><div class="md:col-span-1"><button type="button" onclick="this.closest('.item-row').remove()" class="w-full px-3 py-2 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg transition-colors flex items-center justify-center"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button></div>`;
c.appendChild(d);
}
function scheduleVerification(){clearTimeout(verifyTimeout);verifyTimeout=setTimeout(verifyAddress,1500);}
async function verifyAddress(){
const city=document.querySelector('input[name="city"]').value.trim();
const pincode=document.querySelector('input[name="pincode"]').value.trim();
const status=document.getElementById('verification-status');
if(!city||!pincode){status.classList.add('hidden');return;}
status.className='flex items-start space-x-3 p-4 rounded-lg bg-yellow-50 border border-yellow-200';
status.innerHTML='<svg class="w-5 h-5 text-yellow-600 mt-0.5 flex-shrink-0 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg><span class="text-sm font-medium text-yellow-800">Verifying address...</span>';
try{
const query=new URLSearchParams({city,postalcode:pincode,country:'India',format:'json',addressdetails:1,limit:10});
const res=await fetch(`https://nominatim.openstreetmap.org/search?${query}`,{headers:{'Accept':'application/json','User-Agent':'MyOrderApp/1.0'}});
const data=await res.json();
if(!data||data.length===0){status.className='flex items-start space-x-3 p-4 rounded-lg bg-red-50 border border-red-200';status.innerHTML='<svg class="w-5 h-5 text-red-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"/></svg><span class="text-sm font-medium text-red-800">No matching address found</span>';return;}
let exact=false;
for(let r of data){if(r.address){const rc=r.address.city||r.address.town||r.address.village||'';const rp=r.address.postcode||'';if(rc.toLowerCase()===city.toLowerCase()&&rp===pincode){exact=true;break;}}}
if(exact){status.className='flex items-start space-x-3 p-4 rounded-lg bg-green-50 border border-green-200';status.innerHTML='<svg class="w-5 h-5 text-green-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/></svg><span class="text-sm font-medium text-green-800">Address verified successfully!</span>';}
else{status.className='flex items-start space-x-3 p-4 rounded-lg bg-red-50 border border-red-200';status.innerHTML='<svg class="w-5 h-5 text-red-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"/></svg><span class="text-sm font-medium text-red-800">City and pincode do not match</span>';}
}catch(e){status.className='flex items-start space-x-3 p-4 rounded-lg bg-red-50 border border-red-200';status.innerHTML='<svg class="w-5 h-5 text-red-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"/></svg><span class="text-sm font-medium text-red-800">Unable to verify address</span>';}
}
</script>
<script src="../valid.js"></script>
</body>
</html>