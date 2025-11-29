<?php
// Handle AJAX request
if (isset($_POST['action']) && $_POST['action'] === 'countFeedback') {

    $feedback = trim($_POST['feedback'] ?? "");

    if ($feedback === "") {
        echo json_encode([
            "status" => "error",
            "message" => "Feedback cannot be empty."
        ]);
        exit;
    }

    // Counting logic
    $wordCount = str_word_count($feedback);
    $charCount = strlen($feedback);
    $lineCount = substr_count($feedback, "\n") + 1;

    echo json_encode([
        "status" => "success",
        "words" => $wordCount,
        "chars" => $charCount,
        "lines" => $lineCount
    ]);
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Feedback Word Count</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #eef2ff, #d9e4f5);
            min-height: 100vh;
            padding-top: 40px;
        }

        .card-module {
            max-width: 750px;
            margin: 0 auto;
            padding: 35px;
            border-radius: 20px;
            background: white;
            box-shadow: 0 12px 40px rgba(0,0,0,0.12);
            animation: fadeIn 0.4s ease;
        }

        h3 {
            color: #0d6efd;
            font-weight: 800;
            letter-spacing: 1px;
            margin-bottom: 15px;
        }

        textarea {
            resize: vertical;
            min-height: 150px;
            border-radius: 12px !important;
            border: 2px solid #d0d7e1;
            transition: 0.3s;
        }

        textarea:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 8px rgba(13,110,253,0.3);
        }

        .btn-modern {
            border-radius: 10px;
            margin-top: 15px;
            width: 100%;
            padding: 12px;
            font-weight: 700;
            letter-spacing: .5px;
            background: #0d6efd;
            transition: 0.3s;
        }

        .btn-modern:hover {
            transform: scale(1.05);
            background: #0b5ed7;
            box-shadow: 0 8px 20px rgba(13,110,253,0.3);
        }

        .error-msg { 
            color: #dc3545; 
            font-size: 0.9rem; 
            margin-top: 8px; 
            font-weight: 500;
        }

        .result-box {
            margin-top: 25px;
            padding: 25px;
            background: #e8f1ff;
            border-left: 6px solid #0d6efd;
            border-radius: 12px;
            font-size: 1.15rem;
            animation: fadeIn 0.4s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to   { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>

<body>

<div class="container">
    <div class="card card-module">
        <h3>Student Feedback Word Count</h3>
        <p class="text-secondary">Enter your feedback below. System will count words, characters, and lines.</p>

        <!-- Feedback Input -->
        <textarea id="feedbackInput" class="form-control" placeholder="Type your feedback here..."></textarea>
        <div id="feedbackError" class="error-msg"></div>

        <!-- Button -->
        <button class="btn btn-primary btn-modern" id="countBtn">Count Feedback</button>

        <!-- Output -->
        <div id="result"></div>
    </div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<script>
$(document).ready(function() {

    // Focus on first field
    $("#feedbackInput").focus();

    $("#countBtn").click(function () {

        let feedback = $("#feedbackInput").val().trim();
        $("#feedbackError").text("");
        $("#result").html("");

        if (feedback === "") {
            $("#feedbackError").text("⚠ Feedback cannot be empty.");
            return;
        }

        $.ajax({
            url: "<?php echo $_SERVER['PHP_SELF']; ?>",
            type: "POST",
            data: {
                action: "countFeedback",
                feedback: feedback
            },
            success: function(response) {
                let data = JSON.parse(response);

                if (data.status === "error") {
                    $("#feedbackError").text("⚠ " + data.message);
                } else {
                    $("#result").html(`
                        <div class="result-box">
                            <p><strong>Words:</strong> ${data.words}</p>
                            <p><strong>Characters:</strong> ${data.chars}</p>
                            <p><strong>Lines:</strong> ${data.lines}</p>
                        </div>
                    `);
                }
            }
        });

    });

});
</script>

</body>
</html>
