<?php
if (!defined('NV_IS_QLBENHVIEN_ADMIN')) {
    die('Stop!!!');
}

global $db, $db_config, $nv_Request, $module_name, $module_file, $global_config;

$table_doctor = $db_config['prefix'] . "_ql_benhvien_bacsi";
$table_specialty = $db_config['prefix'] . "_ql_benhvien_chuyenkhoa";

// Lấy ID bác sĩ từ URL
$id = $nv_Request->get_int('id', 'get', 0);
if ($id <= 0) {
    die('Lỗi: ID không hợp lệ.');
}

// Lấy thông tin chi tiết bác sĩ + tên chuyên khoa
$sql = "
    SELECT bs.*, ck.tenchuyenkhoa
    FROM $table_doctor AS bs
    LEFT JOIN $table_specialty AS ck ON bs.chuyenkhoa_id = ck.id
    WHERE bs.id = :id
";
$stmt = $db->prepare($sql);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$doctor = $stmt->fetch();

if (empty($doctor)) {
    die('Không tìm thấy bác sĩ.');
}

// Xử lý dữ liệu hiển thị
$doctor['ngaysinh_vn'] = !empty($doctor['ngaysinh']) ? date('d/m/Y', strtotime($doctor['ngaysinh'])) : '-';
$doctor['gioitinh_text'] = ($doctor['gioitinh'] == 1) ? 'Nam' : (($doctor['gioitinh'] == 0) ? 'Nữ' : 'Khác');

// Chuẩn bị template
$xtpl = new XTemplate(
    'doctor_detail.tpl',
    NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file
);

// Gán dữ liệu
$xtpl->assign('ID', $doctor['id']);
$xtpl->assign('HOTEN', $doctor['hoten']);
$xtpl->assign('NGAYSINH', $doctor['ngaysinh_vn']);
$xtpl->assign('GIOITINH', $doctor['gioitinh_text']);
$xtpl->assign('CHUYENKHOA', !empty($doctor['tenchuyenkhoa']) ? $doctor['tenchuyenkhoa'] : '—');
$xtpl->assign('TRINHDO', $doctor['trinhdo']);
$xtpl->assign('LICHLAMVIEC', nl2br($doctor['lichlamviec']));
$xtpl->assign('EMAIL', $doctor['email']);
$xtpl->assign('SDT', $doctor['sdt']);
$xtpl->assign('USERID', !empty($doctor['userid']) ? $doctor['userid'] : 'NULL');

// Hình ảnh
if (!empty($doctor['hinhanh']) && file_exists(NV_ROOTDIR . '/' . $doctor['hinhanh'])) {
    $xtpl->assign('HINHANH', NV_BASE_SITEURL . $doctor['hinhanh']);
    $xtpl->parse('main.IMAGE');
} else {
    $xtpl->parse('main.NO_IMAGE');
}

// Liên kết
$xtpl->assign('BACK_LINK', NV_BASE_ADMINURL . "index.php?nv=$module_name&op=doctor");
$xtpl->assign('EDIT_LINK', NV_BASE_ADMINURL . "index.php?nv=$module_name&op=doctor_edit&id=" . $doctor['id']);

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
