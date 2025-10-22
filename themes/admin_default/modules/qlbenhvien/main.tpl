<!-- BEGIN: main -->
<h2>{TITLE}</h2>

<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>ID Bệnh nhân</th>
            <th>Tên bệnh nhân</th>
            <th>Tên bác sĩ</th>
            <th>Ngày khám</th>
            <th>Giờ khám</th>
            <th>Trạng thái</th>
            <th>Ghi chú</th>
        </tr>
    </thead>
    <tbody>
        <!-- BEGIN: loop -->
        <tr>
            <td>{ROW.id}</td>
            <td>{ROW.benhnhan_id}</td>
            <td>{ROW.ten_benhnhan}</td>
            <td>{ROW.ten_bacsi}</td>
            <td>{ROW.ngaykham}</td>
            <td>{ROW.giokham}</td>
            <td>{ROW.trangthai_label}</td>
            <td>{ROW.ghichu}</td>
        </tr>
        <!-- END: loop -->
    </tbody>
</table>
<!-- END: main -->
