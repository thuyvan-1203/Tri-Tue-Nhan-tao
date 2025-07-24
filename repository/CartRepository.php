<?php
     require_once(__DIR__."../../database/connect.php");
     class CartRepository{
          // THÊM SẢN PHẨM VÀO GIỎ HÀNG
          public function addToCart($user_id, $product_id, $quantity, $price) {
               global $conn;
               try {
                   // Kiểm tra xem sản phẩm đã có trong giỏ hàng chưa
                   $check_sql = "SELECT id, quantity FROM cart 
                                WHERE user_id = ? AND product_id = ?";
                   $stmt = $conn->prepare($check_sql);
                   $stmt->bind_param("ii", $user_id, $product_id);
                   $stmt->execute();
                   $result = $stmt->get_result();
                   
                   if ($result->num_rows > 0) {
                       $cart_item = $result->fetch_assoc();
                       $new_quantity = $cart_item['quantity'] + $quantity;
                       
                       $update_sql = "UPDATE cart 
                                    SET quantity = ?, price = ?
                                    WHERE id = ?";
                       $stmt = $conn->prepare($update_sql);
                       $stmt->bind_param("idi", $new_quantity, $price, $cart_item['id']);
                       if ($stmt->execute()) {
                           return [
                               'success' => true,
                               'message' => 'Cập nhật giỏ hàng thành công'
                           ];
                       }
                   } else {
                       // Nếu sản phẩm chưa có, thêm mới
                       $insert_sql = "INSERT INTO cart (user_id, product_id, quantity, price) 
                                    VALUES (?, ?, ?, ?)";
                       $stmt = $conn->prepare($insert_sql);
                       $stmt->bind_param("iiid", $user_id, $product_id, $quantity, $price);
                       if ($stmt->execute()) {
                           return [
                               'success' => true,
                               'message' => 'Thêm vào giỏ hàng thành công'
                           ];
                       }
                   }
                   return [
                       'success' => false,
                       'message' => 'Không thể thêm hoặc cập nhật giỏ hàng'
                   ];
               } catch (Exception $e) {
                   return [
                       'success' => false,
                       'message' => 'Lỗi: ' . $e->getMessage()
                   ];
               }
           }

          //Tìm sản phẩm trong giỏ hàng theo user_id và product_id
          public function findByUserIdAndProductId($user_id, $product_id){
               global $conn;
               $sql = "SELECT * FROM cart WHERE user_id = $user_id AND product_id = $product_id";
               return mysqli_query($conn, $sql);
          }

          // Lấy tất cả sản phẩm trong giỏ hàng của người dùng
          public function findByUserId($user_id) {
               global $conn;
               $sql = "SELECT c.*, p.name, p.product_image, p.category_id, cat.name AS category_name 
                    FROM cart c 
                    JOIN Product p ON c.product_id = p.id 
                    JOIN Category cat ON p.category_id = cat.id 
                    WHERE c.user_id = $user_id";
               return mysqli_query($conn, $sql);
          }

          // Cập nhật số lượng sản phẩm
          public function updateQuantity($user_id, $product_id, $quantity) {
               global $conn;
               $sql = "UPDATE cart SET quantity = $quantity WHERE user_id = $user_id AND product_id = $product_id";
               return mysqli_query($conn, $sql);
          }

          // Xóa sản phẩm khỏi giỏ hàng
          public function delete($user_id, $product_id) {
               global $conn;
               $sql = "DELETE FROM cart WHERE user_id = $user_id AND product_id = $product_id";
               return mysqli_query($conn, $sql);
          }
     }
?>