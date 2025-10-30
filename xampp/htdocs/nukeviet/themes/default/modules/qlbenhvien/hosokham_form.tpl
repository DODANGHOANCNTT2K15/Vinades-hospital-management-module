<div class="card mt-4">
    <div class="card-header">
        <h5 class="card-title">Ghi Hồ sơ Khám bệnh (Chẩn đoán & Đơn thuốc)</h5>
    </div>
    <div class="card-body">
        <div class="alert alert-info">{HOSOKHAM.msg}</div>
        <form action="{MODULE_URL}&amp;op=schedule_edit&amp;id={HOSOKHAM.lichkham_id}" method="post">
            <input type="hidden" name="lichkham_id" value="{HOSOKHAM.lichkham_id}">

            <div class="form-group">
                <label for="ngaykham_hsk">Ngày/Giờ Khám:</label>
                <input type="datetime-local" class="form-control" name="ngaykham_hsk" 
                       value="{HOSOKHAM.ngaykham|date('%Y-%m-%dT%H:%M')}" required>
            </div>

            <div class="form-group">
                <label for="chandoan">Chẩn đoán (*):</label>
                <textarea name="chandoan" class="form-control" rows="5">{HOSOKHAM.chandoan}</textarea>
            </div>

            <div class="form-group">
                <label for="ketqua">Kết quả Khám/Xét nghiệm:</label>
                <textarea name="ketqua" class="form-control" rows="5">{HOSOKHAM.ketqua}</textarea>
            </div>
            
            <div class="form-group">
                <label for="ketluan">Kết luận/Đơn thuốc cơ bản:</label>
                <textarea name="ketluan" class="form-control" rows="5">{HOSOKHAM.ketluan}</textarea>
            </div>

            <button type="submit" name="submit_hosokham" value="1" class="btn btn-success">Lưu Chẩn đoán & Đơn thuốc</button>

        </form>
    </div>
</div>