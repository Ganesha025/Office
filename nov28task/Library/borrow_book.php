<?php
session_start();
if(!isset($_SESSION['books'])) $_SESSION['books'] = [];
$message="";
$errors=['book_index'=>'','borrow_date'=>''];

if(isset($_POST['borrow'])){
    $index = $_POST['book_index'] ?? '';
    $borrow_date = $_POST['borrow_date'] ?? '';

    if($index==='') $errors['book_index']="Select a book!";
    if(!$borrow_date) $errors['borrow_date']="Enter borrow date!";

    if(empty(array_filter($errors))){
        $_SESSION['books'][$index]['borrowed']=true;
        $_SESSION['books'][$index]['borrow_date']=$borrow_date;
        $_SESSION['books'][$index]['return_date']=null;
        $message="Book borrowed successfully!";
        $errors=['book_index'=>'','borrow_date'=>''];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Borrow Book - MyLibrary</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>.container{background:#fff;padding:30px;border-radius:15px;margin-top:50px;max-width:500px;}</style>
</head>
<body>
<?php include('common/navbar.php'); ?>
<div class="container">
<h2 class="mb-4 text-center">Borrow Book</h2>
<?php if($message):?><div class="alert alert-success"><?php echo $message;?></div><?php endif;?>
<form method="POST">
  <div class="mb-3">
    <label>Select Book</label>
    <select name="book_index" class="form-select">
      <option value="">--Select--</option>
      <?php foreach($_SESSION['books'] as $i=>$b){ if(!$b['borrowed']) echo "<option value='$i'>".htmlspecialchars($b['title'])."</option>";} ?>
    </select>
    <div class="text-danger"><?php echo $errors['book_index'];?></div>
  </div>
  <div class="mb-3">
    <label>Borrow Date</label>
    <input type="date" name="borrow_date" class="form-control">
    <div class="text-danger"><?php echo $errors['borrow_date'];?></div>
  </div>
  <button type="submit" name="borrow" class="btn btn-primary w-100">Borrow</button>
</form>
</div>
</body>
</html>
