<?php
require_once("../../../database/connect.php");
require_once("UserController.php");

header('Content-Type: application/json');
$response = ['success' => false, 'message' => ''];

// Khởi tạo controller
$userController = new UserController();

// Kiểm tra action được gửi lên
if(isset($_POST['action'])) {
    $action = $_POST['action'];
    
    switch($action) {
        case 'get_all_users':
            // Lấy tất cả người dùng
            $users = $userController->getAll();
            $usersList = [];
            
            while($user = mysqli_fetch_assoc($users)) {
                $usersList[] = $user;
            }
            
            $response['success'] = true;
            $response['users'] = $usersList;
            break;
            
        case 'get_user':
            // Lấy thông tin một người dùng
            if(isset($_POST['id'])) {
                $id = intval($_POST['id']);
                $user = $userController->getById($id);
                
                if($user) {
                    $response['success'] = true;
                    $response['user'] = $user;
                } else {
                    $response['message'] = 'Không tìm thấy người dùng';
                }
            } else {
                $response['message'] = 'ID người dùng không hợp lệ';
            }
            break;
            
          case 'edit_user':
          // Cập nhật thông tin người dùng
          if(isset($_POST['id']) && isset($_POST['full_name']) && isset($_POST['email']) && isset($_POST['role_id'])) {
               $id = intval($_POST['id']);
               $fullname = $_POST['full_name'];
               $email = $_POST['email'];
               $phone = $_POST['phone_number'] ?? '';
               $address = $_POST['address'] ?? '';
               $role = intval($_POST['role_id']);
               
               try {
                    $userController->updateById($id, $fullname, $email, $phone, $address, $role);
                    $response['success'] = true;
                    $response['message'] = 'Cập nhật người dùng thành công';
               } catch(Exception $e) {
                    $response['message'] = 'Lỗi: ' . $e->getMessage();
               }
          } else {
               $response['message'] = 'Thiếu thông tin cần thiết';
          }
          break;
        case 'delete_user':
            // Xóa người dùng
            if(isset($_POST['id'])) {
                $id = intval($_POST['id']);
                
                try {
                    $userController->deleteById($id);
                    $response['success'] = true;
                    $response['message'] = 'Xóa người dùng thành công';
                } catch(Exception $e) {
                    $response['message'] = 'Lỗi: ' . $e->getMessage();
                }
            } else {
                $response['message'] = 'ID người dùng không hợp lệ';
            }
            break;
            
        default:
            $response['message'] = 'Action không hợp lệ';
            break;
    }
} else {
    $response['message'] = 'Thiếu tham số action';
}

echo json_encode($response);
?>