<?php
session_start();
include 'db.php';

$usernameError = "";
$passwordError = "";
$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Username Validation
    if (!preg_match("/^[A-Za-z ]{1,20}$/", $username)) {
        $usernameError = "Username must be alphabets and spaces only (max 20 characters).";
    }

    // Password Validation
    if (!preg_match("/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).{1,8}$/", $password)) {
        $passwordError = "Password must contain 1 uppercase, 1 lowercase, 1 number, 1 special character (max 8 chars).";
    }

    if ($usernameError === "" && $passwordError === "") {

        if (isset($_POST['login'])) {

            $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();

            if ($result && password_verify($password, $result['password'])) {
                $_SESSION['username'] = $username;
                header("Location: index.php");
                exit();
            } else {
                $message = "Invalid username or password!";
            }

        } elseif (isset($_POST['signup'])) {

            $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $message = "Username already exists!";
            } else {

                $hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
                $stmt->bind_param("ss", $username, $hash);

                if ($stmt->execute()) {
                    $message = "Signup successful! You can now login.";
                } else {
                    $message = "Signup failed. Try again.";
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Superhero Academy</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<script>
// Username validation
function validateUsername(e) {
    let value = e.value.replace(/[^A-Za-z ]/g, "");
    e.value = value.substring(0, 20);
}

// Password limit
function validatePassword(e) {
    if (e.value.length > 8) {
        e.value = e.value.substring(0, 8);
    }
}

// SHOW / HIDE PASSWORD
function togglePassword() {
    const pwdField = document.getElementById("passwordField");
    const icon = document.getElementById("toggleIcon");

    if (pwdField.type === "password") {
        pwdField.type = "text";
        icon.textContent = "visibility_off";
    } else {
        pwdField.type = "password";
        icon.textContent = "visibility";
    }
}
</script>

</head>
<body class="bg-gray-50 font-sans flex flex-col min-h-screen">

<header class="bg-white shadow-md py-4">
    <div class="container mx-auto flex justify-between items-center px-4">
        <h1 class="text-2xl font-bold text-gray-800">Superhero Academy</h1>
        <nav class="space-x-4">
            <a href="#" class="text-gray-600 hover:text-blue-600">Home</a>
            <a href="#" class="text-gray-600 hover:text-blue-600">About</a>
            <a href="#" class="text-gray-600 hover:text-blue-600">Contact</a>
        </nav>
    </div>
</header>

<main class="flex-grow flex items-center justify-center">
    <form method="POST" class="bg-white shadow-lg rounded-xl p-10 w-full max-w-md space-y-6">

        <h2 class="text-3xl font-bold text-gray-800 text-center">Login / Signup</h2>

        <!-- Username -->
        <div>
            <label class="block text-gray-700 mb-1">Username <span class="text-red-600">*</span></label>
            <div class="flex items-center border rounded-lg px-3 py-2">
                <span class="material-icons text-gray-400 mr-2">person</span>
                <input autofocus type="text" name="username"
                       oninput="validateUsername(this)"
                       placeholder="Enter username"
                       required class="w-full outline-none">
            </div>
            <?php if ($usernameError): ?>
                <p class="text-red-500 text-sm mt-1"><?php echo $usernameError; ?></p>
            <?php endif; ?>
        </div>

        <!-- Password -->
        <div>
            <label class="block text-gray-700 mb-1">Password <span class="text-red-600">*</span></label>
            <div class="flex items-center border rounded-lg px-3 py-2">
                <span class="material-icons text-gray-400 mr-2">lock</span>
                
                <input type="password" id="passwordField" name="password"
                       oninput="validatePassword(this)"
                       placeholder="Enter password"
                       required class="w-full outline-none">

                <span class="material-icons text-gray-500 ml-2 cursor-pointer"
                      id="toggleIcon" onclick="togglePassword()">
                      visibility
                </span>
            </div>

            <?php if ($passwordError): ?>
                <p class="text-red-500 text-sm mt-1"><?php echo $passwordError; ?></p>
            <?php endif; ?>
        </div>

        <!-- Buttons -->
        <div class="flex justify-between">
            <button type="submit" name="login"
                class="w-1/2 mr-2 bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg font-semibold transition">
                Login
            </button>

            <button type="submit" name="signup"
                class="w-1/2 ml-2 bg-green-600 hover:bg-green-700 text-white py-2 rounded-lg font-semibold transition">
                Signup
            </button>
        </div>

        <?php if ($message): ?>
            <p class="text-red-500 text-center mt-2"><?php echo $message; ?></p>
        <?php endif; ?>

    </form>
</main>

<footer class="bg-white shadow-inner py-6 mt-10">
    <div class="container mx-auto text-center text-gray-500">
        &copy; <?php echo date("Y"); ?> Superhero Academy. All rights reserved.
    </div>
</footer>

</body>
</html>
