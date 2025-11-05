<!-- BEGIN: main -->
<link rel="stylesheet" href="{PATIENT_EDIT_CSS}">

<div class="form-container">
    <h2>Chỉnh sửa bệnh nhân</h2>

    <!-- Hiện lỗi nếu có -->
    <!-- BEGIN: error -->
    <div class="error-message">{ERROR}</div>
    <!-- END: error -->

    <form action="{ACTION_LINK}" method="post" class="patient-form">
        <div class="form-group">
            <label for="hoten">Họ tên <span class="required">*</span></label>
            <input type="text" id="hoten" name="hoten" value="{HOTEN}" required>
        </div>

        <div class="form-group">
            <label for="ngaysinh">Ngày sinh</label>
            <input type="date" id="ngaysinh" name="ngaysinh" value="{NGAYSINH}">
        </div>

        <div class="form-group">
            <label>Giới tính</label>
            <div class="gender-radio">
                <label><input type="radio" name="gioitinh" value="1" {GIOITINH_MALE_CHECKED}> Nam</label>
                <label><input type="radio" name="gioitinh" value="0" {GIOITINH_FEMALE_CHECKED}> Nữ</label>
            </div>
        </div>

        <div class="form-group">
            <label for="diachi">Địa chỉ</label>
            <textarea id="diachi" name="diachi" rows="3">{DIA_CHI}</textarea>
        </div>

        <div class="form-group">
            <label for="sdt">Số điện thoại</label>
            <input type="text" id="sdt" name="sdt" value="{SDT}">
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{EMAIL}">
        </div>

        <input type="hidden" name="submit" value="1">

        <div class="form-actions">
            <a href="{BACK_LINK}" class="btn btn-secondary">← Quay lại</a>
            <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
        </div>
    </form>
</div>
<!-- END: main -->
