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
<style>.container{background:#fff;padding:30px;border-radius:15px;margin-top:50px;max-width:500px;}</style>
</head>
<body>
<?php include('common/navbar.php'); ?>
<div class="container">
<h2 class="mb-4 text-center">Return Book</h2>
<?php if($message):?><div class="alert alert-success"><?php echo $message;?></div><?php endif;?>
<form method="POST">
  <div class="mb-3">
    <label>Select Book</label>
    <select name="book_index" class="form-select">
      <option value="">--Select--</option>
      <?php foreach($_SESSION['books'] as $i=>$b){ if($b['borrowed']) echo "<option value='$i'>".htmlspecialchars($b['title'])."</option>";} ?>
    </select>
    <div class="text-danger"><?php echo $errors['book_index'];?></div>
  </div>
  <div class="mb-3">
    <label>Return Date</label>
    <input type="date" name="return_date" class="form-control" min="<?php echo date('Y-m-d'); ?>">
    <div class="text-danger"><?php echo $errors['return_date'];?></div>
  </div>
  <button type="submit" name="return" class="btn btn-primary w-100">Return Book</button>
</form>
</div>
</body>
</html>
