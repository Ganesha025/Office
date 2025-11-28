<?php
$interrogation = [
    ["question" => "Where is the cartel leader?", "options" => ["Juarez", "El Paso", "Nogales", "Tijuana"]],
    ["question" => "What is the mission objective?", "options" => ["Intercept shipment", "Rescue hostage", "Gather intel", "Sabotage operations"]],
    ["question" => "Who are the other suspects?", "options" => ["Gang A", "Gang B", "Gang C", "Unknown"]],
    ["question" => "What is the timeline for the operation?", "options" => ["24 hours", "48 hours", "1 week", "Immediate"]],
    ["question" => "Where are the weapons stored?", "options" => ["Warehouse 1", "Abandoned house", "Dockyard", "Safehouse"]],
    ["question" => "Who are the cartel informants?", "options" => ["Police", "Civilians", "Rival cartel", "Unknown"]],
    ["question" => "What are the escape routes?", "options" => ["North road", "River path", "Airport", "Tunnel"]],
    ["question" => "Which locations are under surveillance?", "options" => ["Juarez", "El Paso", "Nogales", "All of the above"]],
    ["question" => "What is the communication protocol?", "options" => ["Radio", "Encrypted phones", "Messengers", "All of the above"]],
    ["question" => "What is the next planned operation?", "options" => ["Drug shipment", "Arms deal", "Territory expansion", "Unknown"]]
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tactical Interrogation System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>body{font-family:'Inter',sans-serif}</style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex flex-col">
    <header class="bg-slate-900 border-b border-slate-800 sticky top-0 z-50 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-3">
                    <span class="material-icons text-red-500 text-3xl">security</span>
                    <div>
                        <h1 class="text-lg sm:text-xl font-bold text-slate-100">Tactical Interrogation</h1>
                        <p class="text-xs text-slate-400 hidden sm:block">Classified Operation Protocol</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="hidden md:flex items-center gap-2 bg-slate-800 px-3 py-1.5 rounded-lg">
                        <span class="material-icons text-green-500 text-sm">check_circle</span>
                        <span class="text-xs font-medium">Active Session</span>
                    </div>
                    <button class="material-icons text-slate-400 hover:text-slate-100 transition">notifications</button>
                    <button class="material-icons text-slate-400 hover:text-slate-100 transition">account_circle</button>
                </div>
            </div>
        </div>
    </header>

    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
        <div class="bg-slate-900 border border-slate-800 rounded-xl shadow-2xl overflow-hidden">
            <div class="bg-slate-800 border-b border-slate-700 px-4 sm:px-6 py-4">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <span class="material-icons text-amber-500 text-2xl">quiz</span>
                        <h2 class="text-lg sm:text-xl font-bold text-slate-100">Intelligence Gathering Protocol</h2>
                    </div>
                    <div class="flex items-center gap-2 bg-slate-900 px-4 py-2 rounded-lg border border-slate-700">
                        <span class="material-icons text-blue-500 text-lg">assessment</span>
                        <span id="answeredCount" class="text-sm font-semibold text-slate-100">0 / 10 Completed</span>
                    </div>
                </div>
            </div>

            <form id="interrogationForm" class="p-4 sm:p-6 space-y-4">
                <?php foreach($interrogation as $index => $item): ?>
                <div class="bg-slate-800 border border-slate-700 rounded-lg p-4 sm:p-5 hover:border-slate-600 transition">
                    <div class="flex items-start gap-3 mb-3">
                        <div class="bg-red-500 text-white rounded-full w-8 h-8 flex items-center justify-center font-bold text-sm flex-shrink-0">
                            <?= $index + 1 ?>
                        </div>
                        <p class="font-semibold text-slate-100 text-sm sm:text-base leading-relaxed"><?= $item['question'] ?></p>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 ml-0 sm:ml-11">
                        <?php foreach($item['options'] as $option): ?>
                        <label class="flex items-center gap-2.5 bg-slate-900 border border-slate-700 rounded-lg px-4 py-3 cursor-pointer hover:bg-slate-850 hover:border-blue-500 transition group">
                            <input type="radio" name="q<?= $index ?>" value="<?= $option ?>" class="answerOption w-4 h-4 text-blue-500 border-slate-600 focus:ring-2 focus:ring-blue-500 focus:ring-offset-0">
                            <span class="text-sm text-slate-300 group-hover:text-slate-100"><?= $option ?></span>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endforeach; ?>
                
                <div class="flex gap-3 pt-4">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg transition flex items-center gap-2">
                        <span class="material-icons text-lg">send</span>
                        Submit Intelligence
                    </button>
                    <button type="button" onclick="resetForm()" class="bg-slate-700 hover:bg-slate-600 text-white font-semibold px-6 py-3 rounded-lg transition flex items-center gap-2">
                        <span class="material-icons text-lg">refresh</span>
                        Reset Form
                    </button>
                </div>
            </form>
        </div>
    </main>

    <footer class="bg-slate-900 border-t border-slate-800 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-2 text-slate-400 text-sm">
                    <span class="material-icons text-lg">shield</span>
                    <span>Classified System <span class="hidden sm:inline">• Top Secret Clearance Required</span></span>
                </div>
                <div class="flex items-center gap-6 text-slate-400 text-sm">
                    <a href="#" class="hover:text-slate-100 transition flex items-center gap-1">
                        <span class="material-icons text-sm">description</span>
                        <span>Documentation</span>
                    </a>
                    <a href="#" class="hover:text-slate-100 transition flex items-center gap-1">
                        <span class="material-icons text-sm">support</span>
                        <span>Support</span>
                    </a>
                    <a href="#" class="hover:text-slate-100 transition flex items-center gap-1">
                        <span class="material-icons text-sm">info</span>
                        <span>About</span>
                    </a>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-slate-800 text-center text-xs text-slate-500">
                © 2025 Tactical Operations Division • All Rights Reserved • v2.1.0
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('interrogationForm');
            const answeredCount = document.getElementById('answeredCount');
            const answerOptions = document.querySelectorAll('.answerOption');
            
            function updateCounter() {
                const answered = document.querySelectorAll('.answerOption:checked').length;
                answeredCount.textContent = `${answered} / 10 Completed`;
            }
            
            answerOptions.forEach(option => {
                option.addEventListener('change', updateCounter);
            });
            
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = {};
                const answers = document.querySelectorAll('.answerOption:checked');
                
                answers.forEach(input => {
                    formData[input.name] = input.value;
                });
                
                const answeredCount = Object.keys(formData).length;
                
                if (answeredCount === 10) {
                    alert('All intelligence gathered successfully! Mission data ready for analysis.');
                } else {
                    alert(`Intelligence partially gathered: ${answeredCount}/10 questions answered. Proceed with available data?`);
                }
                
                console.log('Collected Intelligence:', formData);
            });
        });
        
        function resetForm() {
            document.getElementById('interrogationForm').reset();
            document.getElementById('answeredCount').textContent = '0 / 10 Completed';
        }
    </script>
</body>
</html>