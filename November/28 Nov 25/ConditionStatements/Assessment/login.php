<?php
session_start();
require 'db.php';

if (isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $isPremium = isset($_POST['is_premium']) ? 1 : 0;

    if (empty($email)) {
        $error = "Email is required.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user) {
            $_SESSION['user'] = $user;
            header('Location: index.php');
            exit;
        } else {
            if (empty($name)) {
                $error = "Name is required for signup.";
            } else {
                $stmt = $pdo->prepare("INSERT INTO users (name, email, is_premium) VALUES (?, ?, ?)");
                $stmt->execute([$name, $email, $isPremium]);
                $userId = $pdo->lastInsertId();
                $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
                $stmt->execute([$userId]);
                $newUser = $stmt->fetch();
                $_SESSION['user'] = $newUser;
                header('Location: index.php');
                exit;
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
    <title>Login / Signup</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body class="min-h-screen flex flex-col bg-gray-50">

    <header class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center space-x-2">
                    <span class="material-icons text-blue-600 text-3xl">account_circle</span>
                    <span class="text-xl font-bold text-gray-900">AuthPortal</span>
                </div>
                <nav class="hidden md:flex items-center space-x-8">
                    <a href="#" class="text-gray-600 hover:text-gray-900 font-medium transition">Home</a>
                    <a href="#" class="text-gray-600 hover:text-gray-900 font-medium transition">Features</a>
                    <a href="#" class="text-gray-600 hover:text-gray-900 font-medium transition">Contact</a>
                </nav>
                <button class="md:hidden">
                    <span class="material-icons text-gray-700">menu</span>
                </button>
            </div>
        </div>
    </header>

    <main class="flex-1 flex items-center justify-center px-4 sm:px-6 lg:px-8 py-12">
        <div class="w-full max-w-md">
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                
                <div class="bg-blue-600 px-8 py-6 text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-white rounded-full mb-3">
                        <span class="material-icons text-blue-600 text-4xl">lock_open</span>
                    </div>
                    <h2 class="text-2xl font-bold text-white mb-1">Welcome Back</h2>
                    <p class="text-blue-100 text-sm">Login or create your account</p>
                </div>

                <form method="POST" action="" class="px-8 py-8 space-y-6">
                    
                    <?php if (!empty($error)): ?>
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4 flex items-start space-x-3">
                        <span class="material-icons text-red-500 text-xl">error_outline</span>
                        <p class="text-red-700 text-sm flex-1"><?php echo htmlspecialchars($error); ?></p>
                    </div>
                    <?php endif; ?>

                    <div class="space-y-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Full Name</label>
                            <div class="relative">
                                <span class="material-icons absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">person</span>
                                <input type="text" name="name" placeholder="Enter your full name" 
                                    class="w-full pl-11 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
                            <div class="relative">
                                <span class="material-icons absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">email</span>
                                <input type="email" name="email" placeholder="Enter your email" required
                                    class="w-full pl-11 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition">
                            </div>
                        </div>

                        <div class="flex items-center space-x-3 bg-gray-50 p-4 rounded-lg">
                            <input type="checkbox" name="is_premium" id="premium" class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <label for="premium" class="flex items-center space-x-2 cursor-pointer">
                                <span class="material-icons text-yellow-500">stars</span>
                                <span class="text-sm font-medium text-gray-700">Premium Member</span>
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition flex items-center justify-center space-x-2 shadow-md hover:shadow-lg">
                        <span>Continue</span>
                        <span class="material-icons">arrow_forward</span>
                    </button>

                    <p class="text-center text-sm text-gray-500">
                        New user? Account will be created automatically
                    </p>
                </form>
            </div>

            <p class="text-center mt-6 text-sm text-gray-500">
                Protected by enterprise-grade security
            </p>
        </div>
    </main>

    <footer class="bg-white border-t border-gray-200 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center space-x-2 mb-4">
                        <span class="material-icons text-blue-600 text-2xl">account_circle</span>
                        <span class="text-lg font-bold text-gray-900">AuthPortal</span>
                    </div>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        Secure authentication platform for modern applications. Built with enterprise-grade security and user experience in mind.
                    </p>
                </div>
                
                <div>
                    <h3 class="font-semibold text-gray-900 mb-4">Quick Links</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="text-gray-600 hover:text-blue-600 transition">About Us</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-blue-600 transition">Features</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-blue-600 transition">Pricing</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-blue-600 transition">Support</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="font-semibold text-gray-900 mb-4">Legal</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="text-gray-600 hover:text-blue-600 transition">Privacy Policy</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-blue-600 transition">Terms of Service</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-blue-600 transition">Cookie Policy</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-200 mt-8 pt-6 flex flex-col sm:flex-row justify-between items-center space-y-4 sm:space-y-0">
                <p class="text-gray-500 text-sm">© 2025 SavageInfo. All rights reserved.</p>
                <div class="flex items-center space-x-6">
                    <a href="#" class="text-gray-400 hover:text-gray-600 transition">
                        <span class="material-icons">facebook</span>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-gray-600 transition">
                        <span class="material-icons">link</span>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-gray-600 transition">
                        <span class="material-icons">mail</span>
                    </a>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>