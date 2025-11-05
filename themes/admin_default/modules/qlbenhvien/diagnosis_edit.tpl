<!-- BEGIN: main -->
<link rel="stylesheet" href="{DIAGNOSES_EDIT_CSS}">

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
