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
<!-- BEGIN: total_info -->
<div class="mb-2 text-muted">{TOTAL_INFO}</div>
<!-- END: total_info -->

<!-- BEGIN: pagination -->
<div class="text-center mt-3">{NV_GENERATE_PAGE}</div>
<!-- END: pagination -->

<!-- BEGIN: detail -->
<h3>Chi tiết lịch khám #{ROW.id}</h3>
<p><strong>Bệnh nhân:</strong> {ROW.ten_benhnhan}</p>
<p><strong>Bác sĩ:</strong> {ROW.ten_bacsi}</p>
<p><strong>Ngày khám:</strong> {ROW.ngaykham_vn}</p>
<p><strong>Giờ khám:</strong> {ROW.giokham}</p>
<p><strong>Trạng thái:</strong> {ROW.trangthai_text}</p>
<p><strong>Ghi chú:</strong> {ROW.ghichu}</p>

<a href="index.php?nv={MODULE_NAME}&op=schedule" class="btn btn-secondary mt-3">← Quay lại danh sách</a>
<!-- END: detail -->


<!-- BEGIN: error -->
<p style="color:red;">{MESSAGE}</p>
<!-- END: error -->

<!-- END: main -->
