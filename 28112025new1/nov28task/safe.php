<?php
// TASK 5: Decrypt Message
$decrypted = "";
$shift = 3;
$encryptedMessage = "";

// Predefined messages for dropdown
$messages = [
    "lbh zhphv",
    "uryyb jbeyq",
    "fubrf vf frperg",
    "wklv lv d whvw",
    "pbzchgre vf zl anzr",
    "grfg zrffntr",
    "svyr vf sha"
];

if(isset($_POST['decrypt'])){
    $encryptedMessage = $_POST['message'];
    $shift = (int)$_POST['shift'];
    $decrypted = "";
    for($i=0; $i<strlen($encryptedMessage); $i++){
        $char = $encryptedMessage[$i];
        if($char >= 'a' && $char <= 'z'){
            $ascii = ord($char) - $shift;
            if($ascii < ord('a')) $ascii += 26;
            $decrypted .= chr($ascii);
        } elseif($char >= 'A' && $char <= 'Z'){
            $ascii = ord($char) - $shift;
            if($ascii < ord('A')) $ascii += 26;
            $decrypted .= chr($ascii);
        } else {
            $decrypted .= $char;
        }
    }
}

// TASK 2: Safe Houses
$safeHouses = ["Juarez", "El Paso", "Nogales"];

// TASK 3: Interrogation Questions
$interrogationQuestions = ["Where is the cartel leader?", "What is the mission objective?"];

// TASK 4: Mission Supplies with packing status
$missionSupplies = [
    ["name"=>"weapons", "packed"=>true],
    ["name"=>"communication gear", "packed"=>false],
    ["name"=>"medical supplies", "packed"=>true]
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Cartel Intelligence System</title>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

<header class="bg-gray-800 text-white text-center py-6 font-bold text-2xl">Cartel Intelligence Report</header>

<div class="container mx-auto my-10 px-4 space-y-10">

    <!-- TASK 2: Safe Houses -->
    <div class="bg-white p-6 rounded-2xl shadow-lg space-y-3">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Task 2: Safe House Locations</h2>
        <?php for($i=0; $i<count($safeHouses); $i++): ?>
            <div class="p-3 bg-gray-100 rounded-lg border-l-4 border-gray-800">
                Safe House <?= $i+1 ?>: <?= htmlspecialchars($safeHouses[$i]) ?>
            </div>
        <?php endfor; ?>
    </div>

    <!-- TASK 3: Interrogation Questions -->
    <div class="bg-white p-6 rounded-2xl shadow-lg space-y-3">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Task 3: Interrogation Questions</h2>
        <?php foreach($interrogationQuestions as $q): ?>
            <div class="p-3 bg-gray-100 rounded-lg border-l-4 border-yellow-600">
                Question: <?= htmlspecialchars($q) ?>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- TASK 4: Mission Supplies -->
    <div class="bg-white p-6 rounded-2xl shadow-lg space-y-3">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Task 4: Mission Supplies</h2>
        <?php
        $index = 0;
        $totalSupplies = count($missionSupplies);
        while($index < $totalSupplies):
            $item = $missionSupplies[$index];
        ?>
            <div class="p-3 bg-gray-100 rounded-lg border-l-4 border-green-600">
                <?= htmlspecialchars($item['name']) ?> → <strong><?= $item['packed'] ? "Packed" : "Not Packed" ?></strong>
            </div>
        <?php 
            $index++;
        endwhile; 
        ?>
    </div>

    <!-- TASK 5: Decrypt Message -->
    <div class="bg-white p-8 rounded-2xl shadow-lg space-y-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Task 5: Decrypt Cartel Message</h2>

        <form method="POST" class="space-y-6">

            <!-- Encrypted Message -->
            <div class="space-y-2">
                <label class="font-medium text-gray-700">
                    Encrypted Message: <span class="text-red-500">*</span>
                </label>
                <select name="message" id="messageSelect" class="w-full p-3 border rounded-lg" required>
                    <option value="" disabled selected>Select an encrypted message</option>
                    <?php foreach($messages as $msg): 
                        $selected = ($msg == $encryptedMessage) ? "selected" : "";
                    ?>
                        <option value="<?= htmlspecialchars($msg) ?>" <?= $selected ?>><?= htmlspecialchars($msg) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Shift -->
            <div class="space-y-2">
                <label class="font-medium text-gray-700">
                    Shift Value: <span class="text-red-500">*</span>
                </label>
                <select name="shift" class="w-full p-3 border rounded-lg" required>
                    <?php for($i=1;$i<=25;$i++): 
                        $selected = ($i==$shift) ? "selected" : "";
                    ?>
                        <option value="<?= $i ?>" <?= $selected ?>><?= $i ?></option>
                    <?php endfor; ?>
                </select>
            </div>

            <button type="submit" name="decrypt" class="w-full bg-gray-800 text-white py-3 rounded-lg font-semibold hover:bg-gray-900 transition">Decrypt Message</button>

        </form>

        <!-- Decrypted Output -->
        <?php if($decrypted != ""): ?>
            <div class="mt-6 p-4 bg-gray-100 rounded-lg text-gray-800 font-medium">
                <strong>Decrypted Message:</strong> <?= htmlspecialchars($decrypted) ?>
            </div>
        <?php endif; ?>
    </div>

</div>

<script>
$(document).ready(function(){
    // Focus on the encrypted message select input on page load
    $("#messageSelect").focus();
});
</script>

</body>
</html>
