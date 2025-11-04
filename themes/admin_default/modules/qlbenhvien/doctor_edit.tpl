<!-- BEGIN: main -->
<style>
.form-container {
    background: #fff;
    padding: 25px 30px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    max-width: 700px;
    margin: 0 auto;
}
.form-group { margin-bottom: 15px; }
label { font-weight: 600; color: #333; display: block; margin-bottom: 5px; }
input, select, textarea {
    width: 100%; padding: 8px; border: 1px solid #ccc;
    border-radius: 6px; font-size: 14px;
}
.gender-radio { display: flex; gap: 15px; }
.btn { padding: 8px 16px; border-radius: 6px; cursor: pointer; text-decoration: none; }
.btn-primary { background: #2563eb; color: white; border: none; }
.btn-secondary { background: #f3f4f6; border: 1px solid #ccc; color: #333; }
.error-message { color: red; font-weight: 600; margin-bottom: 15px; }
</style>

<div class="form-container">
    <h2>Chỉnh sửa thông tin bác sĩ</h2>

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
