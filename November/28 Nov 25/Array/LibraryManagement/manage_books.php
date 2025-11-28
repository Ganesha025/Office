<?php
include 'db.php';

if(isset($_POST['action'])){
    $title = $_POST['title'] ?? '';
    $author = $_POST['author'] ?? '';
    $year = $_POST['year'] ?? '';
    $category = $_POST['category'] ?? '';
    $id = $_POST['id'] ?? 0;

    if($_POST['action'] == 'add'){
        $conn->query("INSERT INTO books (title,author,year,category) VALUES ('$title','$author','$year','$category')");
    } elseif($_POST['action'] == 'update'){
        $conn->query("UPDATE books SET title='$title', author='$author', year='$year', category='$category' WHERE id=$id");
    } elseif($_POST['action'] == 'delete'){
        $conn->query("DELETE FROM books WHERE id=$id");
    }
    header("Location: manage_books.php");
    exit;
}

$books = $conn->query("SELECT * FROM books ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Books - Library Management</title>
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
                <a href="manage_books.php" class="px-4 py-2 text-sm font-medium bg-blue-50 text-blue-600 rounded-lg">Manage Books</a>
                <a href="borrow_return.php" class="px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 hover:text-blue-600 rounded-lg transition">Borrow / Return</a>
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
        <h2 class="text-3xl font-bold text-gray-900">Manage Books</h2>
        <p class="mt-1 text-sm text-gray-600">Add, edit, or delete books from your library collection</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Add / Update Book</h3>
        <form method="post" class="space-y-4">
    <input type="hidden" name="action" id="action" value="add">
    <input type="hidden" name="id" id="book_id">
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Title</label>
            <input type="text" name="title" id="title" placeholder="Enter book title" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition">
        </div>
        
        <div>
            <label for="author" class="block text-sm font-medium text-gray-700 mb-2">Author</label>
            <input type="text" name="author" id="author" placeholder="Enter author's name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition">
        </div>
        
        <div>
            <label for="year" class="block text-sm font-medium text-gray-700 mb-2">Year</label>
            <input type="number" name="year" id="year" placeholder="Enter publication year" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition">
        </div>
        
        <div>
            <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Category</label>
            <input type="text" name="category" id="category" placeholder="Enter book category" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition">
        </div>
    </div>
    
    <div class="flex gap-3">
        <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition">
            <span id="submitText">Add Book</span>
        </button>
        <button type="button" onclick="resetForm()" class="px-6 py-2.5 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 transition">
            Reset
        </button>
    </div>
</form>

    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">All Books</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Title</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Author</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Year</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php while($row = $books->fetch_assoc()): ?>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= $row['id'] ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= $row['title'] ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"><?= $row['author'] ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"><?= $row['year'] ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"><?= $row['category'] ?></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full <?= $row['status'] == 'Available' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                <?= $row['status'] ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button onclick="editBook(<?= $row['id'] ?>,'<?= addslashes($row['title']) ?>','<?= addslashes($row['author']) ?>',<?= $row['year'] ?>,'<?= addslashes($row['category']) ?>')" class="text-blue-600 hover:text-blue-800 mr-3">
                                <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </button>
                            <form method="post" class="inline">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                <button type="submit" onclick="return confirm('Are you sure you want to delete this book?')" class="text-red-600 hover:text-red-800">
                                    <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
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
                <p class="text-center text-sm text-gray-500">&copy; 2024 SavageInfo System. All rights reserved.</p>
            </div>
        </div>
    </div>
</footer>

<script>
function editBook(id,title,author,year,category){
    document.getElementById('action').value='update';
    document.getElementById('book_id').value=id;
    document.getElementById('title').value=title;
    document.getElementById('author').value=author;
    document.getElementById('year').value=year;
    document.getElementById('category').value=category;
    document.getElementById('submitText').textContent='Update Book';
    window.scrollTo({top: 0, behavior: 'smooth'});
}

function resetForm(){
    document.getElementById('action').value='add';
    document.getElementById('book_id').value='';
    document.getElementById('title').value='';
    document.getElementById('author').value='';
    document.getElementById('year').value='';
    document.getElementById('category').value='';
    document.getElementById('submitText').textContent='Add Book';
}
</script>

</body>
</html>