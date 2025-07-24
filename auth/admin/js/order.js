// Function to view order details
function viewOrder(id) {
    fetch('./controller/handle_order.php', {
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
            document.getElementById('orderId').textContent = '#' + order.id;
            document.getElementById('orderCustomer').textContent = order.full_name + ' (' + order.email + ')';
            document.getElementById('orderDate').textContent = order.ordered_date;
            document.getElementById('orderStatus').textContent = order.status;

            const itemsContainer = document.getElementById('orderItems');
            itemsContainer.innerHTML = '';
            details.forEach(item => {
                const row = `<tr>
                    <td>${item.product_name}</td>
                    <td>${item.quantity}</td>
                    <td>${Number(item.price).toLocaleString()} VNĐ</td>
                    <td>${Number(item.total_money).toLocaleString()} VNĐ</td>
                </tr>`;
                itemsContainer.innerHTML += row;
            });

            const modal = new bootstrap.Modal(document.getElementById('viewOrderModal'));
            modal.show();
        } else {
            alert('Lỗi: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra khi lấy chi tiết đơn hàng: ' + error.message);
    });
}

function deleteOrder(id) {
    if (confirm('Bạn có chắc muốn xóa đơn hàng này?')) {
        fetch('./controller/handle_order.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'action=delete_order&id=' + id,
            credentials: 'include'
        })
        .then(response => {
            if (!response.ok) throw new Error('Lỗi server: ' + response.status);
            return response.json();
        })
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert('Lỗi: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi xóa đơn hàng.');
        });
    }
}

function updateOrderStatus(id) {
    fetch('./controller/handle_order.php', {
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
            if (data.order.status === 'Đã hoàn thành') {
                alert('Đơn hàng đã hoàn thành và không thể chỉnh sửa trạng thái.');
                return;
            }
            document.getElementById('updateOrderId').value = id;
            document.getElementById('updateOrderStatusSelect').value = data.order.status;

            const modal = new bootstrap.Modal(document.getElementById('updateOrderStatusModal'));
            modal.show();
        } else {
            alert('Lỗi: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra khi lấy thông tin đơn hàng.');
    });
}

document.getElementById('updateOrderStatusForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const id = document.getElementById('updateOrderId').value;
    const status = document.getElementById('updateOrderStatusSelect').value;

    fetch('./controller/handle_order.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'action=update_status&id=' + id + '&status=' + encodeURIComponent(status),
        credentials: 'include'
    })
    .then(response => {
        if (!response.ok) throw new Error('Lỗi server: ' + response.status);
        return response.json();
    })
    .then(data => {
        if (data.success) {
            const modal = bootstrap.Modal.getInstance(document.getElementById('updateOrderStatusModal'));
            modal.hide();
            alert(data.message);
            location.reload();
        } else {
            alert('Lỗi: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra khi cập nhật trạng thái.');
    });
});