<!-- BEGIN: main -->
<link rel="stylesheet" href="{PATIENT_DETAIL_CSS}">

<div class="detail-container">
    <h2>Thông tin bệnh nhân</h2>

    <table class="detail-table" cellpadding="0" cellspacing="0" border="0">
        <tr>
            <th>ID</th>
            <td>{ID}</td>
        </tr>
        <tr>
            <th>Họ tên</th>
            <td>{HOTEN}</td>
        </tr>
        <tr>
            <th>Ngày sinh</th>
            <td>{NGAYSINH}</td>
        </tr>
        <tr>
            <th>Giới tính</th>
            <td>{GIOITINH}</td>
        </tr>
        <tr>
            <th>Địa chỉ</th>
            <td>{DIA_CHI}</td>
        </tr>
        <tr>
            <th>Số điện thoại</th>
            <td>{SDT}</td>
        </tr>
        <tr>
            <th>Email</th>
            <td>{EMAIL}</td>
        </tr>
        <tr>
            <th>User ID</th>
            <td>{USERID}</td>
        </tr>
        <tr>
            <th>Ngày tạo</th>
            <td>{NGAYTAO}</td>
        </tr>
    </table>

    <div style="margin-top: 25px;">
        <a href="{BACK_LINK}" class="btn btn-secondary">← Quay lại</a>
        <a href="{EDIT_LINK}" class="btn btn-primary">Chỉnh sửa</a>
    </div>
</div>
<!-- END: main -->
