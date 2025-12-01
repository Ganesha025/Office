<?php
session_start();

// Initialize variables
$text = $wordToFind = $wordToReplace = "";
$wordCount = $charCount = 0;
$uppercaseText = "";
$sentences = [];
$wordFrequency = [];

// Initialize error messages
$textError = $findError = $replaceError = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input
    $text = preg_replace("/[^a-zA-Z ,\n]/", "", $_POST['text']); 
    $wordToFind = preg_replace("/[^a-zA-Z ]/", "", $_POST['find']); 
    $wordToReplace = preg_replace("/[^a-zA-Z ]/", "", $_POST['replace']); 

    // Validate text
    if (empty(trim($text))) {
        $textError = "Please enter text (max 250 words).";
    } else {
        $wordCount = str_word_count($text);
        if ($wordCount > 250) {
            $textError = "Text cannot exceed 250 words.";
        }
    }

    // Validate word to find
    if (empty(trim($wordToFind))) {
        $findError = "Please enter a word to find (max 20 chars).";
    } elseif (strlen($wordToFind) > 20) {
        $findError = "Word to find cannot exceed 20 characters.";
    } elseif (!empty($text) && stripos($text, $wordToFind) === false) {
        $findError = "The word to find does not exist in the text.";
    }

    // Validate word to replace
    if (empty(trim($wordToReplace))) {
        $replaceError = "Please enter a word to replace (max 20 chars).";
    } elseif (strlen($wordToReplace) > 20) {
        $replaceError = "Word to replace cannot exceed 20 characters.";
    }

    // Only process analysis if no errors
    if (!$textError && !$findError && !$replaceError) {
        // Character count excluding spaces
        $charCount = strlen(str_replace(' ', '', $text));

        // Find & replace
        $replacedText = str_ireplace($wordToFind, $wordToReplace, $text);

        // Uppercase
        $uppercaseText = strtoupper($text);

        // Extract sentences
        $sentences = preg_split('/(?<=[.?!])\s+/', $text, -1, PREG_SPLIT_NO_EMPTY);

        // Word frequency
        $words = str_word_count(strtolower($text), 1);
        $wordFrequency = array_count_values($words);
        arsort($wordFrequency);

        // Store results in session for PRG
        $_SESSION['results'] = [
            'text' => $text,
            'replacedText' => $replacedText,
            'uppercaseText' => $uppercaseText,
            'sentences' => $sentences,
            'wordFrequency' => $wordFrequency,
            'wordCount' => $wordCount,
            'charCount' => $charCount
        ];

        // Redirect to prevent form resubmission
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}

