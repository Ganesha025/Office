<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>E-Commerce Price & Shipping Calculator</title>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">

<style>
body {
    margin: 0;
    font-family: 'Inter', sans-serif;
    background: #f5f7fa;
    display: flex;
    flex-direction: column;
    min-height: 100vh;
}
header {
    background: linear-gradient(135deg,#ff7e5f,#feb47b);
    padding: 25px 0;
    text-align: center;
    color: #fff;
    font-size: 28px;
    font-weight: 600;
}
.wrapper {
    width: 70%;
    margin: 30px auto;
    flex: 1;
}
.card {
    background: #fff;
    padding: 35px 40px;
    margin-bottom: 30px;
    border-radius: 15px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
}
h2 { margin-bottom: 25px; text-align: center; }
label { display: block; font-weight: 600; color: #333; margin-bottom: 6px; }
input, select {
    width: 100%;
    padding: 14px;
    margin-bottom: 20px;
    border: 1px solid #ccc;
    border-radius: 10px;
    font-size: 15px;
}
input:focus, select:focus {
    border-color: #ff7e5f;
    outline: none;
    box-shadow: 0 0 6px rgba(255,126,95,0.4);
}
input[type=number]::-webkit-inner-spin-button,
input[type=number]::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
}
input[type=number] { -moz-appearance: textfield; }
button {
    width: 100%;
    padding: 16px;
    background: #ff7e5f;
    border: none;
    border-radius: 12px;
    color: #fff;
    font-size: 17px;
    cursor: pointer;
    font-weight: 600;
}
button:hover { background: #feb47b; transform: scale(1.03); }
.error { color: red; font-size: 14px; margin-top: -15px; margin-bottom: 15px; display: none; }
footer { background: #2d2d2d; color: #fff; text-align: center; padding: 18px; margin-top: auto; }
</style>
</head>

<body>
<header>E-Commerce Price & Shipping Calculator</header>
<div class="wrapper">
<div class="card">
<h2>Discount Calculator</h2>
<form id="calcForm" action="result.php" method="POST">

<label>Customer Name</label>
<input type="text" id="customer" name="customer" maxlength="20" placeholder="Alphabets & spaces only" autofocus>
<div class="error" id="customerError">Enter valid name (20 letters max, alphabets + spaces only)</div>

<label>Ordered Item</label>
<input type="text" id="item" name="item" maxlength="20" placeholder="Alphabets & spaces only">
<div class="error" id="itemError">Enter valid item name (20 letters max, alphabets + spaces only)</div>

<label>Purchase Amount (₹)</label>
<input type="number" id="amount" name="amount" min="100" max="100000" placeholder="100 - 100000">
<div class="error" id="amountError">Enter a number between 100 and 100000</div>

<label>Premium Member?</label>
<select id="member" name="member">
    <option value="">-- Select --</option>
    <option value="yes">Yes</option>
    <option value="no">No</option>
</select>
<div class="error" id="memberError">Select member status</div>
</div>

<div class="card">
<h2>Shipping Cost Calculator</h2>

<label>Destination</label>
<select id="destination" name="destination">
    <option value="">-- Select Destination --</option>
    <option value="domestic">Domestic</option>
    <option value="international">International</option>
</select>
<div class="error" id="destError">Select destination</div>

<label>Weight (kg)</label>
<input type="number" id="weight" name="weight" min="1" max="25" placeholder="1 - 25">
<div class="error" id="weightError">Enter weight between 1 and 25</div>

<button type="submit">Calculate Total</button>
</form>
</div>
</div>

<footer>© 2025 E-Commerce System | Designed with ❤️</footer>

<script>
$(document).ready(function(){
    $("#customer").focus();

    // Alphabets + spaces only
    $("#customer, #item").on("input", function(){ this.value=this.value.replace(/[^A-Za-z ]/g,''); });

    // Purchase amount limit
    $("#amount").on("input", function(){
        if(this.value>100000) this.value=100000;
        if(this.value<100) this.value=100;
    });

    // Weight limit
    $("#weight").on("input", function(){
        if(this.value>25) this.value=25;
        if(this.value<1) this.value=1;
    });

    $("#calcForm").submit(function(e){
        let valid=true;
        let customer=$("#customer").val().trim();
        let item=$("#item").val().trim();
        let amount=$("#amount").val();
        let member=$("#member").val();
        let dest=$("#destination").val();
        let weight=$("#weight").val();

        if(customer==="" || !/^[A-Za-z ]{1,20}$/.test(customer)){ $("#customerError").show(); valid=false; } else{ $("#customerError").hide(); }
        if(item==="" || !/^[A-Za-z ]{1,20}$/.test(item)){ $("#itemError").show(); valid=false; } else{ $("#itemError").hide(); }
        if(amount==="" || amount<100 || amount>100000){ $("#amountError").show(); valid=false; } else{ $("#amountError").hide(); }
        if(member===""){ $("#memberError").show(); valid=false; } else{ $("#memberError").hide(); }
        if(dest===""){ $("#destError").show(); valid=false; } else{ $("#destError").hide(); }
        if(weight==="" || weight<1 || weight>25){ $("#weightError").show(); valid=false; } else{ $("#weightError").hide(); }

        if(!valid) e.preventDefault();
    });
});
</script>
</body>
</html>
