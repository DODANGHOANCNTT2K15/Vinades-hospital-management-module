<?php
if (!defined('NV_IS_QLBENHVIEN_ADMIN')) die('Stop!!!');

global $db, $db_config, $nv_Request, $module_name, $module_file, $global_config;

$table = $db_config['prefix'] . "_ql_benhvien_chuyenkhoa";

// Khởi tạo XTemplate
$xtpl = new XTemplate(
    'specialties_add.tpl',
    NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file
);

// Nếu submit form
if ($nv_Request->isset_request('save', 'post')) {
    $tenchuyenkhoa = $nv_Request->get_title('tenchuyenkhoa', 'post', '');
    $mota = $nv_Request->get_title('mota', 'post', '');
    $trangthai = $nv_Request->get_int('trangthai', 'post', 0);

    $errors = [];

    if (empty($tenchuyenkhoa)) $errors[] = '⚠️ Vui lòng nhập tên chuyên khoa.';

    if (empty($errors)) {
        $stmt = $db->prepare("
            INSERT INTO $table (tenchuyenkhoa, mota, trangthai)
            VALUES (:tenchuyenkhoa, :mota, :trangthai)
        ");
        $stmt->bindParam(':tenchuyenkhoa', $tenchuyenkhoa, PDO::PARAM_STR);
        $stmt->bindParam(':mota', $mota, PDO::PARAM_STR);
        $stmt->bindValue(':trangthai', $trangthai > 0 ? 1 : 0, PDO::PARAM_INT);
        $stmt->execute();

        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?nv=' . $module_name . '&op=specialties');
        exit;
    } else {
        $xtpl->assign('ERROR', implode('<br>', $errors));
        $xtpl->parse('main.error');
    }
}

$xtpl->assign('ACTION', NV_BASE_ADMINURL . 'index.php?nv=' . $module_name . '&op=specialties_add');
$xtpl->assign('BACK_LINK', NV_BASE_ADMINURL . 'index.php?nv=' . $module_name . '&op=specialties');

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
