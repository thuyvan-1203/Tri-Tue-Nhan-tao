<?php
    require_once(__DIR__."/../../../database/connect.php");
    class ProductController {
        // Thêm sản phẩm mới
        public function insert($category_id, $name, $price, $sale_price, $product_image, $product_image_2, $product_image_3, $description) {
            global $conn;

            if (empty($category_id) || empty($name) || empty($price) || empty($product_image)) {
                return false; // Thiếu thông tin bắt buộc
            }

            $created_at = date('Y-m-d H:i:s');
            $updated_at = date('Y-m-d H:i:s');
            
            // Escape các tham số để tránh SQL injection
            $name = mysqli_real_escape_string($conn, $name);
            $description = mysqli_real_escape_string($conn, $description);
            $product_image = mysqli_real_escape_string($conn, $product_image);
            $product_image_2 = mysqli_real_escape_string($conn, $product_image_2);
            $product_image_3 = mysqli_real_escape_string($conn, $product_image_3);
            
            $sql = "INSERT INTO Product(category_id, name, price, sale_price, product_image, product_image_2, product_image_3, description, created_at, updated_at) 
                    VALUES($category_id, '$name', $price, $sale_price, '$product_image', '$product_image_2', '$product_image_3', '$description', '$created_at', '$updated_at')"; 
            
            if (!mysqli_query($conn, $sql)) {
                error_log("SQL Error: " . mysqli_error($conn));
                return false;
            }

            return mysqli_insert_id($conn);
        }
    
        // Lấy tất cả sản phẩm
        public function getAll($limit = null) {
            global $conn;
            $sql = "SELECT p.*, c.name as category_name 
                    FROM Product p 
                    JOIN Category c ON c.id = p.category_id 
                    ORDER BY p.created_at DESC"; 
            
            if($limit != null) {
                $sql .= " LIMIT 0," . $limit;
            }
            return mysqli_query($conn, $sql);     
        }
    
        // Lấy sản phẩm theo ID
        public function getById($id) {
            global $conn;
        
            $id = (int)$id; // Đảm bảo id là số nguyên
            $sql = "SELECT p.*, c.name as category_name 
                    FROM Product p 
                    JOIN Category c ON c.id = p.category_id 
                    WHERE p.id = $id"; 
        
            // Xóa dòng debug
            // echo "SQL Query: " . $sql;
        
            $result = mysqli_query($conn, $sql);
            
            // Kiểm tra lỗi truy vấn
            if (!$result) {
                error_log("SQL Error: " . mysqli_error($conn));
                return false;
            }
        
            return $result;  // Trả về kết quả, kể cả khi không có dòng nào
        }
    
        // Xóa sản phẩm
        public function deleteById($id) {
            global $conn;

            // Lấy thông tin sản phẩm để xóa các file ảnh
            $result = $this->getById($id);
            if ($result && mysqli_num_rows($result) > 0) {
                $product = mysqli_fetch_assoc($result);
                
                // Xóa ảnh chính
                if (!empty($product['product_image'])) {
                    $image_path = __DIR__ . "/../../../" . $product['product_image'];
                    if (file_exists($image_path)) {
                        @unlink($image_path); // Xóa ảnh chính nếu tồn tại
                    }
                }
                
                // Xóa ảnh phụ 1
                if (!empty($product['product_image_2'])) {
                    $image_path_2 = __DIR__ . "/../../../" . $product['product_image_2'];
                    if (file_exists($image_path_2)) {
                        @unlink($image_path_2); // Xóa ảnh phụ 1 nếu tồn tại
                    }
                }
                
                // Xóa ảnh phụ 2
                if (!empty($product['product_image_3'])) {
                    $image_path_3 = __DIR__ . "/../../../" . $product['product_image_3'];
                    if (file_exists($image_path_3)) {
                        @unlink($image_path_3); // Xóa ảnh phụ 2 nếu tồn tại
                    }
                }
            }

            // Xóa sản phẩm khỏi database
            $id = (int)$id; // Đảm bảo id là số nguyên
            $sql = "DELETE FROM Product WHERE id = $id";
            return mysqli_query($conn, $sql);
        }
    
        // Cập nhật sản phẩm
        public function update($id, $category_id, $name, $price, $sale_price, $product_image, $product_image_2, $product_image_3, $description) {
            global $conn;
            $updated_at = date('Y-m-d H:i:s');
            
            // Escape các tham số
            $name = mysqli_real_escape_string($conn, $name);
            $description = mysqli_real_escape_string($conn, $description);
            $product_image = mysqli_real_escape_string($conn, $product_image);
            $product_image_2 = mysqli_real_escape_string($conn, $product_image_2);
            $product_image_3 = mysqli_real_escape_string($conn, $product_image_3);
            
            $sql = "UPDATE Product 
                    SET category_id = $category_id,
                        name = '$name',
                        price = $price,
                        sale_price = $sale_price,
                        product_image = '$product_image',
                        product_image_2 = '$product_image_2',
                        product_image_3 = '$product_image_3',
                        description = '$description',
                        updated_at = '$updated_at'
                    WHERE id = $id"; 
            return mysqli_query($conn, $sql);
        }
    
        // Đếm số sản phẩm theo danh mục
        public function countProductByCategory($category_id = null) {
            global $conn;
            $sql = "SELECT COUNT(*) as total FROM Product";
            if($category_id != null) {
                $sql .= " WHERE category_id = $category_id"; 
            }
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_assoc($result);
            return $row['total'];
        }
    
        // Lấy sản phẩm theo danh mục  nếu có giá trị sort truyền vào thì sẽ sắp xếp 
        public function getByCategory($category_id, $limit = null, $sort = 'default') {
            global $conn;
            $sql = "SELECT p.*, c.name as category_name, 
                    CASE WHEN p.sale_price = 0 THEN p.price ELSE p.sale_price END as display_price 
                    FROM Product p 
                    JOIN Category c ON c.id = p.category_id 
                    WHERE p.category_id = $category_id";
            
            // Thêm điều kiện sắp xếp
            switch($sort) {
                case 'price_asc':
                    $sql .= " ORDER BY display_price ASC";
                    break;
                case 'price_desc':
                    $sql .= " ORDER BY display_price DESC";
                    break;
                default:
                    $sql .= " ORDER BY p.created_at DESC";
            }
            
            if($limit != null) {
                $sql .= " LIMIT 0," . $limit;
            }
            return mysqli_query($conn, $sql);
        }

        // Upload ảnh sản phẩm
        public function uploadImage($file, $old_image = null) {
            $target_dir = __DIR__ . "/../../../uploads/products/";
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
        
            $file_extension = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
            $file_name = time() . '_' . uniqid() . '.' . $file_extension;
            $target_file = $target_dir . $file_name;
            
            if (move_uploaded_file($file["tmp_name"], $target_file)) {
                // Xóa ảnh cũ nếu tồn tại
                if ($old_image && file_exists(__DIR__ . "/../../../" . $old_image)) {
                    unlink(__DIR__ . "/../../../" . $old_image);
                }
                return "uploads/products/" . $file_name;
            }
            return false;
        }

        public function getFilteredProducts($filters) {
            global $conn;
            $sql = "SELECT p.*, c.name as category_name 
                    FROM Product p 
                    JOIN Category c ON p.category_id = c.id 
                    WHERE 1=1";

            // Lọc theo danh mục
            if (!empty($filters['category_id'])) {
                $category_id = mysqli_real_escape_string($conn, $filters['category_id']);
                $sql .= " AND p.category_id = '$category_id'";
            }

            // Lọc theo thời gian
            if (!empty($filters['time'])) {
                switch ($filters['time']) {
                    case '1_day':
                        $sql .= " AND p.created_at >= NOW() - INTERVAL 1 DAY";
                        break;
                    case '7_days':
                        $sql .= " AND p.created_at >= NOW() - INTERVAL 7 DAY";
                        break;
                    case '30_days':
                        $sql .= " AND p.created_at >= NOW() - INTERVAL 30 DAY";
                        break;
                }
            }

            // Lọc theo khoảng giá
            if (!empty($filters['price_min'])) {
                $price_min = mysqli_real_escape_string($conn, $filters['price_min']);
                $sql .= " AND p.price >= '$price_min'";
            }
            if (!empty($filters['price_max'])) {
                $price_max = mysqli_real_escape_string($conn, $filters['price_max']);
                $sql .= " AND p.price <= '$price_max'";
            }

            $sql .= " ORDER BY p.id DESC";
            return mysqli_query($conn, $sql);
        }

        // Phương thức lấy sản phẩm mới nhất
        public function getLatestProducts($limit = 8) { // Giới hạn sản phẩm mới nhất
            global $conn;
            $query = "SELECT p.id, p.name, p.description, p.price, p.sale_price, 
                             p.product_image, p.product_image_2, p.product_image_3, 
                             c.name AS category_name
                      FROM Product p
                      LEFT JOIN Category c ON p.category_id = c.id
                      ORDER BY p.id DESC
                      LIMIT ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $limit);
            $stmt->execute();
            return $stmt->get_result();
        }
    }
?>