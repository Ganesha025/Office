<?php
$analysis = [
    'words' => 0,
    'chars' => 0,
    'replaced_text' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $text = $_POST['textInput'] ?? '';
    $findWord = $_POST['findWord'] ?? '';
    $replaceWord = $_POST['replaceWord'] ?? '';

    // Word count
    $analysis['words'] = str_word_count($text);

    // Character count (excluding spaces)
    $analysis['chars'] = strlen(str_replace(' ', '', $text));

    // Find and replace
    if ($findWord) {
        $analysis['replaced_text'] = str_ireplace($findWord, $replaceWord ?: $findWord, $text);
    } else {
        $analysis['replaced_text'] = $text;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Text Analyzer</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<style>
body{font-family:'Inter',sans-serif}
.glass-card{background:rgba(255,255,255,0.95);backdrop-filter:blur(10px)}
.dark .glass-card{background:rgba(17,24,39,0.95)}
.stat-card{transition:all 0.3s ease;border-left:4px solid #3b82f6}
.stat-card:hover{transform:translateY(-2px);box-shadow:0 10px 25px -5px rgba(0,0,0,0.1)}
span{
    color:#15438dff;
}
</style>
</head>
<body class="bg-slate-50 dark:bg-slate-900 min-h-screen flex flex-col transition-colors duration-300">
<header class="glass-card shadow-lg border-b border-slate-200/80 dark:border-slate-700/80 sticky top-0 z-50">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
<div class="flex items-center justify-between h-16">
<div class="flex items-center gap-3">
<div class="w-12 h-12 bg-blue-600 rounded-2xl flex items-center justify-center shadow-lg">
<span class="material-icons text-white text-2xl">analytics</span>
</div>
<div>
<h1 class="text-2xl font-bold text-slate-900 dark:text-white">Text Analyzer</h1>
<p class="text-sm text-slate-600 dark:text-slate-400 hidden sm:block">Advanced Text Analysis Tool</p>
</div>
</div>
<div class="flex items-center gap-4">
<button onclick="toggleDark()" class="p-3 rounded-2xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-all duration-300">
<span class="material-icons text-slate-600 dark:text-slate-400">dark_mode</span>
</button>
</div>
</div>
</div>
</header>
<main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
<div class="grid lg:grid-cols-3 gap-8 mb-8">
<div class="lg:col-span-2">
<div class="glass-card p-8 rounded-3xl shadow-lg border border-slate-200/50 dark:border-slate-700/50">
<div class="mb-6">
<label class="block text-lg font-semibold mb-4 text-slate-900 dark:text-white flex items-center gap-3">
<span class="material-icons text-blue-600 text-2xl">edit_note</span>
<span>Enter Your Text</span>
</label>
<textarea id="textInput" rows="8" class="w-full p-6 border-2 border-slate-200 text-slate-900 dark:text-white dark:border-slate-700 dark:bg-slate-800 rounded-2xl focus:outline-none focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 bg-white resize-none placeholder-slate-400 dark:placeholder-slate-500" placeholder="Type or paste your text here..."></textarea>
</div>
<div class="grid md:grid-cols-2 gap-6 mb-8">
<div>
<label class="block text-sm font-semibold mb-3 text-slate-700 dark:text-slate-300 flex items-center gap-2">
<span class="material-icons text-blue-600">search</span>
<span>Find Word</span>
</label>
<input type="text" id="findWord" class="w-full p-4 border-2 border-slate-200 text-slate-900 dark:text-white dark:border-slate-700 dark:bg-slate-800 rounded-2xl focus:outline-none focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 bg-white placeholder-slate-400 dark:placeholder-slate-500" placeholder="Word to find">
</div>
<div>
<label class="block text-sm font-semibold mb-3 text-slate-700 dark:text-slate-300 flex items-center gap-2">
<span class="material-icons text-blue-600">swap_horiz</span>
<span>Replace With</span>
</label>
<input type="text" id="replaceWord" class="w-full p-4 border-2 border-slate-200 text-slate-900 dark:text-white dark:border-slate-700 dark:bg-slate-800 rounded-2xl focus:outline-none focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 bg-white placeholder-slate-400 dark:placeholder-slate-500" placeholder="Replacement word">
</div>
</div>
<button onclick="analyzeText()" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold text-lg py-4 px-8 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 flex items-center justify-center gap-3">
<span class="material-icons">analytics</span>
<span>Analyze Text</span>
</button>
</div>
</div>
<div class="lg:col-span-1">
<div class="glass-card p-8 rounded-3xl shadow-lg border border-slate-200/50 dark:border-slate-700/50 h-full">
<h2 class="text-xl font-bold mb-6 text-slate-900 dark:text-white flex items-center gap-3">
<span class="material-icons text-blue-600">info</span>
<span>Quick Stats</span>
</h2>
<div class="space-y-4">
<div class="stat-card bg-white dark:bg-slate-800 p-4 rounded-2xl border border-slate-200 dark:border-slate-700">
<div class="flex justify-between items-center">
<span class="text-slate-600 dark:text-slate-400 font-medium">Words Analyzed</span>
<span id="quickWords" class="text-2xl font-bold text-blue-600">0</span>
</div>
</div>
<div class="stat-card bg-white dark:bg-slate-800 p-4 rounded-2xl border border-slate-200 dark:border-slate-700">
<div class="flex justify-between items-center">
<span class="text-slate-600 dark:text-slate-400 font-medium">Characters</span>
<span id="quickChars" class="text-2xl font-bold text-green-600">0</span>
</div>
</div>
<div class="stat-card bg-white dark:bg-slate-800 p-4 rounded-2xl border border-slate-200 dark:border-slate-700">
<div class="flex justify-between items-center">
<span class="text-slate-600 dark:text-slate-400 font-medium">Readability</span>
<span id="quickRead" class="text-2xl font-bold text-purple-600">0</span>
</div>
</div>
</div>
</div>
</div>
</div>
<div id="results" class="hidden">
<div class="grid xl:grid-cols-2 gap-8 mb-8">
<div class="glass-card p-8 rounded-3xl shadow-lg border border-slate-200/50 dark:border-slate-700/50">
<h2 class="text-xl font-bold mb-6 text-slate-900 dark:text-white flex items-center gap-3">
<span class="material-icons text-blue-600">description</span>
<span>Original Text</span>
</h2>
<div class="bg-slate-50 dark:bg-slate-800 p-6 rounded-2xl border-2 border-slate-200 dark:border-slate-700 max-h-80 overflow-y-auto">
<p id="origText" class="whitespace-pre-line text-slate-700 dark:text-slate-300 text-base leading-relaxed"></p>
</div>
<button onclick="copyToClip('origText')" class="mt-6 w-full px-6 py-3 bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-300 rounded-2xl font-semibold transition-all duration-300 flex items-center justify-center gap-2">
<span class="material-icons">content_copy</span>
<span>Copy Text</span>
</button>
</div>
<div class="glass-card p-8 rounded-3xl shadow-lg border border-slate-200/50 dark:border-slate-700/50">
<h2 class="text-xl font-bold mb-6 text-slate-900 dark:text-white flex items-center gap-3">
<span class="material-icons text-blue-600">calculate</span>
<span>Detailed Statistics</span>
</h2>
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
<div class="stat-card bg-white dark:bg-slate-800 p-4 rounded-2xl border border-slate-200 dark:border-slate-700">
<div class="flex justify-between items-center">
<span class="text-slate-600 dark:text-slate-400 font-medium">Word Count</span>
<span id="statWords" class="text-xl font-bold text-blue-600">0</span>
</div>
</div>
<div class="stat-card bg-white dark:bg-slate-800 p-4 rounded-2xl border border-slate-200 dark:border-slate-700">
<div class="flex justify-between items-center">
<span class="text-slate-600 dark:text-slate-400 font-medium">Characters</span>
<span id="statChars" class="text-xl font-bold text-green-600">0</span>
</div>
</div>
<div class="stat-card bg-white dark:bg-slate-800 p-4 rounded-2xl border border-slate-200 dark:border-slate-700">
<div class="flex justify-between items-center">
<span class="text-slate-600 dark:text-slate-400 font-medium">With Spaces</span>
<span id="statSpace" class="text-xl font-bold text-purple-600">0</span>
</div>
</div>
<div class="stat-card bg-white dark:bg-slate-800 p-4 rounded-2xl border border-slate-200 dark:border-slate-700">
<div class="flex justify-between items-center">
<span class="text-slate-600 dark:text-slate-400 font-medium">Sentences</span>
<span id="statSent" class="text-xl font-bold text-orange-600">0</span>
</div>
</div>
<div class="stat-card bg-white dark:bg-slate-800 p-4 rounded-2xl border border-slate-200 dark:border-slate-700">
<div class="flex justify-between items-center">
<span class="text-slate-600 dark:text-slate-400 font-medium">Paragraphs</span>
<span id="statPara" class="text-xl font-bold text-red-600">0</span>
</div>
</div>
<div class="stat-card bg-white dark:bg-slate-800 p-4 rounded-2xl border border-slate-200 dark:border-slate-700">
<div class="flex justify-between items-center">
<span class="text-slate-600 dark:text-slate-400 font-medium">Avg Word Length</span>
<span id="statAvgWord" class="text-xl font-bold text-indigo-600">0</span>
</div>
</div>
<div class="stat-card bg-white dark:bg-slate-800 p-4 rounded-2xl border border-slate-200 dark:border-slate-700">
<div class="flex justify-between items-center">
<span class="text-slate-600 dark:text-slate-400 font-medium">Avg Sentence Length</span>
<span id="statAvgSent" class="text-xl font-bold text-pink-600">0</span>
</div>
</div>
<div class="stat-card bg-white dark:bg-slate-800 p-4 rounded-2xl border border-slate-200 dark:border-slate-700">
<div class="flex justify-between items-center">
<span class="text-slate-600 dark:text-slate-400 font-medium">Readability Score</span>
<span id="statRead" class="text-xl font-bold text-teal-600">0</span>
</div>
</div>
</div>
</div>
</div>
<div class="grid xl:grid-cols-2 gap-8 mb-8">
<div class="glass-card p-8 rounded-3xl shadow-lg border border-slate-200/50 dark:border-slate-700/50">
<h2 class="text-xl font-bold mb-6 text-slate-900 dark:text-white flex items-center gap-3">
<span class="material-icons text-blue-600">find_replace</span>
<span>Find & Replace</span>
</h2>
<div class="bg-slate-50 dark:bg-slate-800 p-6 rounded-2xl border-2 border-slate-200 dark:border-slate-700 max-h-80 overflow-y-auto">
<div id="replText" class="whitespace-pre-line text-slate-700 dark:text-slate-300 text-base leading-relaxed"></div>
</div>
<button onclick="copyToClip('replText')" class="mt-6 w-full px-6 py-3 bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-300 rounded-2xl font-semibold transition-all duration-300 flex items-center justify-center gap-2">
<span class="material-icons">content_copy</span>
<span>Copy Replaced Text</span>
</button>
</div>
<div class="glass-card p-8 rounded-3xl shadow-lg border border-slate-200/50 dark:border-slate-700/50">
<h2 class="text-xl font-bold mb-6 text-slate-900 dark:text-white flex items-center gap-3">
<span class="material-icons text-blue-600">text_format</span>
<span>Text Transforms</span>
</h2>
<div class="space-y-4">
<div class="bg-slate-50 dark:bg-slate-800 p-4 rounded-2xl border-2 border-slate-200 dark:border-slate-700">
<p class="text-xs font-semibold text-slate-500 dark:text-slate-400 mb-2">UPPERCASE</p>
<p id="upperText" class="text-sm text-slate-700 dark:text-slate-300 break-words"></p>
</div>
<div class="bg-slate-50 dark:bg-slate-800 p-4 rounded-2xl border-2 border-slate-200 dark:border-slate-700">
<p class="text-xs font-semibold text-slate-500 dark:text-slate-400 mb-2">lowercase</p>
<p id="lowerText" class="text-sm text-slate-700 dark:text-slate-300 break-words"></p>
</div>
<div class="bg-slate-50 dark:bg-slate-800 p-4 rounded-2xl border-2 border-slate-200 dark:border-slate-700">
<p class="text-xs font-semibold text-slate-500 dark:text-slate-400 mb-2">Title Case</p>
<p id="titleText" class="text-sm text-slate-700 dark:text-slate-300 break-words"></p>
</div>
</div>
</div>
</div>
<div class="glass-card p-8 rounded-3xl shadow-lg border border-slate-200/50 dark:border-slate-700/50">
<h2 class="text-xl font-bold mb-6 text-slate-900 dark:text-white flex items-center gap-3">
<span class="material-icons text-blue-600">bar_chart</span>
<span>Word Frequency Analysis</span>
</h2>
<div class="bg-slate-50 dark:bg-slate-800 p-6 rounded-2xl border-2 border-slate-200 dark:border-slate-700">
<canvas id="freqChart" height="300"></canvas>
</div>
</div>
</div>
</main>
<footer class="glass-card border-t border-slate-200/80 dark:border-slate-700/80 mt-12">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
<div class="grid md:grid-cols-4 gap-8 mb-8">
<div class="md:col-span-2">
<div class="flex items-center gap-3 mb-4">
<div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center">
<span class="material-icons text-white">analytics</span>
</div>
<h3 class="text-lg font-bold text-slate-900 dark:text-white">Text Analyzer</h3>
</div>
<p class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed">
Advanced text analysis tool providing comprehensive insights into your content. Analyze, transform, and visualize your text data with professional precision.
</p>
</div>
<div>
<h4 class="text-sm font-semibold text-slate-900 dark:text-white mb-4">Tools</h4>
<ul class="space-y-2 text-sm">
<li><a href="#" class="text-slate-600 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Word Counter</a></li>
<li><a href="#" class="text-slate-600 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Text Converter</a></li>
<li><a href="#" class="text-slate-600 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Readability Score</a></li>
<li><a href="#" class="text-slate-600 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">SEO Analyzer</a></li>
</ul>
</div>
<div>
<h4 class="text-sm font-semibold text-slate-900 dark:text-white mb-4">Support</h4>
<ul class="space-y-2 text-sm">
<li><a href="#" class="text-slate-600 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Documentation</a></li>
<li><a href="#" class="text-slate-600 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">API Reference</a></li>
<li><a href="#" class="text-slate-600 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Contact Us</a></li>
<li><a href="#" class="text-slate-600 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Feedback</a></li>
</ul>
</div>
</div>
<div class="pt-8 border-t border-slate-200 dark:border-slate-700">
<div class="flex flex-col md:flex-row items-center justify-between gap-4">
<div class="text-center md:text-left">
<p class="text-slate-600 dark:text-slate-400 text-sm">&copy; 2025 SavageInfo. All rights reserved.</p>
</div>
<div class="flex items-center gap-4">
<a href="#" class="text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
<span class="material-icons">info</span>
</a>
<a href="#" class="text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
<span class="material-icons">help</span>
</a>
<a href="#" class="text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
<span class="material-icons">settings</span>
</a>
</div>
</div>
</div>
</div>
</footer>
<script>
let chartInstance=null;
function toggleDark(){
document.documentElement.classList.toggle('dark');
localStorage.setItem('darkMode',document.documentElement.classList.contains('dark'));
if(chartInstance){
chartInstance.options.scales.y.grid.color=document.documentElement.classList.contains('dark')?'rgba(148,163,184,0.1)':'rgba(148,163,184,0.2)';
chartInstance.options.scales.y.ticks.color=document.documentElement.classList.contains('dark')?'rgba(148,163,184,0.6)':'rgba(148,163,184,1)';
chartInstance.options.scales.x.ticks.color=document.documentElement.classList.contains('dark')?'rgba(148,163,184,0.6)':'rgba(148,163,184,1)';
chartInstance.update();
}
}
if(localStorage.getItem('darkMode')==='true'){document.documentElement.classList.add('dark');}
function countWords(text){
return text.trim()?text.trim().split(/\s+/).length:0;
}
function countCharsNoSpaces(text){
return text.replace(/\s+/g,'').length;
}
function extractSentences(text){
return text.split(/[.!?]+/).filter(s=>s.trim());
}
function wordFrequency(text){
const stopwords=['the','and','a','an','of','in','to','is','it','for','on','with','as','by'];
const words=text.toLowerCase().replace(/[^\w\s]/g,'').split(/\s+/).filter(w=>w&&!stopwords.includes(w));
const freq={};
words.forEach(w=>{freq[w]=(freq[w]||0)+1;});
const sorted=Object.entries(freq).sort((a,b)=>b[1]-a[1]).slice(0,15);
return Object.fromEntries(sorted);
}
function toTitleCase(text){
return text.toLowerCase().replace(/\b\w/g,l=>l.toUpperCase());
}
function analyzeText(){
const text=document.getElementById('textInput').value;
if(!text.trim()){
alert('Please enter some text to analyze');
return;
}
const findWord=document.getElementById('findWord').value;
const replaceWord=document.getElementById('replaceWord').value;
const wordCount=countWords(text);
const charCount=countCharsNoSpaces(text);
const charCountSpace=text.length;
const sentences=extractSentences(text);
const sentenceCount=sentences.length;
const paragraphs=text.split(/\n+/).filter(p=>p.trim());
const paragraphCount=paragraphs.length;
const words=text.trim().split(/\s+/);
const avgWordLen=words.length?words.reduce((s,w)=>s+w.length,0)/words.length:0;
const avgSentenceLen=sentenceCount?wordCount/sentenceCount:0;
const syllables=(text.match(/[aeiouy]{1,2}/gi)||[]).length;
const readability=sentenceCount?206.835-1.015*(wordCount/sentenceCount)-84.6*(syllables/wordCount):0;
document.getElementById('quickWords').textContent=wordCount;
document.getElementById('quickChars').textContent=charCount;
document.getElementById('quickRead').textContent=readability.toFixed(1);
document.getElementById('statWords').textContent=wordCount;
document.getElementById('statChars').textContent=charCount;
document.getElementById('statSpace').textContent=charCountSpace;
document.getElementById('statSent').textContent=sentenceCount;
document.getElementById('statPara').textContent=paragraphCount;
document.getElementById('statAvgWord').textContent=avgWordLen.toFixed(2);
document.getElementById('statAvgSent').textContent=avgSentenceLen.toFixed(2);
document.getElementById('statRead').textContent=readability.toFixed(2);
document.getElementById('origText').textContent=text;
let replacedText=text;
if(findWord){
const regex=new RegExp(findWord.replace(/[.*+?^${}()|[\]\\]/g,'\\$&'),'gi');
replacedText=text.replace(regex,`<mark class="bg-yellow-200 dark:bg-yellow-600 px-1 rounded">${replaceWord||findWord}</mark>`);
}
document.getElementById('replText').innerHTML=replacedText;
document.getElementById('upperText').textContent=text.toUpperCase();
document.getElementById('lowerText').textContent=text.toLowerCase();
document.getElementById('titleText').textContent=toTitleCase(text);
const freq=wordFrequency(text);
const ctx=document.getElementById('freqChart').getContext('2d');
if(chartInstance){chartInstance.destroy();}
const isDark=document.documentElement.classList.contains('dark');
chartInstance=new Chart(ctx,{
type:'bar',
data:{
labels:Object.keys(freq),
datasets:[{
label:'Frequency',
data:Object.values(freq),
backgroundColor:'rgba(37,99,235,0.8)',
borderColor:'rgba(37,99,235,1)',
borderWidth:2,
borderRadius:8,
barPercentage:0.6
}]
},
options:{
responsive:true,
maintainAspectRatio:false,
plugins:{
legend:{display:false},
tooltip:{
backgroundColor:'rgba(17,24,39,0.9)',
padding:12,
cornerRadius:8,
titleFont:{size:14,weight:'bold'},
bodyFont:{size:13}
}
},
scales:{
y:{
beginAtZero:true,
grid:{color:isDark?'rgba(148,163,184,0.1)':'rgba(148,163,184,0.2)'},
ticks:{font:{size:11},color:isDark?'rgba(148,163,184,0.6)':'rgba(148,163,184,1)'}
},
x:{
grid:{display:false},
ticks:{font:{size:11},color:isDark?'rgba(148,163,184,0.6)':'rgba(148,163,184,1)'}
}
}
}
});
document.getElementById('results').classList.remove('hidden');
document.getElementById('results').scrollIntoView({behavior:'smooth',block:'nearest'});
}
function copyToClip(id){
const el=document.getElementById(id);
const text=el.innerText||el.textContent;
navigator.clipboard.writeText(text).then(()=>{
const btn=event.target.closest('button');
const orig=btn.innerHTML;
btn.innerHTML='<span class="material-icons">check</span><span>Copied!</span>';
btn.classList.remove('bg-slate-100','dark:bg-slate-700','hover:bg-slate-200','dark:hover:bg-slate-600');
btn.classList.add('bg-green-100','dark:bg-green-900','text-green-700','dark:text-green-300');
setTimeout(()=>{
btn.innerHTML=orig;
btn.classList.remove('bg-green-100','dark:bg-green-900','text-green-700','dark:text-green-300');
btn.classList.add('bg-slate-100','dark:bg-slate-700','hover:bg-slate-200','dark:hover:bg-slate-600');
},2000);
});
}
</script>
</body>
</html>