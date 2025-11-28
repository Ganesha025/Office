<?php
include 'db.php';

$books = $conn->query("
    SELECT * FROM books 
    WHERE status='borrowed' OR (due_date IS NOT NULL AND due_date < CURDATE())
    ORDER BY due_date ASC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Overdue Penalties - Library Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">

<header class="bg-white shadow-sm border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <div class="flex items-center">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                <h1 class="ml-3 text-2xl font-bold text-gray-900">Library Management</h1>
            </div>
            <nav class="hidden md:flex space-x-1">
                <a href="index.php" class="px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 hover:text-blue-600 rounded-lg transition">Dashboard</a>
                <a href="manage_books.php" class="px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 hover:text-blue-600 rounded-lg transition">Manage Books</a>
                <a href="borrow_return.php" class="px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 hover:text-blue-600 rounded-lg transition">Borrow / Return</a>
                <a href="penalties.php" class="px-4 py-2 text-sm font-medium bg-blue-50 text-blue-600 rounded-lg">Penalties</a>
            </nav>
            <button class="md:hidden p-2 rounded-lg hover:bg-gray-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>
    </div>
</header>

<main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6">
        <div class="flex items-center mb-2">
            <svg class="w-8 h-8 text-red-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <h2 class="text-3xl font-bold text-gray-900">Overdue Books & Penalties</h2>
        </div>
        <p class="mt-1 text-sm text-gray-600">Track late returns and calculate penalties (₹5 per day) - <span id="currentTime" class="font-semibold"></span></p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Title</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Borrower</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Due Date</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Days Late</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Penalty (₹)</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="penaltyTable">
                    <?php while($row = $books->fetch_assoc()): ?>
                    <tr class="hover:bg-gray-50 transition" data-due="<?= $row['due_date'] ?>">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= $row['id'] ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= $row['title'] ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"><?= $row['borrower'] ?? '-' ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"><?= $row['due_date'] ? date('d-m-Y', strtotime($row['due_date'])) : '-' ?></td>
                        <td class="px-6 py-4 whitespace-nowrap days-late"></td>
                        <td class="px-6 py-4 whitespace-nowrap penalty-amount"></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex">
            <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div class="ml-3">
                <h3 class="text-sm font-semibold text-blue-900">Penalty Information</h3>
                <p class="mt-1 text-sm text-blue-700">Late returns are charged at ₹5 per day. Please ensure timely returns to avoid penalties.</p>
            </div>
        </div>
    </div>
</main>

<footer class="bg-white border-t border-gray-200 mt-auto">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="md:flex md:items-center md:justify-between">
            <div class="flex justify-center md:justify-start space-x-6">
                <a href="#" class="text-sm text-gray-600 hover:text-blue-600 transition">About</a>
                <a href="#" class="text-sm text-gray-600 hover:text-blue-600 transition">Contact</a>
                <a href="#" class="text-sm text-gray-600 hover:text-blue-600 transition">Privacy Policy</a>
                <a href="#" class="text-sm text-gray-600 hover:text-blue-600 transition">Terms</a>
            </div>
            <div class="mt-4 md:mt-0">
                <p class="text-center text-sm text-gray-500">&copy; SavageInfo System. All rights reserved.</p>
            </div>
        </div>
    </div>
</footer>

<script>
function updatePenalties() {
    const now = new Date();
    document.getElementById('currentTime').textContent = now.toLocaleString();
    
    const rows = document.querySelectorAll('#penaltyTable tr[data-due]');
    rows.forEach(row => {
        const dueDate = new Date(row.dataset.due);
        const diffTime = now - dueDate;
        const diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24));
        const daysLate = Math.max(0, diffDays);
        const penalty = daysLate * 5;
        
        const isOverdue = daysLate > 0;
        row.className = `hover:bg-gray-50 transition ${isOverdue ? 'bg-red-50' : ''}`;
        
        const daysCell = row.querySelector('.days-late');
        const penaltyCell = row.querySelector('.penalty-amount');
        
        if (isOverdue) {
            daysCell.innerHTML = `<span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">${daysLate} days</span>`;
            penaltyCell.innerHTML = `<span class="text-sm font-semibold text-red-600">₹${penalty}</span>`;
        } else {
            daysCell.innerHTML = `<span class="text-sm text-gray-500">-</span>`;
            penaltyCell.innerHTML = `<span class="text-sm text-gray-500">₹0</span>`;
        }
    });
}

updatePenalties();
setInterval(updatePenalties, 1000);
</script>

</body>
</html>