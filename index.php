<?PHP
     include("header.php")
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang chủ</title>

</head>
<body>
<div class="carousel-container">
    <div
        id="carouselExampleIndicators"
        class="carousel slide"
        data-bs-ride="carousel"
        data-bs-interval="2000"
    >
        <ol class="carousel-indicators">
            <li
                data-bs-target="#carouselExampleIndicators"
                data-bs-slide-to="0"
                class="active"
            ></li>
            <li
                data-bs-target="#carouselExampleIndicators"
                data-bs-slide-to="1"
            ></li>
            <li
                data-bs-target="#carouselExampleIndicators"
                data-bs-slide-to="2"
            ></li>
        </ol>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img
                    src="./img/banner-slide 1.jpg"
                    class="d-block w-100"
                    alt="Slide 1"
                />
                <div class="carousel-caption">
                    <h4>Khám phá đỉnh cao khéo léo của thủ công.</h4>
                    <p>
                        Một sự thanh thản tuyệt vời đã chiếm lấy toàn bộ
                        tâm hồn tôi, giống như những buổi sáng mùa xuân
                        ngọt ngào này chiếm lấy toàn bộ tâm hồn tôi mà
                        tôi tận hưởng bằng cả trái tim mình.
                    </p>
                </div>
            </div>
            <div class="carousel-item">
                <img
                    src="./img/banner-slide 2.jpg"
                    class="d-block w-100"
                    alt="Slide 2"
                />
                <div class="carousel-caption">
                    <h4>
                        Bộ sưu tập phong phú các loại đồ thủ công và đồ
                        trang trí.
                    </h4>
                    <p>
                        Một sự thanh thản tuyệt vời đã chiếm lấy toàn bộ
                        tâm hồn tôi, giống như những buổi sáng mùa xuân
                        ngọt ngào này chiếm lấy toàn bộ tâm hồn tôi mà
                        tôi tận hưởng bằng cả trái tim mình.
                    </p>
                </div>
            </div>
            <div class="carousel-item">
                <img
                    src="./img/banner-slide 3.jpg"
                    class="d-block w-100"
                    alt="Slide 3"
                />
                <div class="carousel-caption">
                    <h4>
                        Đưa nghệ thuật thủ công vào từng góc nhỏ của
                        cuộc sống.
                    </h4>
                    <p>
                        Một sự thanh thản tuyệt vời đã chiếm lấy toàn bộ
                        tâm hồn tôi, giống như những buổi sáng mùa xuân
                        ngọt ngào này chiếm lấy toàn bộ tâm hồn tôi mà
                        tôi tận hưởng bằng cả trái tim mình.
                    </p>
                </div>
            </div>
        </div>
        <!-- control -->
        <a
            class="carousel-control-prev"
            href="#carouselExampleIndicators"
            role="button"
            data-bs-slide="prev"
        >
            <span
                class="carousel-control-prev-icon"
                aria-hidden="true"
            ></span>
            <span class="sr-only"></span>
        </a>
        <a
            class="carousel-control-next"
            href="#carouselExampleIndicators"
            role="button"
            data-bs-slide="next"
        >
            <span
                class="carousel-control-next-icon"
                aria-hidden="true"
            ></span>
            <span class="sr-only"></span>
        </a>
    </div>
</div>

