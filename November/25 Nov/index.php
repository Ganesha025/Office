<?php
function checkEvenOdd($number) {
    if ($number % 2 == 0) {
        return "even";
    } else {
        return "odd";
    }
}
for ($i = 1; $i <= 10; $i++) {
    echo "Number $i is " . checkEvenOdd($i) . "<br>";
}
$fruits = [
    "apple" => "red",
    "banana" => "yellow",
    "grape" => "purple"
];

echo "<h3>Fruit Colors:</h3>";
foreach ($fruits as $fruit => $color) {
    echo ucfirst($fruit) . " is " . $color . "<br>";
}
?>
