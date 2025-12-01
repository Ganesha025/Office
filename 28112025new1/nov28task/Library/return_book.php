<?php
session_start();
if(!isset($_SESSION['books'])) $_SESSION['books'] = [];
$message="";
$errors=['book_index'=>'','return_date'=>''];
$penalty=0;

if(isset($_POST['return'])){
    $index = $_POST['book_index'] ?? '';
    $return_date = $_POST['return_date'] ?? '';

    if($index==='') $errors['book_index']="Select a book!";
    if(!$return_date) $errors['return_date']="Enter return date!";

    if(empty(array_filter($errors))){
        $borrow_date = $_SESSION['books'][$index]['borrow_date'];
        $_SESSION['books'][$index]['borrowed']=false;
        $_SESSION['books'][$index]['return_date']=$return_date;
        $diff=(new DateTime($return_date))->diff(new DateTime($borrow_date))->days;
        $penalty=($diff>7)?($diff-7):0;
        $message="Book returned. Penalty: $".$penalty;
        $errors=['book_index'=>'','return_date'=>''];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Return Book - MyLibrary</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body{background:#f8f9fa;}
.container{background:#fff;padding:30px;border-radius:15px;margin-top:50px;max-width:500px;}
.text-danger-star{color:red;margin-left:2px;}
</style>
</head>
<body>
<?php include('common/navbar.php'); ?>

<div class="container">
<h2 class="mb-4 text-center">Return Book</h2>

<?php if($message):?>
    <div class="alert alert-success"><?php echo $message;?></div>
<?php endif;?>

<form method="POST">
  <!-- Select Book -->
  <div class="mb-3">
    <label class="form-label">Select Book <span class="text-danger-star">*</span></label>
    <select name="book_index" class="form-select" autofocus>
      <option value="">--Select--</option>
      <?php foreach($_SESSION['books'] as $i=>$b){ 
        if($b['borrowed']) 
            echo "<option value='$i'>".htmlspecialchars($b['title'])."</option>";
      } ?>
    </select>
    <div class="text-danger"><?php echo $errors['book_index'];?></div>
  </div>

  <!-- Return Date -->
  <div class="mb-3">
    <label class="form-label">Return Date <span class="text-danger-star">*</span></label>
    <input type="date" name="return_date" class="form-control" min="<?php echo date('Y-m-d'); ?>" value="<?= $_POST['return_date'] ?? '' ?>">
    <div class="text-danger"><?php echo $errors['return_date'];?></div>
  </div>

  <button type="submit" name="return" class="btn btn-primary w-100">Return Book</button>
</form>
</div>

<!-- Optional JS: Focus first invalid field on submit -->
<script>
document.addEventListener('DOMContentLoaded', function(){
    const bookSelect = document.querySelector('select[name="book_index"]');
    const returnDate = document.querySelector('input[name="return_date"]');

    <?php if($errors['book_index']): ?>
        bookSelect.focus();
    <?php elseif($errors['return_date']): ?>
        returnDate.focus();
    <?php endif; ?>
});
</script>

</body>
</html>
