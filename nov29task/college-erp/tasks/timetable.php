<?php
// Handle AJAX request
if(isset($_POST['action']) && $_POST['action']=="generateTimetable"){
    $subjects = $_POST['subjects'] ?? [];

    // Validate inputs
    $errors = [];
    foreach($subjects as $day => $periods){
        foreach($periods as $p => $sub){
            if(empty($sub)){
                $errors[$day][$p] = "Select subject";
            }
        }
    }

    if(!empty($errors)){
        echo json_encode(['status'=>'error','errors'=>$errors]);
        exit;
    }

    // Generate timetable HTML table
    $days = ['Monday','Tuesday','Wednesday','Thursday','Friday'];
    $html = "<div class='table-responsive mt-3'><table class='table table-bordered text-center'>";
    $html .= "<thead class='table-primary'><tr><th>Day / Period</th>";
    for($p=1;$p<=5;$p++){
        $html .= "<th>Period $p</th>";
    }
    $html .= "</tr></thead><tbody>";

    foreach($days as $day){
        $html .= "<tr><th>$day</th>";
        for($p=0;$p<5;$p++){
            $html .= "<td>".$subjects[$day][$p]."</td>";
        }
        $html .= "</tr>";
    }

    $html .= "</tbody></table></div>";

    echo json_encode(['status'=>'success','result'=>$html]);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Class Timetable Generator</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
.card-module { margin:40px auto; padding:30px; border-radius:20px; background:#f8f9fa; max-width:900px; box-shadow:0 12px 40px rgba(0,0,0,0.12);}
.card-module h3 { color:#0d6efd; font-weight:700; display:flex; align-items:center; gap:12px; margin-bottom:20px; }
.subject-select { width:100%; padding:6px; border-radius:8px; }
.error-msg { color:#dc3545; font-size:0.85rem; margin-top:2px; }
.btn-modern { border-radius:10px; font-weight:600; transition:0.3s; margin-top:10px; width:100%; }
.btn-modern:hover { transform:scale(1.05); box-shadow:0 5px 15px rgba(0,0,0,0.15); }
</style>
</head>
<body>
<div class="container">
<div class="card card-module">
<h3>Class Timetable Generator (5×5)</h3>

<div class="table-responsive">
<table class="table table-bordered text-center" id="timetableInputsTable">
    <thead class="table-secondary">
        <tr>
            <th>Day / Period</th>
            <th>Period 1</th>
            <th>Period 2</th>
            <th>Period 3</th>
            <th>Period 4</th>
            <th>Period 5</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $days = ['Monday','Tuesday','Wednesday','Thursday','Friday'];
        $subjectsList = ['Tam','Eng','Math','Sci','Soc','Phy','Che','Computer'];

        foreach($days as $day){
            echo "<tr><th>$day</th>";
            for($p=0;$p<5;$p++){
                echo "<td>";
                echo "<select class='form-select subject-select subject' data-day='$day' data-period='$p'>";
                echo "<option value=''>Select</option>";
                foreach($subjectsList as $sub){
                    echo "<option value='$sub'>$sub</option>";
                }
                echo "</select>";
                echo "<div class='error-msg' data-day='$day' data-period='$p'></div>";
                echo "</td>";
            }
            echo "</tr>";
        }
        ?>
    </tbody>
</table>
</div>

<button class="btn btn-success btn-modern" id="generateBtn">Generate Timetable</button>
<div id="timetableResult"></div>
</div>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
$(document).ready(function(){
    $(".subject:first").focus();

    $("#generateBtn").click(function(){
        let subjects = {}, valid=true;

        $(".subject").each(function(){
            let day = $(this).data('day');
            let period = $(this).data('period');
            let val = $(this).val();

            if(!subjects[day]) subjects[day] = [];
            subjects[day][period] = val;

            if(val==""){
                $(`.error-msg[data-day='${day}'][data-period='${period}']`).text("Select subject");
                valid=false;
            } else {
                $(`.error-msg[data-day='${day}'][data-period='${period}']`).text("");
            }
        });

        if(!valid){ $("#timetableResult").html(""); return; }

        $.ajax({
            url:"<?php echo $_SERVER['PHP_SELF']; ?>",
            type:"POST",
            data:{action:"generateTimetable", subjects:subjects},
            success:function(res){
                let data = JSON.parse(res);
                if(data.status=="success"){
                    $("#timetableResult").html(data.result);
                    $('html, body').animate({scrollTop: $("#timetableResult").offset().top}, 500);
                }
            }
        });
    });
});
</script>
</body>
</html>
