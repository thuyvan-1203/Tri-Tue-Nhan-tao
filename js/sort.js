/**
 * Khởi tạo chức năng sắp xếp sản phẩm
 * @param {number} categoryId - ID của danh mục sản phẩm
 */
function initProductSorting(categoryId) {
     // Kiểm tra nếu DOM đã sẵn sàng hoặc chờ DOM load hoàn tất
     if (document.readyState === 'loading') {
         document.addEventListener('DOMContentLoaded', function() {
             setupSorting(categoryId);
         });
     } else {
         setupSorting(categoryId);
     }
 }
 
 /**
  * Thiết lập chức năng sắp xếp
  * @param {number} categoryId - ID của danh mục sản phẩm
  */
 function setupSorting(categoryId) {
     const dropdown = document.getElementById('sortingDropdown');
     
     if (!dropdown) {
         console.error('Không tìm thấy phần tử sortingDropdown');
         return;
     }
 
     // Thiết lập giá trị hiện tại từ URL
     const urlParams = new URLSearchParams(window.location.search);
     const currentSort = urlParams.get('sort') || 'default';
     dropdown.value = currentSort;
 
     // Thêm sự kiện cho dropdown
     dropdown.addEventListener('change', function() {
         const sortValue = this.value;
         const productContainer = document.querySelector('.row.container-fluid');
         
         if (!productContainer) {
             console.error('Không tìm thấy container sản phẩm');
             return;
         }
 
         // Hiển thị trạng thái đang tải
         productContainer.innerHTML = '<div class="col-12 text-center">Đang tải...</div>';
 
         // Gửi yêu cầu AJAX lấy sản phẩm đã sắp xếp
         fetch(`get_products.php?sort=${sortValue}&category_id=${categoryId}`)
             .then(response => {
                 if (!response.ok) {
                     throw new Error('Lỗi kết nối: ' + response.status);
                 }
                 return response.json();
             })
             .then(data => {
                 if (data.success && data.products) {
                     updateProductList(data.products);
                     
                     // Cập nhật URL với tham số sắp xếp mới (không tải lại trang)
                     const newUrl = new URL(window.location.href);
                     newUrl.searchParams.set('sort', sortValue);
                     window.history.pushState({}, '', newUrl);
                 } else {
                     productContainer.innerHTML = '<div class="col-12 text-center">Không có sản phẩm nào.</div>';
                 }
             })
             .catch(error => {
                 console.error('Lỗi khi tải sản phẩm:', error);
                 productContainer.innerHTML = '<div class="col-12 text-center">Có lỗi xảy ra khi tải sản phẩm.</div>';
             });
     });
 }
 
 /**
  * Cập nhật danh sách sản phẩm trên giao diện
  * @param {Array} products - Mảng các sản phẩm cần hiển thị
  */
 function updateProductList(products) {
     const productContainer = document.querySelector('.row.container-fluid');
     
     if (!productContainer) {
         console.error('Không tìm thấy container sản phẩm');
         return;
     }
     
     let html = '';
 
     if (products.length > 0) {
         products.forEach(product => {
             html += `
                 <div class="col-sm-6 col-md-4 col-lg-3 mt-4">
                     <div class="home-product-item" onclick="showProductPopup(event)"
                         data-id="${product.id}" 
                         data-name="${product.name}"
                         data-price="${product.price}đ"
                         data-sale-price="${product.sale_price}đ"
                         data-description="${product.description}"
                         data-image="${product.product_image}"
                         data-image2="${product.product_image_2 || ''}"
                         data-image3="${product.product_image_3 || ''}">
                         <a class="home-product__img">
                             <div class="home-product-item__img" style="background-image: url(${product.product_image});"></div>
                         </a>
                         <div class="home-product__name">
                             <div class="home-product-item__name">${product.name}</div>
                             <div class="home-product-item__rating">
                                 <a href="#" class="product-carts">${product.category_name}</a>
                             </div>
                         </div>
                         <div class="home-product-item__sale-off">
                             <span class="home-product-item__sale-off-percent">-${product.sale_percent}%</span>
                         </div>
                         <div class="header__cart-item-price-wrap">
                             <span class="home-product-item__price">${product.sale_price}đ</span>
                         </div>
                     </div>
                 </div>
             `;
         });
     } else {
         html = '<div class="col-12 text-center">Không có sản phẩm nào.</div>';
     }
 
     productContainer.innerHTML = html;
 }