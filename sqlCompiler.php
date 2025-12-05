<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "test";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$result_html = "";
$error_msg = "";

if (isset($_POST['query'])) {
    $query = $_POST['query'];
    if (preg_match('/\bDROP\s+DATABASE\b/i', $query)) {
        $error_msg = "DROP DATABASE queries are not allowed!";
    } else {
        if ($conn->multi_query($query)) {
            do {
                if ($result = $conn->store_result()) {
                    $result_html .= "<div class='overflow-x-auto mb-4'><table class='min-w-full bg-white dark:bg-slate-800 border border-sky-200 dark:border-sky-700'>";
                    $result_html .= "<thead class='bg-sky-600 dark:bg-sky-800 text-white'>";
                    $result_html .= "<tr>";
                    while ($field = $result->fetch_field()) {
                        $result_html .= "<th class='px-4 py-2 text-left text-sm font-medium border border-sky-300 dark:border-sky-600'>{$field->name}</th>";
                    }
                    $result_html .= "</tr></thead><tbody>";
                    while ($row = $result->fetch_assoc()) {
                        $result_html .= "<tr class='hover:bg-sky-50 dark:hover:bg-slate-700 transition-colors duration-150'>";
                        foreach ($row as $cell) {
                            $result_html .= "<td class='px-4 py-2 border border-sky-200 dark:border-sky-700 text-sm text-gray-900 dark:text-gray-100'>{$cell}</td>";
                        }
                        $result_html .= "</tr>";
                    }
                    $result_html .= "</tbody></table></div>";
                    $result->free();
                } else {
                    $result_html .= "<div class='p-3 mb-4 bg-sky-50 dark:bg-sky-900 text-sky-700 dark:text-sky-100 border border-sky-200 dark:border-sky-700 rounded-lg'>Query executed successfully. Affected rows: " . $conn->affected_rows . "</div>";
                }
            } while ($conn->more_results() && $conn->next_result());
        } else {
            $error_msg = "Query Error: " . $conn->error;
        }
    }
}

$tables = [];
$tables_result = $conn->query("SHOW TABLES");
if ($tables_result) {
    while ($row = $tables_result->fetch_array()) {
        $tables[] = $row[0];
    }
}

