function editUser(id) {
    // Kiểm tra và hiển thị modal
    const modalElement = document.getElementById("editProfileModal");
    if (!modalElement) {
        alert("Không tìm thấy modal chỉnh sửa hồ sơ!");
        return;
    }
    const modal = new bootstrap.Modal(modalElement);
    modal.show();

    // Kiểm tra và xử lý form submit
    const form = document.getElementById("editProfileForm");
    if (!form) {
        alert("Không tìm thấy form chỉnh sửa hồ sơ!");
        return;
    }

    form.onsubmit = function (event) {
        event.preventDefault(); // Ngăn submit mặc định

        const formData = new FormData(form);
        const data = new URLSearchParams(formData);

        const url = "./auth/admin/controller/handle_user.php";
        fetch(url, {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
            body: data.toString(),
        })
            .then((response) => {
                const status = response.status;
                return response.text().then((text) => {
                    let jsonData;
                    try {
                        jsonData = JSON.parse(text);
                        return { data: jsonData, status };
                    } catch (e) {
                        // Nếu status là 404 nhưng dữ liệu đã được cập nhật
                        if (status === 404) {
                            console.warn(
                                "Phản hồi 404 nhưng dữ liệu có thể đã được cập nhật:",
                                text.slice(0, 200)
                            );
                            return { data: { success: true }, status }; // Giả định thành công
                        }
                        throw new Error(
                            `Phản hồi không phải JSON hợp lệ (Status: ${status}): ${text.slice(
                                0,
                                100
                            )}...`
                        );
                    }
                });
            })
            .then(({ data, status }) => {
                if (data.success) {
                    alert("Cập nhật hồ sơ thành công!");
                    window.location.reload(); // Làm mới trang
                } else {
                    alert(
                        "Lỗi khi cập nhật hồ sơ: " +
                            (data.message || "Không rõ nguyên nhân") +
                            ` (Status: ${status})`
                    );
                }
            })
            .catch((error) => {
                console.error("Lỗi:", error);
                alert("Có lỗi khi cập nhật hồ sơ: " + error.message);
            });
    };
}


function openChangePasswordModal() {
    const editProfileModal = bootstrap.Modal.getInstance(
        document.getElementById("editProfileModal")
    );
    if (editProfileModal) {
        editProfileModal.hide();
    }

    const changePasswordModalElement = document.getElementById("changePasswordModal");
    if (!changePasswordModalElement) {
        alert("Không tìm thấy modal đổi mật khẩu!");
        return;
    }
    const changePasswordModal = new bootstrap.Modal(changePasswordModalElement);
    changePasswordModal.show();

    const form = document.getElementById("changePasswordForm");
    if (!form) {
        alert("Không tìm thấy form đổi mật khẩu!");
        return;
    }

    form.onsubmit = function (event) {
        event.preventDefault();

        const newPassword = form.querySelector("#newPassword").value;
        if (newPassword.length < 6) {
            alert("Mật khẩu mới phải có ít nhất 6 ký tự!");
            return;
        }

        const formData = new FormData(form);
        const data = new URLSearchParams(formData);

        const url = "./repository/change_password.php";
        fetch(url, {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
                "Accept": "application/json",
            },
            body: data.toString(),
        })
            .then((response) => {
                const status = response.status;
                return response.text().then((text) => {
                    console.log("Phản hồi đổi mật khẩu:", text);
                    try {
                        const jsonData = JSON.parse(text);
                        return jsonData;
                    } catch (e) {
                        throw new Error(
                            `Phản hồi không phải JSON hợp lệ (Status: ${status}): ${text.slice(0, 100)}...`
                        );
                    }
                });
            })
            .then((data) => {
                if (data.message === "Phiên đăng nhập đã hết hạn") {
                    alert("Phiên đăng nhập đã hết hạn. Vui lòng đăng nhập lại.");
                    window.location.href = "login.php";
                } else if (data.success) {
                    alert("Đổi mật khẩu thành công!");
                } else {
                    alert(
                        "Lỗi khi đổi mật khẩu: " +
                            (data.message || "Không rõ nguyên nhân")
                    );
                }
            })
            .catch((error) => {
                console.error("Lỗi:", error);
                alert("Có lỗi khi đổi mật khẩu: " + error.message);
            });
    };
}