<!DOCTYPE html>
<html>
<head>
    <title>PHP String Function Examples</title>
</head>
<style>
    body { padding-left:90px; }
</style>
<body>

<h2>PHP String Function Outputs</h2>

<?php

echo "<h3>1. User Input Validation</h3>";
$email = "  john@example.com  ";
$email = trim($email);

$password = "secret123";

if (strpos($email, "@") !== false) {
    echo "Email Check: Valid Email<br>";
} else {
    echo "Email Check: Invalid Email<br>";
}

if (strlen($password) >= 8) {
    echo "Password Check: Password OK<br><br>";
} else {
    echo "Password Check: Password Too Short<br><br>";
}


echo "<h3>2. URL Slug</h3>";
$title = "New iPhone 16 Pro Max Review!";
$slug_temp = str_replace(" ", "-", $title);
$slug = strtolower($slug_temp);
echo "Slug: " . $slug . "<br><br>";


echo "<h3>3. File Extension</h3>";
$filename = "photo.jpg";
$dot_position = strrpos($filename, ".");
$ext = substr($filename, $dot_position + 1);
echo "File Extension: " . $ext . "<br><br>";


echo "<h3>4. Search in String</h3>";
$keyword = "phone";
$product = "Latest Android Phone with AI Camera";

$lower_product = strtolower($product);
$lower_keyword = strtolower($keyword);

if (strpos($lower_product, $lower_keyword) !== false) {
    echo "Search Result: Keyword Found<br><br>";
} else {
    echo "Search Result: Keyword Not Found<br><br>";
}


echo "<h3>5. Replace in Template</h3>";
$template = "Hello {name}, Welcome!";
$message = str_replace("{name}", "John", $template);
echo "Email Message: " . $message . "<br><br>";


echo "<h3>6. CSV Explode</h3>";
$csv = "John,Doe,25,USA";
$user = explode(",", $csv);

echo "CSV to Array:";
echo "<pre>";
print_r($user);
echo "</pre><br>";


echo "<h3>7. Number Formatting</h3>";
$price = 12345.5;
$formatted_price = number_format($price, 2);
echo "Formatted Price: " . $formatted_price . "<br><br>";


echo "<h3>8. Censoring Bad Words</h3>";
$comment = "This is a bad comment";
$badwords = ["bad"];
$cleaned = str_replace($badwords, "***", $comment);
echo "Cleaned Comment: " . $cleaned . "<br><br>";


echo "<h3>9. Case-insensitive Comparison</h3>";
$input = "Admin";
$lower_input = strtolower($input);

if ($lower_input == "admin") {
    echo "Compare Result: Match<br><br>";
} else {
    echo "Compare Result: No Match<br><br>";
}


echo "<h3>10. Text Summary</h3>";
$content = "This is a long blog article...";
$summary = substr($content, 0, 10) . "...";
echo "Summary: " . $summary . "<br><br>";


echo "<h3>11. Capitalize Words</h3>";
$title2 = "hello world";
$capitalized_text = ucwords($title2);
echo "Capitalized: " . $capitalized_text . "<br><br>";

?>

</body>
</html>
