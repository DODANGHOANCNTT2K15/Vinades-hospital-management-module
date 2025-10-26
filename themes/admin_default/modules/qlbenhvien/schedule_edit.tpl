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

    .schedule-form select {
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        width: 100%;
        padding: 8px 40px 8px 10px; /* chừa chỗ cho mũi tên */
        font-size: 14px;
        color: #333;
        border: 1px solid #ccc;
        border-radius: 6px;
        background-color: #fff;
        background-image: url("data:image/svg+xml;utf8,<svg fill='%23666' height='14' viewBox='0 0 24 24' width='14' xmlns='http://www.w3.org/2000/svg'><path d='M7 10l5 5 5-5z'/></svg>");
        background-repeat: no-repeat;
        background-position: right 10px center; /* 👈 Dịch mũi tên vào 10px */
        background-size: 14px;
        cursor: pointer;
    }

    .schedule-form select:focus {
        border-color: #2b6cb0;
        box-shadow: 0 0 0 3px rgba(43,108,176,0.15);
        outline: none;
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

    .schedule-form .form-control,
    .schedule-form .form-select {
        width: 100%;
        padding: 8px 10px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 14px;
        transition: all 0.2s ease;
    }

    .schedule-form .form-control:focus,
    .schedule-form .form-select:focus {
        border-color: #2b6cb0;
        box-shadow: 0 0 0 2px rgba(43, 108, 176, 0.2);
        outline: none;
    }

    .schedule-form textarea {
        resize: vertical;
        min-height: 70px;
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
</style>

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
