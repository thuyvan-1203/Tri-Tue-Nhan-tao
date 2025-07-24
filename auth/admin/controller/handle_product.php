<?php
    header('Content-Type: application/json');
    require_once("ProductController.php");

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        try {
            $productController = new ProductController();

            if (isset($_POST['action'])) {
                // Thêm sản phẩm
                if ($_POST['action'] == 'add_product') {
                    if (empty($_POST['category_id']) || empty($_POST['name']) || empty($_POST['price']) || empty($_FILES['product_image'])) {
                        echo json_encode(['success' => false, 'message' => 'Vui lòng điền đầy đủ thông tin sản phẩm.']);
                        exit;
                    }

                    if (!isset($_FILES['product_image']) || $_FILES['product_image']['error'] !== UPLOAD_ERR_OK) {
                        echo json_encode(['success' => false, 'message' => 'Vui lòng chọn ảnh sản phẩm chính hợp lệ.']);
                        exit;
                    }
 
                    // Upload ảnh chính
                    $image_path = $productController->uploadImage($_FILES['product_image']);
                    
                    // Upload ảnh phụ 1
                    $image_path_2 = '';
                    if (isset($_FILES['product_image_2']) && $_FILES['product_image_2']['error'] === UPLOAD_ERR_OK) {
                        $image_path_2 = $productController->uploadImage($_FILES['product_image_2']);
                    }
                    
                    // Upload ảnh phụ 2
                    $image_path_3 = '';
                    if (isset($_FILES['product_image_3']) && $_FILES['product_image_3']['error'] === UPLOAD_ERR_OK) {
                        $image_path_3 = $productController->uploadImage($_FILES['product_image_3']);
                    }
                    
                    if ($image_path) {
                        $product_id = $productController->insert(
                            $_POST['category_id'],
                            $_POST['name'],
                            $_POST['price'],
                            $_POST['sale_price'],
                            $image_path,
                            $image_path_2,
                            $image_path_3,
                            $_POST['description']
                        );
                        
                        if($product_id) {
                            echo json_encode(['success' => true, 'message' => 'Thêm sản phẩm thành công.']);
                        } else {
                            echo json_encode(['success' => false, 'message' => 'Lỗi khi thêm sản phẩm vào cơ sở dữ liệu.']);
                        }
                    } else {
                        echo json_encode(['success' => false, 'message' => 'Lỗi khi upload ảnh.']);
                    }
                    exit;
                }

                // Lấy thông tin sản phẩm để sửa
                elseif ($_POST['action'] == 'get_product') {
                    $id = (int)$_POST['id'];
                    $result = $productController->getById($id);
                    
                    if ($result && mysqli_num_rows($result) > 0) {
                        $product = mysqli_fetch_assoc($result);
                        echo json_encode([
                            'success' => true, 
                            'product' => $product
                        ]);
                    } else {
                        echo json_encode([
                            'success' => false, 
                            'message' => 'Không tìm thấy sản phẩm.'
                        ]);
                    }
                    exit;
                }

                // Xóa sản phẩm
                elseif ($_POST['action'] == 'delete_product') {
                    $id = (int)$_POST['id'];
                    if ($productController->deleteById($id)) {
                        echo json_encode(['success' => true, 'message' => 'Xóa sản phẩm thành công']);
                    } else {
                        echo json_encode(['success' => false, 'message' => 'Lỗi khi xóa sản phẩm. Vui lòng thử lại.']);
                    }
                    exit;
                }

                // Sửa sản phẩm
                elseif ($_POST['action'] == 'edit_product') {
                    // Kiểm tra dữ liệu đầu vào
                    if (empty($_POST['id']) || empty($_POST['name']) || empty($_POST['price'])) {
                        echo json_encode(['success' => false, 'message' => 'Vui lòng điền đầy đủ thông tin sản phẩm.']);
                        exit;
                    }
                    
                    // Lấy dữ liệu từ form
                    $id = (int)$_POST['id'];
                    
                    // Nếu không có category_id trong POST, lấy từ database
                    if (empty($_POST['category_id'])) {
                        $result = $productController->getById($id);
                        if ($result && mysqli_num_rows($result) > 0) {
                            $product = mysqli_fetch_assoc($result);
                            $category_id = $product['category_id'];
                        } else {
                            echo json_encode(['success' => false, 'message' => 'Sản phẩm không tồn tại.']);
                            exit;
                        }
                    } else {
                        $category_id = (int)$_POST['category_id'];
                    }
                    
                    $name = $_POST['name'];
                    $price = (float)$_POST['price'];
                    $sale_price = (float)$_POST['sale_price'];
                    $description = $_POST['description'];
                    
                    // Kiểm tra xem sản phẩm có tồn tại không
                    $result = $productController->getById($id);
                    if (!$result || mysqli_num_rows($result) == 0) {
                        echo json_encode(['success' => false, 'message' => 'Sản phẩm không tồn tại.']);
                        exit;
                    }

                    // Lấy thông tin sản phẩm hiện tại
                    $product = mysqli_fetch_assoc($result);
                    $product_image = $product['product_image']; // Giữ ảnh cũ mặc định
                    $product_image_2 = $product['product_image_2']; // Giữ ảnh phụ 1 cũ
                    $product_image_3 = $product['product_image_3']; // Giữ ảnh phụ 2 cũ

                    // Nếu có ảnh mới thì upload và xóa ảnh cũ
                    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
                        $product_image = $productController->uploadImage($_FILES['product_image'], $product['product_image']);
                        if (!$product_image) {
                            echo json_encode(['success' => false, 'message' => 'Lỗi khi upload ảnh chính mới.']);
                            exit;
                        }
                    }

                    // Xử lý ảnh phụ 1
                    if (isset($_FILES['product_image_2']) && $_FILES['product_image_2']['error'] === UPLOAD_ERR_OK) {
                        $product_image_2 = $productController->uploadImage($_FILES['product_image_2'], $product['product_image_2']);
                        if (!$product_image_2) {
                            echo json_encode(['success' => false, 'message' => 'Lỗi khi upload ảnh phụ 1 mới.']);
                            exit;
                        }
                    }

                    // Xử lý ảnh phụ 2
                    if (isset($_FILES['product_image_3']) && $_FILES['product_image_3']['error'] === UPLOAD_ERR_OK) {
                        $product_image_3 = $productController->uploadImage($_FILES['product_image_3'], $product['product_image_3']);
                        if (!$product_image_3) {
                            echo json_encode(['success' => false, 'message' => 'Lỗi khi upload ảnh phụ 2 mới.']);
                            exit;
                        }
                    }

                    // Cập nhật sản phẩm
                    $update_result = $productController->update(
                        $id,
                        $category_id,
                        $name,
                        $price,
                        $sale_price,
                        $product_image,
                        $product_image_2,
                        $product_image_3,
                        $description
                    );

                    if ($update_result) {
                        echo json_encode(['success' => true, 'message' => 'Cập nhật sản phẩm thành công.']);
                    } else {
                        echo json_encode(['success' => false, 'message' => 'Lỗi khi cập nhật sản phẩm.']);
                    }
                    exit;
                } else {
                    echo json_encode(['success' => false, 'message' => 'Hành động không hợp lệ']);
                    exit;
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Hành động không được chỉ định']);
                exit;
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()]);
            exit;
        }
    }

    echo json_encode(['success' => false, 'message' => 'Phương thức không được hỗ trợ']);
?>