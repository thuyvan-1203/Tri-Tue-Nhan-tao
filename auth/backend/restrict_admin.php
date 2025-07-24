<?php
//Kiểm tra quyền truy cập của người dùng
    require_once("auth.php");
    $checkCookie = Auth::loginWithCookie();
    if ($checkCookie != null) {
        if ($checkCookie['role_id'] == 0) {
            header("Location: ../../index.php");
            exit;
        }
    } else {
        header('Location: ../login.php');
        exit;
    }
?>