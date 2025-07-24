<?php
require_once(__DIR__ . "../../auth/backend/auth.php");

header('Content-Type: application/json');
$response = ['success' => false, 'message' => ''];

if (!isset($_SESSION['user_id'])) {
    $response['message'] = 'Phiên đăng nhập đã hết hạn';
    echo json_encode($response);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'change_password') {
        $user_id = $_POST['user_id'] ?? '';
        $old_password = $_POST['old_password'] ?? '';
        $new_password = $_POST['new_password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        if (empty($user_id) || empty($old_password) || empty($new_password) || empty($confirm_password)) {
            $response['message'] = 'Vui lòng điền đầy đủ thông tin';
            echo json_encode($response);
            exit;
        }

        $result = Auth::changePassword($user_id, $old_password, $new_password, $confirm_password);

        if ($result) {
            $response['success'] = true;
            $response['message'] = $_SESSION['success_message'] ?? 'Đổi mật khẩu thành công';
        } else {
            $response['message'] = $_SESSION['error_message'] ?? 'Đổi mật khẩu thất bại';
        }
    }
}
echo json_encode($response);
?>