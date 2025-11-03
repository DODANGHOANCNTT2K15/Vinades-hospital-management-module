<!-- BEGIN: main -->
<style>
.page-header {
    margin-top: 0;
    margin-bottom: 20px;
    width: 100%;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.page-header h2 {
    margin: 0;
    font-size: 20px;
    color: #2b6cb0;
    font-weight: 600;
}
.page-header .btn-success {
    background-color: #38a169;
    border: none;
    padding: 8px 14px;
    border-radius: 6px;
    transition: background 0.2s ease;
}
.page-header .btn-success:hover {
    background-color: #2f855a;
}
.row_data:hover {
    background-color: #4079b5ff !important;
    color: white;
}
</style>

<div class="page-header">
    <h2>Danh sách chẩn đoán</h2>
    <a href="{ADD_LINK}" class="btn btn-success"><i class="fa fa-plus"></i> Thêm chẩn đoán</a>
</div>

<table class="table table-striped table-bordered">
<thead>
<tr>
    <th>ID</th>
    <th>Bệnh nhân</th>
    <th>Bác sĩ</th>
    <th>Ngày khám</th>
    <th>Ngày tạo</th>
    <th>Chẩn đoán</th>
    <th>Đơn thuốc</th>
    <th style="width:140px; text-align:center;">Hành động</th>
</tr>
</thead>
<tbody>
<!-- BEGIN: row -->
<tr class="row_data">
    <td>{ROW.id}</td>
    <td>{ROW.patient_name}</td>
    <td>{ROW.doctor_name}</td>
    <td>{ROW.ngaykham}</td>
    <td>{ROW.ngaytao}</td>
    <td>{ROW.chandoan}</td>
    <td>{ROW.donthuoc}</td>
    <td style="text-align:center;">
        <a href="{ROW.link_edit}" class="btn btn-primary btn-sm">
            <i class="fa fa-edit"></i> Sửa
        </a>
        <a href="{ROW.link_delete}" class="btn btn-danger btn-sm"
           onclick="return confirm('Bạn có chắc muốn xóa chẩn đoán này không?');">
            <i class="fa fa-trash"></i> Xóa
        </a>
    </td>
</tr>
<!-- END: row -->

<!-- BEGIN: no_data -->
<tr><td colspan="8" class="text-center text-muted">Không có dữ liệu chẩn đoán.</td></tr>
<!-- END: no_data -->
</tbody>
</table>

<div class="mb-2 text-muted">{TOTAL_INFO}</div>

<!-- BEGIN: pagination -->
<div class="text-center mt-3">{NV_GENERATE_PAGE}</div>
<!-- END: pagination -->
<!-- END: main -->
