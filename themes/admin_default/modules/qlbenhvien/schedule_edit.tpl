<!-- BEGIN: main -->
<link rel="stylesheet" href="{SCHEDULE_EDIT_CSS}">

<div class="schedule-edit-container">
    <h2>Chỉnh sửa lịch khám</h2>

    <form action="{ACTION_LINK}" method="post" class="schedule-form">

        <div class="form-group">
            <label>Bệnh nhân <span class="required">*</span></label>
            <select name="benhnhan_id" class="form-select" required>
                <!-- BEGIN: patient_option -->
                <option value="{PATIENT.id}" {PATIENT.selected}>{PATIENT.hoten} ({PATIENT.sdt})</option>
                <!-- END: patient_option -->
            </select>
        </div>

        <div class="form-group">
            <label>Bác sĩ phụ trách</label>
            <select name="bacsi_id" class="form-select">
                <option value="0" {BS_SELECTED}>-- Chưa phân công --</option>
                <!-- BEGIN: doctor_option -->
                <option value="{DOCTOR.id}" {DOCTOR.selected}>{DOCTOR.hoten} - {DOCTOR.khoa}</option>
                <!-- END: doctor_option -->
            </select>
        </div>

        <div class="form-group">
            <label>Ngày khám <span class="required">*</span></label>
            <input type="date" name="ngaykham" class="form-control" value="{NGAYKHAM}">
        </div>

        <div class="form-group">
            <label>Giờ khám</label>
            <input type="time" name="giokham" class="form-control" value="{GIOKHAM}">
        </div>

        <div class="form-group">
            <label>Trạng thái</label>
            <select name="trangthai" class="form-select">
                <!-- BEGIN: status_option -->
                <option value="{STATUS_KEY}" {STATUS_SELECTED}>{STATUS_TEXT}</option>
                <!-- END: status_option -->
            </select>
        </div>

        <div class="form-group">
            <label>Ghi chú</label>
            <textarea name="ghichu" class="form-control" rows="3">{GHICHU}</textarea>
        </div>

        <input type="hidden" name="submit" value="1">

        <div class="form-actions">
            <a href="{BACK_LINK}" class="btn btn-secondary">← Quay lại</a>
            <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
        </div>
    </form>
</div>
<!-- END: main -->
