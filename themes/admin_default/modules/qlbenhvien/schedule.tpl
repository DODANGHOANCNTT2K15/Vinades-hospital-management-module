<!-- BEGIN: main -->
<link rel="stylesheet" href="{SCHEDULE_CSS}">

<!-- BEGIN: list -->
<div class="page-header">
    <h2>Danh sách lịch khám</h2>

    <div style="display:flex; align-items:center; gap:10px;">
        <!-- Nút mở popup lọc -->
        <button type="button" class="btn btn-info" onclick="openFilterPopup()">
            <i class="fa fa-filter"></i> Bộ lọc
        </button>

        <a href="{ADD_LINK}" class="btn btn-success">
            <i class="fa fa-plus"></i> Thêm lịch khám
        </a>

        <div id="filterPopup" class="modal"
            style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.4); z-index:999; align-items:center; justify-content:center;">
        <div style="background:#fff; padding:20px; border-radius:8px; width:400px;">
            <h4 style="margin-top:0;">Lọc lịch khám</h4>
            <form method="get" action="{NV_BASE_ADMINURL}index.php">
            <input type="hidden" name="nv" value="{MODULE_NAME}">
            <input type="hidden" name="op" value="schedule">

            <div class="form-group mb-2">
                <label>Bệnh nhân:</label>
                <select name="benhnhan_id" class="form-control">
                <option value="0">-- Tất cả --</option>
                <!-- BEGIN: benhnhan -->
                <option value="{BENHNHAN.id}" {BENHNHAN.selected}>{BENHNHAN.hoten}</option>
                <!-- END: benhnhan -->
                </select>
            </div>

            <div class="form-group mb-2">
                <label>Bác sĩ:</label>
                <select name="bacsi_id" class="form-control">
                <option value="0">-- Tất cả --</option>
                <!-- BEGIN: bacsi -->
                <option value="{BACSI.id}" {BACSI.selected}>{BACSI.hoten}</option>
                <!-- END: bacsi -->
                </select>
            </div>
            
            <div style="text-align:right;">
                <button type="submit" class="btn btn-primary">Áp dụng</button>
                <button type="button" class="btn btn-secondary" onclick="closeFilterPopup()">Đóng</button>
            </div>
            </form>
        </div>
        </div>

        <script>
        function openFilterPopup() {
            document.getElementById('filterPopup').style.display = 'flex';
        }
        function closeFilterPopup() {
            document.getElementById('filterPopup').style.display = 'none';
        }
        </script>
    </div>
</div>


<table class="table table-striped table-bordered">
<thead>
<tr>
    <th>ID</th>
    <th>Bệnh nhân</th>
    <th>Bác sĩ</th>
    <th>Ngày</th>
    <th>Giờ</th>
    <th>Trạng thái</th>
    <th>Hành động</th>
</tr>
</thead>
<tbody>
<!-- BEGIN: row -->
<tr onclick="window.location='{ROW.link_detail}'" style="cursor:pointer;" class="row_data">
    <td>{ROW.id}</td>
    <td>{ROW.ten_benhnhan}</td>
    <td>{ROW.ten_bacsi}</td>
    <td>{ROW.ngaykham}</td>
    <td>{ROW.giokham}</td>
    <td>{ROW.trangthai_text}</td>
    <td style="text-align:center;">
        <a href="{ROW.link_edit}" class="btn btn-primary btn-sm">
            <i class="fa fa-edit"></i> Sửa
        </a>
        <a href="{ROW.link_delete}" class="btn btn-danger btn-sm"
           onclick="return confirm('Bạn có chắc muốn xóa lịch khám này không?');">
            <i class="fa fa-trash"></i> Xóa
        </a>
    </td>
</tr>
<!-- END: row -->

<!-- BEGIN: no_data -->
<tr><td colspan="8" class="text-center text-muted">Không có dữ liệu lịch khám.</td></tr>
<!-- END: no_data -->

</tbody>
</table>
<div class="mb-2 text-muted">{TOTAL_INFO}</div>
<!-- BEGIN: pagination -->
<div class="text-center mt-3">{NV_GENERATE_PAGE}</div>
<!-- END: pagination -->
<!-- END: list -->

<!-- BEGIN: detail -->
<div class="page-header">
    <h2>Chi tiết lịch khám #{ROW.id}</h2>
    <a href="{BACK_LINK}" class="btn btn-secondary">
        ← Quay lại danh sách
    </a>
</div>

<table class="table table-bordered">
    <tr><th>ID</th><td>{ROW.id}</td></tr>
    <tr><th>Bệnh nhân</th><td>{ROW.ten_benhnhan}</td></tr>
    <tr><th>Bác sĩ</th><td>{ROW.ten_bacsi}</td></tr>
    <tr><th>Ngày khám</th><td>{ROW.ngaykham_vn}</td></tr>
    <tr><th>Giờ khám</th><td>{ROW.giokham}</td></tr>
    <tr><th>Trạng thái</th><td>{ROW.trangthai_text}</td></tr>
    <tr><th>Ghi chú</th><td>{ROW.ghichu}</td></tr>
</table>

<div class="mt-3">
    <a href="{LINK_CONFIRM}" class="btn btn-success">
        <i class="fa fa-check"></i> Xác nhận
    </a>
    <a href="{LINK_CANCEL}" class="btn btn-warning">
        <i class="fa fa-times"></i> Hủy lịch
    </a>
    <a href="{LINK_EDIT}" class="btn btn-primary">
        <i class="fa fa-edit"></i> Sửa thông tin
    </a>
    <a href="{LINK_DELETE}" class="btn btn-danger"
       onclick="return confirm('Bạn có chắc muốn xóa lịch khám này không?');">
        <i class="fa fa-trash"></i> Xóa
    </a>
</div>
<!-- END: detail -->

<!-- BEGIN: error -->
<p style="color:red;">{MESSAGE}</p>
<!-- END: error -->

<!-- END: main -->
