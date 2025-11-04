<!-- BEGIN: main -->
<link rel="stylesheet" href="{HISTORY_CSS}">
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

    .form-container {
        background: #fff;
        border-radius: 10px;
        padding: 25px 30px;
        max-width: 650px;
        margin: 0 auto;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    }

    form .form-group {
        margin-bottom: 16px;
    }

    form label {
        display: block;
        font-weight: 600;
        color: #333;
        margin-bottom: 6px;
    }

    form input[type="text"],
    form input[type="date"],
    form input[type="email"],
    form textarea {
        width: 100%;
        padding: 8px 10px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 14px;
        transition: all 0.2s ease;
        box-sizing: border-box;
    }

    form input[type="text"]:focus,
    form input[type="date"]:focus,
    form input[type="email"]:focus,
    form textarea:focus {
        border-color: #2b6cb0;
        box-shadow: 0 0 0 2px rgba(43, 108, 176, 0.2);
        outline: none;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 10px;
        margin-top: 20px;
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

    .required {
        color: red;
        margin-left: 3px;
    }

    .error-message {
        color: red;
        font-weight: 600;
        margin-bottom: 15px;
    }

    .gender-radio {
        display: flex;
        gap: 15px;
        align-items: center;
    }
</style>

<div class="form-container">
    <h2>Chỉnh sửa bệnh nhân</h2>

    <!-- Hiện lỗi nếu có -->
    <!-- BEGIN: error -->
    <div class="error-message">{ERROR}</div>
    <!-- END: error -->

    <form action="{ACTION_LINK}" method="post" class="patient-form">
        <div class="form-group">
            <label for="hoten">Họ tên <span class="required">*</span></label>
            <input type="text" id="hoten" name="hoten" value="{HOTEN}" required>
        </div>

        <div class="form-group">
            <label for="ngaysinh">Ngày sinh</label>
            <input type="date" id="ngaysinh" name="ngaysinh" value="{NGAYSINH}">
        </div>

        <div class="form-group">
            <label>Giới tính</label>
            <div class="gender-radio">
                <label><input type="radio" name="gioitinh" value="1" {GIOITINH_MALE_CHECKED}> Nam</label>
                <label><input type="radio" name="gioitinh" value="0" {GIOITINH_FEMALE_CHECKED}> Nữ</label>
            </div>
        </div>

        <div class="form-group">
            <label for="diachi">Địa chỉ</label>
            <textarea id="diachi" name="diachi" rows="3">{DIA_CHI}</textarea>
        </div>

        <div class="form-group">
            <label for="sdt">Số điện thoại</label>
            <input type="text" id="sdt" name="sdt" value="{SDT}">
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{EMAIL}">
        </div>

        <input type="hidden" name="submit" value="1">

        <div class="form-actions">
            <a href="{BACK_LINK}" class="btn btn-secondary">← Quay lại</a>
            <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
        </div>
    </form>
</div>
<!-- END: main -->
