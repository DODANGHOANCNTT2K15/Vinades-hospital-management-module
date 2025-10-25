<!-- BEGIN: main -->
<link rel="stylesheet" href="{HISTORY_CSS}">

<div class="lich-history-page">
  <h2>Lịch sử đặt lịch khám</h2>

  <!-- BEGIN: empty -->
  <div class="alert alert-info">Bạn chưa có lịch khám nào.</div>
  <!-- END: empty -->

  <!-- BEGIN: row -->
  <div class="lichkham-card">
    <div style="display:flex; justify-content:space-between; margin-bottom:8px;">
      <strong>{LICH.ngaykham}</strong>
      <span class="status {LICH.class}">{LICH.trangthai_text}</span>
    </div>
    <p><strong>⏰ Giờ khám:</strong> {LICH.giokham}</p>
    <p><strong>👨‍⚕️ Bác sĩ:</strong> {LICH.bacsi}</p>
    <p><strong>📝 Ghi chú:</strong> {LICH.ghichu}</p>
  </div>
  <!-- END: row -->

    <!-- BEGIN: pagination -->
  <div class="pagination" style="text-align:center; margin-top:20px;">
      <!-- BEGIN: prev -->
      <a href="?page={PREV_PAGE}" class="prev">&laquo; Trước</a>
      <!-- END: prev -->

      <span>Trang {CURRENT_PAGE} / {TOTAL_PAGES}</span>

      <!-- BEGIN: next -->
      <a href="?page={NEXT_PAGE}" class="next">Sau &raquo;</a>
      <!-- END: next -->
  </div>
  <!-- END: pagination -->


</div>
<!-- END: main -->
