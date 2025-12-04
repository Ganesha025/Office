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
                    $result_html .= "<div class='overflow-x-auto mb-4'><table class='min-w-full bg-white dark:bg-slate-800 border border-blue-200 dark:border-blue-700'>";
                    $result_html .= "<thead class='bg-gradient-to-r from-blue-600 to-cyan-600 dark:from-blue-800 dark:to-cyan-800 text-white'>";
                    $result_html .= "<tr>";
                    while ($field = $result->fetch_field()) {
                        $result_html .= "<th class='px-4 py-2 text-left text-sm font-medium border border-blue-300 dark:border-blue-600'>{$field->name}</th>";
                    }
                    $result_html .= "</tr></thead><tbody>";
                    while ($row = $result->fetch_assoc()) {
                        $result_html .= "<tr class='hover:bg-blue-50 dark:hover:bg-slate-700'>";
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
    <title>ZenTech SQL</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class'
        }
    </script>
    <script type="module">
        import {EditorState} from "https://cdn.jsdelivr.net/npm/@codemirror/state@6.5.1/dist/index.js";
        import {EditorView, lineNumbers, keymap, highlightActiveLine, highlightActiveLineGutter} from "https://cdn.jsdelivr.net/npm/@codemirror/view@6.4.0/dist/index.js";
        import {sql, MySQL, schemaCompletionSource} from "https://cdn.jsdelivr.net/npm/@codemirror/lang-sql@6.9.1/dist/index.js";
        import {autocompletion, closeBrackets, closeBracketsKeymap, completionKeymap} from "https://cdn.jsdelivr.net/npm/@codemirror/autocomplete@6.4.0/dist/index.js";
        import {defaultKeymap, history, historyKeymap, indentWithTab} from "https://cdn.jsdelivr.net/npm/@codemirror/commands@6.1.3/dist/index.js";
        import {syntaxHighlighting, defaultHighlightStyle, bracketMatching, foldGutter, indentOnInput} from "https://cdn.jsdelivr.net/npm/@codemirror/language@6.4.0/dist/index.js";
        import {highlightSelectionMatches, searchKeymap} from "https://cdn.jsdelivr.net/npm/@codemirror/search@6.2.3/dist/index.js";
        import {oneDark} from "https://cdn.jsdelivr.net/npm/@codemirror/theme-one-dark@6.1.0/dist/index.js";

        const schema = <?php echo json_encode($schema); ?>;
        const textarea = document.getElementById('queryInput');

        window.initEditor = () => {
            const mySchema = {};
            for (const [table, columns] of Object.entries(schema)) {
                mySchema[table] = columns;
            }

            const isDark = document.documentElement.classList.contains('dark');

            const editor = new EditorView({
                state: EditorState.create({
                    doc: textarea.value,
                    extensions: [
                        lineNumbers(),
                        highlightActiveLineGutter(),
                        highlightActiveLine(),
                        history(),
                        foldGutter(),
                        indentOnInput(),
                        bracketMatching(),
                        closeBrackets(),
                        autocompletion({
                            override: [schemaCompletionSource({schema: mySchema, dialect: MySQL})],
                            activateOnTyping: true,
                            maxRenderedOptions: 10
                        }),
                        highlightSelectionMatches(),
                        sql({
                            dialect: MySQL,
                            schema: mySchema,
                            upperCaseKeywords: true
                        }),
                        syntaxHighlighting(defaultHighlightStyle),
                        isDark ? oneDark : [],
                        keymap.of([
                            ...closeBracketsKeymap,
                            ...defaultKeymap,
                            ...searchKeymap,
                            ...historyKeymap,
                            ...completionKeymap,
                            indentWithTab
                        ]),
                        EditorView.updateListener.of((update) => {
                            if (update.docChanged) {
                                textarea.value = update.state.doc.toString();
                                clearTimeout(window.compileTimeout);
                                window.compileTimeout = setTimeout(() => {
                                    document.getElementById('autoSubmit').click();
                                }, 1000);
                            }
                        }),
                        EditorView.theme({
                            "&": {
                                fontSize: "14px",
                                fontFamily: "'Consolas', 'Monaco', 'Courier New', monospace"
                            },
                            ".cm-content": {
                                caretColor: isDark ? "#60a5fa" : "#2563eb",
                                padding: "10px 0"
                            },
                            ".cm-gutters": {
                                backgroundColor: isDark ? "#1e293b" : "#f8fafc",
                                color: isDark ? "#64748b" : "#94a3b8",
                                border: "none"
                            },
                            ".cm-activeLineGutter": {
                                backgroundColor: isDark ? "#334155" : "#e0f2fe"
                            },
                            ".cm-activeLine": {
                                backgroundColor: isDark ? "#1e3a5f" : "#dbeafe"
                            },
                            ".cm-selectionMatch": {
                                backgroundColor: isDark ? "#334155" : "#bfdbfe"
                            }
                        })
                    ]
                }),
                parent: document.getElementById('editor')
            });
            textarea.style.display = 'none';
            window.editor = editor;
        }

        // Reinitialize editor on theme change
        window.reinitEditor = () => {
            if (window.editor) {
                const content = window.editor.state.doc.toString();
                document.getElementById('queryInput').value = content;
                window.editor.destroy();
                window.initEditor();
            }
        }
    </script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        function toggleDarkMode() {
            document.documentElement.classList.toggle('dark');
            localStorage.setItem('darkMode', document.documentElement.classList.contains('dark'));
            // Reinitialize editor with new theme
            setTimeout(() => {
                if (window.reinitEditor) window.reinitEditor();
            }, 100);
        }
        
        if (localStorage.getItem('darkMode') === 'true') {
            document.documentElement.classList.add('dark');
        }
    </script>
