// Hiện chi tiết đơn hàng trong modal
function viewOrder(id) {
    fetch('./auth/admin/controller/handle_order.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'action=get_order&id=' + id,
        credentials: 'include'
    })
    .then(response => {
        if (!response.ok) throw new Error('Lỗi server: ' + response.status);
        return response.json();
    })
    .then(data => {
        if (data.success) {
            const order = data.order;
            const details = data.details;

            // Hiển thị thông tin đơn hàng
            document.getElementById('orderId').textContent = '#' + (order.id || 'Không xác định');
            document.getElementById('orderCustomer').textContent = (order.full_name || 'Không xác định');
            document.getElementById('orderEmail').textContent = order.email || 'Không xác định';
            document.getElementById('orderAddress').textContent = order.address || 'Không xác định';
            document.getElementById('orderPhoneNumber').textContent = order.phone_number || 'Không xác định';
            document.getElementById('orderDate').textContent = order.ordered_date || 'Không xác định';
            document.getElementById('orderStatus').textContent = order.status || 'Đang chờ xác nhận';

            // Hiển thị danh sách sản phẩm
            const itemsContainer = document.getElementById('orderItems');
            itemsContainer.innerHTML = '';
            if (details && details.length > 0) {
                details.forEach(item => {
                    const row = `<tr>
                        <td>${item.product_image ? `<img src="${item.product_image}" alt="Hình ảnh sản phẩm" style="width: 100px; height: auto;" />` : 'Không xác định'}</td>
                        <td>${item.product_name || 'Không xác định'}</td>
                        <td>${item.quantity || 0}</td>
                        <td>${Number(item.price || 0).toLocaleString()} VNĐ</td>
                        <td>${Number(item.total_money || 0).toLocaleString()} VNĐ</td>
                    </tr>`;
                    itemsContainer.innerHTML += row;
                });
            } else {
                itemsContainer.innerHTML = '<tr><td colspan="4">Không có sản phẩm nào</td></tr>';
            }

            // Hiển thị modal
            const modal = new bootstrap.Modal(document.getElementById('viewOrderModal'));
            modal.show();
        } else {
            alert('Lỗi: ' + (data.message || 'Không thể lấy chi tiết đơn hàng'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra khi lấy chi tiết đơn hàng: ' + error.message);
    });
}
