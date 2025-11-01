<!-- BEGIN: main -->
<link rel="stylesheet" href="{DOCTOR_CSS}">

<div class="doctor-page">
    <h2>Danh sách bác sĩ</h2>

    <!-- Form tìm kiếm -->
    <form method="get" class="search-form">
        <input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}">
        <input type="hidden" name="{NV_OP_VARIABLE}" value="doctor">
        <input type="text" name="keyword" placeholder="Tìm theo tên bác sĩ" value="{KEYWORD}">
        <select name="chuyenkhoa_id">
            <option value="0">-- Tất cả chuyên khoa --</option>
            <!-- BEGIN: khoa_option -->
            <option value="{KHOA.id}" {KHOA.selected}>{KHOA.tenchuyenkhoa}</option>
            <!-- END: khoa_option -->
        </select>
        <button type="submit">Tìm kiếm</button>
    </form>

    <!-- Danh sách bác sĩ -->
    <div class="doctor-list">
        <!-- BEGIN: list -->
        <!-- BEGIN: row -->
        <div class="doctor-item">
            <img src="{BACSILIST.hinhanh}" alt="{BACSILIST.hoten}">
            <div class="info">
                <h3>{BACSILIST.hoten}</h3>
                <p><strong>Chuyên khoa:</strong> {BACSILIST.tenchuyenkhoa}</p>
                <p><strong>Trình độ:</strong> {BACSILIST.trinhdo}</p>
                <p><strong>Lịch làm việc:</strong> {BACSILIST.lichlamviec}</p>
                <p><strong>Email:</strong> {BACSILIST.email}</p>
                <p><strong>SĐT:</strong> {BACSILIST.sdt}</p>
            </div>
        </div>
        <!-- END: row -->
        <!-- END: list -->
    </div>

    <!-- Pagination -->
    <!-- BEGIN: pagination -->
    <div class="pagination">
        <!-- BEGIN: page -->
        <a href="{PAGE.link}" class="{PAGE.active}">{PAGE.num}</a>
        <!-- END: page -->
    </div>
    <!-- END: pagination -->
</div>
<!-- END: main -->
