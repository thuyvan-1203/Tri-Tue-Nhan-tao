<?php
require_once(__DIR__ . "/database/connect.php");

// Lấy từ khóa tìm kiếm
$query = isset($_POST['query']) ? $_POST['query'] : '';
$query = mysqli_real_escape_string($conn, $query);
$search_term = "%$query%";

// Truy vấn tìm kiếm sản phẩm
$sql = "SELECT p.*, c.name AS category_name 
        FROM product p 
        LEFT JOIN category c ON p.category_id = c.id 
        WHERE p.name LIKE '$search_term' ";
$result = mysqli_query($conn, $sql);

if (!$result) {
    echo '<p>Có lỗi xảy ra khi tìm kiếm. Vui lòng thử lại.</p>';
    exit;
}

if (mysqli_num_rows($result) > 0) {
    echo '<div class="row container-fluid">';
    while ($product = mysqli_fetch_assoc($result)) {
        ?>
        <div class="col-sm-6 col-md-4 col-lg-3 mt-4">
            <div class="home-product-item" onclick="showProductPopup(event)"
                data-id="<?php echo $product['id']; ?>" 
                data-name="<?php echo htmlspecialchars($product['name']); ?>"
                data-price="<?php echo number_format($product['price'], 0, ',', '.'); ?>đ"
                data-sale-price="<?php echo number_format($product['sale_price'], 0, ',', '.'); ?>đ"
                data-description="<?php echo htmlspecialchars($product['description']); ?>"
                data-image="<?php echo $product['product_image']; ?>"
                data-image2="<?php echo $product['product_image_2']; ?>"
                data-image3="<?php echo $product['product_image_3']; ?>">
                
                <a class="home-product__img">
                    <div class="home-product-item__img" style="background-image: url(<?php echo $product['product_image']; ?>);"></div>
                </a>
                
                <div class="home-product__name">
                    <div class="home-product-item__name"><?php echo htmlspecialchars($product['name']); ?></div>
                </div>
                
                <div class="header__cart-item-price-wrap">
                    <span class="home-product-item__price"><?php echo number_format($product['sale_price'], 0, ',', '.'); ?>đ</span>
                </div>
            </div>
        </div>
        <?php
    }
    echo '</div>';
} else {
    echo '<p>Không tìm thấy sản phẩm nào phù hợp với "' . htmlspecialchars($query) . '".</p>';
}
?>