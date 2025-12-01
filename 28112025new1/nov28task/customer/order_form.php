<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Customer Order Form</title>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-r from-pink-400 to-yellow-400 min-h-screen flex flex-col">

<!-- HEADER -->
<header class="bg-white shadow-md py-4">
    <div class="container mx-auto flex justify-between items-center px-4">
        <h1 class="text-xl font-bold text-pink-700">MyShop</h1>
        <nav>
            <a href="#" class="text-gray-700 hover:text-pink-700 mx-2">Home</a>
            <a href="#" class="text-gray-700 hover:text-pink-700 mx-2">Orders</a>
            <a href="#" class="text-gray-700 hover:text-pink-700 mx-2">Contact</a>
        </nav>
    </div>
</header>

<!-- MAIN FORM -->
<main class="flex-grow flex items-center justify-center py-10">
    <div class="bg-white rounded-2xl shadow-lg p-8 w-full max-w-3xl">
        <h2 class="text-2xl font-semibold text-center text-pink-700 mb-6">Customer Order Form</h2>

        <form id="orderForm" action="view_order.php" method="POST" class="space-y-4">

            <!-- Customer Name -->
            <div>
                <label class="block font-medium text-gray-700">
                    Customer Name <span class="text-red-600">*</span>
                </label>
                <input type="text" id="customer_name" name="customer_name" maxlength="20" autofocus
                       class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-pink-400">
                <div id="name_error" class="text-red-600 text-sm mt-1 hidden">
                    Only alphabets and spaces allowed (max 20 characters).
                </div>
            </div>

            <!-- Order Date -->
            <div>
                <label class="block font-medium text-gray-700">
                    Order Date <span class="text-red-600">*</span>
                </label>
                <input type="date" id="order_date" name="order_date" 
                       class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-pink-400">
                <div id="date_error" class="text-red-600 text-sm mt-1 hidden">
                    Select a valid date (today or past only).
                </div>
            </div>

            <!-- Items -->
            <h3 class="text-lg font-medium text-gray-700 mt-4">Order Items</h3>
            <div id="itemsContainer" class="space-y-2">
                <div class="flex gap-2">
                    <div class="flex-1">
                        <label class="block text-gray-700 text-sm">Item Name <span class="text-red-600">*</span></label>
                        <input type="text" name="item_name[]" class="itemName w-full p-2 border rounded-lg" placeholder="Item Name" maxlength="20">
                    </div>
                    <div class="flex-1">
                        <label class="block text-gray-700 text-sm">Print Details <span class="text-red-600">*</span></label>
                        <input type="text" name="item_print[]" class="itemPrint w-full p-2 border rounded-lg" placeholder="Print Details" maxlength="50">
                    </div>
                    <div class="flex items-end">
                        <button type="button" class="removeBtn bg-red-500 hover:bg-red-600 text-white px-4 rounded-lg">Remove</button>
                    </div>
                </div>
            </div>
            <div id="item_error" class="text-red-600 text-sm mt-1 hidden">
                Item Name: alphabets + spaces only (max 20). Print Details: max 50 characters.
            </div>

            <button type="button" id="addItem" class="bg-green-500 hover:bg-green-600 text-white px-5 py-2 rounded-lg font-semibold mt-2">+ Add Item</button>
            <button type="submit" id="submitBtn" class="w-full bg-pink-700 hover:bg-pink-800 text-white px-5 py-3 rounded-xl font-bold mt-4">Submit Order</button>
        </form>
    </div>
</main>

<!-- FOOTER -->
<footer class="bg-white shadow-inner py-6">
    <div class="container mx-auto text-center text-gray-600">
        &copy; <?= date('Y') ?> MyShop. All rights reserved.
    </div>
</footer>

<script>
$(document).ready(function(){
    $("#customer_name").focus();

    // Restrict customer name and item name to alphabets + spaces, max length 20
    $(document).on("input", "#customer_name, .itemName", function(){
        this.value = this.value.replace(/[^A-Za-z ]/g,'').slice(0,20);
    });

    // Restrict print details to max 50 characters
    $(document).on("input", ".itemPrint", function(){
        this.value = this.value.slice(0,50);
    });

    // Add new item row
    $("#addItem").click(function(){
        $("#itemsContainer").append(`
            <div class="flex gap-2">
                <div class="flex-1">
                    <label class="block text-gray-700 text-sm">Item Name <span class="text-red-600">*</span></label>
                    <input type="text" name="item_name[]" class="itemName w-full p-2 border rounded-lg" placeholder="Item Name" maxlength="20">
                </div>
                <div class="flex-1">
                    <label class="block text-gray-700 text-sm">Print Details <span class="text-red-600">*</span></label>
                    <input type="text" name="item_print[]" class="itemPrint w-full p-2 border rounded-lg" placeholder="Print Details" maxlength="50">
                </div>
                <div class="flex items-end">
                    <button type="button" class="removeBtn bg-red-500 hover:bg-red-600 text-white px-4 rounded-lg">Remove</button>
                </div>
            </div>
        `);
    });

    // Remove item row
    $(document).on("click", ".removeBtn", function(){
        $(this).parent().remove();
    });

    // Restrict order date to today or past
    let today = new Date().toISOString().split("T")[0];
    $("#order_date").attr("max", today);

    // Form validation
    $("#orderForm").submit(function(e){
        let valid = true;
        let name = $("#customer_name").val().trim();
        if(name === "" || !/^[A-Za-z ]+$/.test(name)){
            $("#name_error").removeClass("hidden");
            valid = false;
        } else { $("#name_error").addClass("hidden"); }

        let date = $("#order_date").val();
        if(date === "" || date > today){
            $("#date_error").removeClass("hidden");
            valid = false;
        } else { $("#date_error").addClass("hidden"); }

        $(".itemName").each(function(){
            if($(this).val().trim() === "" || !/^[A-Za-z ]+$/.test($(this).val())){
                $("#item_error").removeClass("hidden");
                valid = false;
            }
        });

        $(".itemPrint").each(function(){
            if($(this).val().length > 50){
                $("#item_error").removeClass("hidden");
                valid = false;
            }
        });

        if(!valid) e.preventDefault();
    });
});
</script>

</body>
</html>
