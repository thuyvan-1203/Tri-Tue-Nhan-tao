<?php
require_once(__DIR__ . "/../../../database/connect.php");

class OrderController {
    public function getAll($limit = null) {
        global $conn;
        $sql = "SELECT o.id, o.user_id, u.full_name, u.email, u.phone_number, o.ordered_date, o.status 
                FROM `Order_Management` o 
                JOIN User u ON u.id = o.user_id 
                ORDER BY o.ordered_date DESC";
        if ($limit !== null) {
            $sql .= " LIMIT 0," . (int)$limit;
        }
        return mysqli_query($conn, $sql);
    }

    public function getById($id) {
        global $conn;
        if (!is_numeric($id) || $id <= 0) return false;
        $id = (int)$id;
        $sql = "SELECT o.*, u.full_name, u.email, u.phone_number 
                FROM `Order_Management` o 
                JOIN User u ON u.id = o.user_id 
                WHERE o.id = $id";
        $result = mysqli_query($conn, $sql);
        if (!$result) {
            error_log("SQL Error: " . mysqli_error($conn));
            return false;
        }
        return $result;
    }

    public function getByUserId($user_id) {
        global $conn;
        if (!is_numeric($user_id) || $user_id <= 0) return false;
        $user_id = (int)$user_id;
        $sql = "SELECT o.id, o.user_id, u.full_name, u.email, u.phone_number, o.ordered_date, o.status 
                FROM `Order_Management` o 
                JOIN User u ON u.id = o.user_id 
                WHERE o.user_id = $user_id 
                ORDER BY o.ordered_date DESC";
        $result = mysqli_query($conn, $sql);
        if (!$result) {
            error_log("SQL Error: " . mysqli_error($conn));
            return false;
        }
        return $result;
    }

    public function getOrderDetails($order_id) {
        global $conn;
        if (!is_numeric($order_id) || $order_id <= 0) return false;
        $order_id = (int)$order_id;
        $sql = "SELECT od.*, p.name as product_name, p.product_image 
                FROM `Detail_Order` od 
                JOIN Product p ON p.id = od.product_id 
                WHERE od.order_id = $order_id";
        $result = mysqli_query($conn, $sql);
        if (!$result) {
            error_log("SQL Error: " . mysqli_error($conn));
            return false;
        }
        return $result;
    }

    public function updateStatus($id, $status) {
        global $conn;
        if (!is_numeric($id) || $id <= 0) return false;
        $id = (int)$id;
        $status = mysqli_real_escape_string($conn, $status);
        $sql = "UPDATE `Order_Management` 
                SET status = '$status'
                WHERE id = $id";
        return mysqli_query($conn, $sql);
    }

    public function deleteById($id) {
        global $conn;
        if (!is_numeric($id) || $id <= 0) return false;
        $id = (int)$id;
        $sql_detail = "DELETE FROM `Detail_Order` WHERE order_id = $id";
        mysqli_query($conn, $sql_detail);
        $sql = "DELETE FROM `Order_Management` WHERE id = $id";
        return mysqli_query($conn, $sql);
    }
}
?>