<?php
if (!defined('NV_IS_QLBENHVIEN_ADMIN')) die('Stop!!!');

global $db, $db_config, $nv_Request, $module_name, $module_file, $global_config;

$tbDoctor = $db_config['prefix'] . "_ql_benhvien_bacsi";
$tbSpec   = $db_config['prefix'] . "_ql_benhvien_chuyenkhoa";

$per_page = 15;
$page = $nv_Request->get_int('page', 'get', 1);

$xtpl = new XTemplate(
    'doctor.tpl',
    NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file
);

$xtpl->assign('ADD_LINK', NV_BASE_ADMINURL . "index.php?nv=$module_name&op=doctor_add");

// 1️⃣ Lấy danh sách bác sĩ
$total = $db->query("SELECT COUNT(*) FROM " . $tbDoctor)->fetchColumn();

$sql = "SELECT d.*, s.tenchuyenkhoa 
        FROM " . $tbDoctor . " AS d
        LEFT JOIN " . $tbSpec . " AS s ON s.id = d.chuyenkhoa_id
        ORDER BY d.hoten ASC
        LIMIT " . (($page - 1) * $per_page) . ", $per_page";

$result = $db->query($sql)->fetchAll();

foreach ($result as $row) {
    $row['gioitinh_text'] = ($row['gioitinh'] == 1) ? 'Nam' : (($row['gioitinh'] == 2) ? 'Nữ' : 'Khác');
    $row['ngaysinh_vn']  = !empty($row['ngaysinh']) ? date('d/m/Y', strtotime($row['ngaysinh'])) : '-';
    $row['link_edit']    = NV_BASE_ADMINURL . "index.php?nv=$module_name&op=doctor_edit&id=" . $row['id'];
    $row['link_delete']  = NV_BASE_ADMINURL . "index.php?nv=$module_name&op=doctor_delete&id=" . $row['id'] . "&checkss=" . md5($row['id'] . NV_CHECK_SESSION);

    $xtpl->assign('ROW', $row);
    $xtpl->parse('main.list.row'); // parse từng dòng
}

// Phân trang
$base_url = NV_BASE_ADMINURL . "index.php?nv=$module_name&op=doctor";
$generate_page = nv_generate_page($base_url, $total, $per_page, $page);
if (!empty($generate_page)) {
    $xtpl->assign('NV_GENERATE_PAGE', $generate_page);
    $xtpl->parse('main.list.pagination');
}

$xtpl->assign('TOTAL_INFO', "Tổng cộng $total bác sĩ");
$xtpl->parse('main.list');
$xtpl->parse('main');

$contents = $xtpl->text('main');

// --- Chỉ dùng nv_admin_theme, không include header/footer ---
echo nv_admin_theme($contents);
