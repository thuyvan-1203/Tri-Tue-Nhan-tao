$(document).ready(function() {
    console.log('search.js loaded');

    // Mở modal khi nhấp vào liên kết tìm kiếm
    $('#open-search-modal').click(function(e) {
        e.preventDefault();
        console.log('Open search modal clicked');
        $('#search-modal').modal('show');
    });

    // Xử lý khi modal đóng
    $('#search-modal').on('hidden.bs.modal', function() {
        console.log('Modal closed');
        $('#search-results').html('');
        $('#search-form input').val('');
    });

    // Xử lý form tìm kiếm bằng AJAX
    $('#search-form').submit(function(e) {
        e.preventDefault();
        let query = $(this).find('input[name="query"]').val();
        console.log('Search form submitted with query:', query);

        if (query.length < 3) {
            $('#search-results').html('<p>Từ khóa phải dài ít nhất 3 ký tự.</p>');
            return;
        }

        $.ajax({
            url: 'search.php',
            method: 'POST',
            data: { query: query },
            success: function(data) {
                console.log('AJAX success:', data);
                $('#search-results').html(data);
            },
            error: function(xhr, status, error) {
                console.error('AJAX error:', status, error);
                $('#search-results').html('<p>Có lỗi xảy ra khi tìm kiếm. Vui lòng thử lại.</p>');
            }
        });
    });
});