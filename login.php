<?php
  require_once("./auth/backend/auth.php");

  // Nếu đã đăng nhập, chuyển hướng về trang chủ
  if (Auth::loginWithCookie()) {
    header("Location: ./index.php");
    exit;
  }
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="shortcut icon" href="./img/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="css/login.css">
    <title>Sign In</title>
  </head>
  <body>
    <div class="container">
      <h2>Đăng Nhập</h2>
      <form method="POST">
        <label>Tên Đăng Nhập</label>
        <input type="text" placeholder="Username" name="user_name" require />
        <label>Mật Khẩu</label>
        <input type="password" placeholder="Password" name="password" require />
        <div class="remember-me-container">
          <label for="remember_me">Ghi nhớ tôi</label>
          <input type="checkbox" name="remember_me" id="remember_me">
        </div>
        <input type="submit" class="login_button" value="Đăng Nhập" name="submit" />
        <div class="register">
          <a href="./index.php">Trở Về Trang Chủ</a>
          <p>Chưa Có Tài Khoản?</p>
          <a href="./register.php">Đăng Ký Ngay</a>
        </div>
      </form>
    </div>
  </body>
</html>

<?php 
  if (isset($_POST['submit'])) {
    $remember = isset($_POST['remember_me']);
    $run = Auth::login($_POST['user_name'], $_POST['password'], $remember);
    if ($run) {
        header("Location: ./index.php");
    } else {
        echo "<script>alert('Tài khoản hoặc mật khẩu không chính xác');</script>";
    }
}
?>
