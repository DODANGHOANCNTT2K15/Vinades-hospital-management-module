<!-- BEGIN: main -->
<style>
.specialies-edit-container {
    background: #fff;
    border-radius: 10px;
    padding: 25px 30px;
    max-width: 650px;
    margin: 0 auto;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
}
.specialies-form .form-group {
    margin-bottom: 16px;
}
.specialies-form label {
    display: block;
    font-weight: 600;
    color: #333;
    margin-bottom: 6px;
}
.specialies-form .form-control,
.specialies-form .form-select,
.specialies-form textarea {
    width: 100%;
    padding: 8px 10px;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: 14px;
    transition: all 0.2s ease;
}
.specialies-form .form-control:focus,
.specialies-form .form-select:focus {
    border-color: #2b6cb0;
    box-shadow: 0 0 0 2px rgba(43,108,176,0.2);
    outline: none;
}
.specialies-form textarea {
    resize: vertical;
    min-height: 80px;
}
.form-actions {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    gap: 10px;
    margin-top: 20px;
}
.btn {
    display: inline-block;
    font-weight: 500;
    border-radius: 6px;
    padding: 8px 16px;
    font-size: 14px;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.2s ease;
}
.btn-primary {
    background-color: #2b6cb0;
    color: #fff;
    border: none;
}
.btn-primary:hover {
    background-color: #1e4f8f;
}
.btn-secondary {
    background-color: #f3f4f6;
    color: #333;
    border: 1px solid #ccc;
}
.btn-secondary:hover {
    background-color: #e5e7eb;
}
.required {
    color: red;
    margin-left: 3px;
}
.error-message {
    color: red;
    font-weight: 600;
    margin-bottom: 10px;
}
</style>

<div class="specialies-edit-container">
    <h2>Chỉnh sửa chuyên khoa</h2>

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
