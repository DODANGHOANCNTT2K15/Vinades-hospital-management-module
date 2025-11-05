<!-- BEGIN: main -->
<link rel="stylesheet" href="{SPECIALTIES_EDIT_CSS}">

<div class="specialies-edit-container">
    <h2 style="font-size:20px; color: #2b6cb0;
    font-weight: 600;">Chỉnh sửa chuyên khoa</h2>

    <!-- BEGIN: error -->
    <div class="error-message">{ERROR}</div>
    <!-- END: error -->

    <form action="{ACTION_LINK}" method="post" class="specialies-form">
        <div class="form-group">
            <label>Tên chuyên khoa <span class="required">*</span></label>
            <input type="text" name="tenchuyenkhoa" class="form-control" value="{TENCHUYENKHOA}" required>
        </div>

        <div class="form-group">
            <label>Mô tả</label>
            <textarea name="mota" class="form-control" rows="4">{MOTA}</textarea>
        </div>

        <div class="form-group">
            <label>Trạng thái</label>
            <select name="trangthai" class="form-select">
                <!-- BEGIN: status_option -->
                <option value="{STATUS_KEY}" {STATUS_SELECTED}>{STATUS_TEXT}</option>
                <!-- END: status_option -->
            </select>
        </div>

        <input type="hidden" name="submit" value="1">

        <div class="form-actions">
            <a href="{BACK_LINK}" class="btn btn-secondary">← Quay lại</a>
            <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
        </div>
    </form>
</div>
<!-- END: main -->
