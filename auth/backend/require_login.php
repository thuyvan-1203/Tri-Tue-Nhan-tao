<?php
    // Kiểm tra cookie và đăng nhập tự động
    require_once("auth.php");
    $checkCookie = Auth::loginWithCookie();
    if($checkCookie == null){
        header("Location: auth/login");
    }
?>