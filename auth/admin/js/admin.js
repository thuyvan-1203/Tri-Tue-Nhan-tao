// Handle navigation
document.querySelectorAll(".nav-link[data-section]").forEach((link) => {
    link.addEventListener("click", (e) => {
        e.preventDefault();

        // Kiểm tra sự tồn tại của các phần tử trước khi thao tác
        const sections = document.querySelectorAll(".content-section");
        if (sections) {
            sections.forEach((s) => s.classList.remove("active"));
        }
        const navLinks = document.querySelectorAll(".nav-link");
        if (navLinks) {
            navLinks.forEach((l) => l.classList.remove("active"));
        }

        // Thêm class "active" vào mục đã click
        link.classList.add("active");
        const sectionId = link.getAttribute("data-section");
        const section = document.getElementById(sectionId);
        if (section) {
            section.classList.add("active");
            // Cập nhật query string trong URL
            history.pushState(null, '', `?section=${sectionId}`);
        }
    });
});
