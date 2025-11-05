<!-- BEGIN: main -->
<link rel="stylesheet" href="{SPECIALTIES_CSS}">

<div class="schedule-add-container">
    <h2 class="form-title" style="font-size:20px; color: #2b6cb0;
    font-weight: 600;">Thêm chuyên khoa mới</h2>

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
