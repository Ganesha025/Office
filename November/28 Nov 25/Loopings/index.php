<?php
$safeHouses = ["Juarez", "El Paso", "Nogales"];
$missionSupplies = [
    ["item" => "Weapons", "isPacked" => true],
    ["item" => "Communication Gear", "isPacked" => false],
    ["item" => "Medical Supplies", "isPacked" => true]
];
$cartelMessage = "lbh zhphv";
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dynamic Cartel Mission</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
body { font-family: 'Inter', sans-serif; }
</style>
</head>
<body class="bg-neutral-950 text-neutral-100 min-h-screen flex flex-col">

<header class="bg-neutral-900 border-b border-neutral-800 sticky top-0 z-50">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <div class="flex items-center gap-3">
                <span class="material-icons text-red-500 text-3xl">shield</span>
                <h1 class="text-xl sm:text-2xl font-bold tracking-tight">Mission Control</h1>
            </div>
            <div class="flex items-center gap-4">
                <span class="material-icons text-neutral-400 hover:text-neutral-100 cursor-pointer hidden sm:block">notifications</span>
                <span class="material-icons text-neutral-400 hover:text-neutral-100 cursor-pointer">account_circle</span>
            </div>
        </div>
    </div>
</header>

<main class="flex-1 container mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
    <div class="max-w-4xl mx-auto">
        
        <div class="bg-neutral-900 border border-neutral-800 rounded-2xl p-6 sm:p-8 mb-6">
            <div class="flex items-center gap-3 mb-6">
                <span class="material-icons text-blue-500">location_on</span>
                <h2 class="text-xl sm:text-2xl font-semibold">Safe House Selection</h2>
            </div>
            <div id="houses" class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4">
                <?php foreach($safeHouses as $house): ?>
                    <button class="house-btn bg-neutral-800 hover:bg-neutral-700 border border-neutral-700 hover:border-blue-500 p-4 rounded-xl transition-all duration-200 flex items-center justify-center gap-2 text-sm sm:text-base font-medium" data-name="<?= $house ?>">
                        <span class="material-icons text-blue-400">home</span>
                        <?= $house ?>
                    </button>
                <?php endforeach; ?>
            </div>
            <div id="selectedHouse" class="mt-4 text-sm text-neutral-400"></div>
        </div>

        <div class="bg-neutral-900 border border-neutral-800 rounded-2xl p-6 sm:p-8 mb-6">
            <div class="flex items-center gap-3 mb-6">
                <span class="material-icons text-green-500">inventory_2</span>
                <h2 class="text-xl sm:text-2xl font-semibold">Mission Supplies</h2>
            </div>
            <div id="supplies" class="space-y-3">
                <?php foreach($missionSupplies as $supply): ?>
                    <label class="flex items-center gap-3 p-4 bg-neutral-800 border border-neutral-700 rounded-xl hover:border-green-500 cursor-pointer transition-all duration-200">
                        <input type="checkbox" class="supply w-5 h-5 rounded border-neutral-600 bg-neutral-700 text-green-600 focus:ring-2 focus:ring-green-500" data-name="<?= $supply['item'] ?>" <?= $supply['isPacked'] ? 'checked' : '' ?>>
                        <span class="flex-1 text-sm sm:text-base font-medium"><?= $supply['item'] ?></span>
                        <span class="material-icons text-neutral-500 text-xl">drag_indicator</span>
                    </label>
                <?php endforeach; ?>
            </div>
            <div id="selectedSupplies" class="mt-4 text-sm text-neutral-400"></div>
        </div>

        <div class="bg-neutral-900 border border-neutral-800 rounded-2xl p-6 sm:p-8 mb-6">
            <div class="flex items-center gap-3 mb-6">
                <span class="material-icons text-amber-500">lock</span>
                <h2 class="text-xl sm:text-2xl font-semibold">Encrypted Message</h2>
            </div>
            <div class="bg-neutral-800 border border-neutral-700 rounded-xl p-4 mb-4">
                <p class="text-xs text-neutral-500 mb-2 uppercase tracking-wide">Encrypted Data</p>
                <p id="encryptedMessage" class="font-mono text-sm sm:text-base text-amber-400"><?= $cartelMessage ?></p>
            </div>
            <button id="decryptBtn" class="w-full sm:w-auto bg-red-600 hover:bg-red-700 px-6 py-3 rounded-xl font-semibold flex items-center justify-center gap-2 transition-all duration-200">
                <span class="material-icons">vpn_key</span>
                Decrypt Message
            </button>
            <div id="decryptedMessage" class="mt-4 text-sm sm:text-base text-green-400 font-medium"></div>
        </div>

        <div id="weaponDiv" class="bg-red-600 border-2 border-red-500 rounded-2xl p-6 sm:p-8 text-center hidden animate-pulse">
            <div class="flex items-center justify-center gap-3 mb-2">
                <span class="material-icons text-4xl">military_tech</span>
            </div>
            <p class="text-xl sm:text-2xl font-bold">Weapon Unlocked</p>
            <p class="text-3xl sm:text-4xl font-black mt-2">AK-47</p>
        </div>

    </div>
