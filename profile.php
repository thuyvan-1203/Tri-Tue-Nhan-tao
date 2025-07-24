<?php
require_once("auth/admin/controller/UserController.php");
require_once("auth/admin/controller/OrderController.php");
session_start();

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
// Kiểm tra nếu không có user_id, có thể chuyển hướng hoặc hiển thị thông báo
if (!$user_id) {
    echo "<script>
        alert('Vui lòng đăng nhập để vào hồ sơ');
        window.location.href = 'login.php';
    </script>";
    exit;
}
$userController = new UserController();
$user = $userController->getById($user_id);

$orderController = new OrderController();
$orders = $orderController->getByUserId($user_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="./img/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="./css/profile.css">
    <title>Hồ sơ người dùng</title>
</head>
<body>
    <?php include("header.php"); ?>

    <div class="avatar-container">
        <div class="user-avatar">
            <i class="fa-solid fa-user"></i>
        </div>
    </div>

    <div class="content-container">
        <div class="card">
            <div class="container profile-container">
                <!-- Xem thông tin người dùng -->
                <div class="user_profile">
                    <div class="profile-header">
                        <h2>Hồ sơ người dùng</h2>
                        <p>Xem và chỉnh sửa thông tin cá nhân của bạn</p>
                    </div>
            
                    <div class="profile-info">
                        <p><label>Họ tên:</label> <?php echo htmlspecialchars($user['full_name']); ?></p>
                        <p><label>Email:</label> <?php echo htmlspecialchars($user['email']); ?></p>
                        <p><label>Số điện thoại:</label> <?php echo htmlspecialchars($user['phone_number'] ?? '-'); ?></p>
                        <p><label>Địa chỉ:</label> <?php echo htmlspecialchars($user['address'] ?? '-'); ?></p>
                        <p><label>Vai trò:</label> <?php echo $user['role_id'] == 1 ? 'Admin' : 'User'; ?></p>
                    </div>
            
                    <div class="action_button">
                        <button class="btn btn-primary" onclick="editUser(<?php echo $user['id']; ?>)">
                            <i class="fa-solid fa-pencil"></i> Chỉnh sửa hồ sơ
                        </button>
                
                        <a href="./auth/backend/logoutCookie.php" class="btn btn-danger">
                            <i class="fa-solid fa-right-to-bracket"></i> Đăng xuất
                        </a>
                    </div>
                </div>
        
                <!-- Xem đơn hàng -->
                <div class="user_order">
                    <div id="orders" class="content-section">
                        <div class="profile-header">
                            <h2>Quản lý đơn hàng</h2>
                            <p>Xem chi tiết và trạng thái đơn hàng của bạn</p>
                        </div>
                        <div class="card mt-3">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Mã đơn hàng</th>
                                                <th>Tổng tiền</th>
                                                <th>Trạng thái</th>
                                                <th>Thời gian đặt hàng</th>
                                                <th>Thao tác</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                if ($orders && mysqli_num_rows($orders) > 0) {
                                                    while($order = mysqli_fetch_assoc($orders)) {
                                                        // Xác định màu dựa trên trạng thái đơn hàng
                                                        switch($order['status']) {
                                                            case 'Đang chờ xác nhận':
                                                                $statusClass = 'text-warning';
                                                                break;
                                                            case 'Đã xác nhận':
                                                                $statusClass = 'text-primary';
                                                                break;
                                                            case 'Đang tiến hàng':
                                                                $statusClass = 'text-info';
                                                                break;
                                                            case 'Đã hủy':
                                                                $statusClass = 'text-danger';
                                                                break;
                                                            case 'Đã hoàn thành':
                                                                $statusClass = 'text-success';
                                                                break;
                                                            default:
                                                                $statusClass = 'text-secondary';
                                                                break;
                                                        }
                                                        // Tính tổng tiền từ chi tiết đơn hàng nếu không có total_amount trực tiếp
                                                        $details_result = $orderController->getOrderDetails($order['id']);
                                                        $total_amount = 0;
                                                        if ($details_result && mysqli_num_rows($details_result) > 0) {
                                                            while ($detail = mysqli_fetch_assoc($details_result)) {
                                                                $total_amount += $detail['total_money'];
                                                            }
                                                        }
                                            ?>
                                            <tr>
                                                <td>#<?php echo $order['id']; ?></td>
                                                <td><?php echo number_format($total_amount); ?> VNĐ</td>
                                                <td><span class="<?php echo $statusClass; ?>"><?php echo $order['status']; ?></span></td>
                                                <td><?php echo $order['ordered_date'] ?></td>
                                                <td>
                                                    <button class="btn btn-primary" onclick="viewOrder(<?php echo $order['id']; ?>)">
                                                        <i class="fa-solid fa-eye"></i> Xem chi tiết
                                                    </button>
                                                </td>
                                            </tr>
                                            <?php
                                                    }
                                                } else {
                                            ?>
                                            <tr>
                                                <td colspan="5" class="text-center">Không có đơn hàng nào</td>
                                            </tr>
                                            <?php
                                                }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        
            <!-- Modal chỉnh sửa hồ sơ -->
            <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editProfileModalLabel">Chỉnh sửa hồ sơ</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="editUser(<?php echo $user['id']; ?>)"></button>
                        </div>
                        <div class="modal-body">
                            <form id="editProfileForm" method="POST">
                                <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                                <input type="hidden" name="role_id" value="<?php echo $user['role_id']; ?>">
                                <div class="mb-3">
                                    <label for="editFullname" class="form-label">Họ tên</label>
                                    <input type="text" class="form-control" id="editFullname" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="editEmail" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="editEmail" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="editPhone" class="form-label">Số điện thoại</label>
                                    <input type="text" class="form-control" id="editPhone" name="phone_number" value="<?php echo htmlspecialchars($user['phone_number'] ?? ''); ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="editAddress" class="form-label">Địa chỉ</label>
                                    <input type="text" class="form-control" id="editAddress" name="address" value="<?php echo htmlspecialchars($user['address'] ?? ''); ?>">
                                </div>
                                <input type="hidden" name="action" value="edit_user">
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                            <button type="button" class="btn btn-warning" onclick="openChangePasswordModal()">Đổi mật khẩu</button>
                            <button type="submit" form="editProfileForm" class="btn btn-primary">Lưu thay đổi</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal đổi mật khẩu -->
    <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="changePasswordModalLabel">Đổi mật khẩu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="changePasswordForm" method="POST">
                        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                        <div class="mb-3">
                            <label for="oldPassword" class="form-label">Mật khẩu cũ</label>
                            <input type="password" class="form-control" id="oldPassword" name="old_password" required>
                        </div>
                        <div class="mb-3">
                            <label for="newPassword" class="form-label">Mật khẩu mới</label>
                            <input type="password" class="form-control" id="newPassword" name="new_password" required>
                        </div>
                        <div class="mb-3">
                            <label for="confirmPassword" class="form-label">Xác nhận mật khẩu mới</label>
                            <input type="password" class="form-control" id="confirmPassword" name="confirm_password" required>
                        </div>
                        <input type="hidden" name="action" value="change_password">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" form="changePasswordForm" class="btn btn-primary">Lưu thay đổi</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Xem chi tiết đơn hàng -->
    <div class="modal fade" id="viewOrderModal" tabindex="-1" aria-labelledby="viewOrderModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewOrderModalLabel">Chi tiết đơn hàng</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="orderDetails">
                        <p><strong>Mã đơn hàng:</strong> <span id="orderId"></span></p>
                        <p><strong>Khách hàng:</strong> <span id="orderCustomer"></span></p>
                        <p><strong>Email:</strong> <span id="orderEmail"></span></p>
                        <p><strong>Địa chỉ:</strong> <span id="orderAddress"></span></p>
                        <p><strong>Số điện thoại:</strong> <span id="orderPhoneNumber"></span></p>
                        <p><strong>Ngày đặt hàng:</strong> <span id="orderDate"></span></p>
                        <p><strong>Trạng thái:</strong> <span id="orderStatus"></span></p>
                        <h6>Chi tiết sản phẩm:</h6>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Hình ảnh</th>
                                    <th>Tên sản phẩm</th>
                                    <th>Số lượng</th>
                                    <th>Giá</th>
                                    <th>Tổng</th>
                                </tr>
                            </thead>
                            <tbody id="orderItems"></tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>
    <script src="js/profile_user.js"></script>
    <script src="js/profile_order.js"></script>
    <?php include("footer.php"); ?>
</body>
</html>