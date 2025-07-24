<?php
    require_once(__DIR__."/auth/backend/auth.php");
    require_once("./database/connect.php");
    
    //Lấy số lượng sản phẩm trong giỏ hàng để hiện thi ra icon giỏ hàng
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        // Truy vấn tổng số lượng sản phẩm trong giỏ hàng
        $sql = "SELECT SUM(quantity) AS total_items FROM cart WHERE user_id = '$user_id'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        
        $total_items = $row['total_items'] ?? 0;
    } else {
        $total_items = 0; // Nếu chưa đăng nhập
    }
?>

<head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="shortcut icon" href="img/favicon.png" type="image/x-icon" />
        <link rel="stylesheet" href="css/header.css"/>
        <link rel="stylesheet" href="css/footer.css"/>
        <link rel="stylesheet" href="css/index.css"/>
        <link rel="stylesheet" href="css/bootstrap.css">
        <link rel="stylesheet" href="css/shop.css">
        <link rel="stylesheet" href="./css/shipping&return.css">
        <link rel="stylesheet" href="./css/cart.css">
        <link rel="stylesheet" href="./fonts_icon/fontawesome-free-6.7.1-web/fontawesome-free-6.7.1-web/css/all.min.css">
        

</head>

<body>
    <header class="header">
        <div class="menu">
            <div class="menu-left">
                <div class="left-text">
                    <a href="index.php">Trang chủ</a>
                    <a href="shop.php">Cửa Hàng</a>
                    <a href="aboutus.php">Giới thiệu</a>
                </div>
            </div>
            <div class="logo">
                <a href="index.php"><img src="./img/w-hmp-logo-full-dark.svg" alt="Logo"/></a>
            </div>
            <div class="menu-right">
                <div class="right-text">
                    <a href="#" id="open-search-modal"><i class="fa-solid fa-magnifying-glass"></i> Tìm Kiếm</a>
                    <a href="./cart.php"><i class="fa-solid fa-cart-shopping"></i> Giỏ Hàng <span class="total_cart_num"><?= $total_items ?></span></a>
                    <?php require_once("auth/backend/user_menu.php"); ?>
                </div>
            </div>
        </div>

        <!-- Modal tìm kiếm Bootstrap -->
        <div class="modal fade" id="search-modal" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="searchModalLabel">Tìm kiếm sản phẩm</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="search-form" class="search-form">
                            <div class="input-group">
                                <input type="text" class="form-control" name="query" placeholder="Nhập từ khóa..." required>
                                <button type="submit" class="btn btn-secondary"><i class="fa-solid fa-magnifying-glass"></i></button>
                            </div>
                        </form>
                        <!-- Hiện kết quả tìm kiếm -->
                        <div id="search-results" class="mt-3"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    </div>
                </div>
            </div>
        </div>
    </header>
        
    <script src="./js/JQuery.js"></script>
    <script src="./js/JsDelivr.js"></script>
    <!-- <script src="./js/shop.js"></script> -->
    <script src="./js/about.js"></script>
    <script src="./js/search.js"></script>
    <script src="./js/cart.js"></script>
</body>