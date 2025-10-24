<?php
if (!defined('NV_IS_MOD_QLBENHVIEN')) die('Stop!!!');

global $db, $db_config, $nv_Request, $user_info, $module_info, $module_file;

$table_hoso = $db_config['prefix'] . "_ql_benhvien_hosokham";
$notice = '';

// Thêm hồ sơ (nếu có submit)
if ($nv_Request->isset_request('submit', 'post')) {
    $benhnhan = $nv_Request->get_title('benhnhan', 'post', (isset($user_info['full_name']) ? $user_info['full_name'] : 'Khách'));
    $chandoan  = $nv_Request->get_title('chandoan', 'post', '');
    $donthuoc  = $nv_Request->get_title('donthuoc', 'post', '');
    if (!empty($chandoan) && !empty($donthuoc)) {
        $stmt = $db->prepare("INSERT INTO " . $table_hoso . " (benhnhan, chandoan, donthuoc, ngaykham) VALUES (:benhnhan, :chandoan, :donthuoc, NOW())");
        $stmt->bindParam(':benhnhan', $benhnhan, PDO::PARAM_STR);
        $stmt->bindParam(':chandoan', $chandoan, PDO::PARAM_STR);
        $stmt->bindParam(':donthuoc', $donthuoc, PDO::PARAM_STR);
        $stmt->execute();
        $notice = '<p style="color:green;">✅ Hồ sơ khám đã được lưu.</p>';
    } else {
        $notice = '<p style="color:red;">⚠️ Vui lòng nhập chẩn đoán và đơn thuốc.</p>';
    }
}

// Lấy danh sách hồ sơ
$list = [];
try {
    $sql = "SELECT id, benhnhan, chandoan, donthuoc, ngaykham FROM " . $table_hoso . " ORDER BY ngaykham DESC";
    $result = $db->query($sql);
    while ($row = $result->fetch()) {
        $row['ngaykham'] = date('d/m/Y H:i', strtotime($row['ngaykham']));
        $row['benhnhan'] = htmlspecialchars($row['benhnhan']);
        $row['chandoan']  = htmlspecialchars($row['chandoan']);
        $row['donthuoc']  = htmlspecialchars($row['donthuoc']);
        $list[] = $row;
    }
} catch (Exception $e) {
    // nếu bảng chưa tồn tại thì $list rỗng
    $list = [];
}

$xtpl = new XTemplate('hosokham.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
$xtpl->assign('NOTICE', $notice);

// form: mặc định tên bệnh nhân là tên user
$xtpl->assign('DEFAULT_BENHNHAN', isset($user_info['full_name']) ? htmlspecialchars($user_info['full_name']) : '');

// gán danh sách
foreach ($list as $r) {
    $xtpl->assign('ROW', $r);
    $xtpl->parse('main.loop');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
