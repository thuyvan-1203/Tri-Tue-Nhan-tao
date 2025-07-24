<?php
    ob_start(); // Bắt đầu buffer để tránh lỗi header
    include("header.php");
    require_once("./repository/CartRepository.php");
    session_start();
    $CartRepository = new CartRepository();

    //Lấy user_id từ session
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    // Kiểm tra nếu không có user_id, có thể chuyển hướng hoặc hiển thị thông báo
    if (!$user_id) {
        echo "<script>
            alert('Vui lòng đăng nhập để vào giỏ hàng');
            window.location.href = 'login.php';
        </script>";
        exit;
    }

    // Lấy danh sách sản phẩm trong giỏ hàng
    $CartItem = $CartRepository->findByUserId($user_id);

    // Khởi tạo phí vận chuyển
    $shipping_fee = isset($_POST['shipping']) ? (int)$_POST['shipping'] : 25000; // Mặc định là giao hàng tiêu chuẩn

    // Xử lý cập nhật số lượng sản phẩm
    if (isset($_POST['update'])) {
        $product_id = $_POST['product_id'];
        $quantity = $_POST['quantity'];
        // Cập nhật số lượng sản phẩm trong giỏ hàng
        $CartRepository->updateQuantity($user_id, $product_id, $quantity);
        header("Location: cart.php"); // Chuyển hướng lại trang giỏ hàng
        exit;
    }

    // Xử lý xóa sản phẩm
    if (isset($_POST['delete'])) {
        $product_id = $_POST['product_id'];
        $CartRepository->delete($user_id, $product_id);
        header("Location: cart.php"); // Chuyển hướng lại trang giỏ hàng
        exit;  
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ Hàng</title>
</head>
<body>

<div class="cart-container">
    <div class="card">
        <div class="row">
            <div class="col-md-8 cart">
                <div class="title">
                    <div class="row">
                        <div class="col"><h4><b>Giỏ Hàng</b></h4></div>
                        <div class="col align-self-center text-right text-muted" style="text-align: right;">
                            Sản phẩm trong giỏ : <?php echo mysqli_num_rows($CartItem)?> 
                        </div>
                    </div>
                </div>    
                <!-- Sử dụng vòng lặp while để bao toàn bộ khung giỏ hàng hiển thị sản phẩm -->
                <?php 
                    $total = 0;
                    if (mysqli_num_rows($CartItem) > 0) {
                        while ($item = mysqli_fetch_assoc($CartItem)) { // Để biến item hiển thị sản phẩm ở dưới tiếp cận được dữ liệu
                            $subtotal = $item['price'] * $item['quantity'];
                            $total += $subtotal;    
                ?>
                        <div class="row border-top border-bottom">
                            <div class="row main align-items-center">
                                <div class="col-2"><img class="img-fluid" src="<?php echo $item['product_image']; ?>"></div>
                                <div class="col">
                                    <div class="row text-muted"><?php echo $item['category_name']; ?></div>
                                    <div class="row"><?php echo $item['name']; ?></div>
                                </div>
                                <div class="col">
                                    <form method="POST">
                                        <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                                        <input type="number" name="quantity" class="border" value="<?php echo $item['quantity']; ?>" min="1" style="width: 50px; text-align: center;" onchange="this.form.submit();">
                                        <input type="hidden" name="update" value="1">
                                    </form>
                                </div>
                                <div class="col">
                                <?php echo number_format($subtotal)?> VNĐ  <!-- Hiện thị giá tiền sản phẩm x số lượng sản phẩm --> 
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                                    <input type="hidden" name="delete" value="1"> 
                                    <button type="submit" style="border: 0;"><i class="fa-solid fa-x"></i></button>
                                </form>
                                </div>
                            </div>
                        </div>
                <?php
                    }
                } else {
                    echo '<p>Giỏ hàng của bạn đang trống.</p>';
                }
                ?>
                <div class="back-to-shop"><a href="shop.php">←<span class="text-muted">Tiếp tục mua sắm</span></a></div>
            </div>
            <div class="col-md-4 summary">
                <div><h5><b>Tóm Tắt Đơn Hàng</b></h5></div>
                <hr>
                <div class="row">
                    <div class="col" style="padding-left:0;">SỐ LƯỢNG: <?php echo mysqli_num_rows($CartItem)?></div>
                    <div class="col text-right"><?php echo number_format($total)?> VNĐ</div> <!-- Hiện thị tổng số lượng sản phẩm trong giỏ hàng -->
                </div>
                <form  method="POST">
                    <p>SHIPPING</p>
                    <select name="shipping" onchange="this.form.submit();">
                        <option value="25000" <?php echo $shipping_fee == 25000 ? 'selected' : ''; ?>>Giao hàng tiêu chuẩn - 25.000 VNĐ</option>
                        <option value="35000" <?php echo $shipping_fee == 35000 ? 'selected' : ''; ?>>Giao hàng nhanh - 35.000 VNĐ</option>
                        <option value="65000" <?php echo $shipping_fee == 65000 ? 'selected' : ''; ?>>Giao hàng hỏa tốc - 65.000 VNĐ (Nội thành)</option>
                        <option value="0" <?php echo $shipping_fee == 0 ? 'selected' : ''; ?>>Free Ship Cái Đéo</option>
                    </select>
                    <p>CODE</p>
                    <input id="code" name="code" placeholder="Nhập mã giảm giá" onclick="alert('Chức năng chưa được phát triển')">
                    <div class="row" style="border-top: 1px solid rgba(0,0,0,.1); padding: 2vh 0;">
                        <div class="col">TỔNG CỘNG</div>
                        <div class="col text-right"><?php echo number_format($total + $shipping_fee);?> VNĐ</div>
                    </div>
                    <a class="btn" href="./checkout.php" style="font-size: large;">Thanh Toán</a>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>
<?php include("footer.php"); ?>