<!-- BEGIN: main -->

<!-- BEGIN: list -->
<h2>Danh sách lịch khám</h2>
<a href="{ADD_LINK}" class="btn btn-primary mb-3">+ Thêm lịch mới</a>
<table class="table table-striped table-bordered">
<thead>
<tr>
    <th>ID</th>
    <th>Bệnh nhân</th>
    <th>Bác sĩ</th>
    <th>Ngày</th>
    <th>Giờ</th>
    <th>Trạng thái</th>
    <th>Ghi chú</th>
    <th>Hành động</th>
</tr>
</thead>
<tbody>
<!-- BEGIN: row -->
<tr>
    <td>{ROW.id}</td>
    <td>{ROW.ten_benhnhan}</td>
    <td>{ROW.ten_bacsi}</td>
    <td>{ROW.ngaykham}</td>
    <td>{ROW.giokham}</td>
    <td>{ROW.trangthai_text}</td>
    <td>{ROW.ghichu}</td>
    <td>
        <a href="{ROW.link_edit}">Sửa</a> |
        <a href="{ROW.link_delete}" onclick="return confirm('Xóa lịch này?')">Xóa</a>
    </td>
</tr>
<!-- END: row -->
</tbody>
</table>
<!-- END: list -->

<!-- BEGIN: add -->
<h3>Thêm lịch khám</h3>
<!-- BEGIN: message -->
<p style="color:green;">{MESSAGE}</p>
<!-- END: message -->

<form method="post" action="{FORM_ACTION}">
    <p>
        <label>Bệnh nhân:
            <select name="benhnhan_id" required>
                <option value="">-- Chọn bệnh nhân --</option>
                <!-- BEGIN: benhnhan -->
                <option value="{BN.id}">{BN.hoten}</option>
                <!-- END: benhnhan -->
            </select>
        </label>
    </p>

    <p>
        <label>Bác sĩ:
            <select name="bacsi_id">
                <option value="0">-- Chưa chọn bác sĩ --</option>
                <!-- BEGIN: bacsi -->
                <option value="{BS.id}">{BS.hoten}</option>
                <!-- END: bacsi -->
            </select>
        </label>
    </p>

    <p><label>Ngày khám: <input type="date" name="ngaykham" required></label></p>
    <p><label>Giờ khám: <input type="time" name="giokham" required></label></p>
    <p><label>Ghi chú: <textarea name="ghichu" rows="3" cols="50"></textarea></label></p>

    <button type="submit" name="submit">Thêm lịch</button>
</form>
<!-- END: add -->

<!-- BEGIN: edit -->
<h3>Cập nhật lịch khám</h3>
<!-- BEGIN: message -->
<p style="color:green;">{MESSAGE}</p>
<!-- END: message -->

<form method="post" action="{FORM_ACTION}">
    <p>Bệnh nhân ID: <strong>{ROW.benhnhan_id}</strong></p>

    <p><label>Bác sĩ:
        <select name="bacsi_id">
            <option value="0">-- Chưa chọn bác sĩ --</option>
            <!-- BEGIN: bacsi -->
            <option value="{BS.id}" {BS.selected}>{BS.hoten}</option>
            <!-- END: bacsi -->
        </select>
    </label></p>

    <p><label>Trạng thái:
        <select name="trangthai">
            <option value="pending" {ROW.pending}>Chờ xác nhận</option>
            <option value="confirmed" {ROW.confirmed}>Đã xác nhận</option>
            <option value="cancelled" {ROW.cancelled}>Đã hủy</option>
        </select>
    </label></p>

    <p><label>Ghi chú: <input type="text" name="ghichu" value="{ROW.ghichu}"></label></p>

    <button type="submit" name="save">Lưu thay đổi</button>
</form>
<!-- END: edit -->

<!-- BEGIN: error -->
<p style="color:red;">{MESSAGE}</p>
<!-- END: error -->

<!-- END: main -->
