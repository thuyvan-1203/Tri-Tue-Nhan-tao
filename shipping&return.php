<?php
     include("header.php");
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Giao hàng & hoàn trả</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>

  <div class="container">
    <div class="left-panel">
      Giao hàng & hoàn trả
    </div>
    <div class="right-panel">
      <div class="section-title">Vận chuyển</div>
      <div class="accordion-item">
        <div class="accordion-header">Đóng gói và chuẩn bị</div>
        <div class="accordion-body">
          <p>Để đảm bảo hàng hóa được vận chuyển an toàn, cần lựa chọn vật liệu đóng gói phù hợp với từng loại sản phẩm. Quá trình này bao gồm việc bọc lót cẩn thận để tránh va đập, cố định hàng hóa bên trong và dán nhãn vận chuyển rõ ràng. Việc chuẩn bị kỹ lưỡng giúp giảm thiểu rủi ro hư hỏng trong quá trình di chuyển.</p>
        </div>
      </div>
      <div class="accordion-item">
        <div class="accordion-header">Lên lịch giao hàng</div>
        <div class="accordion-body">
          <p>Việc sắp xếp thời gian giao hàng cần được thực hiện một cách linh hoạt, ưu tiên sự thuận tiện cho cả người gửi và người nhận. Lựa chọn phương thức vận chuyển phù hợp với khoảng cách, thời gian mong muốn và đặc điểm của hàng hóa. Thống nhất thời gian nhận hàng cụ thể để đảm bảo giao dịch thành công.</p>
        </div>
      </div>
      <div class="accordion-item">
        <div class="accordion-header">Xác nhận và theo dõi giao hàng</div>
        <div class="accordion-body">
          <p>Sau khi hàng được gửi đi, khách hàng sẽ nhận được thông tin xác nhận cùng mã vận đơn duy nhất để theo dõi trực tuyến. Quá trình này giúp người nhận nắm bắt được vị trí hiện tại và thời gian giao hàng dự kiến của kiện hàng. Các thông báo cập nhật về trạng thái giao hàng sẽ được gửi đi khi cần thiết.</p>
        </div>
      </div>
      <div class="accordion-item">
        <div class="accordion-header">Hoàn thành giao hàng và sự hài lòng của khách hàng</div>
        <div class="accordion-body">
          <p>Mục tiêu cuối cùng là hàng hóa được giao đến đúng địa chỉ người nhận trong tình trạng nguyên vẹn. Sau khi giao hàng thành công, việc thu thập phản hồi từ khách hàng về trải nghiệm vận chuyển là rất quan trọng để đánh giá chất lượng dịch vụ và tìm kiếm cơ hội cải thiện.</p>
        </div>
      </div>

      <div class="section-title">Trả hàng</div>
      <div class="accordion-item">
        <div class="accordion-header">Chuẩn bị mặt hàng để trả lại</div>
        <div class="accordion-body">
          <p>Hãy tiến hành một cách cẩn thận việc sắp xếp, kiểm tra kỹ lưỡng tình trạng và đóng gói sản phẩm mà bạn muốn trả lại. Đảm bảo rằng sản phẩm đáp ứng đầy đủ các yêu cầu về bao bì, tem mác và các phụ kiện đi kèm theo quy định của người bán.</p>
        </div>
      </div>
      <div class="accordion-item">
        <div class="accordion-header">Cung cấp thông tin liên quan</div>
        <div class="accordion-body">
          <p>Vui lòng cung cấp một cách chi tiết và hoàn toàn chính xác tất cả những thông tin có liên quan đến giao dịch mua hàng ban đầu, bao gồm số đơn hàng, ngày mua, thông tin sản phẩm, cũng như lý do cụ thể cho việc bạn muốn trả lại sản phẩm này.</p>
        </div>
      </div>
      <div class="accordion-item">
        <div class="accordion-header">Thực hiện theo quy trình trả hàng</div>
        <div class="accordion-body">
          <p>Thực hiện một cách tuần tự và đầy đủ tất cả các bước đã được hướng dẫn trong quy trình trả hàng chính thức của người bán hoặc hệ thống. Điều này có thể bao gồm việc điền vào các biểu mẫu trực tuyến hoặc phiếu trả hàng, lựa chọn phương thức gửi trả phù hợp và tuân thủ các mốc thời gian quy định.</p>
        </div>
      </div>
      <div class="accordion-item">
        <div class="accordion-header">Tùy chọn hoàn tiền hoặc đổi hàng</div>
        <div class="accordion-body">
          <p>Xin vui lòng đưa ra quyết định cuối cùng về việc bạn mong muốn nhận lại số tiền đã thanh toán cho sản phẩm ban đầu thông qua phương thức nào. BBạn có nhu cầu đổi lấy một sản phẩm khác có cùng loại, khác loại hoặc khác các thuộc tính như kích cỡ, màu sắc.</p>
        </div>
      </div>
    </div>
  </div>
  <script>
    document.querySelectorAll('.accordion-header').forEach(header => {
      header.addEventListener('click', () => {
        const parent = header.parentElement;
        const isActive = parent.classList.contains('active');
        document.querySelectorAll('.accordion-item').forEach(item => item.classList.remove('active'));
        if (!isActive) {
          parent.classList.add('active');
        }
      });
    });
  </script>
  <?php
     include("footer.php");
  ?>
</body>
</html>