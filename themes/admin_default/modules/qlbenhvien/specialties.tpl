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
    <h2>Danh sách chuyên khoa</h2>
    <a href="{ADD_LINK}" class="btn btn-success"><i class="fa fa-plus"></i> Thêm chuyên khoa</a>
</div>

<table class="table table-striped table-bordered">
<thead>
<tr>
    <th>ID</th>
    <th>Tên chuyên khoa</th>
    <th>Mô tả</th>
    <th>Trạng thái</th>
</tr>
</thead>
<tbody>
<!-- BEGIN: row -->
<tr class="row_data">
    <td>{ROW.id}</td>
    <td>{ROW.tenchuyenkhoa}</td>
    <td>{ROW.mota}</td>
    <td>{ROW.trangthai_text}</td>
</tr>
<!-- END: row -->
</tbody>
</table>

<div class="mb-2 text-muted">{TOTAL_INFO}</div>
<!-- END: list -->
<!-- END: main -->
