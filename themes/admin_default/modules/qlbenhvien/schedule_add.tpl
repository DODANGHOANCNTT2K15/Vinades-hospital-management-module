<!-- BEGIN: main -->
<link rel="stylesheet" href="{HISTORY_CSS}">
<style>
    /* ==== Tiêu đề ==== */
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

    /* ==== Container chính ==== */
    .schedule-add-container {
        background: #fff;
        border-radius: 10px;
        padding: 25px 30px;
        max-width: 650px;
        margin: 0 auto;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    }

    /* ==== Form cơ bản ==== */
    .schedule-form .form-group {
        margin-bottom: 16px;
    }

    .schedule-form label {
        display: block;
        font-weight: 600;
        color: #333;
        margin-bottom: 6px;
    }

    .schedule-form .form-control {
        width: 100%;
        padding: 8px 10px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 14px;
        transition: all 0.2s ease;
    }

    .schedule-form .form-control:focus {
        border-color: #2b6cb0;
        box-shadow: 0 0 0 2px rgba(43, 108, 176, 0.2);
        outline: none;
    }

    .schedule-form textarea {
        resize: vertical;
        min-height: 70px;
    }

    /* ==== Hai cột ngày & giờ ==== */
    .schedule-form .two-cols {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    /* ==== Form error ==== */
    .form-error {
        background: #fde8e8;
        color: #b91c1c;
        border: 1px solid #fca5a5;
        border-radius: 6px;
        padding: 10px 12px;
        margin-bottom: 15px;
        font-size: 14px;
    }

    /* ==== Nút bấm ==== */
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

    /* ==== Dấu sao bắt buộc ==== */
    .required {
        color: red;
        margin-left: 3px;
    }

    .schedule-form select {
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        width: 100%;
        height: 40px;
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

    /* ==== Responsive ==== */
    @media (max-width: 600px) {
        .schedule-add-container {
            padding: 20px;
        }

        .schedule-form .two-cols {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="schedule-add-container">
    <h2 class="form-title">Thêm lịch khám mới</h2>

    <!-- BEGIN: error -->
    <div class="form-error">{ERROR}</div>
    <!-- END: error -->

    <form action="{ACTION}" method="post" class="schedule-form">
        <div class="form-group">
            <label>Bệnh nhân <span class="required">*</span></label>
            <select name="benhnhan_id" class="form-control">
                <option value="">-- Chọn bệnh nhân --</option>
                <!-- BEGIN: benhnhan_option -->
                <option value="{BENHNHAN.id}">{BENHNHAN.label}</option>
                <!-- END: benhnhan_option -->
            </select>
        </div>

        <div class="form-group">
            <label>Bác sĩ phụ trách</label>
            <select name="bacsi_id" class="form-control">
                <option value="0">-- Chưa phân công --</option>
                <!-- BEGIN: bacsi_option -->
                <option value="{BACSI.id}">{BACSI.label}</option>
                <!-- END: bacsi_option -->
            </select>
        </div>

        <div class="form-group two-cols">
            <div>
                <label>Ngày khám <span class="required">*</span></label>
                <select name="ngaykham" class="form-control">
                    <option value="">-- Chọn ngày khám --</option>
                    <!-- BEGIN: ngaykham -->
                    <option value="{NGAYKHAM_VALUE}">{NGAYKHAM_TEXT}</option>
                    <!-- END: ngaykham -->
                </select>
            </div>

            <div>
                <label>Giờ khám</label>
                <select name="giokham" class="form-control">
                    <option value="">-- Chọn giờ --</option>
                    <!-- BEGIN: giokham -->
                    <option value="{GIOKHAM_VALUE}">{GIOKHAM_TEXT}</option>
                    <!-- END: giokham -->
                </select>
            </div>
        </div>

        <div class="form-group">
            <label>Nội dung</label>
            <textarea name="ghichu" rows="3" class="form-control" placeholder="Nhập nội dung (nếu có)"></textarea>
        </div>

        <div class="form-actions">
            <button type="submit" name="save" value="1" class="btn btn-primary">Lưu</button>
            <a href="{BACK_LINK}" class="btn btn-secondary">← Quay lại danh sách</a>
        </div>
    </form>
</div>
<!-- END: main -->
