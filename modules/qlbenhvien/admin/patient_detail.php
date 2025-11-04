<?php
if (!defined('NV_IS_QLBENHVIEN_ADMIN')) {
    die('Stop!!!');
}

global $db, $db_config, $nv_Request, $module_name, $module_file, $global_config;

// Tên bảng bệnh nhân
$table_patient = $db_config['prefix'] . "_ql_benhvien_benhnhan";

// Lấy ID bệnh nhân từ URL
$id = $nv_Request->get_int('id', 'get', 0);

if ($id <= 0) {
    die('ID không hợp lệ!');
}

$xtpl = new XTemplate(
    'patient_detail.tpl',
    NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file
);

// Lấy thông tin bệnh nhân
$sql = "SELECT * FROM $table_patient WHERE id = " . $id;
$patient = $db->query($sql)->fetch();

if (!$patient) {
    die('Không tìm thấy bệnh nhân!');
}

// Xử lý hiển thị giới tính dạng text
$gioitinh_text = '';
if ($patient['gioitinh'] === 1) {
    $gioitinh_text = 'Nam';
} elseif ($patient['gioitinh'] === 0) {
    $gioitinh_text = 'Nữ';
} else {
    $gioitinh_text = 'Không xác định';
}

// Chuyển đổi ngày tạo (nếu có) sang định dạng dễ đọc
$ngaytao_text = '';
if (!empty($patient['ngaytao'])) {
    $ngaytao_text = date('d/m/Y H:i:s', strtotime($patient['ngaytao']));
}

// Gán dữ liệu cho tpl
$xtpl->assign('ID', $patient['id']);
$xtpl->assign('HOTEN', htmlspecialchars($patient['hoten']));
$xtpl->assign('NGAYSINH', !empty($patient['ngaysinh']) ? $patient['ngaysinh'] : '');
$xtpl->assign('GIOITINH', $gioitinh_text);
$xtpl->assign('DIA_CHI', nl2br(htmlspecialchars($patient['diachi'])));
$xtpl->assign('SDT', htmlspecialchars($patient['sdt']));
$xtpl->assign('EMAIL', htmlspecialchars($patient['email']));
$xtpl->assign('USERID', htmlspecialchars($patient['userid']));
$xtpl->assign('NGAYTAO', $ngaytao_text);

// Link về danh sách và link edit
$xtpl->assign('BACK_LINK', NV_BASE_ADMINURL . "index.php?nv=$module_name&op=patient");
$xtpl->assign('EDIT_LINK', NV_BASE_ADMINURL . "index.php?nv=$module_name&op=patient_edit&id=" . $id);

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
