<?php
// Handle AJAX request
if (isset($_POST['action']) && $_POST['action'] === "generateTimetable") {
    $subjects = $_POST['subjects'] ?? [];
    $days = ['mon','tue','wed','thu','fri'];
    $errors = [];

    foreach ($days as $day) {
        for ($p = 0; $p < 5; $p++) {
            if (empty($subjects[$day][$p])) {
                $errors[$day][$p] = "Select subject";
            }
        }
    }

    if (!empty($errors)) {
        echo json_encode(['status'=>'error','errors'=>$errors]);
        exit;
    }

    $dayNames = ['mon'=>"Monday",'tue'=>"Tuesday",'wed'=>"Wednesday",'thu'=>"Thursday",'fri'=>"Friday"];
    $html = "<div class='table-responsive mt-3'><table class='table table-bordered text-center'>";
    $html .= "<thead class='table-primary'><tr><th>Day / Period</th>";
    for ($p = 1; $p <= 5; $p++) {
        $html .= "<th>Period $p</th>";
    }
    $html .= "</tr></thead><tbody>";

    foreach ($days as $day) {
        $html .= "<tr><th>{$dayNames[$day]}</th>";
        for ($p = 0; $p < 5; $p++) {
            $sub = htmlspecialchars($subjects[$day][$p]);
            $html .= "<td>$sub</td>";
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
    .card-module { margin:40px auto; padding:30px; border-radius:20px; background:#f8f9fa; max-width:900px; }
    .subject-select { width:100%; padding:6px; border-radius:8px; }
    .error-msg { color:#dc3545; font-size:0.85rem; margin-top:2px; min-height:18px; }
    .btn-modern { border-radius:10px; font-weight:600; margin-top:10px; width:100%; }
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
              <th>Period 1</th><th>Period 2</th><th>Period 3</th><th>Period 4</th><th>Period 5</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $days = ['mon','tue','wed','thu','fri'];
            $dayNames = ['mon'=>"Monday",'tue'=>"Tuesday",'wed'=>"Wednesday",'thu'=>"Thursday",'fri'=>"Friday"];
            $subjectsList = ['Tam','Eng','Math','Sci','Soc','Phy','Che','Computer'];
            foreach ($days as $day) {
              echo "<tr><th>{$dayNames[$day]}</th>";
              for ($p = 0; $p < 5; $p++) {
                echo "<td>";
                echo "<select class='form-select subject-select subject' data-day='$day' data-period='".(string)$p."'>";
                echo "<option value=''>Select</option>";
                foreach ($subjectsList as $sub) {
                  echo "<option value='".htmlspecialchars($sub)."'>$sub</option>";
                }
                echo "</select>";
                echo "<div class='error-msg'></div>";
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

      // Use event delegation for live validation
      $(document).on("change", ".subject", function(){
        let $sel = $(this);
        let val = $sel.val();
        let $err = $sel.closest('td').find('.error-msg');
        console.log("changed:", $sel.data("day"), $sel.data("period"), val);
        if (val && val !== "") {
          $err.text("");
        } else {
          $err.text("Select subject");
        }
      });

      $("#generateBtn").click(function(){
        let subjects = {}, valid = true;

        $(".subject").each(function(){
          let $sel = $(this);
          let day = $sel.data("day");
          let period = String($sel.data("period"));
          let val = $sel.val();
          if (!subjects[day]) subjects[day] = [];
          subjects[day][period] = val;

          let $err = $sel.closest('td').find('.error-msg');
          if (!val) {
            $err.text("Select subject");
            valid = false;
          } else {
            $err.text("");
          }
        });

        if (!valid) {
          $("#timetableResult").html("");
          return;
        }

        $.ajax({
          url: "<?php echo $_SERVER['PHP_SELF']; ?>",
          type: "POST",
          data: { action: "generateTimetable", subjects: subjects },
          success: function(res) {
            let data = JSON.parse(res);
            if (data.status === "success") {
              $("#timetableResult").html(data.result);
              $('html, body').animate({ scrollTop: $("#timetableResult").offset().top }, 500);
            }
          }
        });
      });

    });
  </script>
</body>
</html>
