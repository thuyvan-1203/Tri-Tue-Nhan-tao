<?php
include("header.php");
require_once("./database/connect.php");

global $conn;
// Lấy order_id từ URL
$order_id = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;
if ($order_id <= 0) {
    die("Order ID không hợp lệ: $order_id");
}
$error = '';

// Lấy thông tin đơn hàng
$order_query = "SELECT * FROM Order_Management WHERE id = ?";
$stmt = mysqli_prepare($conn, $order_query);
if (!$stmt) {
    die("Lỗi chuẩn bị truy vấn Order_Management: " . mysqli_error($conn));
}
mysqli_stmt_bind_param($stmt, "i", $order_id);
mysqli_stmt_execute($stmt);
$order_result = mysqli_stmt_get_result($stmt);
$order = mysqli_fetch_assoc($order_result);
mysqli_stmt_close($stmt);

if (!$order) {
    $error = "Đơn hàng không tồn tại hoặc đã bị xóa.";
    echo "Không tìm thấy đơn hàng với ID: $order_id<br>";
} 

// Lấy chi tiết đơn hàng
$items = [];
$total = 0;
$shipping_fee = 25000; // Giả định phí vận chuyển mặc định
if (!$error) {
    $items_query = "
        SELECT oi.product_id, oi.quantity, oi.total_money, p.name, p.product_image, c.name AS category_name
        FROM Detail_Order oi
        JOIN Product p ON oi.product_id = p.id
        JOIN Category c ON p.category_id = c.id
        WHERE oi.order_id = ?";
    $stmt = mysqli_prepare($conn, $items_query);
    if (!$stmt) {
        die("Lỗi chuẩn bị truy vấn Detail_Order: " . mysqli_error($conn));
    }
    mysqli_stmt_bind_param($stmt, "i", $order_id);
    mysqli_stmt_execute($stmt);
    $items_result = mysqli_stmt_get_result($stmt);
    while ($item = mysqli_fetch_assoc($items_result)) {
        $items[] = $item;
        $total += $item['total_money'];
    }
    mysqli_stmt_close($stmt); 
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/cart.css">
    <style>
          .check_icon_wrapper {
            text-align: center;
          }

          .check_icon {
            font-size: 100px;
            border: 5px solid black;
            display: inline-block;
            border-radius: 50%;
            padding: 5px;
            margin-top: 80px;
          }

          .check_icon i {
            width: 100px;
            height: 100px;
          }
    </style>
    <title>Xác Nhận Đơn Hàng</title>
</head>
<body>
<div class="cart-container">
    <?php if ($error): ?>
        <p style="color: red; text-align: center;"><?php echo htmlspecialchars($error); ?></p>
    <?php else: ?>
        <div class="card">
            <div class="row">
                <div class="col-md-8 cart">
                    <div class="title">
                        <div class="row">
                            <div class="col"><h4><b>Xác Nhận Đơn Hàng #<?php echo htmlspecialchars($order_id); ?></b></h4></div>
                        </div>
                    </div>
                    <div class="row main">
                        <h5>Thông Tin Giao Hàng</h5>
                        <div class="row">
                            <div class="col">Họ và tên: <?php echo htmlspecialchars($order['full_name']); ?></div>
                        </div>
                        <div class="row">
                            <div class="col">Email: <?php echo htmlspecialchars($order['email']); ?></div>
                        </div>
                        <div class="row">
                            <div class="col">Số điện thoại: <?php echo htmlspecialchars($order['phone_number']); ?></div>
                        </div>
                        <div class="row">
                            <div class="col">Địa chỉ: <?php echo htmlspecialchars($order['address']); ?></div>
                        </div>
                        <div class="row">
                            <div class="col">Ngày đặt hàng: <?php echo htmlspecialchars($order['ordered_date']); ?></div>
                        </div>
                        <div class="row">
                            <div class="col">Trạng thái: <?php echo htmlspecialchars($order['status']); ?></div>
                        </div>
                    </div>
                    <div class="row border-top">
                        <h5>Sản Phẩm</h5>
                        <?php if (count($items) > 0): ?>
                            <?php foreach ($items as $item): ?>
                                <div class="row main align-items-center">
                                    <div class="col-2"><img class="img-fluid" src="<?php echo htmlspecialchars($item['product_image']); ?>"></div>
                                    <div class="col">
                                        <div class="row text-muted"><?php echo htmlspecialchars($item['category_name']); ?></div>
                                        <div class="row"><?php echo htmlspecialchars($item['name']); ?></div>
                                    </div>
                                    <div class="col"><?php echo htmlspecialchars($item['quantity']); ?></div>
                                    <div class="col"><?php echo number_format($item['total_money']); ?> VNĐ</div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>Không có sản phẩm trong đơn hàng.</p>
                        <?php endif; ?>
                    </div>
                    <div class="back-to-shop"><a href="index.php">←<span class="text-muted">Quay lại trang chủ</span></a></div>
                </div>
                <div class="col-md-4 summary">
                    <div><h5><b>Tóm Tắt Đơn Hàng</b></h5></div>
                    <hr>
                    <div class="row">
                        <div class="col" style="padding-left:0;">SỐ LƯỢNG: <?php echo count($items); ?></div>
                        <div class="col text-right"><?php echo number_format($total); ?> VNĐ</div>
                    </div>
                    <div class="row">
                        <div class="col" style="padding-left:0;">PHÍ VẬN CHUYỂN</div>
                        <div class="col text-right"><?php echo number_format($shipping_fee); ?> VNĐ</div>
                    </div>
                    <div class="row" style="border-top: 1px solid rgba(0,0,0,.1); padding: 2vh 0;">
                        <div class="col">TỔNG CỘNG</div>
                        <div class="col text-right"><?php echo number_format($total + $shipping_fee); ?> VNĐ</div>
                    </div>
                    <div class="policy-links">
                        <p><a href="shipping-return.php" target="_blank">Chính sách giao hàng - hoàn trả</a></p>
                    </div>
                    <div class="check_icon_wrapper">
                         <div class="check_icon">
                              <i class="fa-solid fa-check"></i>
                         </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
<?php include("footer.php"); ?>