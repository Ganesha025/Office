<!-- common/navbar.php -->

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>
.navbar-custom {
    background-color: #1abc9c; /* Same as Book List page */
    box-shadow: 0 4px 10px rgba(0,0,0,0.3);
    position: sticky;
    top: 0;
    z-index: 9999;
    padding: 0.7rem 0;
}

/* Brand */
.navbar-custom .navbar-brand {
    font-weight: bold;
    font-size: 1.5rem;
    color: pink !important;
    text-shadow: 0 1px 2px rgba(0,0,0,0.3);
}

/* Nav links */
.navbar-custom .nav-link {
    color: brown !important;
    font-weight: 600;
    margin-right: 0.5rem;
    transition: 0.3s ease;
}

/* Hover effect */
.navbar-custom .nav-link:hover {
    color: #8B4513 !important; /* brown on hover */
}

/* Active link */
.navbar-custom .nav-link.active {
    color: #ffd700 !important; /* gold for active page */
    font-weight: 700;
}

/* Mobile toggler */
.navbar-toggler-icon {
    filter: invert(1); /* white icon */
}
</style>

<nav class="navbar navbar-expand-lg navbar-custom">
  <div class="container">
    <a class="navbar-brand" href="book_list.php">
      <i class="bi bi-book-half"></i> MyLibrary
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">

        <li class="nav-item">
            <a class="nav-link <?= basename($_SERVER['PHP_SELF'])=='add_book.php'?'active':'' ?>" href="add_book.php">
              Add Book
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link <?= basename($_SERVER['PHP_SELF'])=='borrow_book.php'?'active':'' ?>" href="borrow_book.php">
              Borrow Book
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link <?= basename($_SERVER['PHP_SELF'])=='return_book.php'?'active':'' ?>" href="return_book.php">
              Return Book
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link <?= basename($_SERVER['PHP_SELF'])=='book_list.php'?'active':'' ?>" href="book_list.php">
              Book List
            </a>
        </li>

      </ul>
    </div>
  </div>
</nav>