</head>
<body class="bg-gradient-to-br from-blue-50 via-cyan-50 to-sky-100 dark:from-slate-900 dark:via-blue-950 dark:to-slate-900 min-h-screen p-6 transition-colors duration-200">

<div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-cyan-600 dark:from-blue-400 dark:to-cyan-400 bg-clip-text text-transparent">ZenTech SQL</h1>
        <button onclick="toggleDarkMode()" class="px-4 py-2 rounded-lg bg-blue-100 dark:bg-slate-700 text-blue-700 dark:text-cyan-400 hover:bg-blue-200 dark:hover:bg-slate-600 transition shadow-sm">
            <span class="dark:hidden">🌙 Dark</span>
            <span class="hidden dark:inline">☀️ Light</span>
        </button>
    </div>

    <form method="post" action="" id="queryForm">
        <textarea id="queryInput" name="query" class="bg-white dark:bg-slate-800 border border-blue-200 dark:border-blue-700 rounded-lg mb-4 w-full pl-5 pt-6 text-gray-900 dark:text-gray-100" rows="10"><?php
    if (isset($_POST['query'])) {
        echo htmlspecialchars($_POST['query']);
    }
?></textarea>
<script>    
        $(document).ready(function() {
            $('#queryInput').focus();
        });
        const lineHeight = 24;
        const numberOfLines = 10;
        document.getElementById('editor').style.height = `${lineHeight * numberOfLines}px`;
</script>
<div id="editor" class="bg-white dark:bg-slate-800 border border-blue-200 dark:border-blue-700 rounded-lg shadow-sm"></div>
        <button type="submit" id="autoSubmit" class="bg-gradient-to-r from-blue-600 to-cyan-600 dark:from-blue-700 dark:to-cyan-700 text-white px-6 py-2 rounded-lg hover:from-blue-700 hover:to-cyan-700 dark:hover:from-blue-600 dark:hover:to-cyan-600 transition mt-4 shadow-md hover:shadow-lg">Execute Query</button>
    </form>

    <div class="mt-6">
        <?php if ($error_msg): ?>
            <div class="p-3 mb-4 bg-red-50 dark:bg-red-900/50 text-red-700 dark:text-red-200 border border-red-200 dark:border-red-700 rounded-lg"><?php echo $error_msg; ?></div>
        <?php endif; ?>
        <?php if ($result_html): ?>
            <?php echo $result_html; ?>
        <?php endif; ?>
    </div>

    <h2 class="text-2xl font-semibold text-blue-900 dark:text-cyan-300 mt-8 mb-4">Database Tables</h2>
    <div class="grid grid-cols-1 gap-4">
        <?php
        foreach ($tables as $index => $table):
            $conn = new mysqli($servername, $username, $password, $dbname);
            $table_result = $conn->query("SELECT * FROM `$table` LIMIT 50"); ?>
            <div class="bg-white dark:bg-slate-800 border border-blue-200 dark:border-blue-700 rounded-lg overflow-hidden shadow-md hover:shadow-lg transition">
                <button onclick="document.getElementById('table-<?php echo $index; ?>').classList.toggle('hidden')" class="w-full text-left px-4 py-3 font-semibold text-blue-900 dark:text-cyan-300 hover:bg-blue-50 dark:hover:bg-slate-700 transition flex justify-between items-center">
                    <span><?php echo $table; ?></span>
                    <span class="text-sm text-blue-600 dark:text-cyan-400">▼</span>
                </button>
                <div id="table-<?php echo $index; ?>" class="overflow-x-auto">
                    <?php if ($table_result && $table_result->num_rows > 0): ?>
                        <table class="min-w-full">
                            <thead class="bg-gradient-to-r from-blue-600 to-cyan-600 dark:from-blue-800 dark:to-cyan-800 text-white">
                            <tr>
                                <?php while ($field = $table_result->fetch_field()): ?>
                                    <th class='px-4 py-2 text-left text-sm font-medium border border-blue-300 dark:border-blue-600'><?php echo $field->name ?></th>
                                <?php endwhile; ?>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $table_result->data_seek(0);
                            while ($row = $table_result->fetch_assoc()):
                                echo "<tr class='hover:bg-blue-50 dark:hover:bg-slate-700'>";
                                foreach ($row as $cell) {
                                    echo "<td class='px-4 py-2 border border-blue-200 dark:border-blue-700 text-sm text-gray-900 dark:text-gray-100'>{$cell}</td>";
                                }
                                echo "</tr>";
                            endwhile; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p class="p-4 text-blue-500 dark:text-cyan-400">No data found.</p>
                    <?php endif; $conn->close(); ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script>initEditor();</script>
</body>
</html>
