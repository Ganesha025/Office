<?php
session_start();
if(!isset($_SESSION['books'])) $_SESSION['books'] = [];

// Handle delete
if(isset($_GET['delete'])){
    $index=intval($_GET['delete']);
    if(isset($_SESSION['books'][$index])){
        array_splice($_SESSION['books'],$index,1);
    }
}

function calculatePenalty($borrow_date,$return_date){
    if(!$borrow_date || !$return_date) return 0;
    $due_days=7;
    $diff=(new DateTime($return_date))->diff(new DateTime($borrow_date))->days;
    return ($diff>$due_days)?($diff-$due_days):0;
}

$total=count($_SESSION['books']);
$borrowed=count(array_filter($_SESSION['books'],fn($b)=>$b['borrowed']));
$available=$total-$borrowed;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Book List - MyLibrary</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<style>
body{background:#f2f6fc; font-family:'Segoe UI',sans-serif; padding-top:80px;}
.container{background:#fff; padding:30px; border-radius:20px; box-shadow:0 10px 25px rgba(0,0,0,0.1);}
table th{background:#3498db; color:#fff;}
.table td .btn-delete{padding:2px 8px; font-size:0.9rem;}
.card{box-shadow:0 5px 15px rgba(0,0,0,0.1);}
tr:focus{outline:2px solid #A855F7; background:#f3e8ff;}
</style>
</head>
<body>
<?php include('common/navbar.php'); ?>

<div class="container">
<h2 class="mb-4 text-center"><i class="bi bi-journal-bookmark"></i> Library Book Collection</h2>

<div class="row mb-4 text-center">
  <div class="col-md-4 mb-2">
    <div class="card p-3"><h5>Total Books</h5><p class="fs-3"><?php echo $total; ?></p></div>
  </div>
  <div class="col-md-4 mb-2">
    <div class="card p-3"><h5>Borrowed</h5><p class="fs-3"><?php echo $borrowed; ?></p></div>
  </div>
  <div class="col-md-4 mb-2">
    <div class="card p-3"><h5>Available</h5><p class="fs-3"><?php echo $available; ?></p></div>
  </div>
</div>

<table class="table table-bordered table-striped align-middle">
<thead>
<tr>
<th>#</th><th>Title</th><th>Author</th><th>Year</th><th>Borrowed</th><th>Borrow Date</th><th>Return Date</th><th>Penalty</th><th>Action</th>
</tr>
</thead>
<tbody>
<?php if($total>0): foreach($_SESSION['books'] as $i=>$book):
$penalty=calculatePenalty($book['borrow_date'],$book['return_date']??date('Y-m-d'));
?>
<tr tabindex="0">
<td><?php echo $i+1;?></td>
<td><?php echo htmlspecialchars($book['title']);?></td>
<td><?php echo htmlspecialchars($book['author']);?></td>
<td><?php echo $book['year'];?></td>
<td><?php echo $book['borrowed']?'Yes':'No';?></td>
<td><?php echo $book['borrow_date']??'-';?></td>
<td><?php echo $book['return_date']??'-';?></td>
<td><?php echo $penalty;?></td>
<td><a href="?delete=<?php echo $i;?>" class="btn btn-danger btn-delete" onclick="return confirm('Are you sure to delete this book?')"><i class="bi bi-trash"></i> Delete</a></td>
</tr>
<?php endforeach; else: ?>
<tr><td colspan="9" class="text-center">No books available</td></tr>
<?php endif;?>
</tbody>
</table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Focus first row on page load
document.addEventListener('DOMContentLoaded', function(){
    const rows = document.querySelectorAll('tbody tr[tabindex]');
    if(rows.length) rows[0].focus();

    // Enable arrow up/down navigation
    rows.forEach((row,i)=>{
        row.addEventListener('keydown', function(e){
            if(e.key==='ArrowDown'){
                if(rows[i+1]) rows[i+1].focus();
                e.preventDefault();
            }
            if(e.key==='ArrowUp'){
                if(rows[i-1]) rows[i-1].focus();
                e.preventDefault();
            }
            // Enter key can trigger delete link click
            if(e.key==='Enter'){
                const link = row.querySelector('a.btn-delete');
                if(link) link.click();
            }
        });
    });
});
</script>
</body>
</html>
