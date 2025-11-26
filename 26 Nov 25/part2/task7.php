<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Course List Management</title>
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="styles.css">
</head>
<body class="bg-green-50 min-h-screen flex items-center justify-center p-6">

<?php
session_start();
if(!isset($_SESSION['courses'])){
    $_SESSION['courses'] = [
        "CSE101"=>"Data Structures",
        "CSE102"=>"Algorithms",
        "CSE103"=>"Database Systems"
    ];
}

if($_SERVER['REQUEST_METHOD']=="POST"){
    if(isset($_POST['add_code'],$_POST['add_name'])){
        $code = strtoupper($_POST['add_code']);
        $name = $_POST['add_name'];
        if(preg_match('/^[A-Z]{3}\d{3,4}$/',$code) && preg_match('/^[A-Za-z ]*\d{0,3}$/',$name) && strlen($name)<=30)
            $_SESSION['courses'][$code]=$name;
    }
    if(isset($_POST['remove_code'])){
        $rCode = strtoupper($_POST['remove_code']);
        if(isset($_SESSION['courses'][$rCode])) unset($_SESSION['courses'][$rCode]);
    }
}
$courses = $_SESSION['courses'];
?>

<div class="w-full max-w-2xl bg-white rounded-2xl shadow-2xl p-6">
<h1 class="text-3xl font-bold text-center text-green-700 mb-6">Course List Management</h1>

<form method="POST" class="space-y-4 mb-4">
<div class="flex gap-2">
<input type="text" name="add_code" id="add_code" placeholder="Course Code (CSE101)" class="w-36 border border-gray-300 p-2 rounded uppercase" maxlength="7" required>
<input type="text" name="add_name" id="add_name" placeholder="Course Name" class="flex-1 border border-gray-300 p-2 rounded" maxlength="30" required>
<button type="submit" class="bg-green-600 text-white py-2 px-4 rounded font-semibold">Add Course</button>
</div>
</form>

<div class="mb-4 relative">
<input type="text" id="remove_code" name="remove_code" placeholder="Search & Remove Course" class="w-full p-2 border border-gray-300 rounded uppercase" autocomplete="off">
<div id="suggestions" class="absolute w-full bg-white border border-gray-300 rounded mt-1 max-h-40 overflow-y-auto hidden z-10"></div>
</div>

<form method="POST">
<button type="submit" id="removeBtn" class="bg-red-600 text-white py-2 px-4 rounded font-semibold mb-6 w-full">Remove Course</button>
</form>

<table class="w-full border-collapse border border-gray-300 text-center" id="courseTable">
<tr class="bg-green-100">
<th class="border border-gray-300 p-2">Course Code</th>
<th class="border border-gray-300 p-2">Course Name</th>
</tr>
<?php foreach($courses as $code=>$name): ?>
<tr class="odd:bg-white even:bg-green-50">
<td class="border border-gray-300 p-2"><?php echo $code; ?></td>
<td class="border border-gray-300 p-2"><?php echo $name; ?></td>
</tr>
<?php endforeach; ?>
</table>
</div>

<script>
// Restrict typing in Course Code input
$('#add_code, #remove_code').on('keypress', function(e){
    let val = $(this).val();
    let key = e.key.toUpperCase();
    if(val.length<3){
        if(!/[A-Z]/.test(key)) e.preventDefault();
    } else if(val.length<7){
        if(!/[0-9]/.test(key)) e.preventDefault();
    } else e.preventDefault();
});

// Restrict typing in Course Name input
$('#add_name').on('keypress', function(e){
    let key = e.key;
    let val = $(this).val();
    let digits = val.replace(/[^0-9]/g,'').length;
    if(!/[A-Za-z ]/.test(key) && !/[0-9]/.test(key)) e.preventDefault();
    if(/[0-9]/.test(key) && digits>=3) e.preventDefault();
    if(val.length>=30) e.preventDefault();
});

// Dynamic search/autocomplete in remove box
let courses = <?php echo json_encode($courses); ?>;
function updateSuggestions(){
    let html = '';
    $.each(courses,(code,name)=>{ html+=`<div class="p-2 hover:bg-green-100 cursor-pointer">${code}</div>`; });
    $('#suggestions').html(html);
}
updateSuggestions();

$('#remove_code').on('input', function(){
    let val = $(this).val().toUpperCase();
    $(this).val(val);
    let filtered = Object.keys(courses).filter(c=>c.includes(val));
    let html='';
    filtered.forEach(c=>{ html+=`<div class="p-2 hover:bg-green-100 cursor-pointer">${c}</div>`; });
    $('#suggestions').html(html).toggle(filtered.length>0);
});

$('#suggestions').on('click','div',function(){
    $('#remove_code').val($(this).text());
    $('#suggestions').hide();
});
</script>

</body>
</html>
