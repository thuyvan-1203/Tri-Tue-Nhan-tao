// Function to edit user
function editUser(id) {
     fetch( './controller/handle_user.php', {
         method: 'POST',
         headers: {
             'Content-Type': 'application/x-www-form-urlencoded',
         },
         body: 'action=get_user&id=' + id
     })
     .then(response => response.json())
     .then(data => {
         if (data.success) {
             const user = data.user;
             
             // Fill form fields with user data
             document.getElementById('editUserId').value = user.id;
             document.getElementById('editUserFullname').value = user.full_name; 
             document.getElementById('editUserEmail').value = user.email;
             document.getElementById('editUserPhone').value = user.phone_number || '';
             document.getElementById('editUserAddress').value = user.address;
             document.getElementById('editUserRole').value = user.role_id; 
             
             // Show modal
             const modal = new bootstrap.Modal(document.getElementById('editUserModal'));
             modal.show();
         } else {
             alert('Không tìm thấy người dùng: ' + (data.message || ''));
         }
     })
     .catch(error => {
         console.error('Lỗi:', error);
         alert('Có lỗi khi lấy thông tin người dùng.');
     });
 }
 
 // Function to delete user
 function deleteUser(id) {
     if(confirm('Bạn có chắc muốn xóa người dùng này?')) {
         fetch( './controller/handle_user.php', {
             method: 'POST',
             headers: {
                 'Content-Type': 'application/x-www-form-urlencoded',
             },
             body: 'action=delete_user&id=' + id
         })
         .then(response => response.json())
         .then(data => {
             if(data.success) {
                 alert(data.message);
                 location.reload(); // Reload page to update user list
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
 
 // Handle edit user form submission
 document.getElementById('editUserForm').addEventListener('submit', function(e) {
     e.preventDefault();
     
     const formData = new FormData(this);
     
     fetch( './controller/handle_user.php', {
         method: 'POST',
         body: formData
     })
     .then(response => response.json())
     .then(data => {
         if (data.success) {
             // Close modal and reset form
             const modal = bootstrap.Modal.getInstance(document.getElementById('editUserModal'));
             modal.hide();
             this.reset();
             
             alert(data.message);
             location.reload(); // Reload page to update user list
         } else {
             alert(data.message || 'Có lỗi xảy ra khi cập nhật người dùng.');
         }
     })
     .catch(error => {
         console.error('Error:', error);
         alert('Có lỗi xảy ra khi kết nối đến server. Vui lòng thử lại.');
     });
 });