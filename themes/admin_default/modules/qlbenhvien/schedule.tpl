<!-- BEGIN: main -->
<style>
.page-header {
    margin-top:0px;
    margin-bottom: 20px;
    width: 100%;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: nowrap;
}

.page-header h2 {
    margin: 0;
    font-size: 20px;
    color: #2b6cb0;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 6px;
}

.page-header .btn-success {
    background-color: #38a169;
    border: none;
    transition: background 0.2s ease, transform 0.1s ease;
    font-weight: 500;
    padding: 8px 14px;
    border-radius: 6px;
}

.page-header .btn-success:hover {
    background-color: #2f855a;
}

.row_data:hover{
    background-color: #4079b5ff !important;
    color: white;
}
</style>
<!-- BEGIN: list -->
<div class="page-header">
    <h2>Danh sách lịch khám</h2>
    <a href="{ADD_LINK}" class="btn btn-success">
        <i class="fa fa-plus"></i> Thêm lịch khám
    </a>
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
</tr>
<!-- END: row -->
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
