<?php
include 'db.php';

if(isset($_POST['action'])){
    $id = $_POST['id'];

    if($_POST['action'] == 'borrow'){
        $borrower = $_POST['borrower'];
        $borrow_date = $_POST['borrow_date'] ?? date('Y-m-d');
        $due_date = $_POST['due_date'] ?? date('Y-m-d', strtotime("+7 days"));

        $conn->query("UPDATE books SET status='borrowed', borrower='$borrower', borrow_date='$borrow_date', due_date='$due_date' WHERE id=$id");
    }

    if($_POST['action'] == 'return'){
        $return_date = $_POST['return_date'] ?? date('Y-m-d');

        $sql = $conn->query("SELECT due_date FROM books WHERE id=$id");
        $row = $sql->fetch_assoc();
        $due_date = $row['due_date'];

        $daysLate = (strtotime($return_date) - strtotime($due_date)) / 86400;
        $penalty = $daysLate > 0 ? $daysLate * 5 : 0;

        $conn->query("UPDATE books SET status='available', return_date='$return_date', penalty=$penalty, borrower=NULL, borrow_date=NULL, due_date=NULL WHERE id=$id");
    }

    header("Location: borrow_return.php");
    exit;
}

$books = $conn->query("SELECT * FROM books ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrow / Return Books - Library Management</title>
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
                <a href="borrow_return.php" class="px-4 py-2 text-sm font-medium bg-blue-50 text-blue-600 rounded-lg">Borrow / Return</a>
                <a href="penalties.php" class="px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 hover:text-blue-600 rounded-lg transition">Penalties</a>
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
        <h2 class="text-3xl font-bold text-gray-900">Borrow / Return Books</h2>
        <p class="mt-1 text-sm text-gray-600">Manage book borrowing and returns</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Title</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Borrower</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Borrow Date</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Due Date</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php while($row = $books->fetch_assoc()): ?>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= $row['id'] ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= $row['title'] ?></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full <?= $row['status'] == 'available' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                <?= ucfirst($row['status']) ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"><?= $row['borrower'] ?? '-' ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"><?= $row['borrow_date'] ? date('d-m-Y', strtotime($row['borrow_date'])) : '-' ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"><?= $row['due_date'] ? date('d-m-Y', strtotime($row['due_date'])) : '-' ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <?php if($row['status'] == 'available'): ?>
                                <button onclick="openBorrowModal(<?= $row['id'] ?>, '<?= addslashes($row['title']) ?>')" class="px-4 py-2 bg-blue-600 text-white text-xs font-medium rounded-lg hover:bg-blue-700 transition">
                                    Borrow
                                </button>
                            <?php else: ?>
                                <button onclick="openReturnModal(<?= $row['id'] ?>, '<?= addslashes($row['title']) ?>')" class="px-4 py-2 bg-green-600 text-white text-xs font-medium rounded-lg hover:bg-green-700 transition">
                                    Return
                                </button>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<div id="borrowModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl max-w-md w-full p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-gray-900">Borrow Book</h3>
            <button onclick="closeBorrowModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <p id="borrowBookTitle" class="text-sm text-gray-600 mb-4"></p>
        <form method="post" class="space-y-4">
            <input type="hidden" name="action" value="borrow">
            <input type="hidden" name="id" id="borrowBookId">
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Borrower Name</label>
                <input type="text" name="borrower" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Borrow Date</label>
                <input type="date" name="borrow_date" value="<?= date('Y-m-d') ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Due Date</label>
                <input type="date" name="due_date" value="<?= date('Y-m-d', strtotime('+7 days')) ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none">
            </div>
            
            <div class="flex gap-3 pt-2">
                <button type="submit" class="flex-1 px-4 py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition">
                    Confirm Borrow
                </button>
                <button type="button" onclick="closeBorrowModal()" class="flex-1 px-4 py-2.5 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 transition">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<div id="returnModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl max-w-md w-full p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-gray-900">Return Book</h3>
            <button onclick="closeReturnModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <p id="returnBookTitle" class="text-sm text-gray-600 mb-4"></p>
        <form method="post" class="space-y-4">
            <input type="hidden" name="action" value="return">
            <input type="hidden" name="id" id="returnBookId">
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Return Date</label>
                <input type="date" name="return_date" value="<?= date('Y-m-d') ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none">
            </div>
            
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                <p class="text-xs text-yellow-800">Note: Late returns are charged ₹5 per day.</p>
            </div>
            
            <div class="flex gap-3 pt-2">
                <button type="submit" class="flex-1 px-4 py-2.5 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition">
                    Confirm Return
                </button>
                <button type="button" onclick="closeReturnModal()" class="flex-1 px-4 py-2.5 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 transition">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

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
                <p class="text-center text-sm text-gray-500">&copy; 2024 SavageInfo System. All rights reserved.</p>
            </div>
        </div>
    </div>
</footer>

<script>
function openBorrowModal(id, title) {
    document.getElementById('borrowBookId').value = id;
    document.getElementById('borrowBookTitle').textContent = 'Borrowing: ' + title;
    document.getElementById('borrowModal').classList.remove('hidden');
}

function closeBorrowModal() {
    document.getElementById('borrowModal').classList.add('hidden');
}

function openReturnModal(id, title) {
    document.getElementById('returnBookId').value = id;
    document.getElementById('returnBookTitle').textContent = 'Returning: ' + title;
    document.getElementById('returnModal').classList.remove('hidden');
}

function closeReturnModal() {
    document.getElementById('returnModal').classList.add('hidden');
}
</script>

</body>
</html>