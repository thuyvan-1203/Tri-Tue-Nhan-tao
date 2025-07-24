<?php
    require_once("../backend/restrict_admin.php");
    
    // Kiểm tra query string để xác định section active
    $activeSection = isset($_GET['section']) && in_array($_GET['section'], ['products', 'orders', 'users']) 
        ? $_GET['section'] 
        : 'products'; // Mặc định là 'products'
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Quản lý</title>
        <link rel="shortcut icon" href="../../img/favicon.png" type="image/x-icon">
        <link rel="stylesheet" href="./css/admin.css">
        <link rel="stylesheet" href="./css/bootstrap.css">
        <link
            rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css"
        />
        <style>
            .content-section {
                display: none;
            }
            .content-section.active {
                display: block;
            }

            .table-striped tr {
            height: 80px;
            }
            .filter-form {
                margin-bottom: 20px;
            }
            .filter-form .form-group {
                margin-right: 15px;
            }
        </style>
        <script>
            const BASE_URL = '<?php echo "/handicraft/auth/admin/"; ?>';
        </script>
    </head>
    <body>
        <div class="container-fluid">
            <div class="row">
                <!-- Sidebar -->
                <div class="col-md-3 col-lg-2 px-0 sidebar">
                    <div class="logo-container">
                        <a href="../../index.php"> 
                             <img
                                 src="../../img/w-hmp-logo-full-dark.svg"
                                 alt="Admin Logo"
                                 id="adminLogo"
                             />
                        </a>
                    </div>
                    <div class="p-3">
                        <h4 class="text-white">Admin Panel</h4>
                    </div>
                    <nav class="nav flex-column px-3">
                        <a class="nav-link <?php echo $activeSection === 'products' ? 'active' : ''; ?>" href="?section=products" data-section="products">
                            <i class="bi bi-box-seam"></i> Quản lý sản phẩm
                        </a>
                        <a class="nav-link <?php echo $activeSection === 'orders' ? 'active' : ''; ?>" href="?section=orders" data-section="orders">
                            <i class="bi bi-cart3"></i> Quản lý đơn hàng
                        </a>
                        <a class="nav-link <?php echo $activeSection === 'users' ? 'active' : ''; ?>" href="?section=users" data-section="users">
                            <i class="bi bi-people"></i> Quản lý người dùng
                        </a>
                        <hr class="border-light my-3">
                        <a class="nav-link external-link" href="../../index.php">
                            <i class="bi bi-house-door"></i> Trở về trang chủ
                        </a>
                        <a class="nav-link external-link text-danger" href="../backend/log_out.php">
                            <i class="bi bi-box-arrow-right"></i> Đăng xuất
                        </a>
                    </nav>
                </div>

                <!-- Main Content -->
                <div class="col-md-9 col-lg-10 main-content">
                    <!-- Products Section -->
                    <div id="products" class="content-section <?php echo $activeSection === 'products' ? 'active' : ''; ?>">
                        <h2>Quản lý sản phẩm</h2>
                        <div class="card mt-3">
                            <div class="card-body">
                                <!-- Form lọc sản phẩm -->
                                <form method="GET" class="filter-form d-flex flex-wrap">
                                    <input type="hidden" name="section" value="products">
                                    <div class="form-group">
                                        <label for="category_filter">Danh mục</label>
                                        <select name="category_id" id="category_filter" class="form-select">
                                            <option value="">Tất cả</option>
                                            <?php
                                                require_once("../../database/connect.php");
                                                $sql = "SELECT * FROM Category";
                                                $result = mysqli_query($conn, $sql);
                                                while ($category = mysqli_fetch_assoc($result)) {
                                                    $selected = isset($_GET['category_id']) && $_GET['category_id'] == $category['id'] ? 'selected' : '';
                                            ?>
                                                <option value="<?php echo $category['id']; ?>" <?php echo $selected; ?>>
                                                    <?php echo $category['name']; ?>
                                                </option>
                                            <?php
                                                }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="time_filter">Thời gian thêm</label>
                                        <select name="time" id="time_filter" class="form-select">
                                            <option value="">Tất cả</option>
                                            <option value="1_day" <?php echo isset($_GET['time']) && $_GET['time'] == '1_day' ? 'selected' : ''; ?>>24 giờ qua</option>
                                            <option value="7_days" <?php echo isset($_GET['time']) && $_GET['time'] == '7_days' ? 'selected' : ''; ?>>7 ngày qua</option>
                                            <option value="30_days" <?php echo isset($_GET['time']) && $_GET['time'] == '30_days' ? 'selected' : ''; ?>>30 ngày qua</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="price_min">Giá từ</label>
                                        <input type="number" name="price_min" id="price_min" class="form-control" value="<?php echo isset($_GET['price_min']) ? htmlspecialchars($_GET['price_min']) : ''; ?>" placeholder="VD: 100000">
                                    </div>
                                    <div class="form-group">
                                        <label for="price_max">Đến</label>
                                        <input type="number" name="price_max" id="price_max" class="form-control" value="<?php echo isset($_GET['price_max']) ? htmlspecialchars($_GET['price_max']) : ''; ?>" placeholder="VD: 500000">
                                    </div>
                                    <div class="form-group align-self-end">
                                        <button type="submit" class="btn btn-primary">Lọc</button>
                                        <a href="?section=products" class="btn btn-secondary">Xóa bộ lọc</a>
                                    </div>
                                </form>
                                <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addProductModal">
                                    <i class="bi bi-plus-circle"></i> Thêm sản phẩm mới
                                </button>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Danh mục</th>
                                                <th>Tên sản phẩm</th>
                                                <th>Giá</th>
                                                <th>Thời gian thêm</th>
                                                <th>Thao tác</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                require_once("controller/ProductController.php");
                                                $productController = new ProductController();
                                                $filters = [
                                                    'category_id' => isset($_GET['category_id']) ? $_GET['category_id'] : '',
                                                    'time' => isset($_GET['time']) ? $_GET['time'] : '',
                                                    'price_min' => isset($_GET['price_min']) ? $_GET['price_min'] : '',
                                                    'price_max' => isset($_GET['price_max']) ? $_GET['price_max'] : ''
                                                ];
                                                $products = $productController->getFilteredProducts($filters);
                                                while($product = mysqli_fetch_assoc($products)) {
                                            ?>
                                                <tr>
                                                    <td><?php echo $product['id']; ?></td>
                                                    <td><?php echo $product['category_name']; ?></td>
                                                    <td><?php echo $product['name']; ?></td>
                                                    <td>
                                                        <?php 
                                                            echo number_format($product['price']) . ' VNĐ';
                                                            if($product['sale_price'] > 0) {
                                                                echo '<br><span class="text-danger">Sale: ' . number_format($product['sale_price']) . ' VNĐ</span>';
                                                            }
                                                        ?>
                                                    </td>
                                                    <td><?php echo $product['created_at']; ?></td>
                                                    <td>
                                                        <button class="btn btn-sm btn-primary mb-1" onclick="editProduct(<?php echo $product['id']; ?>)">
                                                            <i class="bi bi-pencil"></i>
                                                        </button>
                                                        <button class="btn btn-sm btn-danger" onclick="deleteProduct(<?php echo $product['id']; ?>)">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </td>
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

                    <!-- Orders Section -->
                    <div id="orders" class="content-section <?php echo $activeSection === 'orders' ? 'active' : ''; ?>">
                        <h2>Quản lý đơn hàng</h2>
                        <div class="card mt-3">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Mã đơn hàng</th>
                                                <th>Khách hàng</th>
                                                <th>Tổng tiền</th>
                                                <th>Trạng thái</th>
                                                <th>Thao tác</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                require_once("controller/OrderController.php");
                                                $orderController = new OrderController();
                                                $orders = $orderController->getAll();
                                                if ($orders && mysqli_num_rows($orders) > 0) {
                                                    while($order = mysqli_fetch_assoc($orders)) {
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
                                                <td><?php echo $order['full_name']; ?><br></td>
                                                <td><?php echo number_format($total_amount); ?> VNĐ</td>
                                                <td><span class="<?php echo $statusClass; ?>"><?php echo $order['status']; ?></span></td>
                                                <td>
                                                    <button class="btn btn-sm btn-info mb-1" onclick="viewOrder(<?php echo $order['id']; ?>)">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-primary mb-1" onclick="updateOrderStatus(<?php echo $order['id']; ?>)">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-danger" onclick="deleteOrder(<?php echo $order['id']; ?>)">
                                                        <i class="bi bi-trash"></i>
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

                    <!-- Users Section -->
                    <div id="users" class="content-section <?php echo $activeSection === 'users' ? 'active' : ''; ?>">
                        <h2>Quản lý người dùng</h2>
                        <div class="card mt-3">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Tên người dùng</th>
                                                <th>Email</th>
                                                <th>Số điện thoại</th>
                                                <th>Địa chỉ</th>
                                                <th>Vai trò</th>
                                                <th>Thao tác</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                require_once("controller/UserController.php");
                                                $userController = new UserController();
                                                $users = $userController->getAll();
                                                $current_user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
                                                while($user = mysqli_fetch_assoc($users)) {
                                                    if($user['id'] != $current_user_id) {
                                            ?>
                                                <tr>
                                                    <td><?php echo $user['id']; ?></td>
                                                    <td><?php echo $user['full_name']; ?></td>
                                                    <td><?php echo $user['email']; ?></td>
                                                    <td><?php echo $user['phone_number'] ?? '-'; ?></td>
                                                    <td><?php echo $user['address'] ?? '-'; ?></td>
                                                    <td><?php echo $user['role_id'] == 1 ? 'Admin' : 'User'; ?></td>
                                                    <td>
                                                        <button class="btn btn-sm btn-primary mb-1" onclick="editUser(<?php echo $user['id']; ?>)">
                                                            <i class="bi bi-pencil"></i>
                                                        </button>
                                                        <button class="btn btn-sm btn-danger" onclick="deleteUser(<?php echo $user['id']; ?>)">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php
                                                    }
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
        </div>

        <!-- Add Product Modal -->
        <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addProductModalLabel">Thêm sản phẩm mới</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="addProductForm" action="controller/handle_product.php" method="POST" enctype="multipart/form-data">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="productName" class="form-label">Tên sản phẩm</label>
                                    <input type="text" class="form-control" id="productName" name="name" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="productCategory" class="form-label">Danh mục</label>
                                    <select class="form-select" id="productCategory" name="category_id" required>
                                        <option value="">Chọn danh mục</option>
                                        <?php
                                            require_once("../../database/connect.php");
                                            $sql = "SELECT * FROM Category";
                                            $result = mysqli_query($conn, $sql);
                                            while($category = mysqli_fetch_assoc($result)) {
                                        ?>
                                            <option value="<?php echo $category['id']; ?>">
                                                <?php echo $category['name']; ?>
                                            </option>
                                        <?php
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="productPrice" class="form-label">Giá</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="productPrice" name="price" required>
                                        <span class="input-group-text">VNĐ</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="productSale" class="form-label">Giá khuyến mãi</label>
                                    <input type="number" class="form-control" id="productSale" name="sale_price" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="productDescription" class="form-label">Mô tả sản phẩm</label>
                                <textarea class="form-control" id="productDescription" name="description" rows="3"></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Hình ảnh sản phẩm</label>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="productMainImage" class="form-label">Ảnh chính</label>
                                        <input type="file" class="form-control" id="productMainImage" name="product_image" accept="image/*" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="productImage2" class="form-label">Ảnh phụ 1</label>
                                        <input type="file" class="form-control" id="productImage2" name="product_image_2" accept="image/*">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="productImage3" class="form-label">Ảnh phụ 2</label>
                                        <input type="file" class="form-control" id="productImage3" name="product_image_3" accept="image/*">
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="action" value="add_product">
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" form="addProductForm" name="submit" class="btn btn-primary">Lưu sản phẩm</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Sửa sản phẩm -->
        <div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true" >
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editProductModalLabel">Sửa Sản Phẩm</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editProductForm">
                            <input type="hidden" id="editProductId" name="id">
                            <div class="mb-3">
                                <label for="editProductName" class="form-label">Tên sản phẩm</label>
                                <input type="text" class="form-control" id="editProductName" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="editProductCategory" class="form-label">Danh mục</label>
                                <select class="form-select" id="editProductCategory" name="category_id" required>
                                    <option value="">Chọn danh mục</option>
                                    <?php
                                        require_once("../../database/connect.php");
                                        $sql = "SELECT * FROM Category";
                                        $result = mysqli_query($conn, $sql);
                                        while($category = mysqli_fetch_assoc($result)) {
                                    ?>
                                        <option value="<?php echo $category['id']; ?>">
                                            <?php echo $category['name']; ?>
                                        </option>
                                    <?php
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="editProductPrice" class="form-label">Giá</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="editProductPrice" name="price" required>
                                        <span class="input-group-text">VNĐ</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="editProductSalePrice" class="form-label">Giá khuyến mãi</label>
                                    <input type="number" class="form-control" id="editProductSalePrice" name="sale_price" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="editProductDescription" class="form-label">Mô tả sản phẩm</label>
                                <textarea class="form-control" id="editProductDescription" name="description" rows="3"></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Hình ảnh sản phẩm hiện tại</label>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label>Ảnh chính</label>
                                        <img id="currentProductImage" src="" alt="Ảnh chính" class="img-thumbnail" style="max-width: 200px;">
                                    </div>
                                    <div class="col-md-4">
                                        <label>Ảnh phụ 1</label>
                                        <img id="currentProductImage2" src="" alt="Ảnh phụ 1" class="img-thumbnail" style="max-width: 200px;">
                                    </div>
                                    <div class="col-md-4">
                                        <label>Ảnh phụ 2</label>
                                        <img id="currentProductImage3" src="" alt="Ảnh phụ 2" class="img-thumbnail" style="max-width: 200px;">
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Chọn hình ảnh mới</label>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="editProductImage" class="form-label">Ảnh chính mới</label>
                                        <input type="file" class="form-control" id="editProductImage" name="product_image" accept="image/*">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="editProductImage2" class="form-label">Ảnh phụ 1 mới</label>
                                        <input type="file" class="form-control" id="editProductImage2" name="product_image_2" accept="image/*">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="editProductImage3" class="form-label">Ảnh phụ 2 mới</label>
                                        <input type="file" class="form-control" id="editProductImage3" name="product_image_3" accept="image/*">
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="action" value="edit_product">
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" form="editProductForm" name="submit" class="btn btn-primary">Lưu sản phẩm</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Xem chi tiết đơn hàng -->
        <div class="modal fade" id="viewOrderModal" tabindex="-1" aria-labelledby="viewOrderModalLabel fundamentalist-hidden="true">
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
                            <p><strong>Ngày đặt hàng:</strong> <span id="orderDate"></span></p>
                            <p><strong>Trạng thái:</strong> <span id="orderStatus"></span></p>
                            <h6>Chi tiết sản phẩm:</h6>
                            <table class="table">
                                <thead>
                                    <tr>
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

        <!-- Modal Cập nhật trạng thái đơn hàng -->
        <div class="modal fade" id="updateOrderStatusModal" tabindex="-1" aria-labelledby="updateOrderStatusModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="updateOrderStatusModalLabel">Cập nhật trạng thái đơn hàng</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="updateOrderStatusForm">
                            <input type="hidden" id="updateOrderId" name="id">
                            <div class="mb-3">
                                <label for="updateOrderStatusSelect" class="form-label">Chọn trạng thái</label>
                                <select class="form-select" id="updateOrderStatusSelect" name="status">
                                    <option value="Đang chờ xác nhận">Đang chờ xác nhận</option>
                                    <option value="Đã xác nhận">Đã xác nhận</option>
                                    <option value="Đã hủy">Đã hủy</option>
                                    <option value="Đang tiến hành">Đang tiến hành</option>
                                    <option value="Đã hoàn thành">Đã hoàn thành</option>
                                </select>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" form="updateOrderStatusForm" class="btn btn-primary">Cập nhật</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal sửa thông tin User -->
        <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editUserModalLabel">Sửa thông tin người dùng</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editUserForm" action="controller/handle_user.php" method="POST">
                            <input type="hidden" id="editUserId" name="id">
                            <div class="mb-3">
                                <label for="editUserFullname" class="form-label">Họ tên</label>
                                <input type="text" class="form-control" id="editUserFullname" name="full_name" required>
                            </div>
                            <div class="mb-3">
                                <label for="editUserEmail" class="form-label">Email</label>
                                <input type="email" class="form-control" id="editUserEmail" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="editUserPhone" class="form-label">Số điện thoại</label>
                                <input type="text" class="form-control" id="editUserPhone" name="phone_number">
                            </div>
                            <div class="mb-3">
                                <label for="editUserAddress" class="form-label">Địa chỉ</label>
                                <input type="text" class="form-control" id="editUserAddress" name="address">
                            </div>
                            <div class="mb-3">
                                <label for="editUserRole" class="form-label">Vai trò</label>
                                <select class="form-select" id="editUserRole" name="role_id" required>
                                    <option value="0">User</option>
                                    <option value="1">Admin</option>
                                </select>
                            </div>
                            <input type="hidden" name="action" value="edit_user">
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" form="editUserForm" class="btn btn-primary">Lưu thay đổi</button>
                    </div>
                </div>
            </div>
        </div>

        <script src="./js/bootstrap.js"></script>
        <script src="./js/admin.js"></script>
        <script src="./js/product.js"></script>
        <script src="./js/order.js"></script>
        <script src="./js/user.js"></script>
    </body>
</html>