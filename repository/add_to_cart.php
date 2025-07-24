<?php
session_start();
require_once("./CartRepository.php");

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Vui lòng đăng nhập để thêm vào giỏ hàng',       
    ]);
    exit;
}

if (!isset($_POST['product_id']) || !isset($_POST['quantity']) || !isset($_POST['price'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Thiếu thông tin sản phẩm hoặc số lượng'
    ]);
    exit;
}

$user_id = (int)$_SESSION['user_id'];
$product_id = (int)$_POST['product_id'];
$quantity = (int)$_POST['quantity'];
$price = (int)$_POST['price'];

if ($quantity <= 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Số lượng phải lớn hơn 0'
    ]);
    exit;
}

$cartController = new CartRepository();
$result = $cartController->addToCart($user_id, $product_id, $quantity, $price);

echo json_encode($result);
?> 