<?php
require_once(__DIR__ . "../../database/connect.php");

class OrderRepository {
    public function createOrder($user_id, $full_name, $email, $phone_number, $address) {
        global $conn;
        $sql = "INSERT INTO Order_Management (user_id, full_name, email, phone_number, address, ordered_date, status) 
                VALUES (?, ?, ?, ?, ?, NOW(), 'Đang chờ xác nhận')";
        $stmt = mysqli_prepare($conn, $sql);
        if (!$stmt) {
            error_log("Lỗi chuẩn bị truy vấn createOrder: " . mysqli_error($conn));
            return false;
        }
        mysqli_stmt_bind_param($stmt, "issss", $user_id, $full_name, $email, $phone_number, $address);
        $result = mysqli_stmt_execute($stmt);
        if (!$result) {
            error_log("Lỗi thực thi createOrder: " . mysqli_error($conn));
            mysqli_stmt_close($stmt);
            return false;
        }
        $order_id = mysqli_insert_id($conn);
        mysqli_stmt_close($stmt);
        return $order_id;
    }

    public function addOrderDetail($order_id, $product_id, $quantity, $total_money) {
        global $conn;
         // Lấy giá từ bảng Product
         $sql_price = "SELECT sale_price,price FROM Product WHERE id = " . (int)$product_id;
         $result_price = mysqli_query($conn, $sql_price);
         
         $row = mysqli_fetch_assoc($result_price);
         // Nếu sale_price = 0 thì lấy price
         $price = ($row['sale_price'] == 0) ? $row['price'] : $row['sale_price'];
         $total_money = $price * $quantity;
 
         // Chèn vào bảng Detail_Order
         $sql = "INSERT INTO Detail_Order (order_id, product_id, price, quantity, total_money) VALUES (" . (int)$order_id . ", " . (int)$product_id . ",".(int)$price.", " . (int)$quantity . ", " . (float)$total_money . ")";
         return mysqli_query($conn, $sql);
    }

    public function clearCart($user_id) {
        global $conn;
        // Ép kiểu user_id thành số nguyên
        $user_id = (int)$user_id;
        $sql = "DELETE FROM Cart WHERE user_id = $user_id";
        $result = mysqli_query($conn, $sql);
        if (!$result) {
            error_log("Lỗi thực thi clearCart: " . mysqli_error($conn));
            return false;
        }
        return true;
    }
}
?>