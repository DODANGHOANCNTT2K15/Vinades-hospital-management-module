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
/* nhỏ gọn cho nút */
.btn-sm {
    padding: 4px 8px;
    font-size: 13px;
    border-radius: 5px;
}
</style>

<!-- BEGIN: list -->
<div class="page-header">
    <h2>Danh sách bác sĩ</h2>

    <!-- Form tìm kiếm -->
    <form method="get" action="" style="margin:0; display:flex; align-items:center; gap:6px;">
        <input type="hidden" name="nv" value="{MODULE_NAME}">
        <input type="hidden" name="op" value="doctor">
        <input type="text" name="keyword" placeholder="Tìm theo tên bác sĩ" value="{KEYWORD}" class="form-control" style="width: 250px;">
        <button type="submit" class="btn btn-primary">Tìm</button>
        <a href="index.php?nv={MODULE_NAME}&op=doctor" class="btn btn-secondary">Tất cả</a>
    </form>

    <a href="{ADD_LINK}" class="btn btn-success"><i class="fa fa-plus"></i> Thêm bác sĩ</a>
</div>

<table class="table table-striped table-bordered">
<thead>
<tr>
    <th>ID</th>
    <th>Họ và tên</th>
    <th>Ngày sinh</th>
    <th>Giới tính</th>
    <th>Chuyên khoa</th>
    <th width="140">Hành động</th>
</tr>
</thead>
<tbody>
<!-- BEGIN: row -->
<tr class="row_data">
    <td>{ROW.id}</td>
    <td>
        <a href="index.php?nv={MODULE_NAME}&op=doctor_detail&id={ROW.id}">
            {ROW.hoten}
        </a>
    </td>
    <td>{ROW.ngaysinh_vn}</td>
    <td>{ROW.gioitinh_text}</td>
    <td>{ROW.tenchuyenkhoa}</td>
    <td style="text-align:center; white-space:nowrap;">
        <a href="{ROW.link_edit}" class="btn btn-primary btn-sm" style="margin-right:6px;">
            <i class="fa fa-edit"></i> Sửa
        </a>
        <a href="{ROW.link_delete}" class="btn btn-danger btn-sm"
           onclick="return confirm('Bạn có chắc muốn xóa bác sĩ này không?');">
            <i class="fa fa-trash"></i> Xóa
        </a>
    </td>
</tr>
<!-- END: row -->

<!-- BEGIN: no_data -->
<tr><td colspan="8" class="text-center text-muted">Không có bác sĩ nào.</td></tr>
<!-- END: no_data -->
</tbody>
</table>

<div class="mb-2 text-muted">{TOTAL_INFO}</div>
<!-- BEGIN: pagination -->
<div class="text-center mt-3">{NV_GENERATE_PAGE}</div>
<!-- END: pagination -->
<!-- END: list -->
<!-- END: main -->
