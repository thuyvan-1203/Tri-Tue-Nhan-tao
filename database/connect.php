<?php
define('DB_HOST', 'localhost'); // Tùy port ở cấu hình của các bạn để chỉnh sửa có thể là 3306 hoặc 3307
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'bandothucong');
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) {
    die("Connection fail : " . $conn->connect_error);
}
?>