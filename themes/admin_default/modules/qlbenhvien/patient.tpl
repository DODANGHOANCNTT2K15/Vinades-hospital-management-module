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

<!-- BEGIN: list -->
<div class="page-header">
    <h2>Danh sách bệnh nhân</h2>
    <a href="{ADD_LINK}" class="btn btn-success"><i class="fa fa-plus"></i> Thêm bệnh nhân</a>
</div>

<table class="table table-striped table-bordered">
<thead>
<tr>
    <th>ID</th>
    <th>Họ và tên</th>
    <th>Ngày sinh</th>
    <th>Giới tính</th>
    <th>Địa chỉ</th>
    <th>SĐT</th>
    <th>Email</th>
    <th>Ngày tạo</th>
    <th style="text-align:center;">Hành động</th>
</tr>
</thead>
<tbody>
<!-- BEGIN: row -->
<tr class="row_data">
    <td>{ROW.id}</td>
    <td>
        <a href="index.php?nv={MODULE_NAME}&op=patient_detail&id={ROW.id}">
            {ROW.hoten}
        </a>
    </td>
    <td>{ROW.ngaysinh_vn}</td>
    <td>{ROW.gioitinh_text}</td>
    <td>{ROW.diachi}</td>
    <td>{ROW.sdt}</td>
    <td>{ROW.email}</td>
    <td>{ROW.ngaytao_vn}</td>
    <td style="text-align:center;">
        <a href="{ROW.link_edit}" class="btn btn-primary btn-sm">
            <i class="fa fa-edit"></i> Sửa
        </a>
        <a href="{ROW.link_delete}" class="btn btn-danger btn-sm"
           onclick="return confirm('Bạn có chắc muốn xóa bệnh nhân này không?');">
            <i class="fa fa-trash"></i> Xóa
        </a>
    </td>
</tr>
<!-- END: row -->
</tbody>
</table>

<div class="mb-2 text-muted">{TOTAL_INFO}</div>

<!-- BEGIN: pagination -->
<div class="text-center mt-3">{NV_GENERATE_PAGE}</div>
<!-- END: pagination -->
<!-- END: list -->
<!-- END: main -->
