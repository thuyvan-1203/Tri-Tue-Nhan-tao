<?php
require_once("auth/admin/controller/ProductController.php");

header('Content-Type: application/json');

$productController = new ProductController();

// Lấy và kiểm tra category_id
$category_id = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 1; // Mặc định là 1 (Cốc) nếu không có
if ($category_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid category ID']);
    exit;
}

// Lấy và kiểm tra sort
$valid_sorts = ['default', 'price_asc', 'price_desc'];
$sort = isset($_GET['sort']) && in_array($_GET['sort'], $valid_sorts) ? $_GET['sort'] : 'default';

// Lấy danh sách sản phẩm
$products = $productController->getByCategory($category_id, null, $sort);
$product_list = [];

if ($products && mysqli_num_rows($products) > 0) {
    while ($product = mysqli_fetch_assoc($products)) {
        $percent_sale = ($product['price'] - $product['sale_price']) / $product['price'] * 100;
        $product_list[] = [
            'id' => $product['id'],
            'name' => $product['name'],
            'price' => number_format($product['price']),
            'sale_price' => number_format($product['sale_price']),
            'description' => htmlspecialchars($product['description']),
            'product_image' => $product['product_image'],
            'product_image_2' => $product['product_image_2'],
            'product_image_3' => $product['product_image_3'],
            'category_name' => $product['category_name'],
            'sale_percent' => round($percent_sale)
        ];
    }
}

echo json_encode([
    'success' => true,
    'products' => $product_list
]);
?>