<!-- Sản Phẩm Mới -->
<section class="new-products py-5 bg-light">
    <div class="container">
        <div class="head-content">
            <div class="head-content-container">
                <img src="./img/w-hmp-logo-min-dark.svg" alt="logo">
                <h2>Nghệ Thuật - Chạm Tới Sự Tinh Tế</h2>
                <p>Mỗi món gốm là một sự cân bằng giữa hình khối, màu sắc và cảm xúc.
                    Tinh tế trong từng đường nét, mộc mạc trong từng nhịp thở.
                    Chúng tôi mang nghệ thuật thủ công đến gần hơn với nhịp sống hiện đại.
                </p>
            </div>
        </div>
        <div class="art-wrapper">
            <div class="art-container">
                <div class="art-pics">
                    <div class="art-pic-1">
                        <img src="./img/index/art_pic 1.jpg" alt="">
                    </div>
                    <div class="art-pic-2">
                        <img src="./img/index/art_pic 2.jpg" alt="">
                    </div>
                </div>
                <div class="art-words">
                    <div class="art-words-1">
                        <h2>Không chỉ là một món đồ</h2>
                        <p>
                        Chạm vào một món đồ làm bằng tay, ta không chỉ chạm vào
                        vật chất – mà còn chạm vào câu chuyện, vào người, vào những tháng năm đã trôi.
                        </p>
                    </div>
                    <div class="art-words-2">
                        <h2>Điều Ta Thật Sự Tìm Kiếm</h2>
                        <p>
                        Chúng ta không chỉ tìm kiếm vật dụng – mà là tìm những
                        điều khiến mình thấy gần gũi, thấy nhớ, thấy sống chậm
                        lại một chút.
                        </p>
                    </div>
                    <div class="art-words-3">
                        <h2>Từng chi tiết mang một tấm lòng</h2>
                        <p>
                        Sự tỉ mỉ không phải là cố gắng làm cho mọi thứ trở nên hoàn hảo,
                        mà là dành cho từng chi tiết một sự quan tâm thật lòng.
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="home-product">
            <div class="new-arrival"><h2>Sản phẩm mới</h2></div>
            <div class="row container-fluid">
                <!-- Product items -->
                <?php
                // Hiện 8 sản phẩm mới đc thêm vào theo id từ lớn đến bé
                require_once("auth/admin/controller/ProductController.php");
                $productController = new ProductController();
                $products = $productController->getLatestProducts(8); // Chỉnh số lượng sản phẩm mới sẽ lấy ra
                
                if ($products && mysqli_num_rows($products) > 0) {
                    while ($product = mysqli_fetch_assoc($products)) {
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
                            <div class="home-product-item__rating">
                                <a href="#" class="product-carts"><?php echo htmlspecialchars($product['category_name']); ?></a>
                            </div>
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
                                }?></span>
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
        <div class="see-more"><a href="./shop.php">Xem thêm</a></div>
    </div>
</section>

<!-- Product Popup -->
<div id="overlay" class="overlay"></div>
<div id="popup" class="popup" style="margin-top:35px">
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
            <div class="home-product-item__acction">
                <span class="home-product-item__like home-product-item__like--like">
                    <i class="home-product-item__like-icon-empty fa-regular fa-heart"></i>
                    <i class="home-product-item__like-icon-fill fa-solid fa-heart"></i>
                </span>
                <div class="home-product-item__rating">
                    <i class="home-product-item__star fa-solid fa-star" data-index="1"></i>
                    <i class="home-product-item__star fa-solid fa-star" data-index="2"></i>
                    <i class="home-product-item__star fa-solid fa-star" data-index="3"></i>
                    <i class="home-product-item__star fa-solid fa-star" data-index="4"></i>
                    <i class="home-product-item__star fa-solid fa-star" data-index="5"></i>
                </div>
            </div>
            
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

<!-- Middle content -->
 <div class="middle-content-1">
    <div class="content-container">
        <div class="custom-content-1">
            <picture>
                <source srcset="./img/index/middle-content-pic_1.webp" type="image/webp">
                <img src="./img/index/middle-content-pic_1.jpg" alt="Mô tả hình ảnh">
            </picture>
        </div>

        <div class="custom-content-2">
            <video src="./video/middle-content-video_1.mp4"
            playsinline loop muted autoplay 
            poster="./img/index/middle-content-video_1-poster.webp"></video>
        </div>
    </div>
 </div>

 <div class="middle-content-2">
    <div class="content-container">
        <div class="custom-content-1">
            <video src="./video/middle-content-video_2.mp4"
            playsinline loop muted autoplay 
            poster="./img/index/middle-content-video_1-poster.webp"></video>
        </div>

        <div class="custom-content-2">
            <picture>
                <img src="./img/index/middle-content-pic_2.jpg" alt="Mô tả hình ảnh">
            </picture>
        </div>
    </div>
 </div>

 <!-- bottom content -->
  <!-- Khám phá nghệ nhân -->
 <section class="artisan-story py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-4">Hành Trình Của Nghệ Nhân</h2>
        <div class="row align-items-center">
            <div class="col-md-6">
                <video src="./video/artisan-process.mp4" playsinline loop muted autoplay class="w-100"></video>
            </div>
            <div class="col-md-6">
                <h3>Tâm Huyết Trong Từng Sản Phẩm</h3>
                <p>Mỗi món gốm là kết tinh của sự kiên nhẫn, sáng tạo và tình yêu dành cho nghệ thuật. Từ việc chọn đất sét đến nung gốm, các nghệ nhân của chúng tôi gửi gắm câu chuyện và cảm xúc vào từng sản phẩm.</p>
                <a href="aboutus.php" class="btn btn-outline-dark">Khám Phá Thêm</a>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="cta-section py-5" style="background-image: url('./img/aboutus/cta-background.jpg'); background-size: cover; color: white; text-align: center;">
    <div class="container">
        <h2>Khám Phá Bộ Sưu Tập Thủ Công Độc Đáo</h2>
        <p>Hãy để nghệ thuật gốm sứ chạm đến không gian sống của bạn. Ghé thăm cửa hàng hoặc tham gia workshop để tự tay tạo nên kiệt tác của riêng mình!</p>
        <div class="cta-buttons">
            <a href="shop.php" class="btn btn-primary btn-lg mx-2">Mua Sắm Ngay</a>
            <a href="aboutus.php#contact" class="btn btn-outline-light btn-lg mx-2">Liên Hệ</a>
        </div>
    </div>
</section>

<?PHP
     include("footer.php")
?>
</body>
</html>