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

            <div style="margin-top: 10px">
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
