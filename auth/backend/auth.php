<?php // Xử lý đăng nhập, đăng ký, tự động đăng nhập
require_once (__DIR__."/../../database/connect.php") ;
session_start();
class Auth{
    // Kiểm tra có thông tin đã tôgn tại trong database hay không
    public static function checkExist($field,$value){
        global $conn;
        $sql = "select * from User where $field='$value'";
        $run = mysqli_query($conn,$sql);
        if($run->num_rows > 0){
            echo '<script>alert("'.$field.' đã tồn tại")</script>';
            return false;
        }
        return true;
    }

    // Đăng ký tài khoản
    public static function register($username, $fullname, $email, $phonenumber, $address, $password){
        global $conn;
        $hashedPassword = md5($password);
        if(Auth::checkExist("user_name",$username) && Auth::checkExist("email",$email) && Auth::checkExist("phone_number",$username)){ 
                $sql = "insert into User(user_name, full_name, email, phone_number, address, password, role_id)".
            " values('$username', '$fullname', '$email', '$phonenumber', '$address', '$hashedPassword', 0)";
            $run = mysqli_query($conn,$sql);
            echo '<script>alert("Đăng ký thành công!");
            window.location.href="./login.php";</script>';
        }
    }

    // Đăng nhập tạo session nếu tick vào ô ghi nhớ tôi sẽ tạo them cookie
    public static function login($username, $password, $remember = false) {
        global $conn;
        $run = Auth::findOneByUsernameAndPassword($username, md5($password));
        if ($run) {
            // Lưu thông tin vào session
            $_SESSION['user_id'] = $run['id'];
            $_SESSION['user_name'] = $run['user_name'];
            $_SESSION['full_name'] = $run['full_name'];
            $_SESSION['role_id'] = $run['role_id'];

            // Nếu chọn "Ghi nhớ tôi", tạo cookie với token
            if ($remember) {
                $token = bin2hex(random_bytes(16)); // Tạo token ngẫu nhiên
                $sql = "UPDATE User SET remember_token = '$token' WHERE id = " . $run['id'];
                mysqli_query($conn, $sql);

                // Lưu cookie 
                setcookie("remember_token", $token, time() + 1314000, "/");
                setcookie("user_id", $run['id'], time() + 1314000, "/");
            }
            return true;
        }
        return false;
    }

    // Tìm kiếm thông tin tài khoản trong database
    public static function findOneByUsernameAndPassword($username,$password){
        global $conn;
        $sql="select * from User where user_name = '$username' and password = '$password'";
        $run = mysqli_query($conn,$sql)->fetch_assoc();
        return $run;
    }

    // Lấy thông tin tài khoản theo id
    public static function findOneById($id) {
        global $conn;
        $sql = "SELECT * FROM User WHERE id = '$id'";
        $run = mysqli_query($conn, $sql);
        return mysqli_fetch_assoc($run);
    }

    // Tự động đăng nhập nếu có cookie
    public static function loginWithCookie() {
        global $conn;
        if (isset($_COOKIE['remember_token']) && isset($_COOKIE['user_id']) && !isset($_SESSION['user_id'])) {
            $token = $_COOKIE['remember_token'];
            $user_id = $_COOKIE['user_id'];
            $sql = "SELECT * FROM User WHERE id = '$user_id' AND remember_token = '$token'";
            $run = mysqli_query($conn, $sql);
            $result = mysqli_fetch_assoc($run);
            if ($result) {
                // Tạo lại session
                $_SESSION['user_id'] = $result['id'];
                $_SESSION['user_name'] = $result['user_name'];
                $_SESSION['full_name'] = $result['full_name'];
                $_SESSION['role_id'] = $result['role_id'];
                return $result;
            }
        }
        return isset($_SESSION['user_id']) ? Auth::findOneById($_SESSION['user_id']) : null;
    }

    // Đổi mật khẩu
    public static function changePassword($user_id, $old_password, $new_password, $confirm_password) {
        global $conn;
        
        $user = Auth::findOneById($user_id);
        // Kiểm tra mật khẩu cũ
        if ($user['password'] !== md5($old_password)) {
            $_SESSION['error_message'] = 'Mật khẩu cũ không đúng';
            return false;
        }

        // Kiểm tra mật khẩu mới và xác nhận mật khẩu
        if ($new_password !== $confirm_password) {
            $_SESSION['error_message'] = 'Mật khẩu mới và xác nhận mật khẩu không khớp';
            return false;
        }

        // Mã hóa mật khẩu mới
        $hashedNewPassword = md5($new_password);
        // Cập nhật mật khẩu mới vào database
        $sql = "UPDATE User SET password = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "si", $hashedNewPassword, $user_id);
        $run = mysqli_stmt_execute($stmt);

        if ($run) {
            // Xóa cookie remember_token nếu có
            if (isset($_COOKIE['remember_token'])) {
                setcookie("remember_token", "", time() - 3600, "/");
                setcookie("user_id", "", time() - 3600, "/");
                
                // Cập nhật remember_token trong database
                $sql = "UPDATE User SET remember_token = NULL WHERE id = ?";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "i", $user_id);
                mysqli_stmt_execute($stmt);
            }

            $_SESSION['success_message'] = 'Đổi mật khẩu thành công!';
            return true;
        } else {
            $_SESSION['error_message'] = 'Đổi mật khẩu thất bại. Vui lòng thử lại';
            return false;
        }
    }
    
}