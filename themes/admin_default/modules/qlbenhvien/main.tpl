<!-- BEGIN: main -->
<h2>Danh sách lịch khám</h2>

<table class="table table-striped">
  <thead>
    <tr>
      <th>ID</th>
      <th>Bệnh nhân ID</th>
      <th>Bác sĩ ID</th>
      <th>Ngày</th>
      <th>Giờ</th>
      <th>Trạng thái</th>
      <th>Ghi chú</th>
    </tr>
  </thead>
  <tbody>
    <!-- BEGIN: loop -->
    <tr>
      <td>{ROW.id}</td>
      <td>{ROW.benhnhan_id}</td>
      <td>{ROW.bacsi_id}</td>
      <td>{ROW.ngaykham}</td>
      <td>{ROW.giokham}</td>
      <td>{ROW.trangthai}</td>
      <td>{ROW.ghichu}</td>
    </tr>
    <!-- END: loop -->
  </tbody>
</table>

<!-- BEGIN: pagination -->
<div class="pagination">{PAGINATION}</div>
<!-- END: pagination -->
<!-- END: main -->
