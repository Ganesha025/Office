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
                    $result_html .= "<div class='overflow-x-auto mb-4'><table class='min-w-full bg-white border border-gray-300'>";
                    $result_html .= "<thead class='bg-gray-800 text-white'>";
                    $result_html .= "<tr>";
                    while ($field = $result->fetch_field()) {
                        $result_html .= "<th class='px-4 py-2 text-left text-sm font-medium border border-gray-300'>{$field->name}</th>";
                    }
                    $result_html .= "</tr></thead><tbody>";

                    while ($row = $result->fetch_assoc()) {
                        $result_html .= "<tr class='hover:bg-gray-50'>";
                        foreach ($row as $cell) {
                            $result_html .= "<td class='px-4 py-2 border border-gray-300 text-sm'>{$cell}</td>";
                        }
                        $result_html .= "</tr>";
                    }

                    $result_html .= "</tbody></table></div>";
                    $result->free();
                } else {
                    $result_html .= "<div class='p-3 mb-4 bg-green-50 text-green-700 border border-green-200'>Query executed successfully. Affected rows: " . $conn->affected_rows . "</div>";
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

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SQL Real-time Compiler</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script type="module">
        import {EditorState} from "https://cdn.jsdelivr.net/npm/@codemirror/state@6.2.2/dist/index.js";
        import {EditorView, lineNumbers} from "https://cdn.jsdelivr.net/npm/@codemirror/view@6.4.0/dist/index.js";
        import {sql} from "https://cdn.jsdelivr.net/npm/@codemirror/lang-sql@6.2.2/dist/index.js";

        window.initEditor = () => {
            const editor = new EditorView({
                state: EditorState.create({
                    extensions: [
                        lineNumbers(),
                        sql(),
                        EditorView.updateListener.of((update) => {
                            if (update.docChanged) {
                                clearTimeout(window.compileTimeout);
                                window.compileTimeout = setTimeout(() => {
                                    document.getElementById('autoSubmit').click();
                                }, 1000);
                            }
                        })
                    ]
                }),
                parent: document.getElementById('editor')
            });
            window.editor = editor;
        }

        window.getEditorValue = () => window.editor.state.doc.toString();
    </script>
</head>
<body class="bg-gray-100 min-h-screen p-6">

<div class="max-w-7xl mx-auto">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">SQL Real-time Compiler</h1>

    <form method="post" action="" id="queryForm" onsubmit="document.querySelector('textarea[name=query]').value = getEditorValue()">
        <textarea id="editor" name="query" class="bg-white border border-gray-300 rounded mb-4 h-40 w-full" ></textarea>
        <button type="submit" id="autoSubmit" class="bg-gray-800 text-white px-6 py-2 rounded hover:bg-gray-700 transition">Execute Query</button>
    </form>

    <div class="mt-6">
        <?php if ($error_msg): ?>
            <div class="p-3 mb-4 bg-red-50 text-red-700 border border-red-200"><?php echo $error_msg; ?></div>
        <?php endif; ?>
        <?php if ($result_html): ?>
            <?php echo $result_html; ?>
        <?php endif; ?>
    </div>

    <h2 class="text-2xl font-semibold text-gray-800 mt-8 mb-4">Database Tables</h2>
    <div class="grid grid-cols-1 gap-4">
        <?php
        foreach ($tables as $index => $table):
            $conn = new mysqli($servername, $username, $password, $dbname);
            $table_result = $conn->query("SELECT * FROM `$table` LIMIT 50"); ?>
            <div class="bg-white border border-gray-300 rounded overflow-hidden">
                <button onclick="document.getElementById('table-<?php echo $index; ?>').classList.toggle('hidden')" class="w-full text-left px-4 py-3 font-semibold text-gray-800 hover:bg-gray-50 transition flex justify-between items-center">
                    <span><?php echo $table; ?></span>
                    <span class="text-sm">▼</span>
                </button>
                <div id="table-<?php echo $index; ?>" class="overflow-x-auto">
                    <?php if ($table_result && $table_result->num_rows > 0): ?>
                        <table class="min-w-full">
                            <thead class="bg-gray-800 text-white">
                            <tr>
                                <?php while ($field = $table_result->fetch_field()): ?>
                                    <th class='px-4 py-2 text-left text-sm font-medium border border-gray-300'><?php echo $field->name ?></th>
                                <?php endwhile; ?>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $table_result->data_seek(0);
                            while ($row = $table_result->fetch_assoc()):
                                echo "<tr class='hover:bg-gray-50'>";
                                foreach ($row as $cell) {
                                    echo "<td class='px-4 py-2 border border-gray-300 text-sm'>{$cell}</td>";
                                }
                                echo "</tr>";
                            endwhile; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p class="p-4 text-gray-500">No data found.</p>
                    <?php endif; $conn->close(); ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script>initEditor();</script>
</body>
</html>
