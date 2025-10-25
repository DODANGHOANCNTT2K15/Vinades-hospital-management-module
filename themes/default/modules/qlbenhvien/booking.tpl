<!-- BEGIN: main -->
<link rel="stylesheet" href="{BOOKING_CSS}">

<div class="booking-page">
  <h2>Đặt lịch khám bệnh viện</h2>
  {NOTICE}

  <div class="patient-info">
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
    <p>
      <label>Ngày khám:
        <select name="ngaykham">
          <!-- BEGIN: ngaykham -->
          <option value="{NGAYKHAM_VALUE}">{NGAYKHAM_TEXT}</option>
          <!-- END: ngaykham -->
        </select>
      </label>
    </p>
    <p>
      <label>Giờ khám:
        <select name="giokham">
          <!-- BEGIN: giokham -->
          <option value="{GIOKHAM_VALUE}">{GIOKHAM_TEXT}</option>
          <!-- END: giokham -->
        </select>
      </label>
    </p>
    <p><label>Triệu chứng:
      <textarea name="ghichu" rows="3" cols="40" placeholder="Nêu những biểu hiện. Ví dụ: Đau đầu, sốt, ho khan,..."></textarea>
    </label></p>
    <p><button type="submit" name="submit">Đặt lịch</button></p>
  </form>
</div>
<!-- END: main -->
