<?php
if (!defined('NV_IS_QLBENHVIEN_ADMIN')) die('Stop!!!');

global $db, $db_config, $nv_Request, $module_name, $module_file, $global_config, $admin_info;

$tbPatient = $db_config['prefix'] . "_ql_benhvien_benhnhan";

$per_page = 10;
$page = $nv_Request->get_int('page', 'get', 1);
$action = $nv_Request->get_string('action', 'get', 'list');

// =============================
// 1️⃣ Xử lý xóa bệnh nhân
// =============================
if ($action == 'delete') {
    $id = $nv_Request->get_int('id', 'get', 0);
    if ($id > 0) {
        $checkss = $nv_Request->get_string('checkss', 'get', '');
        if ($checkss == md5($id . NV_CHECK_SESSION)) {
            $db->query("DELETE FROM " . $tbPatient . " WHERE id=" . $id);
            nv_insert_logs(NV_LANG_DATA, $module_name, 'Xóa bệnh nhân', 'ID: ' . $id, $admin_info['userid']);
        }
    }
    Header("Location: " . NV_BASE_ADMINURL . "index.php?nv=$module_name&op=patient");
    exit();
}

// =============================
// 2️⃣ Hiển thị danh sách bệnh nhân
// =============================
$xtpl = new XTemplate(
    'patient.tpl',
    NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file
);

$xtpl->assign('ADD_LINK', NV_BASE_ADMINURL . "index.php?nv=$module_name&op=patient_add");

$total = $db->query("SELECT COUNT(*) FROM " . $tbPatient)->fetchColumn();

$sql = "SELECT * FROM " . $tbPatient . " ORDER BY hoten ASC
        LIMIT " . (($page - 1) * $per_page) . ", $per_page";
$result = $db->query($sql)->fetchAll();

foreach ($result as $row) {
    $row['gioitinh_text'] = ($row['gioitinh'] == 1) ? 'Nam' : (($row['gioitinh'] == 0) ? 'Nữ' : 'Khác');
    $row['ngaysinh_vn'] = !empty($row['ngaysinh']) ? date('d/m/Y', strtotime($row['ngaysinh'])) : '-';
    $row['ngaytao_vn'] = !empty($row['ngaytao'])
        ? date('d/m/Y H:i', is_numeric($row['ngaytao']) ? $row['ngaytao'] : strtotime($row['ngaytao']))
        : '-';

    $row['link_edit'] = NV_BASE_ADMINURL . "index.php?nv=$module_name&op=patient_edit&id=" . $row['id'];
    $row['link_delete'] = NV_BASE_ADMINURL . "index.php?nv=$module_name&op=patient&action=delete&id=" . $row['id'] . "&checkss=" . md5($row['id'] . NV_CHECK_SESSION);

    $xtpl->assign('ROW', $row);
    $xtpl->assign('MODULE_NAME', $module_name);
    $xtpl->parse('main.list.row');
}

$base_url = NV_BASE_ADMINURL . "index.php?nv=$module_name&op=patient";
$generate_page = nv_generate_page($base_url, $total, $per_page, $page);
if (!empty($generate_page)) {
    $xtpl->assign('NV_GENERATE_PAGE', $generate_page);
    $xtpl->parse('main.list.pagination');
}

$xtpl->assign('TOTAL_INFO', "Tổng cộng $total bệnh nhân");

$xtpl->parse('main.list');
$xtpl->parse('main');

$contents = $xtpl->text('main');

echo nv_admin_theme($contents);
