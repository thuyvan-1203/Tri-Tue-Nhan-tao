<?php // Đăng xuất người dùng
session_start();
// Xóa cookie
if (isset($_COOKIE['remember_token'])) {
    setcookie('remember_token', '', time() - 1314000, "/");
}
if (isset($_COOKIE['user_id'])) {
    setcookie('user_id', '', time() - 1314000, "/");
}

// Xóa remember_token trong cơ sở dữ liệu
if (isset($_SESSION['user_id'])) {
    require_once (__DIR__."/../../database/connect.php");
    $sql = "UPDATE User SET remember_token = NULL WHERE id = " . $_SESSION['user_id'];
    mysqli_query($conn, $sql);
}

// Hủy session
session_unset();
session_destroy();

// Chuyển hướng về trang chủ
header('Location: ../../index.php');
exit;
?>