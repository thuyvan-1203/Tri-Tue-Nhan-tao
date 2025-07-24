// Xử lý submit form thêm sản phẩm
document.getElementById('addProductForm').addEventListener('submit', function(e) {
     e.preventDefault();
 
     if (typeof BASE_URL === 'undefined') {
         alert('BASE_URL không được định nghĩa. Vui lòng kiểm tra cấu hình.');
         return;
     }
     
     let formData = new FormData(this);
     
     // Sử dụng BASE_URL từ biến global
     fetch( './controller/handle_product.php', {
         method: 'POST',
         body: formData
     })
     .then(response => {
         if (!response.ok) {
             throw new Error('Lỗi mạng: ' + response.statusText);
         }
         return response.json();
     })
     .then(data => {
         if (data.success) {
             // Đóng modal và reset form
             const modal = bootstrap.Modal.getInstance(document.getElementById('addProductModal'));
             modal.hide();
             this.reset();
             
             alert(data.message);
             window.location.reload();
         } else {
             alert(data.message || 'Có lỗi xảy ra khi thêm sản phẩm.');
         }
     })
     .catch(error => {
         console.error('Error:', error);
         alert('Có lỗi xảy ra khi kết nối đến server. Vui lòng thử lại.');
     });
 });
 
 //  hàm deleteProduct
 function deleteProduct(id) {
     if(confirm('Bạn có chắc muốn xóa sản phẩm này?')) {
         fetch( './controller/handle_product.php', {
             method: 'POST',
             headers: {
                 'Content-Type': 'application/x-www-form-urlencoded',
             },
             body: 'action=delete_product&id=' + id
         })
         .then(response => response.json())
         .then(data => {
             if(data.success) {
                 alert(data.message);
                 location.reload();
             } else {
                 alert('Lỗi: ' + data.message);
             }
         })
         .catch(error => {
             console.error('Error:', error);
             alert('Có lỗi xảy ra khi kết nối đến server. Vui lòng thử lại.');
         });
     }
 }
 
 // Hàm lấy thông tin sản phẩm và điền vào modal
 function editProduct(id) {
     fetch('./controller/handle_product.php', {
         method: 'POST',
         headers: {
             'Content-Type': 'application/x-www-form-urlencoded',
         },
         body: 'action=get_product&id=' + id
     })
     .then(response => response.json())
     .then(data => {
         if (data.success) {
             const product = data.product;
             
             // Điền dữ liệu vào các trường trong modal
             document.getElementById('editProductId').value = product.id;
             document.getElementById('editProductName').value = product.name;
             document.getElementById('editProductPrice').value = product.price;
             document.getElementById('editProductSalePrice').value = product.sale_price;
             document.getElementById('editProductDescription').value = product.description;
             
             // Nếu có select box danh mục trong form edit, cập nhật giá trị
             const categorySelect = document.getElementById('editProductCategory');
             if (categorySelect) {
                 categorySelect.value = product.category_id;
             }

             // Hiển thị ảnh hiện tại
             // Ảnh chính
             if (product.product_image) {
                 const imagePath = product.product_image.startsWith('http') ? 
                     product.product_image : 
                     '../../' + product.product_image;
                 document.getElementById('currentProductImage').src = imagePath;
                 document.getElementById('currentProductImage').style.display = 'block';
             } else {
                 document.getElementById('currentProductImage').style.display = 'none';
             }

             // Ảnh phụ 1
             if (product.product_image_2) {
                 const imagePath2 = product.product_image_2.startsWith('http') ? 
                     product.product_image_2 : 
                     '../../' + product.product_image_2;
                 document.getElementById('currentProductImage2').src = imagePath2;
                 document.getElementById('currentProductImage2').style.display = 'block';
             } else {
                 document.getElementById('currentProductImage2').style.display = 'none';
             }

             // Ảnh phụ 2
             if (product.product_image_3) {
                 const imagePath3 = product.product_image_3.startsWith('http') ? 
                     product.product_image_3 : 
                     '../../' + product.product_image_3;
                 document.getElementById('currentProductImage3').src = imagePath3;
                 document.getElementById('currentProductImage3').style.display = 'block';
             } else {
                 document.getElementById('currentProductImage3').style.display = 'none';
             }

             const modal = new bootstrap.Modal(document.getElementById('editProductModal'));
             modal.show();
         } else {
             alert('Không tìm thấy sản phẩm: ' + (data.message || ''));
         }
     })
     .catch(error => {
         console.error('Lỗi:', error);
         alert('Có lỗi khi lấy thông tin sản phẩm.');
     });
 }
 
 // Xử lý submit form sửa sản phẩm
 document.getElementById('editProductForm').addEventListener('submit', function (e) {
     e.preventDefault();
 
     const formData = new FormData();
     
     formData.append('action', 'edit_product');
     formData.append('id', document.getElementById('editProductId').value);
     formData.append('name', document.getElementById('editProductName').value);
     formData.append('price', document.getElementById('editProductPrice').value);
     formData.append('sale_price', document.getElementById('editProductSalePrice').value);
     formData.append('description', document.getElementById('editProductDescription').value);
     
     // Nếu có select box danh mục, thêm vào formData
     const categorySelect = document.getElementById('editProductCategory');
     if (categorySelect) {
         formData.append('category_id', categorySelect.value);
     }
     
     // Thêm ảnh chính nếu có
     const image = document.getElementById('editProductImage').files[0];
     if (image) {
         formData.append('product_image', image);
     }

     // Thêm ảnh phụ 1 nếu có
     const image2 = document.getElementById('editProductImage2').files[0];
     if (image2) {
         formData.append('product_image_2', image2);
     }

     // Thêm ảnh phụ 2 nếu có
     const image3 = document.getElementById('editProductImage3').files[0];
     if (image3) {
         formData.append('product_image_3', image3);
     }

     fetch('./controller/handle_product.php', {
         method: 'POST',
         body: formData
     })
     .then(response => response.json())
     .then(data => {
         if (data.success) {
             alert('Sửa sản phẩm thành công');
             location.reload(); // Reload trang sau khi sửa xong
         } else {
             alert('Lỗi khi sửa sản phẩm: ' + (data.message || ''));
         }
     })
     .catch(error => {
         console.error('Lỗi:', error);
         alert('Có lỗi khi kết nối với server.');
     });
 });