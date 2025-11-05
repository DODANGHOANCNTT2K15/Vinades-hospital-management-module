<!-- BEGIN: main -->
<link rel="stylesheet" href="{DIAGNOSIS_CSS}">

<h2>Ghi nhận chẩn đoán & đơn thuốc</h2>

<!-- BEGIN: error -->
<div class="form-error">{ERROR}</div>
<!-- END: error -->

<form action="{ACTION}" method="post">
    <div class="form-group">
        <label>Lịch khám</label>
        <select name="schedule_id" class="form-control">
            <option value="0">-- Chọn lịch khám --</option>
            <!-- BEGIN: schedule_option -->
            <option value="{SCHEDULE.id}">{SCHEDULE.label}</option>
            <!-- END: schedule_option -->
        </select>
    </div>

    <div class="form-group">
        <label>Chẩn đoán</label>
        <textarea name="chandoan" rows="4" class="form-control" placeholder="Nhập chẩn đoán..."></textarea>
    </div>

    <div class="form-group">
        <label>Đơn thuốc</label>
        <textarea name="donthuoc" rows="4" class="form-control" placeholder="Nhập đơn thuốc (mỗi dòng 1 thuốc)..."></textarea>
    </div>

    <button type="submit" name="save" value="1" class="btn btn-primary">Lưu</button>
</form>
<!-- END: main -->
