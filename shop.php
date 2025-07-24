<?php
    include("header.php")
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!-- Google Font -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Public+Sans:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
        <title>Cốc</title>
        <script src="js/menu.js"></script>

    </head>
    <body>
        <div class="app">
            <div class="container">
                <div class="grid">
                    <div class="app__content">       
                        <div class="home-menu__bar">
                            <div class="home-product__title">
                                <h1 class="home-Product__title_text">Cốc</h1>
                                <p class="home-Product__title_text">Trang Chủ / Cốc</p>
                            </div>
                            <div class="home-filter">
                                <a href="shop.php" class="home-filter__btn Select-fil ">
                                    <img class="menu-img" src="./img/menu__Mug.jpg " alt="">
                                    Cốc
                                </a>
                                <a href="product_pots.php" class="home-filter__btn ">
                                    <img class="menu-img" src="./img/menu__Pots.jpg" alt="">
                                    Chậu
                                </a>
                                <a href="product_plates.php" class="home-filter__btn">
                                    <img class="menu-img" src="./img/menu__Plates.jpg" alt="">
                                    Đĩa
                                </a>
                                <a href="product_decor.php" class="home-filter__btn">
                                    <img class="menu-img" src="./img/menu__Decor.jpg" alt="">
                                    Trang Trí
                                </a>
                                <a href="product_bowl.php" class="home-filter__btn">
                                    <img class="menu-img" src="./img/menu__Bowl.jpg" alt="">
                                    Bát
                                </a>
                            </div>
                        </div>
                        <div class="home-product">
                            <div class="home-product_sorting">
                                <p class="home-product_sorting-title">SẢN PHẨM ĐẶC BIỆT</p>
                                <div class="product-sorting">
                                    <select class="sorting-dropdown"  id="sortingDropdown" name="sorting">
                                        <option value="default">Sắp xếp mặc định</option>
                                        <option value="price_asc">Sắp xếp theo giá: thấp đến cao</option>
                                        <option value="price_desc">Sắp xếp theo giá: cao đến thấp</option>
                                    </select>
                                </div>
                            </div>  
                            <div class="row container-fluid">
                                <!-- Product items -->
                                <?php
                                    require_once("auth/admin/controller/ProductController.php");
                                    $productController = new ProductController();
                                    $sort = isset($_GET['sort']) ? $_GET['sort'] : 'default';
                                    // Lấy danh sách sản phẩm
                                    $products = $productController->getByCategory(1, null, $sort);

                                    if($products && mysqli_num_rows($products) > 0) {
                                        while($product = mysqli_fetch_assoc($products)) {
                                ?>
                                <div class="col-sm-6 col-md-4 col-lg-3 mt-4">
                                    <div class="home-product-item" onclick="showProductPopup(event)"
                                        data-id="<?php echo $product['id']; ?>" 
                                        data-name="<?php echo $product['name']; ?>"
                                        data-price="<?php echo number_format($product['price']); ?>đ"
                                        data-sale-price="<?php echo number_format($product['sale_price'] == 0 ? $product['price'] : $product['sale_price']); ?>đ"
                                        data-description="<?php echo htmlspecialchars($product['description']); ?>"
                                        data-image="<?php echo $product['product_image']; ?>"
                                        data-image2="<?php echo $product['product_image_2']; ?>"
                                        data-image3="<?php echo $product['product_image_3']; ?>">
                                        
                                        <a class="home-product__img">
                                            <div class="home-product-item__img" style="background-image: url(<?php echo $product['product_image']; ?>);"></div>
                                        </a>
                                        
                                        <div class="home-product__name">
                                            <div class="home-product-item__name"><?php echo $product['name']; ?></div>
                                        </div>
                                        
                                        <?php
                                        if ($product['sale_price'] != 0) {
                                        ?>
                                            <div class="home-product-item__sale-off">
                                                <span class="home-product-item__sale-off-percent"><?php
                                                    $percent_sale = ($product['price'] - $product['sale_price']) / $product['price'] * 100;
                                                    echo -round($percent_sale) . '%';
                                                ?></span>
                                            </div>
                                        <?php
                                        }
                                        ?>
                                        
                                        <div class="header__cart-item-price-wrap">
                                            <span class="home-product-item__price">
                                                <?php 
                                                if($product['sale_price'] == 0) {
                                                    echo number_format($product['price'], 0, ',', '.').'đ';
                                                }else{
                                                echo number_format($product['sale_price'], 0, ',', '.').'đ'; 
                                                }?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                        }
                                    } else {
                                        echo '<div class="col-12 text-center">Không có sản phẩm nào.</div>';
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Popup -->
        <div id="overlay" class="overlay"></div>
        <div id="popup" class="popup">
            <div id="popup__close" onclick="closeProductPopup()">
                <i class="popup__icon fa-solid fa-xmark"></i>
            </div>
            
            <div class="product">
                <div class="product__gallery">
                    <div class="gallery__item gallery__item--large">
                        <img id="popupMainImage" src="" alt="" class="w-100 h-auto">
                    </div>
                    <div class="gallery__row">
                        <div id="popupImage2Container" class="gallery__item gallery__item--medium">
                            <img id="popupImage2" src="" alt="" class="w-100 h-auto">
                        </div>
                        <div id="popupImage3Container" class="gallery__item gallery__item--small">
                            <img id="popupImage3" src="" alt="" class="w-100 h-auto">
                        </div>
                    </div>
                </div>
            
                <div class="product__info">
                    <h1 id="popupTitle" class="product__title"></h1>
                    <p class="product__price">
                        <del id="popupOriginalPrice"></del>
                        <strong id="popupSalePrice" class="product__discount"></strong>
                    </p>
                    <div class="product__cart">
                        <button class="product__cart-reduce" >-</button>
                        <div class="product__cart-input" id="quantity">0</div>
                        <button class="product__cart-increase" >+</button>
                        <button class="product__cart-button" >Thêm giỏ hàng</button>
                    </div>
                    
                    <div class="product__cart-trans">
                        <i class="product__cart-trans-icon fa-solid fa-truck-fast"></i>
                        <p class="product__cart-trans-desc">Giao hàng toàn quốc đơn hàng từ 99k</p>
                    </div>
                    <a href="#" class="product__cart-return">
                        <img class="product__cart-return-img" src="./img/doi-removebg-preview.png" alt="">
                        <p class="product__cart-return-desc">Đổi trả trong 24h</p>
                    </a>
            
                    <h2 class="product__description-title">Miêu Tả</h2>
                    <p id="popupDescription" class="product__description"></p>
                </div>
            </div>
        </div>
    </body>
</html>

<?php
    include("footer.php")
?>