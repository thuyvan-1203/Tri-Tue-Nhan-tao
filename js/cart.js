document.addEventListener('DOMContentLoaded', function() {
     // Biến lưu trữ thông tin sản phẩm hiện tại
     let currentProduct = null;
     let quantity = 0;
 
     // Kiểm tra các phần tử cần thiết
     const popup = document.getElementById("popup");
     const overlay = document.getElementById("overlay");
     const closeButton = document.querySelector("#popup__close i");
     const quantityDiv = document.getElementById("quantity");
 
     if (!popup || !overlay || !closeButton || !quantityDiv) {
         console.warn("Trang không chứa popup sản phẩm. Bỏ qua chức năng popup.");
         return;
     }
 
     // Hàm tăng số lượng
     function increase(event) {
         if (event) {
             event.preventDefault();
             event.stopPropagation();
         }
         quantity++;
         updateQuantityDisplay();
     }
 
     // Hàm giảm số lượng
     function decrease(event) {
         if (event) {
             event.preventDefault();
             event.stopPropagation();
         }
         if (quantity > 0) {
             quantity--;
             updateQuantityDisplay();
         }
     }
 
     // Hàm cập nhật hiển thị số lượng
     function updateQuantityDisplay() {
         if (quantityDiv) {
             quantityDiv.textContent = quantity.toString();
         }
     }
 
     // Hàm thêm vào giỏ hàng
     function addToCart(event) {
         if (event) {
             event.preventDefault();
             event.stopPropagation();
         }
 
         console.log("Adding to cart...");
         console.log("Current product:", currentProduct);
         console.log("Quantity:", quantity);
 
         // Kiểm tra currentProduct
         if (!currentProduct || !currentProduct.id) {
             console.error('Không tìm thấy thông tin sản phẩm:', currentProduct);
             alert('Không tìm thấy thông tin sản phẩm');
             return;
         }
 
         // Kiểm tra số lượng
         if (quantity <= 0) {
             alert('Vui lòng chọn số lượng sản phẩm');
             return;
         }
 
         // Tạo FormData object
         const formData = new FormData();
         formData.append('product_id', currentProduct.id);
         formData.append('quantity', quantity);
 
         // Xử lý giá
         let price;
         try {
             // Loại bỏ "đ" và dấu phẩy từ chuỗi giá
             const cleanPrice = (priceStr) => {
                 if (!priceStr) return 0;
                 return parseFloat(priceStr.replace(/[^\d]/g, ''));
             };
             
             // Ưu tiên giá khuyến mãi nếu có
             if (currentProduct.salePrice && currentProduct.salePrice !== "0đ") {
                 price = cleanPrice(currentProduct.salePrice);
             } else {
                 price = cleanPrice(currentProduct.price);
             }
             
             if (isNaN(price)) {
                 throw new Error('Giá không hợp lệ');
             }
 
             console.log("Price to send:", price);
         } catch (error) {
             console.error('Lỗi xử lý giá:', error);
             alert('Có lỗi xảy ra khi xử lý giá sản phẩm');
             return;
         }
 
         formData.append('price', price);
 
         // Gửi request
         fetch('./repository/add_to_cart.php', { // Điều chỉnh đường dẫn nếu cần
             method: 'POST',
             body: formData
         })
         .then(response => {
             if (!response.ok) {
                 throw new Error(`HTTP error! Status: ${response.status}`);
             }
             return response.json();
         })
         .then(data => {
             console.log("Server response:", data);
             if (data.success) {
                 alert('Thêm vào giỏ hàng thành công');
                 closeProductPopup();
             } else {
                 alert(data.message || 'Có lỗi xảy ra khi thêm vào giỏ hàng');
             }
         })
         .catch(error => {
             console.error('Error:', error);
             alert('Có lỗi xảy ra khi thêm vào giỏ hàng');
         });
     }
 
     // Reset số lượng khi đóng popup
     function resetQuantity() {
         quantity = 0;
         updateQuantityDisplay();
     }
 
     // Hàm hiển thị popup sản phẩm
     // Thay thế hàm showProductPopup hiện tại bằng hàm này
function showProductPopup(event) {
    if (event) {
        event.preventDefault();
        event.stopPropagation();
    }

    // Ngăn xử lý nếu sự kiện đã được xử lý
    if (event.handled) return;
    event.handled = true;

    const productItem = event.target.closest(".home-product-item");
    if (!productItem) return;

    console.log("Clicked product item:", productItem);

    try {
        // Đóng modal tìm kiếm nếu có
        if (typeof $ !== 'undefined' && $.fn.modal) {
            $('#search-modal').modal('hide');
        }

        // Lưu thông tin sản phẩm hiện tại
        currentProduct = {
            id: parseInt(productItem.dataset.id),
            name: productItem.dataset.name,
            price: productItem.dataset.price,
            salePrice: productItem.dataset.salePrice,
            description: productItem.dataset.description,
            image: productItem.dataset.image,
            image2: productItem.dataset.image2,
            image3: productItem.dataset.image3
        };

        console.log("Product data:", {
            id: productItem.dataset.id,
            name: productItem.dataset.name,
            price: productItem.dataset.price,
            salePrice: productItem.dataset.salePrice
        });

        // Kiểm tra dữ liệu sản phẩm
        if (!currentProduct.id || !currentProduct.name || !currentProduct.price) {
            console.error('Thiếu thông tin sản phẩm:', currentProduct);
            throw new Error('Thiếu thông tin sản phẩm');
        }

        // Cập nhật giao diện popup
        document.getElementById('popupTitle').textContent = currentProduct.name || "No Name";
        document.getElementById('popupOriginalPrice').textContent = currentProduct.price || "0đ";
        
        const salePriceElement = document.getElementById('popupSalePrice');
        if (currentProduct.salePrice && currentProduct.salePrice !== "0đ") {
            salePriceElement.textContent = currentProduct.salePrice;
            salePriceElement.style.display = 'inline';
            document.getElementById('popupOriginalPrice').style.textDecoration = 'line-through';
        } else {
            salePriceElement.style.display = 'none';
            document.getElementById('popupOriginalPrice').style.textDecoration = 'none';
        }

        document.getElementById('popupDescription').textContent = currentProduct.description || "";
        document.getElementById('popupMainImage').src = currentProduct.image || "";

        // Xử lý ảnh phụ
        const image2Container = document.getElementById('popupImage2Container');
        const image3Container = document.getElementById('popupImage3Container');

        if (currentProduct.image2 && currentProduct.image2 !== 'null') {
            document.getElementById('popupImage2').src = currentProduct.image2;
            image2Container.style.display = 'block';
        } else {
            image2Container.style.display = 'none';
        }

        if (currentProduct.image3 && currentProduct.image3 !== 'null') {
            document.getElementById('popupImage3').src = currentProduct.image3;
            image3Container.style.display = 'block';
        } else {
            image3Container.style.display = 'none';
        }

        // Reset số lượng và hiển thị popup
        resetQuantity();
        
        // Hiển thị overlay và popup
        const overlay = document.getElementById('overlay');
        const popup = document.getElementById('popup');
        
        overlay.style.display = "block";
        popup.style.display = "block";
        
        // Đợi một chút để trình duyệt xử lý việc hiển thị trước
        setTimeout(() => {
            // Thêm class active để kích hoạt hiệu ứng
            overlay.classList.add('active');
            popup.classList.add('active');
        }, 10);

    } catch (error) {
        console.error('Lỗi khi hiển thị popup:', error);
        alert('Không thể hiển thị thông tin sản phẩm');
    }
}

// Thay thế hàm closeProductPopup hiện tại bằng hàm này
function closeProductPopup(event) {
    if (event) {
        event.preventDefault();
        event.stopPropagation();
    }
    
    const overlay = document.getElementById('overlay');
    const popup = document.getElementById('popup');
    
    // Xóa class active để kích hoạt hiệu ứng đóng
    overlay.classList.remove('active');
    popup.classList.remove('active');
    
    // Đợi hiệu ứng hoàn thành rồi mới ẩn popup
    setTimeout(() => {
        popup.style.display = "none";
        overlay.style.display = "none";
        resetQuantity();
        currentProduct = null;
    }, 300); // Đồng bộ với thời gian transition trong CSS
}
 
     // Sử dụng event delegation cho các sản phẩm
     document.body.addEventListener('click', function(event) {
         const productItem = event.target.closest('.home-product-item');
         if (productItem) {
             showProductPopup(event);
         }
     });
 
     // Thêm sự kiện cho các nút trong popup
     const buttons = {
         increase: document.querySelector('.product__cart-increase'),
         decrease: document.querySelector('.product__cart-reduce'),
         addToCart: document.querySelector('.product__cart-button'),
         close: document.getElementById('popup__close')
     };
 
     if (buttons.increase) buttons.increase.addEventListener('click', increase);
     if (buttons.decrease) buttons.decrease.addEventListener('click', decrease);
     if (buttons.addToCart) buttons.addToCart.addEventListener('click', addToCart);
     if (buttons.close) buttons.close.addEventListener('click', closeProductPopup);
 
     // Thêm sự kiện cho overlay
     if (overlay) {
         overlay.addEventListener('click', closeProductPopup);
     }
 
     // Gán các hàm cần thiết vào window để có thể gọi từ HTML
     window.increase = increase;
     window.decrease = decrease;
     window.addToCart = addToCart;
     window.showProductPopup = showProductPopup;
     window.closeProductPopup = closeProductPopup;

     // Xử lý sắp xếp sản phẩm
     const sortingDropdown = document.getElementById('sortingDropdown');
     if (sortingDropdown) {
         sortingDropdown.addEventListener('change', function() {
             const sortValue = this.value;
             const currentPage = window.location.pathname.split('/').pop();
             window.location.href = currentPage + '?sort=' + sortValue;
         });

         // Đặt giá trị sắp xếp hiện tại cho dropdown
         const urlParams = new URLSearchParams(window.location.search);
         const currentSort = urlParams.get('sort') || 'default';
         sortingDropdown.value = currentSort;
     }
 });