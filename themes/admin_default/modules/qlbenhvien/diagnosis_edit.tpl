<!-- BEGIN: main -->
<link rel="stylesheet" href="{HISTORY_CSS}">
<style>
    h2 {
        margin: 0;
        font-size: 20px;
        color: #2b6cb0;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 20px;
    }

    .schedule-edit-container {
        background: #fff;
        border-radius: 10px;
        padding: 25px 30px;
        max-width: 650px;
        margin: 0 auto;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    }

    .schedule-form .form-group {
        margin-bottom: 16px;
    }

    .schedule-form label {
        display: block;
        font-weight: 600;
        color: #333;
        margin-bottom: 6px;
    }

    /* Cho phần p hiển thị thông tin (như lịch khám, bệnh nhân, bác sĩ, ngày tạo) */
    .schedule-form .form-group p {
        margin: 4px 0 0 0;
        font-size: 14px;
        color: #555;
        background: #f9fafb;
        padding: 8px 12px;
        border-radius: 6px;
        border: 1px solid #e2e8f0;
        user-select: text;
        white-space: pre-wrap;
    }

    /* Textarea */
    .schedule-form textarea {
        width: 100%;
        padding: 8px 10px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 14px;
        resize: vertical;
        min-height: 70px;
        transition: all 0.2s ease;
        color: #333;
        font-family: inherit;
    }

    .schedule-form textarea:focus {
        border-color: #2b6cb0;
        box-shadow: 0 0 0 2px rgba(43, 108, 176, 0.2);
        outline: none;
    }

    /* Button nhóm hành động */
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
        user-select: none;
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
</style>

<div class="schedule-edit-container">
    <h2>Chỉnh sửa chẩn đoán</h2>

    <form action="{ACTION_LINK}" method="post" class="schedule-form">

        <div class="form-group">
            <label>Lịch khám</label>
            <p><strong>Ngày khám:</strong> {NGAYKHAM}</p>
            <p><strong>Bệnh nhân:</strong> {BENHNHAN}</p>
            <p><strong>Bác sĩ phụ trách:</strong> {BACSI}</p>
        </div>

        <div class="form-group">
            <label>Ngày tạo</label>
            <p>{NGAYTAO}</p>
        </div>

        <div class="form-group">
            <label>Chẩn đoán <span class="required">*</span></label>
            <textarea name="chandoan" class="form-control" rows="4" required>{CHANDOAN}</textarea>
        </div>

        <div class="form-group">
            <label>Đơn thuốc</label>
            <textarea name="donthuoc" class="form-conrol" rows="3">{DONTHUOC}</textarea>
        </div>

        <input type="hidden" name="submit" value="1">

        <div class="form-actions">
            <a href="{BACK_LINK}" class="btn btn-secondary">← Quay lại</a>
            <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
        </div>
    </form>
</div>
<!-- END: main -->
