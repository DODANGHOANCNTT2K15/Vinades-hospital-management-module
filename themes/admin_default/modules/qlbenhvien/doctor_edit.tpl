<!-- BEGIN: main -->
<link rel="stylesheet" href="{DOCTOR_EDIT_CSS}">

<div class="form-container">
    
    <h2 style="font-size:20px; color: #2b6cb0;
    font-weight: 600;">Chỉnh sửa thông tin bác sĩ</h2>
    <!-- BEGIN: error -->
    <div class="error-message">{ERROR}</div>
    <!-- END: error -->

    <form action="{ACTION_LINK}" method="post">
        <div class="form-group">
            <label>Họ tên *</label>
            <input type="text" name="hoten" value="{HOTEN}" required>
        </div>

        <div class="form-group">
            <label>Ngày sinh</label>
            <input type="date" name="ngaysinh" value="{NGAYSINH}">
        </div>

        <div class="form-group">
            <label>Giới tính</label>
            <div class="gender-radio">
                <label><input type="radio" name="gioitinh" value="1" {GT1}> Nam</label>
                <label><input type="radio" name="gioitinh" value="2" {GT2}> Nữ</label>
                <label><input type="radio" name="gioitinh" value="3" {GT3}> Khác</label>
            </div>
        </div>

        <div class="form-group">
            <label>Chuyên khoa</label>
            <select name="chuyenkhoa_id">
                <!-- BEGIN: spec_option -->
                <option value="{SPEC.id}" {SPEC.selected}>{SPEC.tenchuyenkhoa}</option>
                <!-- END: spec_option -->
            </select>
        </div>

        <div class="form-group">
            <label>Trình độ</label>
            <input type="text" name="trinhdo" value="{TRINHDO}">
        </div>

        <div class="form-group">
            <label>Lịch làm việc</label>
            <input type="text" name="lichlamviec" value="{LICHLAMVIEC}">
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="{EMAIL}">
        </div>

        <div class="form-group">
            <label>Số điện thoại</label>
            <input type="text" name="sdt" value="{SDT}">
        </div>

        <div class="form-group">
            <label>Người dùng liên kết (User ID)</label>
            <select name="userid">
                <option value="0">-- Không liên kết --</option>
                <!-- BEGIN: user_option -->
                <option value="{USER.userid}" {USER.selected}>{USER.display}</option>
                <!-- END: user_option -->
            </select>
        </div>

        <input type="hidden" name="submit" value="1">

        <div class="form-actions" style="text-align:right; margin-top:20px;">
            <a href="{BACK_LINK}" class="btn btn-secondary">← Quay lại</a>
            <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
        </div>
    </form>
</div>
<!-- END: main -->
