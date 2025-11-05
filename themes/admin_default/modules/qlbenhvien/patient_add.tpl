<!-- BEGIN: main -->
<link rel="stylesheet" href="{PATIENT_ADD_CSS}">

<div class="patient-add-container">
    <h2 class="form-title">Thêm bệnh nhân mới</h2>

    <!-- BEGIN: error -->
    <div class="form-error">{ERROR}</div>
    <!-- END: error -->

    <form action="{ACTION}" method="post" class="patient-form">
        <div class="form-group">
            <label>Họ và tên <span class="required">*</span></label>
            <input type="text" name="hoten" class="form-control" placeholder="Nhập họ và tên">
        </div>

        <div class="form-group">
            <label>Ngày sinh <span class="required">*</span></label>
            <input type="text" name="ngaysinh" class="form-control" placeholder="dd/mm/yyyy">
        </div>

        <div class="form-group">
            <label>Giới tính</label>
            <select name="gioitinh" class="form-control">
                <option value="0">-- Chọn giới tính --</option>
                <option value="1">Nam</option>
                <option value="2">Nữ</option>
                <option value="3">Khác</option>
            </select>
        </div>

        <div class="form-group">
            <label>Địa chỉ</label>
            <input type="text" name="diachi" class="form-control" placeholder="Nhập địa chỉ">
        </div>

        <div class="form-group">
            <label>Số điện thoại <span class="required">*</span></label>
            <input type="text" name="sdt" class="form-control" placeholder="Nhập số điện thoại">
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control" placeholder="Nhập email (nếu có)">
        </div>

        <div class="form-actions">
            <button type="submit" name="save" value="1" class="btn btn-primary">Lưu</button>
            <a href="{BACK_LINK}" class="btn btn-secondary">← Quay lại danh sách</a>
        </div>
    </form>
</div>
<!-- END: main -->