// Retrieve results after redirect
if (isset($_SESSION['results'])) {
    $results = $_SESSION['results'];
    $text = $results['text'];
    $replacedText = $results['replacedText'];
    $uppercaseText = $results['uppercaseText'];
    $sentences = $results['sentences'];
    $wordFrequency = $results['wordFrequency'];
    $wordCount = $results['wordCount'];
    $charCount = $results['charCount'];
    unset($_SESSION['results']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Text Analyzer</title>
<script src="https://cdn.tailwindcss.com"></script>
<script>
window.onload = function() {
    document.getElementById('textInput').focus();
};

// Real-time validation functions
function validateText() {
    let textInput = document.getElementById('textInput');
    let errorMsg = document.getElementById('textErrorMsg');
    let wordCountMsg = document.getElementById('wordCountMsg');

    let text = textInput.value.replace(/[^a-zA-Z ,\n]/g, '');
    let words = text.trim().split(/\s+/).filter(w => w.length > 0);

    if(words.length > 250){
        words = words.slice(0, 250);
        text = words.join(" ");
        errorMsg.textContent = "Text cannot exceed 250 words.";
        textInput.classList.add("border-red-500");
        textInput.classList.remove("border-gray-300");
    } else if(words.length === 0) {
        errorMsg.textContent = "Please enter text (max 250 words).";
        textInput.classList.add("border-red-500");
        textInput.classList.remove("border-gray-300");
    } else {
        errorMsg.textContent = "";
        textInput.classList.remove("border-red-500");
        textInput.classList.add("border-gray-300");
    }

    textInput.value = text;
    wordCountMsg.textContent = "Words: " + words.length + "/250";
}

function validateFindWord() {
    let findInput = document.getElementById('findInput');
    let textInput = document.getElementById('textInput');
    let errorMsg = document.getElementById('findErrorMsg');

    findInput.value = findInput.value.replace(/[^a-zA-Z ]/g, '').substring(0,20);

    if(findInput.value.trim() === "") {
        errorMsg.textContent = "Please enter a word to find (max 20 chars).";
        findInput.classList.add("border-red-500");
        findInput.classList.remove("border-gray-300");
    } else {
        let text = textInput.value.toLowerCase();
        let word = findInput.value.toLowerCase();
        if (!text.includes(word)) {
            errorMsg.textContent = "The word does not exist in the entered text.";
            findInput.classList.add("border-red-500");
            findInput.classList.remove("border-gray-300");
        } else {
            errorMsg.textContent = "";
            findInput.classList.remove("border-red-500");
            findInput.classList.add("border-gray-300");
        }
    }
}

function validateReplaceWord() {
    let replaceInput = document.getElementById('replaceInput');
    let errorMsg = document.getElementById('replaceErrorMsg');

    replaceInput.value = replaceInput.value.replace(/[^a-zA-Z ]/g, '').substring(0,20);

    if(replaceInput.value.trim() === "") {
        errorMsg.textContent = "Please enter a word to replace (max 20 chars).";
        replaceInput.classList.add("border-red-500");
        replaceInput.classList.remove("border-gray-300");
    } else {
        errorMsg.textContent = "";
        replaceInput.classList.remove("border-red-500");
        replaceInput.classList.add("border-gray-300");
    }
}
</script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col items-center justify-start py-10">

<div class="bg-white rounded-xl shadow-lg p-8 w-full max-w-4xl">
    <h1 class="text-3xl font-bold mb-6 text-center text-indigo-700">Simple Text Analyzer</h1>

    <form method="post" class="space-y-6" novalidate>
        <!-- Text area -->
        <div>
            <label class="block font-semibold mb-2 text-gray-700">
                Enter Text <span class="text-red-500">*</span> (Max 250 words):
            </label>
            <textarea id="textInput" name="text" rows="5"
                      class="w-full p-4 border-2 border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400 bg-gradient-to-r from-white to-gray-50 transition-all hover:shadow-md"
                      oninput="validateText()"><?php echo htmlspecialchars($text); ?></textarea>
            <p id="wordCountMsg" class="text-sm text-gray-500 mt-1">Words: <?php echo $wordCount; ?>/250</p>
            <p id="textErrorMsg" class="text-red-600 text-sm mt-1"><?php echo $textError; ?></p>
        </div>

        <!-- Word to Find & Replace -->
        <div class="grid grid-cols-2 gap-6">
            <div>
                <label class="block font-semibold mb-2 text-gray-700">
                    Word to Find <span class="text-red-500">*</span> (Max 20 chars):
                </label>
                <input type="text" id="findInput" name="find"
                       class="w-full p-3 border-2 border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-green-400 bg-gradient-to-r from-white to-gray-50 transition-all hover:shadow-md"
                       oninput="validateFindWord()" value="<?php echo htmlspecialchars($wordToFind); ?>">
                <p id="findErrorMsg" class="text-red-600 text-sm mt-1"><?php echo $findError; ?></p>
            </div>
            <div>
                <label class="block font-semibold mb-2 text-gray-700">
                    Replace With <span class="text-red-500">*</span> (Max 20 chars):
                </label>
                <input type="text" id="replaceInput" name="replace"
                       class="w-full p-3 border-2 border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-red-400 focus:border-red-400 bg-gradient-to-r from-white to-gray-50 transition-all hover:shadow-md"
                       oninput="validateReplaceWord()" value="<?php echo htmlspecialchars($wordToReplace); ?>">
                <p id="replaceErrorMsg" class="text-red-600 text-sm mt-1"><?php echo $replaceError; ?></p>
            </div>
        </div>

        <button type="submit"
                class="w-full bg-gradient-to-r from-blue-500 to-indigo-500 text-white font-bold p-3 rounded-lg shadow-lg hover:from-blue-600 hover:to-indigo-600 transition-all">
            Analyze Text
        </button>
    </form>

    <!-- Analysis Results -->
    <?php if (!empty($text) && !empty($replacedText)) : ?>
        <div class="mt-8 space-y-6">

            <div class="p-4 bg-blue-50 rounded-lg shadow">
                <h2 class="font-semibold text-blue-700 mb-2">Original Text:</h2>
                <p class="whitespace-pre-line"><?php echo htmlspecialchars($text); ?></p>
            </div>

            <div class="p-4 bg-green-50 rounded-lg shadow flex justify-between">
                <span class="font-semibold text-green-700">Word Count:</span>
                <span><?php echo $wordCount; ?></span>
            </div>

            <div class="p-4 bg-yellow-50 rounded-lg shadow flex justify-between">
                <span class="font-semibold text-yellow-700">Character Count (excluding spaces):</span>
                <span><?php echo $charCount; ?></span>
            </div>

            <div class="p-4 bg-purple-50 rounded-lg shadow">
                <h2 class="font-semibold text-purple-700 mb-2">Text after Find & Replace:</h2>
                <p class="whitespace-pre-line"><?php echo htmlspecialchars($replacedText); ?></p>
            </div>

            <div class="p-4 bg-pink-50 rounded-lg shadow">
                <h2 class="font-semibold text-pink-700 mb-2">Uppercase Text:</h2>
                <p class="whitespace-pre-line"><?php echo htmlspecialchars($uppercaseText); ?></p>
            </div>

            <div class="p-4 bg-indigo-50 rounded-lg shadow">
                <h2 class="font-semibold text-indigo-700 mb-2">Extracted Sentences:</h2>
                <ul class="list-disc pl-5">
                    <?php foreach ($sentences as $sentence) : ?>
                        <li><?php echo htmlspecialchars($sentence); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="p-4 bg-gray-50 rounded-lg shadow max-h-40 overflow-auto">
                <h2 class="font-semibold text-gray-700 mb-2">Word Frequency:</h2>
                <div class="flex flex-wrap gap-4">
                    <?php foreach ($wordFrequency as $word => $freq) : ?>
                        <span class="bg-gray-200 rounded px-2 py-1 text-sm"><?php echo htmlspecialchars($word) . ": " . $freq; ?></span>
                    <?php endforeach; ?>
                </div>
            </div>

        </div>
    <?php endif; ?>

</div>

</body>
</html>
