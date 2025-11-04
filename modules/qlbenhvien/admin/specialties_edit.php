<?php
if (!defined('NV_IS_QLBENHVIEN_ADMIN')) {
    die('Stop!!!');
}

global $db, $db_config, $nv_Request, $module_name, $module_file, $global_config, $admin_info;

$table = $db_config['prefix'] . "_ql_benhvien_chuyenkhoa";
$id = $nv_Request->get_int('id', 'get', 0);
$submit = $nv_Request->get_int('submit', 'post', 0);

if ($id <= 0) {
    die('ID không hợp lệ!');
}

// 🧭 Lấy dữ liệu chuyên khoa hiện tại
$sql = "SELECT * FROM $table WHERE id = " . $id;
$spec = $db->query($sql)->fetch();

if (!$spec) {
    die('Không tìm thấy chuyên khoa!');
}

// Khởi tạo giao diện
$xtpl = new XTemplate(
    'specialties_edit.tpl',
    NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file
);

$errors = [];

// ✅ Nếu người dùng bấm Lưu
if ($submit) {
    $tenchuyenkhoa = $nv_Request->get_title('tenchuyenkhoa', 'post', '');
    $mota = $nv_Request->get_textarea('mota', '', NV_ALLOWED_HTML_TAGS);
    $trangthai = $nv_Request->get_int('trangthai', 'post', 1);

    // Kiểm tra hợp lệ
    if (empty($tenchuyenkhoa)) {
        $errors[] = '⚠️ Vui lòng nhập tên chuyên khoa.';
    }

    if (empty($errors)) {
        $stmt = $db->prepare("
            UPDATE $table SET 
                tenchuyenkhoa = :tenchuyenkhoa,
                mota = :mota,
                trangthai = :trangthai
            WHERE id = :id
        ");
        $stmt->bindParam(':tenchuyenkhoa', $tenchuyenkhoa, PDO::PARAM_STR);
        $stmt->bindParam(':mota', $mota, PDO::PARAM_STR);
        $stmt->bindParam(':trangthai', $trangthai, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        nv_insert_logs(NV_LANG_DATA, $module_name, 'Cập nhật chuyên khoa', 'ID: ' . $id, $admin_info['userid']);

        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?nv=' . $module_name . '&op=specialties');
        exit();
    } else {
        $xtpl->assign('ERROR', implode('<br>', $errors));
        $xtpl->parse('main.error');
    }
}

// 🧾 Gán dữ liệu ra form
$xtpl->assign('ID', $id);
$xtpl->assign('TENCHUYENKHOA', htmlspecialchars($spec['tenchuyenkhoa']));
$xtpl->assign('MOTA', htmlspecialchars($spec['mota']));
$xtpl->assign('ACTION_LINK', NV_BASE_ADMINURL . 'index.php?nv=' . $module_name . '&op=specialties_edit&id=' . $id);
$xtpl->assign('BACK_LINK', NV_BASE_ADMINURL . 'index.php?nv=' . $module_name . '&op=specialties');

// Trạng thái
$status_list = [
    1 => 'Hoạt động',
    0 => 'Ngưng hoạt động'
];
foreach ($status_list as $key => $text) {
    $xtpl->assign('STATUS_KEY', $key);
    $xtpl->assign('STATUS_TEXT', $text);
    $xtpl->assign('STATUS_SELECTED', ($spec['trangthai'] == $key) ? 'selected' : '');
    $xtpl->parse('main.status_option');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
