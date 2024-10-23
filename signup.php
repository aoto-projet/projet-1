<?php
session_start();
require_once 'config/db.php'
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สมัครสมาชิก</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/bootstrap.min.js"></script> 
</head>
<body>
<div class="card">
    
    <img src="img/bg.jpg"class="card-img-top" alt="">
    <div class="card-body card-img-overlay">
  <p class="text-light"><div class="container" style="width: 500px;">
    <h3 class="mt-4">สมัครสมาชิก</h3>
    <hr>

    <form action="signup_db.php" method="post">


    

<?php if(isset($_SESSION['error'])) { ?>
<div class="alert alert-danger" role="alert">
<?php
echo $_SESSION['error'];
unset($_SESSION['error']);
?>
</div>
<?php } ?>


<?php if(isset($_SESSION['success'])) { ?>
<div class="alert alert-success" role="alert">
<?php
echo $_SESSION['success'];
unset($_SESSION['success']);
?>
</div>
<?php } ?>


<?php if(isset($_SESSION['warning'])) { ?>
<div class="alert alert-warning" role="alert">
<?php
echo $_SESSION['warning'];
unset($_SESSION['warning']);
?>
</div>
<?php } ?>




  <div class="mb-3">
    <label for="firstname" class="form-label text-primary">First name</label>
    <input type="text" class="form-control" name="firstname" aria-describedby="firstname">
    </div>

    <div class="mb-3">
    <label for="lastname" class="form-label text-primary">Last name</label>
    <input type="text" class="form-control"name="lastname" aria-describedby="lasttname">
    </div>

    <div class="mb-3">
    <label for="emali" class="form-label text-primary">Email</label>
    <input type="email" class="form-control" name="email" aria-describedby="email">
    </div>

  <div class="mb-3">
    <label for="password" class="form-label text-primary">Password</label>
    <input type="password" class="form-control"name="password">
  </div>

  <div class="mb-3">
    <label for="confirm password" class="form-label text-primary">Confirm Password</label>
    <input type="password" class="form-control"name="c_password">
  </div>
  
  <button type="submit" class="btn btn-primary"name="signup">Signup</button>
</form>
<hr>
<p>เป็นสมาชิกแล้วใช่ไหมคลิ๊กที่นี่เพื่อเข้าสู่ระบบ<a href="signin.php">เข้าสู่ระบบ</a></p>
</div>
    
        </div>
       
</div>
    
</body>
</html>





