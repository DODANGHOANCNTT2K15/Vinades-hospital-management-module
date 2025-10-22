<!-- BEGIN: main -->
<h2>Đặt lịch khám bệnh viện</h2>
{NOTICE}

<div class="patient-info" style="border:1px solid #ccc;padding:10px;margin-bottom:10px;">
  <h3>Thông tin bệnh nhân</h3>
  <p><strong>Họ tên:</strong> {HOTEN}</p>
  <p><strong>Email:</strong> {EMAIL}</p>
  <!-- BEGIN: gioitinh -->
  <p><strong>Giới tính:</strong> {GIOITINH}</p>
  <!-- END: gioitinh -->
  <!-- BEGIN: sdt -->
  <p><strong>SĐT:</strong> {SDT}</p>
  <!-- END: sdt -->
  <!-- BEGIN: diachi -->
  <p><strong>Địa chỉ:</strong> {DIACHI}</p>
  <!-- END: diachi -->
</div>

<form method="post">
  <p><label>Ngày khám: <input type="date" name="ngaykham" required></label></p>
  <p><label>Giờ khám: <input type="time" name="giokham" required></label></p>
  <p><label>Ghi chú: <textarea name="ghichu" rows="3" cols="40" placeholder="Nhập nội dung ghi chú..."></textarea></label></p>
  <p><button type="submit" name="submit">Đặt lịch</button></p>
</form>
<!-- END: main -->
