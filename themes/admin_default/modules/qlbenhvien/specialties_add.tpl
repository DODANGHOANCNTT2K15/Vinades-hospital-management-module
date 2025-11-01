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
    <h2 class="form-title">Thêm chuyên khoa mới</h2>

    <!-- BEGIN: error -->
    <div class="form-error">{ERROR}</div>
    <!-- END: error -->

    <form action="{ACTION}" method="post" class="schedule-form">
        <div class="form-group">
            <label>Tên chuyên khoa <span class="required">*</span></label>
            <input type="text" name="tenchuyenkhoa" class="form-control" placeholder="Nhập tên chuyên khoa">
        </div>

        <div class="form-group">
            <label>Mô tả</label>
            <textarea name="mota" class="form-control" rows="3" placeholder="Nhập mô tả (nếu có)"></textarea>
        </div>

        <div class="form-group">
            <label>Trạng thái</label>
            <select name="trangthai" class="form-control">
                <option value="1">Hoạt động</option>
                <option value="0">Ngừng hoạt động</option>
            </select>
        </div>

        <div class="form-actions">
            <button type="submit" name="save" value="1" class="btn btn-primary">Lưu</button>
            <a href="{BACK_LINK}" class="btn btn-secondary">← Quay lại danh sách</a>
        </div>
    </form>
</div>
<!-- END: main -->
