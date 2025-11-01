<!-- BEGIN: main -->
<link rel="stylesheet" href="{HISTORY_CSS}">
<style>
h2 { margin:0; font-size:20px; color:#2b6cb0; font-weight:600; margin-bottom:20px; }
.form-group { margin-bottom:16px; }
.form-group label { font-weight:600; margin-bottom:6px; display:block; }
.form-control { width:100%; padding:8px 10px; border:1px solid #d1d5db; border-radius:6px; }
.form-control:focus { border-color:#2b6cb0; box-shadow:0 0 0 2px rgba(43,108,176,0.2); outline:none; }
.form-error { background:#fde8e8; color:#b91c1c; border:1px solid #fca5a5; border-radius:6px; padding:10px 12px; margin-bottom:15px; }
.form-actions { display:flex; justify-content:flex-end; gap:10px; margin-top:20px; }
.btn { border-radius:6px; padding:8px 16px; font-size:14px; cursor:pointer; text-decoration:none; }
.btn-primary { background-color:#2b6cb0; color:#fff; border:none; }
.btn-primary:hover { background-color:#1e4f8f; }
.btn-secondary { background-color:#f3f4f6; color:#333; border:1px solid #ccc; }
.btn-secondary:hover { background-color:#e5e7eb; }
.required { color:red; margin-left:3px; }
</style>

<div class="schedule-add-container">
    <h2 class="form-title">Thêm bác sĩ mới</h2>

    <!-- BEGIN: error -->
    <div class="form-error">{ERROR}</div>
    <!-- END: error -->

    <form action="{ACTION}" method="post" class="schedule-form">
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
            <label>Chuyên khoa</label>
            <select name="chuyenkhoa_id" class="form-control">
                <option value="0">-- Chọn chuyên khoa --</option>
                <!-- BEGIN: chuyenkhoa_option -->
                <option value="{CHUYENKHOA.id}">{CHUYENKHOA.tenchuyenkhoa}</option>
                <!-- END: chuyenkhoa_option -->
            </select>
        </div>

        <div class="form-group">
            <label>Trình độ</label>
            <input type="text" name="trinhdo" class="form-control" placeholder="Nhập trình độ">
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control" placeholder="Nhập email">
        </div>

        <div class="form-group">
            <label>SĐT</label>
            <input type="text" name="sdt" class="form-control" placeholder="Nhập số điện thoại">
        </div>

        <div class="form-group">
            <label>Lịch làm việc</label>
            <input type="text" name="lichlamviec" class="form-control" placeholder="Nhập lịch làm việc">
        </div>

        <div class="form-actions">
            <button type="submit" name="save" value="1" class="btn btn-primary">Lưu</button>
            <a href="{BACK_LINK}" class="btn btn-secondary">← Quay lại danh sách</a>
        </div>
    </form>
</div>
<!-- END: main -->