</main>

<footer class="bg-neutral-900 border-t border-neutral-800 mt-auto">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
            <p class="text-sm text-neutral-500 text-center sm:text-left">© 2024 Mission Control. Classified Operations.</p>
            <div class="flex items-center gap-6">
                <a href="#" class="text-sm text-neutral-500 hover:text-neutral-100 transition-colors">Privacy</a>
                <a href="#" class="text-sm text-neutral-500 hover:text-neutral-100 transition-colors">Terms</a>
                <a href="#" class="text-sm text-neutral-500 hover:text-neutral-100 transition-colors">Support</a>
            </div>
        </div>
    </div>
</footer>

<script>
const houses = document.querySelectorAll(".house-btn");
const selectedHouse = document.getElementById("selectedHouse");
let houseChosen = false;

houses.forEach(btn => {
    btn.addEventListener("click", () => {
        houses.forEach(b => b.classList.remove("bg-blue-600", "border-blue-500"));
        btn.classList.add("bg-blue-600", "border-blue-500");
        selectedHouse.textContent = "✓ Selected: " + btn.dataset.name;
        selectedHouse.classList.add("text-blue-400");
        houseChosen = true;
        checkMissionComplete();
    });
});

const supplies = document.querySelectorAll(".supply");
const selectedSupplies = document.getElementById("selectedSupplies");

supplies.forEach(chk => {
    chk.addEventListener("change", () => {
        const selected = Array.from(supplies).filter(c => c.checked).map(c => c.dataset.name);
        selectedSupplies.textContent = selected.length > 0 ? "✓ Packed: " + selected.join(", ") : "";
        if(selected.length > 0) selectedSupplies.classList.add("text-green-400");
        checkMissionComplete();
    });
});

let decrypted = false;
document.getElementById("decryptBtn").addEventListener("click", () => {
    const encrypted = document.getElementById("encryptedMessage").textContent;
    const shift = 13;
    let decryptedMessage = "";

    for(let i=0; i<encrypted.length; i++){
        const char = encrypted[i];
        if(/[a-zA-Z]/.test(char)){
            const isUpper = char === char.toUpperCase();
            const asciiOffset = isUpper ? 65 : 97;
            decryptedMessage += String.fromCharCode((char.charCodeAt(0) - asciiOffset + shift) % 26 + asciiOffset);
        } else {
            decryptedMessage += char;
        }
    }
    document.getElementById("decryptedMessage").textContent = "✓ Decrypted: " + decryptedMessage;
    decrypted = true;
    checkMissionComplete();
});

function checkMissionComplete(){
    const allSuppliesChecked = Array.from(supplies).every(c => c.checked);
    if(houseChosen && allSuppliesChecked && decrypted){
        document.getElementById("weaponDiv").classList.remove("hidden");
    }
}
</script>

</body>
</html>