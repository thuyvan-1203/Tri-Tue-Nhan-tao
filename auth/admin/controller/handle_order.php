<?php
// Hiện lỗi
ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');
require_once("OrderController.php");


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $orderController = new OrderController();

        if (isset($_POST['action'])) {
            if ($_POST['action'] == 'get_order') {
                $id = (int)$_POST['id'];
                $result = $orderController->getById($id);
                
                if ($result && mysqli_num_rows($result) > 0) {
                    $order = mysqli_fetch_assoc($result);
                    $details_result = $orderController->getOrderDetails($id);
                    $details = [];
                    if ($details_result) {
                        while ($row = mysqli_fetch_assoc($details_result)) {
                            $details[] = $row;
                        }
                    }
                    echo json_encode(['success' => true, 'order' => $order, 'details' => $details]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Không tìm thấy đơn hàng.']);
                }
                exit;
            }

            elseif ($_POST['action'] == 'delete_order') {
                $id = (int)$_POST['id'];
                if ($orderController->deleteById($id)) {
                    echo json_encode(['success' => true, 'message' => 'Xóa đơn hàng thành công']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Lỗi khi xóa đơn hàng.']);
                }
                exit;
            }

            elseif ($_POST['action'] == 'update_status') {
                if (empty($_POST['id']) || empty($_POST['status'])) {
                    echo json_encode(['success' => false, 'message' => 'Vui lòng cung cấp đầy đủ thông tin.']);
                    exit;
                }
                
                $id = (int)$_POST['id'];
                $status = $_POST['status'];
                $validStatuses = [
                    'Đang chờ xác nhận',
                    'Đã xác nhận',
                    'Đã hủy',
                    'Đang tiến hành',
                    'Đã hoàn thành'
                ];
                
                if (!in_array($status, $validStatuses)) {
                    echo json_encode(['success' => false, 'message' => 'Trạng thái không hợp lệ.']);
                    exit;
                }

                $result = $orderController->getById($id);
                if (!$result || mysqli_num_rows($result) == 0) {
                    echo json_encode(['success' => false, 'message' => 'Đơn hàng không tồn tại.']);
                    exit;
                }

                if ($orderController->updateStatus($id, $status)) {
                    echo json_encode(['success' => true, 'message' => 'Cập nhật trạng thái thành công.']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Lỗi khi cập nhật trạng thái.']);
                }
                exit;
            }
        }
        echo json_encode(['success' => false, 'message' => 'Hành động không hợp lệ']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()]);
    }
    exit;
}

echo json_encode(['success' => false, 'message' => 'Phương thức không được hỗ trợ']);
?>