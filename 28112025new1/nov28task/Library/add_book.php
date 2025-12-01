<?php
session_start();
if(!isset($_SESSION['books'])) $_SESSION['books'] = [];
$message = "";
$errors = ['title'=>'', 'author'=>'', 'year'=>''];

if(isset($_POST['submit'])){
    $title = trim($_POST['title']);
    $author = trim($_POST['author']);
    $year = trim($_POST['year']);

    if(!$title) $errors['title'] = "Title is required!";
    elseif(!preg_match("/^[A-Za-z ]{1,20}$/",$title)) $errors['title']="Only letters & spaces max 20 chars";

    if(!$author) $errors['author'] = "Author is required!";
    elseif(!preg_match("/^[A-Za-z ]{1,20}$/",$author)) $errors['author']="Only letters & spaces max 20 chars";

    if(!$year) $errors['year']="Year is required!";
    elseif(!preg_match("/^\d{4}$/",$year) || $year<1900 || $year>2025) $errors['year']="Year must be 1900-2025";

    if(empty(array_filter($errors))){
        $_SESSION['books'][] = ['title'=>$title,'author'=>$author,'year'=>$year,'borrowed'=>false,'borrow_date'=>null,'return_date'=>null];
        $message = "Book added successfully!";
        $errors = ['title'=>'','author'=>'','year'=>''];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Add Book - MyLibrary</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body{background:#f2f6fc; padding-top:80px; font-family:'Segoe UI',sans-serif;}
.container{background:#fff;padding:40px;border-radius:20px;max-width:600px;margin:auto;box-shadow:0 10px 25px rgba(0,0,0,0.1);}
.text-danger{font-size:0.9rem;}
input::-webkit-outer-spin-button,input::-webkit-inner-spin-button{-webkit-appearance:none;margin:0;}
input[type=number]{-moz-appearance:textfield;}
.text-danger-star{color:red;margin-left:2px;}
</style>
</head>
<body>
<?php include('common/navbar.php'); ?>
<div class="container">
<h2 class="mb-4 text-center">Add New Book</h2>
<?php if($message):?><div class="alert alert-success"><?php echo $message;?></div><?php endif;?>
<form method="POST" novalidate>
  <div class="mb-3">
    <label>Title <span class="text-danger">*</span></label>
    <input type="text" name="title" class="form-control" maxlength="20" autofocus
           oninput="this.value=this.value.replace(/[^A-Za-z ]/g,'')"
           value="<?php echo isset($title)?htmlspecialchars($title):'';?>">
    <div class="text-danger"><?php echo $errors['title'];?></div>
  </div>
  <div class="mb-3">
    <label>Author <span class="text-danger">*</span></label>
    <input type="text" name="author" class="form-control" maxlength="20"
           oninput="this.value=this.value.replace(/[^A-Za-z ]/g,'')"
           value="<?php echo isset($author)?htmlspecialchars($author):'';?>">
    <div class="text-danger"><?php echo $errors['author'];?></div>
  </div>
  <div class="mb-3">
    <label>Year <span class="text-danger">*</span></label>
    <input type="text" name="year" class="form-control" maxlength="4" placeholder="1900-2025"
           oninput="
           this.value=this.value.replace(/[^0-9]/g,'');
           if(this.value.length>4) this.value=this.value.slice(0,4);
           if(this.value.length===4){let val=parseInt(this.value);if(val<1900)this.value='1900';if(val>2025)this.value='2025';}"
           value="<?php echo isset($year)?htmlspecialchars($year):'';?>"
           style="-moz-appearance:textfield;-webkit-appearance:none;">
    <div class="text-danger"><?php echo $errors['year'];?></div>
  </div>
  <button type="submit" name="submit" class="btn btn-primary w-100">Add Book</button>
</form>
</div>
</body>
</html>
