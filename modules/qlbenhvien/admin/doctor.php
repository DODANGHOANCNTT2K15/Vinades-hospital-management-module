<?php
if (!defined('NV_IS_QLBENHVIEN_ADMIN')) die('Stop!!!');

global $db, $db_config, $nv_Request, $module_name, $module_file, $global_config, $admin_info;

$tbDoctor = $db_config['prefix'] . "_ql_benhvien_bacsi";
$tbSpec   = $db_config['prefix'] . "_ql_benhvien_chuyenkhoa";

$action = $nv_Request->get_title('action', 'get,post', '');
$id = $nv_Request->get_int('id', 'get,post', 0);

// ==========================
// XÓA BÁC SĨ
// ==========================
if ($action == 'delete' && $id > 0) {
    $checkss = $nv_Request->get_title('checkss', 'get', '');
    if ($checkss == md5($id . NV_CHECK_SESSION)) {
        $db->query("DELETE FROM " . $tbDoctor . " WHERE id=" . $id);
        nv_insert_logs(NV_LANG_DATA, $module_name, 'Xóa bác sĩ', 'ID: ' . $id, $admin_info['userid']);
        Header("Location: " . NV_BASE_ADMINURL . "index.php?nv=$module_name&op=doctor");
        exit();
    } else {
        die('Invalid session check!');
    }
}

// ==========================
// DANH SÁCH BÁC SĨ
// ==========================

$per_page = 10;
$page = $nv_Request->get_int('page', 'get', 1);

// Lấy từ khóa tìm kiếm
$keyword = $nv_Request->get_title('keyword', 'get,post', '');

$xtpl = new XTemplate(
    'doctor.tpl',
    NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file
);

// Nạp CSS riêng cho trang chẩn đoán
$doctor_admin_css = NV_BASE_SITEURL . 'modules/' . $module_file . '/css/doctor_admin.css';
$xtpl->assign('DOCTOR_ADMIN_CSS', $doctor_admin_css);

$xtpl->assign('ADD_LINK', NV_BASE_ADMINURL . "index.php?nv=$module_name&op=doctor_add");
$xtpl->assign('KEYWORD', htmlspecialchars($keyword, ENT_QUOTES)); // Gán để giữ lại giá trị search trong input
$xtpl->assign('MODULE_NAME', $module_name);

// Tạo điều kiện WHERE nếu có từ khóa tìm kiếm
$where = '';
if (!empty($keyword)) {
    // Tránh SQL Injection: đã dùng PDO nên nên có thể dùng prepared statements hoặc tự xử lý
    // Ở đây dùng LIKE đơn giản:
    $keyword_esc = $db->quote('%' . $keyword . '%'); // Thêm dấu % để tìm kiếm LIKE
    $where = "WHERE d.hoten LIKE $keyword_esc";
}

// Đếm tổng số bác sĩ có lọc
$sql_count = "SELECT COUNT(*) FROM " . $tbDoctor . " AS d $where";
$total = $db->query($sql_count)->fetchColumn();

// Lấy dữ liệu bác sĩ theo trang và điều kiện
$sql = "SELECT d.*, s.tenchuyenkhoa 
        FROM " . $tbDoctor . " AS d
        LEFT JOIN " . $tbSpec . " AS s ON s.id = d.chuyenkhoa_id
        $where
        ORDER BY d.id ASC
        LIMIT " . (($page - 1) * $per_page) . ", $per_page";

$result = $db->query($sql)->fetchAll();

if (!empty($result)) {
    foreach ($result as $row) {
        $row['gioitinh_text'] = ($row['gioitinh'] == 1) ? 'Nam' : (($row['gioitinh'] == 2) ? 'Nữ' : 'Khác');
        $row['ngaysinh_vn']  = !empty($row['ngaysinh']) ? date('d/m/Y', strtotime($row['ngaysinh'])) : '-';
        $row['link_edit']    = NV_BASE_ADMINURL . "index.php?nv=$module_name&op=doctor_edit&id=" . $row['id'];
        $row['link_delete']  = NV_BASE_ADMINURL . "index.php?nv=$module_name&op=doctor&action=delete&id=" . $row['id'] . "&checkss=" . md5($row['id'] . NV_CHECK_SESSION);

        $xtpl->assign('ROW', $row);
        
        $xtpl->parse('main.list.row');
    }
} else {
    $xtpl->parse('main.list.no_data'); // Dòng này để hiển thị khi không có bác sĩ nào
}

// Phân trang (giữ lại từ khóa trong link phân trang)
$base_url = NV_BASE_ADMINURL . "index.php?nv=$module_name&op=doctor";
if (!empty($keyword)) {
    $base_url .= "&keyword=" . urlencode($keyword);
}
$generate_page = nv_generate_page($base_url, $total, $per_page, $page);
if (!empty($generate_page)) {
    $xtpl->assign('NV_GENERATE_PAGE', $generate_page);
    $xtpl->parse('main.list.pagination');
}

$xtpl->assign('TOTAL_INFO', "Tổng cộng $total bác sĩ");
$xtpl->parse('main.list');
$xtpl->parse('main');

$contents = $xtpl->text('main');

echo nv_admin_theme($contents);
