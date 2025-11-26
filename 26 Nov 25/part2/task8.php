<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Student Directory Search</title>
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="styles.css">
</head>
<body class="bg-indigo-50 min-h-screen flex items-center justify-center p-6">

<?php
$students = [
    "2025CSE001"=>["name"=>"Anu","department"=>"CSE","year"=>"2025","email"=>"anu@gmail.com","mobile"=>"9876543210"],
    "2025CSE002"=>["name"=>"Kavi","department"=>"CSE","year"=>"2025","email"=>"kavi@gmail.com","mobile"=>"9876543211"],
    "2025ECE003"=>["name"=>"Chandra","department"=>"ECE","year"=>"2025","email"=>"chandra@gmail.com","mobile"=>"9876543212"],
    "2025MEC004"=>["name"=>"Deepa","department"=>"MEC","year"=>"2025","email"=>"deepa@gmail.com","mobile"=>"9876543213"],
    "2025CIV005"=>["name"=>"Aisha","department"=>"CIV","year"=>"2025","email"=>"aisha@gmail.com","mobile"=>"9876543214"]
];
?>

<div class="w-full max-w-2xl bg-white rounded-2xl shadow-2xl p-6">
<h1 class="text-3xl font-bold text-center text-indigo-700 mb-6">Student Directory Search</h1>

<div class="relative">
<input type="text" id="searchReg" placeholder="Enter Registration No" class="w-full p-3 border border-gray-300 rounded uppercase mb-4" maxlength="10">
<div id="suggestions" class="absolute w-full bg-white border border-gray-300 rounded mt-1 max-h-40 overflow-y-auto hidden z-10"></div>
</div>

<div id="studentDetails" class="mt-4 text-center text-indigo-700 font-semibold"></div>

<table class="w-full border-collapse border border-gray-300 text-center mt-6">
<tr class="bg-indigo-100">
<th class="border border-gray-300 p-2">Reg No</th>
<th class="border border-gray-300 p-2">Name</th>
<th class="border border-gray-300 p-2">Department</th>
<th class="border border-gray-300 p-2">Year</th>
<th class="border border-gray-300 p-2">Email</th>
<th class="border border-gray-300 p-2">Mobile</th>
</tr>
<?php foreach($students as $reg=>$info): ?>
<tr class="odd:bg-white even:bg-indigo-50">
<td class="border border-gray-300 p-2"><?php echo $reg; ?></td>
<td class="border border-gray-300 p-2"><?php echo $info['name']; ?></td>
<td class="border border-gray-300 p-2"><?php echo $info['department']; ?></td>
<td class="border border-gray-300 p-2"><?php echo $info['year']; ?></td>
<td class="border border-gray-300 p-2"><?php echo $info['email']; ?></td>
<td class="border border-gray-300 p-2"><?php echo $info['mobile']; ?></td>
</tr>
<?php endforeach; ?>
</table>
</div>

<script>
let students = <?php echo json_encode($students); ?>;

function updateSuggestions(val){
    let filtered = Object.keys(students).filter(k => k.includes(val));
    if(filtered.length>0){
        let html = '';
        filtered.forEach(k => html+=`<div class="p-2 cursor-pointer hover:bg-indigo-100">${k}</div>`);
        $('#suggestions').html(html).show();
    } else $('#suggestions').hide();
}

$('#searchReg').on('input', function(){
    let val = $(this).val().toUpperCase();
    $(this).val(val);
    updateSuggestions(val);
    if(students[val]){
        let info = students[val];
        $('#studentDetails').html(
            `<p>Name: ${info.name}</p>
             <p>Department: ${info.department}</p>
             <p>Year: ${info.year}</p>
             <p>Email: ${info.email}</p>
             <p>Mobile: ${info.mobile}</p>`
        );
    } else {
        $('#studentDetails').text('Student Not Found');
    }
});

$('#suggestions').on('click','div',function(){
    let val = $(this).text();
    $('#searchReg').val(val);
    let info = students[val];
    $('#studentDetails').html(
        `<p>Name: ${info.name}</p>
         <p>Department: ${info.department}</p>
         <p>Year: ${info.year}</p>
         <p>Email: ${info.email}</p>
         <p>Mobile: ${info.mobile}</p>`
    );
    $('#suggestions').hide();
});

$('#searchReg').on('keypress', function(e){
    if(!/[A-Za-z0-9]/.test(e.key)) e.preventDefault();
});
</script>

</body>
</html>
