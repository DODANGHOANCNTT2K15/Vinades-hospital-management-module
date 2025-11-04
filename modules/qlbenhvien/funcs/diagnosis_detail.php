<?php
if (!defined('NV_IS_MOD_QLBENHVIEN')) die('Stop!!!');

global $db, $db_config, $nv_Request, $module_info, $module_file;

$id = $nv_Request->get_int('id', 'get', 0);

$table_chandoan = $db_config['prefix'] . "_ql_benhvien_chandoan";
$table_lichkham = $db_config['prefix'] . "_ql_benhvien_lichkham";
$table_benhnhan = $db_config['prefix'] . "_ql_benhvien_benhnhan";
$table_bacsi = $db_config['prefix'] . "_ql_benhvien_bacsi";

// Truy vấn lấy dữ liệu chẩn đoán và các thông tin liên quan
$sql = "SELECT c.*, 
               lk.ngaykham,
               bnh.hoten AS benhnhan,
               bs.hoten AS bacsi
        FROM $table_chandoan AS c
        INNER JOIN $table_lichkham AS lk ON c.schedule_id = lk.id
        LEFT JOIN $table_benhnhan AS bnh ON lk.benhnhan_id = bnh.id
        LEFT JOIN $table_bacsi AS bs ON lk.bacsi_id = bs.id
        WHERE c.schedule_id = :id
        LIMIT 1";

$stmt = $db->prepare($sql);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$data = $stmt->fetch();

$xtpl = new XTemplate('diagnosis_detail.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);

if ($data) {
    // Format ngày giờ
    $data['ngaytao'] = date('d/m/Y H:i', strtotime($data['ngaytao']));
    $data['ngaykham'] = date('d/m/Y', strtotime($data['ngaykham']));
    $xtpl->assign('DATA', $data);
    $xtpl->parse('main.data');
} else {
    $xtpl->parse('main.empty');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
?>
