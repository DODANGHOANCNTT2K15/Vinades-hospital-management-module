<!-- BEGIN: main -->
<link rel="stylesheet" href="{DIAGNOSES_LIST_CSS}">

<div class="page-header">
    <h2>Danh sách chẩn đoán</h2>

    <div style="display:flex; align-items:center; gap:10px;">
        <!-- Nút mở popup lọc -->
        <button type="button" class="btn btn-info" onclick="openFilterPopup()">
            <i class="fa fa-filter"></i> Bộ lọc
        </button>

        <a href="{ADD_LINK}" class="btn btn-success"><i class="fa fa-plus"></i> Thêm chẩn đoán</a>

        <!-- Popup bộ lọc -->
        <div id="filterPopup" class="modal"
            style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.4); z-index:999; align-items:center; justify-content:center;">
            <div style="background:#fff; padding:20px; border-radius:8px; width:400px;">
                <h4 style="margin-top:0;">Lọc chẩn đoán</h4>
                <form method="get" action="{NV_BASE_ADMINURL}index.php" style="gap: 8px; display: flex; flex-direction: column;">
                    <input type="hidden" name="nv" value="{MODULE_NAME}">
                    <input type="hidden" name="op" value="diagnosis_list">

                    <label>Bệnh nhân:</label>
                    <select name="benhnhan_id" class="form-control">
                        <option value="0">-- Tất cả --</option>
                        <!-- BEGIN: benhnhan -->
                        <option value="{BENHNHAN.id}" {BENHNHAN.selected}>{BENHNHAN.hoten}</option>
                        <!-- END: benhnhan -->
                    </select>

                    <label>Bác sĩ:</label>
                    <select name="bacsi_id" class="form-control">
                        <option value="0">-- Tất cả --</option>
                        <!-- BEGIN: bacsi -->
                        <option value="{BACSI.id}" {BACSI.selected}>{BACSI.hoten}</option>
                        <!-- END: bacsi -->
                    </select>

                    <div style="text-align:right; margin-top: 10px;">
                        <button type="submit" class="btn btn-primary">Áp dụng</button>
                        <button type="button" class="btn btn-secondary" onclick="closeFilterPopup()">Đóng</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function openFilterPopup() {
    document.getElementById('filterPopup').style.display = 'flex';
}
function closeFilterPopup() {
    document.getElementById('filterPopup').style.display = 'none';
}
</script>

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
