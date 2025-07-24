<?php
  require_once("./auth/backend/auth.php");
?>

<!DOCTYPE html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="css/register.css">
    <link rel="stylesheet" href="css/Tailwindcss.css">
    <title>Đăng Ký Tài Khoản</title>

  </head>
  <body class="flex items-center justify-center min-h-screen">
    <div class="account-box">
      <h2 class="text-2xl font-bold text-center mb-4">Đăng Ký Tài Khoản</h2>
      <form id="registerForm" class="space-y-4" method="POST">
        <div class="form-group mb-3">
          <label for="user_name">User Name</label>
          <input type="text" class="form-control" id="user_name" name="user_name" placeholder="Enter your user name" required>
        </div>
        <div class="form-group mb-3">
          <label for="full_name">Full Name</label>
          <input type="text" class="form-control" id="full_name" name="full_name" placeholder="Enter your full name" required>
        </div>
        <div class="form-group mb-3">
          <label for="email">Email</label>
          <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
        </div>
        <div class="form-group mb-3">
          <label for="phone_number">Phone Number</label>
          <input type="text" class="form-control" id="phone_number" name="phone_number" placeholder="Enter your phone number" required>
        </div>
        <div class="form-group mb-3">
          <label for="address">Address</label>
          <input type="text" class="form-control" id="address" name="address" placeholder="Enter your address" required>
        </div>
        <div class="form-group mb-3">
          <label for="password">Password</label>
          <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
        </div>
        <button type="submit" name="submit" class="btn btn-primary">Register</button>
      </form>
      <div style="margin-top: 15px;text-align: center;"><a style="color:blue" href="./index.php">Trở Về Trang Chủ</a></div>
      <div style="margin-top : 10px; text-align:center">Already have an account? <a style="color:blue" href="./login.php">Login</a></div>
    </div>
  </body>
</html>


<?php
  if(isset($_POST['submit'])){
      Auth::register($_POST['user_name'],$_POST['full_name'],$_POST['email'],
      $_POST['phone_number'],$_POST['address'],$_POST['password']);
  }
?>
