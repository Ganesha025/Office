<?php
// Handle AJAX request for ranking students
if(isset($_POST['action']) && $_POST['action'] === 'rankStudents'){
    $students = $_POST['students'] ?? [];
    $results = [];

    foreach($students as $student){
        $name = trim($student['name'] ?? '');
        $score = intval($student['score'] ?? 0);
        $results[] = ['name'=>$name, 'score'=>$score];
    }

    // Sort by score DESC
    usort($results, function($a, $b){ return $b['score'] - $a['score']; });

    // Assign ranks
    $rank = 1;
    $prevScore = null;
    $sameRankCount = 0;

    foreach($results as $k => $student){
        if($prevScore !== null && $student['score'] == $prevScore){
            $results[$k]['rank'] = $rank;
            $sameRankCount++;
        } else {
            $rank += $sameRankCount;
            $results[$k]['rank'] = $rank;
            $rank++;
            $sameRankCount = 0;
        }
        $prevScore = $student['score'];
    }

    echo json_encode($results);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Rank Students</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
.card-module {
    max-width: 900px; margin: 40px auto; padding: 30px;
    border-radius: 20px; background: #f8f9fa;
    box-shadow: 0 12px 40px rgba(0,0,0,0.12);
}

.card-module h3 { color:#0d6efd; font-weight:700; }

.input-field { margin-bottom:10px; }

.input-error { border-color:#dc3545 !important; }

.error-msg {
    color:#dc3545;
    font-size:0.85rem;
    margin-bottom:5px;
}

.btn-modern {
    border-radius:10px; font-weight:600; width:100%;
    margin-top:10px; transition:0.3s;
}
.btn-modern:hover { transform:scale(1.05); }

.remove-btn {
    border:none; background:#dc3545; color:white;
    padding:4px 8px; border-radius:6px; cursor:pointer;
}
.remove-btn:hover { background:#b71c1c; }

.rank-box {
    margin-top:20px; padding:20px; background:#e9ffe9;
    border: 1px solid #28a745; border-radius:12px;
}
</style>

</head>
<body>

<div class="container">
  <div class="card card-module">

    <h3>Rank Students Based on Scores</h3>
    <p class="text-secondary">Enter student name & score. System will rank automatically.</p>

    <button class="btn btn-info btn-modern" id="addStudent">+ Add Student</button>

    <div class="table-responsive mt-3">
      <table class="table table-bordered text-center" id="studentTable">
        <thead class="table-primary">
          <tr>
            <th>Name</th>
            <th>Score (1–100)</th>
            <th>Action</th>
          </tr>
        </thead>

        <tbody>
          <tr>
            <td>
              <input type="text" class="form-control student-name" maxlength="20" placeholder="Enter Name">
              <div class="error-msg name-error"></div>
            </td>
            <td>
              <input type="text" class="form-control student-score" placeholder="1-100">
              <div class="error-msg score-error"></div>
            </td>
            <td>
              <button class="remove-btn">Remove</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <button class="btn btn-success btn-modern" id="rankStudents">Rank Students</button>

    <div id="result"></div>

  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<script>
$(document).ready(function(){

    $(".student-name:first").focus();

    // Name validation (A–Z and spaces only)
    $(document).on("input", ".student-name", function(){
        this.value = this.value.replace(/[^a-zA-Z ]/g,'').slice(0,20);
    });

    // Score validation (1–100, only 2 digits allowed)
    // Score validation (1–100 only)
$(document).on("input", ".student-score", function () {
    // Allow only numbers
    this.value = this.value.replace(/[^0-9]/g, '');

    // If empty, do nothing
    if (this.value === "") return;

    // Convert to int
    let val = parseInt(this.value);

    // Prevent typing more than 100
    if (val > 100) {
        this.value = "100";
    }

    // Prevent typing 0
    if (val === 0) {
        this.value = "";
    }
});

    // Add Student
    $("#addStudent").click(function(){
        $("#studentTable tbody").append(`
            <tr>
              <td>
                <input type="text" class="form-control student-name" maxlength="20" placeholder="Enter Name">
                <div class="error-msg name-error"></div>
              </td>
              <td>
                <input type="text" class="form-control student-score" placeholder="1-100">
                <div class="error-msg score-error"></div>
              </td>
              <td><button class="remove-btn">Remove</button></td>
            </tr>
        `);

        $(".student-name:last").focus();
    });

    // Remove student
    $(document).on("click", ".remove-btn", function(){
        $(this).closest("tr").remove();
    });

    // Rank Students
    $("#rankStudents").click(function(){
        let students = [];
        let hasError = false;

        $("#studentTable tbody tr").each(function(){

            let name = $(this).find(".student-name").val().trim();
            let score = $(this).find(".student-score").val().trim();

            $(this).find(".name-error").text("");
            $(this).find(".score-error").text("");
            $(this).find(".student-name").removeClass("input-error");
            $(this).find(".student-score").removeClass("input-error");

            if(name === ""){
                $(this).find(".name-error").text("⚠ Name required");
                $(this).find(".student-name").addClass("input-error");
                hasError = true;
            }

            if(score === "" || isNaN(score) || score < 1 || score > 100){
                $(this).find(".score-error").text("⚠ Score must be 1–100");
                $(this).find(".student-score").addClass("input-error");
                hasError = true;
            }

            students.push({name:name, score:score});
        });

        if(hasError) return;

        // AJAX call
        $.ajax({
            url: "<?php echo $_SERVER['PHP_SELF']; ?>",
            type: "POST",
            data: {action:'rankStudents', students:students},

            success:function(response){
                let data = JSON.parse(response);

                let output = `
                    <div class="rank-box">
                    <h5 class="text-success fw-bold">Student Rank List</h5>
                    <table class="table table-bordered text-center mt-3">
                    <thead class="table-success">
                      <tr><th>Rank</th><th>Name</th><th>Score</th></tr>
                    </thead><tbody>
                `;

                data.forEach(function(s){
                    output += `<tr>
                        <td>${s.rank}</td>
                        <td>${s.name}</td>
                        <td>${s.score}</td>
                    </tr>`;
                });

                output += "</tbody></table></div>";

                $("#result").html(output);

                // Clear data input rows
                $("#studentTable tbody").html("");
                $("#addStudent").click();
            }
        });

    });

});
</script>

</body>
</html>
