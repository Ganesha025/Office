<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ZenSQLDB";

$conn = new mysqli($servername, $username, $password);
$conn->set_charset("utf8mb4");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$conn->query("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
$conn->select_db($dbname);

$createTableSQL = "
CREATE TABLE IF NOT EXISTS ZenSQLHistory (
    id INT AUTO_INCREMENT PRIMARY KEY,
    query_text TEXT NOT NULL,
    status ENUM('success', 'error') NOT NULL,
    error_message TEXT NULL,
    executed_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
";
$conn->query($createTableSQL);

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$result_html = "";
$error_msg = "";
$history_html = "";

if (isset($_POST['query'])) {
    $query = $_POST['query'];
    $status = 'success';
    $error_message = null;

    if (preg_match('/\bDROP\s+DATABASE\b/i', $query)) {
        $error_msg = "DROP DATABASE queries are not allowed!";
        $status = 'error';
        $error_message = $error_msg;
    } elseif (preg_match('/\bZenSQLHistory\b/i', $query)) {
        $error_msg = "Queries on ZenSQLHistory table are not allowed!";
        $status = 'error';
        $error_message = $error_msg;
    } else {
        try {
            if ($conn->multi_query($query)) {
                do {
                    if ($result = $conn->store_result()) {
                        $result_html .= "<div class='overflow-x-auto mb-4'><table class='min-w-full bg-white dark:bg-slate-800 border border-blue-200 dark:border-blue-700'>";
                        $result_html .= "<thead class='bg-blue-600 dark:bg-blue-800 text-white'><tr>";
                        while ($field = $result->fetch_field()) {
                            $result_html .= "<th class='px-4 py-2 text-left text-sm font-medium border border-blue-300 dark:border-blue-600'>{$field->name}</th>";
                        }
                        $result_html .= "</tr></thead><tbody>";
                        while ($row = $result->fetch_assoc()) {
                            $result_html .= "<tr class='hover:bg-blue-50 dark:hover:bg-slate-700 transition-colors duration-150'>";
                            foreach ($row as $cell) {
                                $result_html .= "<td class='px-4 py-2 border border-blue-200 dark:border-blue-700 text-sm text-gray-900 dark:text-gray-100'>{$cell}</td>";
                            }
                            $result_html .= "</tr>";
                        }
                        $result_html .= "</tbody></table></div>";
                        $result->free();
                    } else {
                        $result_html .= "<div class='p-3 mb-4 bg-blue-50 dark:bg-blue-900 text-blue-700 dark:text-blue-100 border border-blue-200 dark:border-blue-700 rounded-lg'>Query executed successfully. Affected rows: " . $conn->affected_rows . "</div>";
                    }
                } while ($conn->more_results() && $conn->next_result());
            } else {
                throw new Exception("Query failed: " . $conn->error);
            }
        } catch (Exception $e) {
            $status = 'error';
            $error_message = $e->getMessage();
            $error_msg = "Error: " . $e->getMessage();
        }
    }

    $stmt = $conn->prepare("INSERT INTO ZenSQLHistory (query_text, status, error_message) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $query, $status, $error_message);
    $stmt->execute();
    $stmt->close();
}

// Fetch query history
$history_html = "<div class='mt-6'><h2 class='text-xl font-semibold text-blue-900 dark:text-blue-300 mb-4'>Query History</h2>";
$history_result = $conn->query("SELECT * FROM ZenSQLHistory ORDER BY executed_at DESC LIMIT 50");
if ($history_result && $history_result->num_rows > 0) {
    $history_html .= "<div class='overflow-x-auto'><table class='min-w-full bg-white dark:bg-slate-800 border border-blue-200 dark:border-blue-700'><thead class='bg-blue-600 dark:bg-blue-800 text-white'><tr>
        <th class='px-4 py-2 border'>ID</th>
        <th class='px-4 py-2 border'>Query</th>
        <th class='px-4 py-2 border'>Status</th>
        <th class='px-4 py-2 border'>Error Message</th>
        <th class='px-4 py-2 border'>Executed At</th>
    </tr></thead><tbody>";
    while ($row = $history_result->fetch_assoc()) {
        $history_html .= "<tr class='hover:bg-blue-50 dark:hover:bg-slate-700 transition-colors duration-150'>
            <td class='px-4 py-2 border'>{$row['id']}</td>
            <td class='px-4 py-2 border'>{$row['query_text']}</td>
            <td class='px-4 py-2 border'>{$row['status']}</td>
            <td class='px-4 py-2 border'>{$row['error_message']}</td>
            <td class='px-4 py-2 border'>{$row['executed_at']}</td>
        </tr>";
    }
    $history_html .= "</tbody></table></div>";
} else {
    $history_html .= "<p class='text-blue-500 dark:text-blue-400'>No query history yet.</p>";
}
$history_html .= "</div>";

if(isset($_POST['ajax']) && $_POST['ajax'] == 1){
    echo json_encode([
        'result_html' => $result_html,
        'error_msg' => $error_msg,
        'history_html' => $history_html
    ]);
    exit;
}if(isset($_POST['fetchTables']) && $_POST['fetchTables'] == 1) {
    $conn = new mysqli($servername, $username, $password, $dbname);
    $tables_result = $conn->query("SHOW TABLES");
    $tablesData = [];

    while($row = $tables_result->fetch_array()) {
        $tableName = $row[0];
        if(strcasecmp($tableName, 'ZenSQLHistory') === 0) continue;

        $tableRows = [];
        $res = $conn->query("SELECT * FROM `$tableName` LIMIT 50");
        while($r = $res->fetch_assoc()) {
            $tableRows[] = $r;
        }

        $fields = [];
        $fields_res = $conn->query("SHOW COLUMNS FROM `$tableName`");
        while($f = $fields_res->fetch_assoc()) {
            $fields[] = $f['Field'];
        }

        $tablesData[] = [
            'name' => $tableName,
            'fields' => $fields,
            'rows' => $tableRows
        ];
    }

    $conn->close();
    echo json_encode($tablesData);
    exit;
}
if(isset($_POST['fetchHistory']) && $_POST['fetchHistory'] == 1) {
    $conn = new mysqli($servername, $username, $password, $dbname);
    $history_html = "<div class='flex flex-col gap-3'>";

    $res = $conn->query("SELECT * FROM ZenSQLHistory ORDER BY executed_at DESC");
    while($row = $res->fetch_assoc()) {

        $dt = new DateTime($row['executed_at']);
        $formatted = $dt->format('d M y - h:i:s A');

        $isSuccess = $row['status'] === 'success';

        $badgeClass = $isSuccess
            ? 'bg-green-100 text-green-800'
            : 'bg-red-100 text-red-800';

        $title = $isSuccess ? 'Success' : 'Error';
        $icon  = $isSuccess ? 'check_circle' : 'error';

        $history_html .= "
        <div class='history-card flex items-center justify-between p-4 bg-white dark:bg-slate-800 
                    border border-blue-200 dark:border-blue-700 rounded-lg shadow-sm 
                    hover:shadow-md transition-all duration-150 cursor-pointer'
            data-query='".htmlspecialchars($row['query_text'], ENT_QUOTES)."'
            data-status='{$row['status']}'
            data-error='".htmlspecialchars($row['error_message'] ?: '-', ENT_QUOTES)."'
            data-time='{$formatted}'>

            <div class='flex items-center gap-3'>
                <span class='material-icons text-lg {$badgeClass} rounded-full p-1'>
                    {$icon}
                </span>
                <span class='font-medium {$badgeClass} px-3 py-1 rounded-full'>
                    {$title}
                </span>
            </div>

            <span class='text-sm text-gray-500 dark:text-gray-400'>
                {$formatted}
            </span>
        </div>";
    }

    $history_html .= "</div>";
    $conn->close();
    echo $history_html;
    exit;
}
if (isset($_POST['fetchSchema']) && $_POST['fetchSchema'] == 1) {
    $schema = [];
    $tables = $conn->query("SHOW TABLES");

    while ($t = $tables->fetch_array()) {
        if (strcasecmp($t[0], 'ZenSQLHistory') === 0) continue;

        $cols = [];
        $res = $conn->query("SHOW COLUMNS FROM `{$t[0]}`");
        while ($c = $res->fetch_assoc()) {
            $cols[] = $c['Field'];
        }
        $schema[$t[0]] = $cols;
    }

    echo json_encode($schema);
    exit;
}






// Fetch tables and schema
$tables = [];
$tables_result = $conn->query("SHOW TABLES");
if ($tables_result) {
    while ($row = $tables_result->fetch_array()) {
        if (strcasecmp($row[0], 'ZenSQLHistory') === 0) continue;
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
    <link rel="icon" href="https://reactnative.dev/img/header_logo.svg">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
        .rotate-180{transform:rotate(90deg)}
        .editor-container{position:relative}
        #queryInput{background:transparent;position:relative;z-index:2;color:transparent;caret-color:#1e293b;resize:none}
        .dark #queryInput{caret-color:#e2e8f0}
        #highlight{position:absolute;top:0;left:0;padding:1rem;white-space:pre-wrap;word-wrap:break-word;pointer-events:none;z-index:1;font-family:monospace;font-size:0.875rem;line-height:1.25rem;overflow:hidden}
        .sql-keyword{color:#dc2626;font-weight:600}
        .dark .sql-keyword{color:#f87171}
    </style>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
$(document).on('click', '.history-card', function() {
    const query = $(this).data('query');
    const status = $(this).data('status');
    const error = $(this).data('error');
    const time = $(this).data('time');

    Swal.fire({
        title: 'Query Details',
       html: `
    <div style="text-align:left">
        <p><strong>Status:</strong> ${status}</p>
        <p><strong>Executed At:</strong> ${time}</p>
        <p><strong>Error:</strong> ${error}</p>

        <pre class="bg-gray-100 dark:bg-slate-700 p-2 rounded mt-2 overflow-auto"
             style="max-height:300px; white-space:pre-wrap; word-break:break-word;">
${query}
        </pre>
    </div>
`,
        icon: status === 'success' ? 'success' : 'error',
        width: '600px',
        showCloseButton: true
    });
});
</script>

    <script>
  const sqlKeywords = [
    // DML & DDL
    'SELECT', 'FROM', 'WHERE', 'INSERT', 'UPDATE', 'DELETE', 'CREATE', 'ALTER', 'DROP',
    'TABLE', 'DATABASE', 'INDEX', 'VIEW', 'TRIGGER', 'PROCEDURE', 'FUNCTION', 'INTO',
    'VALUES', 'SET', 'JOIN', 'INNER', 'LEFT', 'RIGHT', 'FULL', 'OUTER', 'CROSS', 'NATURAL', 'ON',
    'AND', 'OR', 'NOT', 'IN', 'BETWEEN', 'LIKE', 'IS', 'NULL', 'AS', 'DISTINCT', 'ALL',
    'ORDER', 'BY', 'ASC', 'DESC', 'GROUP', 'HAVING', 'LIMIT', 'OFFSET', 'UNION', 'INTERSECT',
    'EXCEPT', 'EXISTS', 'CASE', 'WHEN', 'THEN', 'ELSE', 'END', 'PRIMARY', 'KEY', 'FOREIGN',
    'REFERENCES', 'UNIQUE', 'CHECK', 'DEFAULT', 'AUTO_INCREMENT', 'NOT NULL', 'CONSTRAINT',
    'ADD', 'MODIFY', 'COLUMN', 'RENAME', 'TRUNCATE', 'CASCADE', 'RESTRICT', 'GRANT', 'REVOKE',
    'COMMIT', 'ROLLBACK', 'SAVEPOINT', 'TRANSACTION', 'BEGIN', 'START', 'MERGE', 'CALL', 'DECLARE',
    'LOOP', 'WHILE', 'REPEAT', 'EXIT', 'CONTINUE', 'CURSOR', 'OPEN', 'FETCH', 'CLOSE',
    'FOR', 'IF', 'ELSEIF', 'ELSE', 'USING', 'MATCH', 'ON DELETE', 'ON UPDATE', 'ARRAY',
    'TEMPORARY', 'TEMP', 'IDENTITY', 'GENERATED', 'ALWAYS', 'WITH', 'RECURSIVE', 'PARTITION', 'OVER',
    'ROWS', 'RANGE', 'PRECEDING', 'FOLLOWING', 'UNBOUNDED', 'ROWS BETWEEN', 'RANGE BETWEEN',
    'COUNT', 'SUM', 'AVG', 'MIN', 'MAX', 'CONCAT', 'SUBSTRING', 'UPPER', 'LOWER', 'TRIM',
    'LENGTH', 'COALESCE', 'CAST', 'CONVERT', 'IFNULL', 'NULLIF', 'NVL', 'REPLACE', 'ASCII',
    'CHARINDEX', 'REVERSE', 'REPLICATE', 'POWER', 'CEIL', 'FLOOR', 'ROUND', 'TRUNCATE',
    'RADIANS', 'DEGREES', 'EXTRACT', 'POSITION', 'DATE', 'TIME', 'DATETIME', 'TIMESTAMP',
    'YEAR', 'MONTH', 'DAY', 'HOUR', 'MINUTE', 'SECOND', 'NOW', 'CURDATE', 'CURTIME',
    'CURRENT_DATE', 'CURRENT_TIME', 'CURRENT_TIMESTAMP', 'LOCALTIME', 'LOCALTIMESTAMP',
    'VARIANCE', 'STDDEV', 'MEDIAN', 'NTILE', 'ROW_NUMBER', 'RANK', 'DENSE_RANK', 'FIRST_VALUE',
    'LAST_VALUE', 'PERCENT_RANK', 'CUME_DIST', 'WINDOW','INT', 'INTEGER', 'VARCHAR', 'TEXT', 'CHAR', 'NCHAR', 'DECIMAL', 'FLOAT', 'DOUBLE',
    'BOOLEAN', 'BLOB', 'ENUM', 'NUMERIC', 'VARYING', 'REAL', 'SMALLINT', 'BIGINT', 'DATEONLY',
    'TIMEONLY', 'TIMESTAMPTZ', 'JSONB','JSON', 'JSON_OBJECT', 'JSON_ARRAY', 'JSON_VALUE', 'JSON_QUERY',
    'XML', 'XMLAGG', 'XMLFOREST', 'XMLPARSE', 'XMLSERIALIZE','SESSION_USER', 'CURRENT_USER', 'SYSTEM_USER', 'USER', 'TRUE', 'FALSE', 'IFNULL', 'NVL',
    'SHOW', 'DESCRIBE', 'DESC', 'EXPLAIN', 'USE', 'OVERLAPS', 'IDENTITY', 'GENERATED', 'ALWAYS',
    'REPLACE', 'TEMP', 'TEMPORARY', 'LEADING', 'TRAILING', 'CONTINUE', 'EXIT', 'DECLARE'
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

    currentSuggestions = allSuggestions
        .filter(s => s.toLowerCase().startsWith(currentWord.toLowerCase()))
        .slice(0, 15);

    if (currentSuggestions.length === 0) {
        hideSuggestions();
        return;
    }

    const dropdown = document.getElementById('suggestions');
    dropdown.innerHTML = '';

    currentSuggestions.forEach((sugg, idx) => {
        const div = document.createElement('div');
        div.textContent = sugg;
        div.className = 'px-4 py-2 cursor-pointer hover:bg-blue-500 hover:text-white transition-all duration-150';
        div.onclick = () => insertSuggestion(input, sugg, currentWord.length);
        dropdown.appendChild(div);
    });

    selectedIndex = 0; // VS Code style: first suggestion auto-selected
    updateSelection();
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
    if (e.ctrlKey && e.key === 'Enter') {
        e.preventDefault();
        document.getElementById('queryForm').requestSubmit(); // triggers your AJAX form submit
        return;
    }

    const dropdown = document.getElementById('suggestions');
    if (!dropdown || dropdown.classList.contains('hidden')) return;

    if (e.key === 'ArrowDown') {
        e.preventDefault();
        selectedIndex = (selectedIndex + 1) % currentSuggestions.length;
        updateSelection();
    } else if (e.key === 'ArrowUp') {
        e.preventDefault();
        selectedIndex = (selectedIndex - 1 + currentSuggestions.length) % currentSuggestions.length;
        updateSelection();
    } else if (e.key === 'Enter') {
        if (selectedIndex >= 0 && currentSuggestions.length > 0) {
            e.preventDefault();
            const input = document.getElementById('queryInput');
            const text = input.value.substring(0, input.selectionStart);
            const words = text.split(/[\s,();]+/);
            const currentWord = words[words.length - 1];
            insertSuggestion(input, currentSuggestions[selectedIndex], currentWord.length);
        }
    } else if (e.key === 'Tab') {
        if (selectedIndex >= 0 && currentSuggestions.length > 0) {
            e.preventDefault(); // prevent default tab
            const input = document.getElementById('queryInput');
            const text = input.value.substring(0, input.selectionStart);
            const words = text.split(/[\s,();]+/);
            const currentWord = words[words.length - 1];
            insertSuggestion(input, currentSuggestions[selectedIndex], currentWord.length);
        }
    } else if (e.key === 'Escape') {
        hideSuggestions();
    }
   
}

function updateSelection() {
    const dropdown = document.getElementById('suggestions');
    const items = dropdown.children;
    for (let i = 0; i < items.length; i++) {
        if (i === selectedIndex) {
            items[i].classList.add('bg-blue-500', 'text-white');
            items[i].scrollIntoView({ block: 'nearest' });
        } else {
            items[i].classList.remove('bg-blue-500', 'text-white');
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
            icon.classList.toggle('rotate-0');
        }
         document.getElementById('queryInput').addEventListener('keydown', function(e) {
        if (e.ctrlKey && e.key === 'Enter') { // Ctrl + Enter
            e.preventDefault(); // prevent newline
            document.getElementById('queryForm').requestSubmit(); // run the form
        }
    });
    </script>
</head>
<body class="bg-blue-50 dark:bg-slate-900 min-h-screen p-4 sm:p-6 transition-colors duration-150">
    <!-- Slide-in History Modal -->
     <div id="historyModal" class="fixed right-0 top-0 h-full w-96 bg-white dark:bg-slate-800 shadow-lg transform translate-x-full transition-transform duration-300 flex flex-col z-50">

    <div class="flex justify-between items-center p-4 border-b border-gray-200 dark:border-gray-700">
        <h2 class="text-lg font-semibold text-blue-900 dark:text-blue-300">Query History</h2>
        <button onclick="closeHistoryModal()" class="text-gray-500 hover:text-gray-900 dark:hover:text-white">
            <span class="material-icons">close</span>
        </button>
    </div>
    <div id="historyContent" class="p-4 overflow-y-auto flex-1">
    <?php echo $history_html; ?>
</div>

</div>
<div class="bg-blue-100 dark:bg-slate-800 border-2 border-blue-700 rounded-2xl p-4 mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">

  <!-- Questions -->
  <div class="flex flex-col gap-2 border p-3 rounded-xl flex-1">
    <div class="flex justify-between items-center cursor-pointer" id="toggleQuestions">
      <h2 class="text-lg sm:text-xl font-semibold text-blue-900 dark:text-blue-300">SQL Questions</h2>
      <span id="toggleIcon" class="material-icons transition-transform duration-200">expand_more</span>
    </div>
    <ol id="questionsList" class="list-decimal list-inside text-gray-800 dark:text-gray-200 mt-2">
      <li>Retrieve all students with marks above 70.</li>
      <li>Count the number of students in each department.</li>
    </ol>
  </div>

  <!-- Timer & Submit -->
  <div class="flex flex-col sm:flex-row items-center gap-4 border p-3 rounded-xl">
    <div class="text-blue-700 dark:text-blue-400 font-mono text-lg">
      Time Left: <span id="timer">10:00</span>
    </div>
    <button id="submitQuiz" class="bg-blue-600 dark:bg-blue-700 text-white px-4 py-2 rounded-2xl hover:bg-blue-700 dark:hover:bg-blue-600 transition-all duration-150 shadow-md flex items-center gap-2">
      <span class="material-icons text-lg">check_circle</span>
      Submit
    </button>
  </div>

</div>

<script>
// Toggle Questions
let questionsOpen = true;
const questionsList = document.getElementById('questionsList');
const toggleIcon = document.getElementById('toggleIcon');
document.getElementById('toggleQuestions').addEventListener('click', () => {
    questionsOpen = !questionsOpen;
    questionsList.style.display = questionsOpen ? 'block' : 'none';
    toggleIcon.style.transform = questionsOpen ? 'rotate(0deg)' : 'rotate(-180deg)';
});

// Timer
let totalSeconds = 10 * 60;
const timerEl = document.getElementById('timer');
const timerInterval = setInterval(() => {
    const minutes = Math.floor(totalSeconds / 60);
    const seconds = totalSeconds % 60;
    timerEl.textContent = `${minutes.toString().padStart(2,'0')}:${seconds.toString().padStart(2,'0')}`;
    if(totalSeconds <= 0){
        clearInterval(timerInterval);
        Swal.fire({
            title:"Time's up!", 
            text:"Please submit your queries.", 
            icon:"warning", 
            confirmButtonText:"OK",
            customClass:{popup:'rounded-2xl', confirmButton:'rounded-full'}
        }).then(()=>{document.getElementById('submitQuiz').click()});
    }
    totalSeconds--;
}, 1000);

// Submit with confirmation
document.getElementById('submitQuiz').addEventListener('click', (e)=>{
    e.preventDefault();
    Swal.fire({
        title: 'Are you sure?',
        text: "Do you want to submit your SQL queries?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, submit!',
        cancelButtonText: 'Cancel',
        reverseButtons: true,
        customClass:{popup:'rounded-2xl', confirmButton:'rounded-full', cancelButton:'rounded-full'}
    }).then((result)=>{
        if(result.isConfirmed){
            Swal.fire({
                title:'Submitted!', 
                text:'Your queries have been submitted.', 
                icon:'success', 
                timer:2000, 
                showConfirmButton:false,
                customClass:{popup:'rounded-2xl'}
            });
            if(window.electronAPI){
                window.electronAPI.submitExam(); // call Electron main process
            }
        }
    });
});
</script>

<div class="max-w-7xl mx-auto">
 <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 w-full">
  <a href="javascript:location.reload()"
   class="text-2xl sm:text-3xl font-bold text-blue-600 dark:text-blue-400 relative inline-block transition-all duration-300 hover:scale-110 hover:text-blue-500 dark:hover:text-blue-300
          after:content-[''] after:absolute after:left-0 after:bottom-0 after:w-full after:h-0.5 after:bg-blue-500 dark:after:bg-blue-300 after:scale-x-0 after:origin-right after:transition-transform after:duration-300 hover:after:scale-x-100 hover:after:origin-left
          hover:drop-shadow-[0_0_12px_rgba(59,130,246,0.8)] dark:hover:drop-shadow-[0_0_12px_rgba(96,165,250,0.8)]">
    NovaSQL
</a>

    <div class="flex items-center gap-4">

    <!-- History -->
    <span onclick="openHistoryModal()"
          class="flex items-center gap-2 text-blue-600 dark:text-blue-400 text-lg font-medium hover:text-blue-500 dark:hover:text-blue-300 transition-all duration-150 cursor-pointer">
        <span class="material-symbols-outlined text-2xl">history</span>
        <span>History</span>
    </span>
    </div>
</div>
<script>
function openHistoryModal() {
    $.post('', { fetchHistory: 1 }, function(html) {
        $('#historyContent').html(html);
    });
    const modal = document.getElementById('historyModal');
    const historyContent = document.getElementById('historyContent');
    historyContent.innerHTML = document.getElementById('queryHistory').innerHTML;

    modal.classList.remove('translate-x-full'); // slide in

    // Close modal when clicking outside
    function outsideClickListener(e) {
          if (Swal.isVisible()) return;

        if (!modal.contains(e.target) && e.target.id !== 'historyLink') {
            closeHistoryModal();
        }
    }
    modal.outsideClickListener = outsideClickListener;
    setTimeout(() => {
        window.addEventListener('click', outsideClickListener);
    }, 10);

    // Close modal on Escape key
    function escapeKeyListener(e) {
        if (e.key === 'Escape') {
            closeHistoryModal();
        }
    }
    modal.escapeKeyListener = escapeKeyListener;
    window.addEventListener('keydown', escapeKeyListener);
}

function closeHistoryModal() {
    const modal = document.getElementById('historyModal');
    modal.classList.add('translate-x-full'); // slide out

    setTimeout(() => {
        if (modal.outsideClickListener) {
            window.removeEventListener('click', modal.outsideClickListener);
            modal.outsideClickListener = null;
        }
        if (modal.escapeKeyListener) {
            window.removeEventListener('keydown', modal.escapeKeyListener);
            modal.escapeKeyListener = null;
        }
    }, 300); // match transition duration
}

</script>

<form method="post" action="" id="queryForm">
  <div class="relative">
    <div class="editor-container bg-white dark:bg-slate-800 border border-blue-200 dark:border-blue-700 rounded-lg mb-4 relative overflow-hidden flex">
      <!-- Line Numbers -->
      <div id="lineNumbers" 
           class="w-12 text-right pr-2 pt-4 text-gray-400 dark:text-gray-500 font-mono text-sm select-none 
                  bg-transparent dark:bg-slate-900 dark:border-blue-700">
      </div>

      <!-- Textarea Container -->
      <div class="relative flex-1">
        <div id="highlight" class="text-gray-900 dark:text-gray-100 font-mono text-sm pl-4 absolute top-0 left-0 w-full h-full pointer-events-none whitespace-pre-wrap break-words"></div>
        <textarea id="queryInput" placeholder="Enter query here..." 
                  name="query"
                  class="w-full h-full p-4 pl-4 font-mono text-sm bg-transparent outline-none resize-none overflow-auto"
                  rows="10" spellcheck="false"><?php
                      if (isset($_POST['query'])) {
                          echo htmlspecialchars($_POST['query']);
                      }
                  ?></textarea>
      </div>
    </div>

    <div id="suggestions" 
         class="hidden fixed z-50 bg-white dark:bg-slate-800 border border-blue-200 dark:border-blue-700 rounded-lg shadow-lg max-h-60 overflow-y-auto text-sm text-gray-900 dark:text-gray-100 transition-all duration-150">
    </div>
  </div>

  <button type="submit" 
      class="bg-blue-600 dark:bg-blue-700 text-white px-6 py-2 rounded-lg hover:bg-blue-700 dark:hover:bg-blue-600 transition-all duration-150 shadow-md hover:shadow-lg flex items-center gap-2 hover:scale-105">
      <span class="material-icons text-lg">play_arrow</span>
      Execute
  </button>
</form>
<div id="queryResult" class="mt-6"></div>
<div id="queryResult" class="mt-6"></div>
<div id="queryHistory" class="mt-6"></div>

<script>
$(document).ready(function() {
    $('#queryForm').on('submit', function(e) {
        e.preventDefault(); // prevent page reload
        let query = $('#queryInput').val();

        $.ajax({
            url: '', // current PHP file
            method: 'POST',
            data: { query: query, ajax: 1 },
            dataType: 'json',
           success: function(response) {
    let html = '';
    if(response.error_msg) {
        html += `<div class="p-3 mb-4 bg-red-50 dark:bg-red-900/50 text-red-700 dark:text-red-200 border border-red-200 dark:border-red-700 rounded-lg flex items-start gap-2 transition-all duration-150">
                    <span class="material-icons text-lg">error</span>
                    <span>${response.error_msg}</span>
                </div>`;
    }
    if(response.result_html) {
        html += response.result_html;
    }
    $('#queryResult').html(html);

    // Refresh tables without reloading
    refreshTables();
}
,
            error: function(xhr, status, error) {
                $('#queryResult').html(`<div class="p-3 mb-4 bg-red-50 dark:bg-red-900/50 text-red-700 dark:text-red-200 border border-red-200 dark:border-red-700 rounded-lg flex items-start gap-2 transition-all duration-150">
                                            <span class="material-icons text-lg">error</span>
                                            <span>AJAX error: ${error}</span>
                                        </div>`);
            }
        });
    });

    $('#queryInput').on('input', function(e) {
    updateHighlight();
    showSuggestions(this, this.selectionStart);

    const value = $(this).val();
    if (value.trim() === "") {
        // Remove from localStorage if input is empty
        localStorage.removeItem('zenSQLLastQuery');
    } else {
        // Save current query to localStorage while typing
        localStorage.setItem('zenSQLLastQuery', value);
    }
});
const lastQuery = localStorage.getItem('zenSQLLastQuery');
if (lastQuery) {
    $('#queryInput').val(lastQuery);
    updateHighlight();
    updateLineNumbers();
}

});
</script>

<script>
const textarea = document.getElementById("queryInput");
const lineNumbers = document.getElementById("lineNumbers");
const highlight = document.getElementById("highlight");

function updateLineNumbers() {
    const lines = textarea.value.split("\n").length;
    lineNumbers.innerHTML = Array.from({length: lines}, (_, i) => i + 1).join("<br>");
}

function syncScroll() {
    lineNumbers.scrollTop = textarea.scrollTop;
    highlight.scrollTop = textarea.scrollTop;
}

function updateHighlight() {
    const text = textarea.value
        .replace(/[<>&]/g, c => ({'<':'&lt;','>':'&gt;','&':'&amp;'}[c]))
        .replace(/\b(SELECT|FROM|WHERE|INSERT|UPDATE|DELETE|CREATE|ALTER|DROP|TABLE|JOIN|AND|OR|NOT)\b/gi, '<span class="text-red-500 font-bold">$1</span>');
    highlight.innerHTML = text + "\n";
}

// Initialize
textarea.addEventListener("input", () => {
    updateLineNumbers();
    updateHighlight();
});
textarea.addEventListener("scroll", syncScroll);
window.addEventListener("resize", updateLineNumbers);

// Initial render
updateLineNumbers();
updateHighlight();
</script>

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
    <h2 class="text-xl sm:text-2xl font-semibold text-blue-900 dark:text-blue-300 mt-8 mb-4 flex items-center gap-2">
        <span class="material-icons">storage</span>
        Your Tables
    </h2>
    <div class="grid grid-cols-1 gap-4">
        <?php
        foreach ($tables as $index => $table):
            $conn = new mysqli($servername, $username, $password, $dbname);
            $table_result = $conn->query("SELECT * FROM `$table` LIMIT 50"); ?>
            <div class="bg-white dark:bg-slate-800 border border-blue-200 dark:border-blue-700 rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-all duration-150">
                <button onclick="toggleTable(<?php echo $index; ?>)" class="w-full text-left px-4 py-3 font-semibold text-blue-900 dark:text-blue-300 hover:bg-blue-50 dark:hover:bg-slate-700 transition-all duration-150 flex justify-between items-center">
                    <span class="flex items-center gap-2">
                        <span class="material-icons text-lg">table_chart</span>
                        <?php echo $table; ?>
                    </span>
                    <span class="material-icons text-blue-600 dark:text-blue-400 transition-transform duration-200 -rotate-180" id="expand-icon-<?php echo $index; ?>">expand_more</span>
                </button>
                <div id="table-<?php echo $index; ?>" class="overflow-x-auto transition-all duration-200">
                    <?php if ($table_result && $table_result->num_rows > 0): ?>
                        <table class="min-w-full">
                            <thead class="bg-blue-600 dark:bg-blue-800 text-white">
                            <tr>
                                <?php while ($field = $table_result->fetch_field()): ?>
                                    <th class='px-4 py-2 text-left text-sm font-medium border border-blue-300 dark:border-blue-600 whitespace-nowrap'><?php echo $field->name ?></th>
                                <?php endwhile; ?>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $table_result->data_seek(0);
                            while ($row = $table_result->fetch_assoc()):
                                echo "<tr class='hover:bg-blue-50 dark:hover:bg-slate-700 transition-colors duration-150'>";
                                foreach ($row as $cell) {
                                    echo "<td class='px-4 py-2 border border-blue-200 dark:border-blue-700 text-sm text-gray-900 dark:text-gray-100'>{$cell}</td>";
                                }
                                echo "</tr>";
                            endwhile; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p class="p-4 text-blue-500 dark:text-blue-400 flex items-center gap-2">
                            <span class="material-icons">info</span>
                            Table is empty.
                        </p>
                    <?php endif; $conn->close(); ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<script>
    function refreshTables() {
    $.ajax({
        url: '', // same PHP file
        method: 'POST',
        data: { fetchTables: 1 },
        dataType: 'json',
        success: function(tables) {
            const container = $('.grid.grid-cols-1.gap-4');
            container.empty();

            tables.forEach((table, index) => {
                let html = `
                <div class="bg-white dark:bg-slate-800 border border-blue-200 dark:border-blue-700 rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-all duration-150">
                    <button onclick="toggleTable(${index})" class="w-full text-left px-4 py-3 font-semibold text-blue-900 dark:text-blue-300 hover:bg-blue-50 dark:hover:bg-slate-700 transition-all duration-150 flex justify-between items-center">
                        <span class="flex items-center gap-2">
                            <span class="material-icons text-lg">table_chart</span>
                            ${table.name}
                        </span>
                        <span class="material-icons text-blue-600 dark:text-blue-400 transition-transform duration-200 -rotate-180" id="expand-icon-${index}">expand_more</span>
                    </button>
                    <div id="table-${index}" class="overflow-x-auto transition-all duration-200">
                `;

                if(table.rows.length > 0) {
                    html += `<table class="min-w-full"><thead class="bg-blue-600 dark:bg-blue-800 text-white"><tr>`;
                    table.fields.forEach(f => html += `<th class='px-4 py-2 text-left text-sm font-medium border border-blue-300 dark:border-blue-600 whitespace-nowrap'>${f}</th>`);
                    html += `</tr></thead><tbody>`;
                    table.rows.forEach(row => {
                        html += "<tr class='hover:bg-blue-50 dark:hover:bg-slate-700 transition-colors duration-150'>";
                        table.fields.forEach(f => html += `<td class='px-4 py-2 border border-blue-200 dark:border-blue-700 text-sm text-gray-900 dark:text-gray-100'>${row[f]}</td>`);
                        html += "</tr>";
                    });
                    html += "</tbody></table>";
                } else {
                    html += `<p class="p-4 text-blue-500 dark:text-blue-400 flex items-center gap-2">
                                <span class="material-icons">info</span>
                                Table is empty.
                             </p>`;
                }

                html += `</div></div>`;
                container.append(html);
            });
        }
    });
}

</script>
</body>
</html>