$schema = [];
foreach ($tables as $table) {
    $cols_result = $conn->query("SHOW COLUMNS FROM `$table`");
    $columns = [];
    while ($col = $cols_result->fetch_assoc()) {
        $columns[] = $col['Field'];
    }
    $schema[$table] = $columns;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ZenSQL</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class'
        }
    </script>
    <style>
        *{outline:none;transition:all .15s ease}
        input[type="number"]::-webkit-outer-spin-button,input[type="number"]::-webkit-inner-spin-button{-webkit-appearance:none;margin:0}
        textarea{scrollbar-width:none;-ms-overflow-style:none}
        input[type="number"]{-moz-appearance:textfield}
        input[type="search"]::-webkit-search-cancel-button,input[type="search"]::-webkit-search-decoration,input[type="search"]::-webkit-search-results-button,input[type="search"]::-webkit-search-results-decoration{display:none}
        textarea,#suggestions{scrollbar-width:none;-ms-overflow-style:none;overflow:auto}
        textarea::-webkit-scrollbar,#suggestions::-webkit-scrollbar{display:none}
        .rotate-180{transform:rotate(180deg)}
        .editor-container{position:relative}
        #queryInput{background:transparent;position:relative;z-index:2;color:transparent;caret-color:#1e293b;resize:none}
        .dark #queryInput{caret-color:#e2e8f0}
        #highlight{position:absolute;top:0;left:0;padding:1rem;white-space:pre-wrap;word-wrap:break-word;pointer-events:none;z-index:1;font-family:monospace;font-size:0.875rem;line-height:1.25rem;overflow:hidden}
        .sql-keyword{color:#dc2626;font-weight:600}
        .dark .sql-keyword{color:#f87171}
    </style>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        const sqlKeywords = [
            'SELECT', 'FROM', 'WHERE', 'INSERT', 'UPDATE', 'DELETE', 'CREATE', 'ALTER', 'DROP', 
            'TABLE', 'DATABASE', 'INDEX', 'VIEW', 'TRIGGER', 'PROCEDURE', 'FUNCTION', 'INTO', 
            'VALUES', 'SET', 'JOIN', 'INNER', 'LEFT', 'RIGHT', 'FULL', 'OUTER', 'CROSS', 'ON', 
            'AND', 'OR', 'NOT', 'IN', 'BETWEEN', 'LIKE', 'IS', 'NULL', 'AS', 'DISTINCT', 'ALL', 
            'ORDER', 'BY', 'ASC', 'DESC', 'GROUP', 'HAVING', 'LIMIT', 'OFFSET', 'UNION', 'INTERSECT', 
            'EXCEPT', 'EXISTS', 'CASE', 'WHEN', 'THEN', 'ELSE', 'END', 'PRIMARY', 'KEY', 'FOREIGN', 
            'REFERENCES', 'UNIQUE', 'CHECK', 'DEFAULT', 'AUTO_INCREMENT', 'NOT NULL', 'CONSTRAINT', 
            'ADD', 'MODIFY', 'COLUMN', 'RENAME', 'TRUNCATE', 'CASCADE', 'RESTRICT', 'GRANT', 'REVOKE', 
            'COMMIT', 'ROLLBACK', 'SAVEPOINT', 'TRANSACTION', 'BEGIN', 'START', 'COUNT', 'SUM', 'AVG', 
            'MIN', 'MAX', 'CONCAT', 'SUBSTRING', 'UPPER', 'LOWER', 'TRIM', 'LENGTH', 'COALESCE', 
            'CAST', 'CONVERT', 'DATE', 'TIME', 'DATETIME', 'TIMESTAMP', 'YEAR', 'MONTH', 'DAY', 
            'HOUR', 'MINUTE', 'SECOND', 'NOW', 'CURDATE', 'CURTIME', 'INT', 'VARCHAR', 'TEXT', 
            'CHAR', 'DECIMAL', 'FLOAT', 'DOUBLE', 'BOOLEAN', 'BLOB', 'ENUM', 'IF', 'IFNULL', 
            'NULLIF', 'REPLACE', 'SHOW', 'DESCRIBE', 'DESC', 'EXPLAIN', 'USE', 'WITH', 'RECURSIVE',
            'PARTITION', 'OVER', 'ROW_NUMBER', 'RANK', 'DENSE_RANK', 'LEAD', 'LAG', 'FIRST_VALUE',
            'LAST_VALUE', 'NTILE', 'WINDOW', 'ROWS', 'RANGE', 'PRECEDING', 'FOLLOWING', 'UNBOUNDED',
            'CURRENT', 'ROWS BETWEEN', 'RANGE BETWEEN'
        ];

        const schema = <?php echo json_encode($schema); ?>;
        let allSuggestions = [];

        function initSuggestions() {
            allSuggestions = [...sqlKeywords];
            for (const table in schema) {
                allSuggestions.push(table);
                allSuggestions = allSuggestions.concat(schema[table]);
            }
        }

        let currentSuggestions = [];
        let selectedIndex = -1;

        function highlightSQL(text) {
            const pattern = new RegExp('\\b(' + sqlKeywords.join('|') + ')\\b', 'gi');
            return text.replace(/[<>&]/g, c => ({'<':'&lt;','>':'&gt;','&':'&amp;'}[c]))
                       .replace(pattern, '<span class="sql-keyword">$1</span>');
        }

        function syncScroll() {
            const input = document.getElementById('queryInput');
            const highlight = document.getElementById('highlight');
            highlight.scrollTop = input.scrollTop;
            highlight.scrollLeft = input.scrollLeft;
        }

        function updateHighlight() {
            const input = document.getElementById('queryInput');
            const highlight = document.getElementById('highlight');
            const text = input.value + '\n';
            highlight.innerHTML = highlightSQL(text);
        }

        function getCaretCoordinates(element) {
            const div = document.createElement('div');
            const style = getComputedStyle(element);
            ['position', 'top', 'left', 'width', 'height', 'padding', 'border', 'boxSizing', 
             'font', 'letterSpacing', 'whiteSpace', 'wordWrap', 'lineHeight', 'overflowWrap'].forEach(prop => {
                div.style[prop] = style[prop];
            });
            div.style.position = 'absolute';
            div.style.visibility = 'hidden';
            div.style.overflow = 'auto';
            document.body.appendChild(div);
            const text = element.value.substring(0, element.selectionStart);
            div.textContent = text;
            const span = document.createElement('span');
            span.textContent = element.value.substring(element.selectionStart) || '.';
            div.appendChild(span);
            const rect = element.getBoundingClientRect();
            const coordinates = {
                top: rect.top + span.offsetTop + parseInt(style.borderTopWidth) + window.scrollY,
                left: rect.left + span.offsetLeft + parseInt(style.borderLeftWidth) + window.scrollX
            };
            document.body.removeChild(div);
            return coordinates;
        }
        function showSuggestions(input, cursorPos) {
            const text = input.value.substring(0, cursorPos);
            const words = text.split(/[\s,();]+/);
            const currentWord = words[words.length - 1];

            if (currentWord.length < 1) {
                hideSuggestions();
                return;
            }
            currentSuggestions = allSuggestions.filter(s => 
                s.toLowerCase().startsWith(currentWord.toLowerCase())
            ).slice(0, 15);
            if (currentSuggestions.length === 0) {
                hideSuggestions();
                return;
            }
            const dropdown = document.getElementById('suggestions');
            dropdown.innerHTML = '';
            currentSuggestions.forEach((sugg, idx) => {
                const div = document.createElement('div');
                div.textContent = sugg;
                div.className = 'px-4 py-2 cursor-pointer hover:bg-sky-500 hover:text-white transition-all duration-150';
                div.onclick = () => insertSuggestion(input, sugg, currentWord.length);
                dropdown.appendChild(div);
            });
            selectedIndex = -1;
            dropdown.classList.remove('hidden');
            positionDropdown(input);
        }
        function hideSuggestions() {
            document.getElementById('suggestions').classList.add('hidden');
            currentSuggestions = [];
            selectedIndex = -1;
        }
        function insertSuggestion(input, suggestion, replaceLength) {
            const cursorPos = input.selectionStart;
            const text = input.value;
            const before = text.substring(0, cursorPos - replaceLength);
            const after = text.substring(cursorPos);
            input.value = before + suggestion + after;
            const newPos = before.length + suggestion.length;
            input.setSelectionRange(newPos, newPos);
            updateHighlight();
            hideSuggestions();
            input.focus();
        }
        function positionDropdown(input) {
            const dropdown = document.getElementById('suggestions');
            const coords = getCaretCoordinates(input);
            dropdown.style.top = (coords.top + 20) + 'px';
            dropdown.style.left = coords.left + 'px';
            dropdown.style.minWidth = '200px';
        }
        function handleKeyDown(e) {
            const dropdown = document.getElementById('suggestions');
            if (dropdown.classList.contains('hidden')) return;

            if (e.key === 'ArrowDown') {
                e.preventDefault();
                selectedIndex = Math.min(selectedIndex + 1, currentSuggestions.length - 1);
                updateSelection();
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                selectedIndex = Math.max(selectedIndex - 1, -1);
                updateSelection();
            } else if (e.key === 'Enter' && selectedIndex >= 0) {
                e.preventDefault();
                const input = document.getElementById('queryInput');
                const text = input.value.substring(0, input.selectionStart);
                const words = text.split(/[\s,();]+/);
                const currentWord = words[words.length - 1];
                insertSuggestion(input, currentSuggestions[selectedIndex], currentWord.length);
            } else if (e.key === 'Escape') {
                hideSuggestions();
            }
        }
        function updateSelection() {
            const dropdown = document.getElementById('suggestions');
            const items = dropdown.children;
            for (let i = 0; i < items.length; i++) {
                if (i === selectedIndex) {
                    items[i].classList.add('bg-sky-500', 'text-white');
                    items[i].scrollIntoView({ block: 'nearest' });
                } else {
                    items[i].classList.remove('bg-sky-500', 'text-white');
                }
            }
        }
        function toggleDarkMode() {
            document.documentElement.classList.toggle('dark');
            localStorage.setItem('darkMode', document.documentElement.classList.contains('dark'));
        }
        
        if (localStorage.getItem('darkMode') === 'true') {
            document.documentElement.classList.add('dark');
        }
        $(function() {
            initSuggestions();
            const $input = $('#queryInput');
            
            updateHighlight();
            
            $input.on('input', function(e) {
                updateHighlight();
                showSuggestions(this, this.selectionStart);
            });
            $input.on('scroll', syncScroll);
            $input.on('keydown', handleKeyDown);
            $input.on('click', function(e) {
                showSuggestions(this, this.selectionStart);
            });
            $(document).on('click', function(e) {
                if (!$(e.target).closest('#queryInput, #suggestions').length) {
                    hideSuggestions();
                }
            });
            $input.focus();
        });

        function toggleTable(index) {
            const table = document.getElementById('table-' + index);
            const icon = document.getElementById('expand-icon-' + index);
            table.classList.toggle('hidden');
            icon.classList.toggle('rotate-180');
        }
    </script>
</head>
<body class="bg-blue-50 dark:bg-slate-900 min-h-screen p-4 sm:p-6 transition-colors duration-150">
<div class="max-w-7xl mx-auto">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <h1 class="text-2xl sm:text-3xl font-bold text-sky-600 dark:text-sky-400">ZenSQL</h1>
        <button onclick="toggleDarkMode()" class="px-4 py-2 rounded-lg bg-sky-100 dark:bg-slate-700 text-sky-700 dark:text-sky-400 hover:bg-sky-200 dark:hover:bg-slate-600 transition-all duration-150 shadow-sm flex items-center gap-2">
            <span class="material-icons text-lg dark:hidden">dark_mode</span>
            <span class="material-icons text-lg hidden dark:inline">light_mode</span>
            <span class="dark:hidden">Dark</span>
            <span class="hidden dark:inline">Light</span>
        </button>
    </div>
    <form method="post" action="" id="queryForm">
        <div class="relative">
            <div class="editor-container bg-white dark:bg-slate-800 border border-sky-200 dark:border-sky-700 rounded-lg mb-4 relative">
                <div id="highlight" class="text-gray-900 dark:text-gray-100 font-mono text-sm"></div>
                <textarea id="queryInput" placeholder="Enter your SQL query here..." name="query" class="w-full p-4 font-mono text-sm" rows="10" spellcheck="false"><?php
    if (isset($_POST['query'])) {
        echo htmlspecialchars($_POST['query']);
    }
?></textarea>
            </div>
            <div id="suggestions" class="hidden fixed z-50 bg-white dark:bg-slate-800 border border-sky-200 dark:border-sky-700 rounded-lg shadow-lg max-h-60 overflow-y-auto text-sm text-gray-900 dark:text-gray-100 transition-all duration-150"></div>
        </div>
        <button type="submit" class="bg-sky-600 dark:bg-sky-700 text-white px-6 py-2 rounded-lg hover:bg-sky-700 dark:hover:bg-sky-600 transition-all duration-150 shadow-md hover:shadow-lg flex items-center gap-2 hover:scale-105">
            <span class="material-icons text-lg">play_arrow</span>
            Execute Query
        </button>
    </form>
    <div class="mt-6">
        <?php if ($error_msg): ?>
            <div class="p-3 mb-4 bg-red-50 dark:bg-red-900/50 text-red-700 dark:text-red-200 border border-red-200 dark:border-red-700 rounded-lg flex items-start gap-2 transition-all duration-150">
                <span class="material-icons text-lg">error</span>
                <span><?php echo $error_msg; ?></span>
            </div>
        <?php endif; ?>
        <?php if ($result_html): ?>
            <?php echo $result_html; ?>
        <?php endif; ?>
    </div>
    <h2 class="text-xl sm:text-2xl font-semibold text-sky-900 dark:text-sky-300 mt-8 mb-4 flex items-center gap-2">
        <span class="material-icons">storage</span>
        Database Tables
    </h2>
    <div class="grid grid-cols-1 gap-4">
        <?php
        foreach ($tables as $index => $table):
            $conn = new mysqli($servername, $username, $password, $dbname);
            $table_result = $conn->query("SELECT * FROM `$table` LIMIT 50"); ?>
            <div class="bg-white dark:bg-slate-800 border border-sky-200 dark:border-sky-700 rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-all duration-150">
                <button onclick="toggleTable(<?php echo $index; ?>)" class="w-full text-left px-4 py-3 font-semibold text-sky-900 dark:text-sky-300 hover:bg-sky-50 dark:hover:bg-slate-700 transition-all duration-150 flex justify-between items-center">
                    <span class="flex items-center gap-2">
                        <span class="material-icons text-lg">table_chart</span>
                        <?php echo $table; ?>
                    </span>
                    <span class="material-icons text-sky-600 dark:text-sky-400 transition-transform duration-200" id="expand-icon-<?php echo $index; ?>">expand_more</span>
                </button>
                <div id="table-<?php echo $index; ?>" class="overflow-x-auto transition-all duration-200">
                    <?php if ($table_result && $table_result->num_rows > 0): ?>
                        <table class="min-w-full">
                            <thead class="bg-sky-600 dark:bg-sky-800 text-white">
                            <tr>
                                <?php while ($field = $table_result->fetch_field()): ?>
                                    <th class='px-4 py-2 text-left text-sm font-medium border border-sky-300 dark:border-sky-600 whitespace-nowrap'><?php echo $field->name ?></th>
                                <?php endwhile; ?>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $table_result->data_seek(0);
                            while ($row = $table_result->fetch_assoc()):
                                echo "<tr class='hover:bg-sky-50 dark:hover:bg-slate-700 transition-colors duration-150'>";
                                foreach ($row as $cell) {
                                    echo "<td class='px-4 py-2 border border-sky-200 dark:border-sky-700 text-sm text-gray-900 dark:text-gray-100'>{$cell}</td>";
                                }
                                echo "</tr>";
                            endwhile; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p class="p-4 text-sky-500 dark:text-sky-400 flex items-center gap-2">
                            <span class="material-icons">info</span>
                            Table is empty.
                        </p>
                    <?php endif; $conn->close(); ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
</body>
</html>
