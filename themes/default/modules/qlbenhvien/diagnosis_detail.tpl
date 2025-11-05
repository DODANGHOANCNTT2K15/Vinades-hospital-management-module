<!-- BEGIN: main -->
<link rel="stylesheet" href="{DIAGNOSIS_DETAIL_CSS}">

<div class="diagnosis-detail">
  <h2>Chi tiết chẩn đoán</h2>

  <!-- BEGIN: data -->
  <div class="info-row">
    <div class="info-label">Bệnh nhân:</div>
    <div class="info-value">{DATA.benhnhan}</div>
  </div>
  <div class="info-row">
    <div class="info-label">Bác sĩ:</div>
    <div class="info-value">{DATA.bacsi}</div>
  </div>
  <div class="info-row">
    <div class="info-label">Ngày khám:</div>
    <div class="info-value">{DATA.ngaykham}</div>
  </div>
  <div class="info-row">
    <div class="info-label">Ngày tạo:</div>
    <div class="info-value">{DATA.ngaytao}</div>
  </div>
  <div class="info-row">
    <div class="info-label">Chẩn đoán:</div>
    <div class="info-value">{DATA.chandoan}</div>
  </div>
  <div class="info-row">
    <div class="info-label">Đơn thuốc:</div>
    <div class="info-value">{DATA.donthuoc}</div>
  </div>
  <!-- END: data -->

  <!-- BEGIN: empty -->
  <p class="empty-message">Không tìm thấy thông tin chẩn đoán.</p>
  <!-- END: empty -->

  <button class="btn-back" onclick="goBack()">← Quay về</button>

<script>
function goBack() {
  window.history.back();
}
</script>
</div>
<!-- END: main -->
