<?php
// AJAX handler
if (isset($_POST['action']) && $_POST['action'] === 'checkHoliday') {

    // *** FUTURE HOLIDAYS ONLY ***
    $holidays = [
        "2025-12-25" => "Christmas",
        "2026-01-01" => "New Year 2026",
        "2026-01-14" =>  "pongal",
        "2026-01-26" => "Republic Day 2026",
        "2026-08-15" => "Independence Day 2026",
        "2026-10-02" => "Gandhi Jayanti 2026"
    ];

    $inputDate = $_POST['date'] ?? "";
    $result = [];

    if ($inputDate === "") {
        $result = ["status" => "error", "message" => "Please select a date."];
    } else {
        if (array_key_exists($inputDate, $holidays)) {
            $result = [
                "status" => "holiday",
                "holidayName" => $holidays[$inputDate],
                "date" => $inputDate
            ];
        } else {
            $result = [
                "status" => "notHoliday",
                "date" => $inputDate
            ];
        }
    }

    echo json_encode($result);
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Holiday Calendar Highlighter</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .card-module {
            max-width: 600px; margin: 40px auto;
            padding: 30px; border-radius: 18px;
            background: #f8f9fa;
            box-shadow: 0 12px 40px rgba(0,0,0,0.12);
        }
        h3 { color: #0d6efd; font-weight: 700; }
        .btn-modern {
            border-radius: 10px; font-weight: 600;
            width: 100%; margin-top: 10px;
            transition: .3s;
        }
        .btn-modern:hover { transform: scale(1.05); }
        .input-error { border-color: #dc3545 !important; }
        .error-msg { color: #dc3545; margin-top: 5px; font-size: 0.85rem; }
        .result-box {
            padding: 15px; border-radius: 12px;
            margin-top: 20px; font-weight: 600;
            text-align: center;
            font-size: 17px;
        }
        .holiday { background: #28a745; color: white; }
        .not-holiday { background: #dc3545; color: white; }
    </style>
</head>
<body>

<div class="container">
    <div class="card card-module">
        <h3>Holiday Calendar Highlighter</h3>
        <p class="text-secondary">Select a future date to check if it's a holiday.</p>

        <div>
            <label class="form-label fw-bold">Select Date</label>
            <?php 
                // Set min date = today for future restriction
                $today = date("Y-m-d"); 
            ?>
            <input type="date" class="form-control" id="inputDate" min="<?php echo $today; ?>">
            <div class="error-msg" id="dateError"></div>
        </div>

        <button class="btn btn-success btn-modern" id="checkBtn">Check Holiday</button>

        <div id="result"></div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<script>
$(document).ready(function () {

    // Focus on first field
    $("#inputDate").focus();

    $("#checkBtn").click(function () {
        let date = $("#inputDate").val().trim();

        $("#inputDate").removeClass("input-error");
        $("#dateError").text("");

        if (date === "") {
            $("#inputDate").addClass("input-error");
            $("#dateError").text("⚠ Please select a date.");
            return;
        }

        $.ajax({
            url: "<?php echo $_SERVER['PHP_SELF']; ?>",
            type: "POST",
            data: { action: "checkHoliday", date: date },
            success: function (response) {
                let data = JSON.parse(response);
                let html = "";

                if (data.status === "holiday") {
                    html = `
                        <div class='result-box holiday'>
                            🎉 <strong>${data.holidayName}</strong> is a Holiday!<br>
                            Date: ${data.date}
                        </div>`;
                }
                else if (data.status === "notHoliday") {
                    html = `
                        <div class='result-box not-holiday'>
                            ❌ ${data.date} is NOT a holiday.
                        </div>`;
                }

                $("#result").html(html);
            }
        });

    });

});
</script>

</body>
</html>
