<!-- BEGIN: main -->
<style>
    h2 {
        margin: 0;
        font-size: 20px;
        color: #2b6cb0;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 20px;
    }

    .detail-container {
        background: #fff;
        border-radius: 10px;
        padding: 25px 30px;
        max-width: 700px;
        margin: 0 auto;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    }

    table.detail-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }

    table.detail-table th,
    table.detail-table td {
        padding: 10px 12px;
        border: 1px solid #d1d5db;
        vertical-align: top;
    }

    table.detail-table th {
        width: 30%;
        background-color: #f9fafb;
        font-weight: 600;
        color: #374151;
        text-align: left;
    }

    table.detail-table td {
        background-color: #fff;
        color: #4b5563;
    }

    .doctor-image {
        display: block;
        max-width: 120px;
        border-radius: 8px;
        margin: 10px 0;
    }

    .btn {
        display: inline-block;
        font-weight: 500;
        border-radius: 6px;
        padding: 8px 16px;
        font-size: 14px;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s ease;
        margin-top: 20px;
        margin-right: 10px;
    }

    .btn-primary {
        background-color: #2b6cb0;
        color: #fff;
        border: none;
    }

    .btn-primary:hover {
        background-color: #1e4f8f;
    }

    .btn-secondary {
        background-color: #f3f4f6;
        color: #333;
        border: 1px solid #ccc;
    }

    .btn-secondary:hover {
        background-color: #e5e7eb;
    }
</style>

<div class="detail-container">
    <h2>Thông tin bác sĩ</h2>

    <table class="detail-table">
        <tr><th>ID</th><td>{ID}</td></tr>
        <tr><th>Họ tên</th><td>{HOTEN}</td></tr>
        <tr><th>Ngày sinh</th><td>{NGAYSINH}</td></tr>
        <tr><th>Giới tính</th><td>{GIOITINH}</td></tr>
        <tr><th>Chuyên khoa</th><td>{CHUYENKHOA}</td></tr>
        <tr><th>Trình độ</th><td>{TRINHDO}</td></tr>
        <tr><th>Lịch làm việc</th><td>{LICHLAMVIEC}</td></tr>
        <tr><th>Email</th><td>{EMAIL}</td></tr>
        <tr><th>Số điện thoại</th><td>{SDT}</td></tr>
        <tr><th>User ID</th><td>{USERID}</td></tr>
    </table>

    <div style="margin-top: 25px;">
        <a href="{BACK_LINK}" class="btn btn-secondary">← Quay lại</a>
        <a href="{EDIT_LINK}" class="btn btn-primary">✏️ Chỉnh sửa</a>
    </div>
</div>
<!-- END: main -->
