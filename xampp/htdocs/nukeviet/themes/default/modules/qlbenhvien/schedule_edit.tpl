<div class="card">
    <div class="card-header">
        <h3 class="card-title">Chỉnh Sửa Lịch Khám (ID: {DATA.id})</h3>
    </div>
    <div class="card-body">
    
        <div class="alert alert-danger">{ERROR}</div>
        <form action="{FORM_ACTION}" method="post">
            
            <h4>1. Thông tin Lịch khám</h4>
            <hr>

            <div class="form-group">
                <label>Bệnh Nhân <span class="text-danger">(*)</span></label>
                <select name="benhnhan_id" class="form-control">
                    {BENHNHAN_SELECT}
                </select>
            </div>

            <div class="form-group">
                <label>Bác Sĩ Phụ Trách</label>
                <select name="bacsi_id" class="form-control">
                    {BACSI_SELECT}
                </select>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Ngày Khám <span class="text-danger">(*)</span></label>
                        <input type="date" name="ngaykham" value="{DATA.ngaykham}" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Giờ Khám <span class="text-danger">(*)</span></label>
                        <input type="time" name="giokham" value="{DATA.giokham}" class="form-control" required>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Trạng Thái</label><br>
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="status_pending" name="trangthai" value="pending" class="custom-control-input" {STATUS_PENDING_CHECKED}>
                    <label class="custom-control-label" for="status_pending">Chờ xác nhận</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="status_confirmed" name="trangthai" value="confirmed" class="custom-control-input" {STATUS_CONFIRMED_CHECKED}>
                    <label class="custom-control-label" for="status_confirmed">Đã xác nhận</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="status_cancelled" name="trangthai" value="cancelled" class="custom-control-input" {STATUS_CANCELLED_CHECKED}>
                    <label class="custom-control-label" for="status_cancelled">Đã hủy</label>
                </div>
            </div>

            <div class="form-group">
                <label>Lý Do Khám/Ghi Chú</label>
                {LYDO_EDITOR}
            </div>

            <h4 class="mt-4">2. Hồ Sơ Khám Bệnh</h4>
            <hr>

            <div class="form-group">
                <label>Chẩn Đoán</label>
                {CHANDOAN_EDITOR}
            </div>

            <div class="form-group">
                <label>Đơn Thuốc/Yêu cầu</label>
                {DONTHUOC_EDITOR}
            </div>

            <div class="mt-4">
                <input type="submit" name="submit" value="Cập Nhật Lịch Khám" class="btn btn-primary">
                <a href="{NV_BASE_ADMINURL}index.php?nv={MODULE_NAME}&op=schedule" class="btn btn-secondary">Quay lại</a>
            </div>
            
        </form>
    </div>
</div>